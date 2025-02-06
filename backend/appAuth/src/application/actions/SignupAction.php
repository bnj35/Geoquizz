<?php

namespace geoquizz\application\actions;

use geoquizz\application\provider\auth\AuthProviderInterface;
use geoquizz\application\renderer\JsonRenderer;
use geoquizz\core\dto\auth\CredentialsDTO;
use geoquizz\core\services\auth\AuthentificationServiceBadDataException;
use geoquizz\core\services\auth\AuthentificationServiceInterface;
use geoquizz\core\services\auth\AuthentificationServiceInternalServerErrorException;
use geoquizz\core\services\auth\AuthentificationServiceNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;
//AMQP
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class SignupAction extends AbstractAction
{
    private AuthProviderInterface $authnProviderInterface;

    public function __construct(AuthProviderInterface $authnProviderInterface,){
        $this->authnProviderInterface = $authnProviderInterface;
    }


    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $token = $rq->getHeader('Authorization')[0] ?? throw new HttpUnauthorizedException($rq, 'missing Authorization Header');
        $authHeader = sscanf($token, "Basic %s")[0] ;

        $decodedAuth = base64_decode($authHeader);
        list($email, $password) = explode(':', $decodedAuth, 2);

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
                'details' => " Welcome to GeoQuizz, $user! You have successfully created an account."
            ];

            $msg = new AMQPMessage(json_encode($messageData), ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
            $channel->basic_publish($msg, 'notification_exchange');

            $channel->close();
            $connection->close();
        }

        try {
            $this->authnProviderInterface->register(new CredentialsDTO($email, $password));
            return JsonRenderer::render($rs, 201);
        } catch (AuthentificationServiceNotFoundException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        } catch (AuthentificationServiceBadDataException $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        } catch (AuthentificationServiceInternalServerErrorException $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
    }
}
