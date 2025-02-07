<?php

namespace geoquizz\application\actions;

use geoquizz\application\actions\AbstractAction;
use geoquizz\core\services\partie\ServicePartieInterface;

use Psr\Http\Message\ResponseInterface;

use geoquizz\core\services\partie\ServicePartieInternalServerError;
use geoquizz\core\services\partie\ServicePartieInvalidDataException;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;

//amqp
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


//renderer
use geoquizz\application\renderer\JsonRenderer;

class ClosePartieAction extends AbstractAction
{
    private ServicePartieInterface $partieService;

    public function __construct(ServicePartieInterface $partieService)
    {
        $this->partieService = $partieService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {

        try {
            $id = $args['id'];
            $this->partieService->closePartie($id);
            $partie = $this->partieService->getPartieById($id);
            $userId = $this->partieService->getUserIdByPartieId($id);
            $email = $this->partieService->getEmailByPartieId($userId);
            $response = [
                'type' => 'resource',
                'local' => 'FR-fr',
                'partie' => $partie,
                'links' => [
                    'self' => [
                        'href' => '/parties/' . $id,
                    ]
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
                    'details' => " Bravo $user! La partie est terminée."
                ];

                $msg = new AMQPMessage(json_encode($messageData), ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
                $channel->basic_publish($msg, 'notification_exchange');

                $channel->close();
                $connection->close();
            };

            return JsonRenderer::render($rs, 200, $response);
        } catch (ServicePartieInternalServerError $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        } catch (ServicePartieInvalidDataException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        }
    }

}
