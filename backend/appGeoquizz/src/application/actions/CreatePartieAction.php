<?php

namespace geoquizz\application\actions;

use geoquizz\core\services\partie\ServicePartieInternalServerError;
use geoquizz\core\services\partie\ServicePartieInvalidDataException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

//validation
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;

//exceptions
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;

//routing
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;

//renderer
use geoquizz\application\renderer\JsonRenderer;

//services
use geoquizz\core\services\partie\ServicePartieInterface;
use geoquizz\core\services\directus\DirectusInfoServiceInterface;

//dto
use geoquizz\core\dto\partie\InputPartieDTO;
use geoquizz\application\actions\AbstractAction;

//amqp
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class CreatePartieAction extends AbstractAction
{
    private ServicePartieInterface $partieService;
    private DirectusInfoServiceInterface $directusService;

    public function __construct(ServicePartieInterface $partieService, DirectusInfoServiceInterface $directusService)
    {
        $this->partieService = $partieService;
        $this->directusService = $directusService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try {
            $routeContext = RouteContext::fromRequest($rq);
            $routeParser = $routeContext->getRouteParser();

            $data = $rq->getParsedBody();
            $partieInputValidator = Validator::key('nom', Validator::stringType()->notEmpty())
                ->key('nb_photos', Validator::intType()->notEmpty())
                ->key('score', Validator::intType())
                ->key('theme', Validator::stringType()->notEmpty())
                ->key('temps', Validator::intType()->notEmpty())
                ->key('user_id', Validator::stringType()->notEmpty());
            try {
                $partieInputValidator->assert($data);
            } catch (NestedValidationException $e) {
                throw new HttpBadRequestException($rq, $e->getFullMessage());
            }
            if (filter_var($data["nom"], FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== $data["nom"]) {
                throw new HttpBadRequestException($rq, "Bad data format nom");
            }
            if (!filter_var($data["nb_photos"], FILTER_VALIDATE_INT)) {
                throw new HttpBadRequestException($rq, "Bad data format nb_photos");
            }
            // if (!filter_var($data["score"], FILTER_VALIDATE_INT)) {
            //     throw new HttpBadRequestException($rq, "Bad data format score");
            // }
            if (filter_var($data["theme"], FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== $data["theme"]) {
                throw new HttpBadRequestException($rq, "Bad data format theme");
            }
            if (!filter_var($data["temps"], FILTER_VALIDATE_INT)) {
                throw new HttpBadRequestException($rq, "Bad data format temps");
            }
            if (filter_var($data["user_id"], FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== $data["user_id"]) {
                throw new HttpBadRequestException($rq, "Bad data format user_id");
            }

            $payload = [
                'aud' => 'geoquizz',
                "iat" => time(),
                "exp" => time() + 3600,
                "sub" => $data["user_id"],
                "data" => [
                    "nom" => $data["nom"],
                    "theme" => $data["theme"],
                ]
            ];
            $payload['exp'] = time() + 3600 * 3;
            $token = $this->partieService->getToken($payload);

            $dto = new InputPartieDTO($data["nom"], $token, $data["nb_photos"], $data["score"], $data["theme"], $data["temps"], $data["user_id"]);
            $partie = $this->partieService->createPartie($dto);
            $urlPartie = $routeParser->urlFor('getPartieById', ['id' => $partie->id]);
            $serieId = $this->directusService->getSerieIdByTheme($partie->theme);
            $images = $this->directusService->getImagesBySerieId($serieId, $partie->nb_photos);
            $this->partieService->setPartieImage($images, $partie->id);
            $email = $this->partieService->getEmailByPartieId($data["user_id"]);


            $response = [
                "type" => "resource",
                "locale" => "FR-fr",
                "partie" => $partie,
                "images" => $images,
                "links" => [
                    "self" => ['href' => $urlPartie],
                ]
            ];

            //message queue
            if ($email != null) {
                $connection = new AMQPStreamConnection('rabbitmq', 5672, 'admin', 'admin');
                $channel = $connection->channel();
                $channel->exchange_declare('notification_exchange', 'direct', false, true, false);
                $channel->queue_declare('notification_queue', false, true, false, false, false);
                $channel->queue_bind('notification_queue', 'notification_exchange');
                $user = explode('@', $email)[0];

                $messageData = [
                    'event' => 'CREATE',
                    'recipient' => [
                        'email' => $email,
                        'name' => $user
                    ],
                    'details' => " Bravo $user! Vous avez créer une nouvelle partie."
                ];

                $msg = new AMQPMessage(json_encode($messageData), ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
                $channel->basic_publish($msg, 'notification_exchange');

                $channel->close();
                $connection->close();
            };


            return JsonRenderer::render($rs, 201, $response);
        } catch (ServicePartieInternalServerError $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        } catch (ServicePartieInvalidDataException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        }
    }
}
