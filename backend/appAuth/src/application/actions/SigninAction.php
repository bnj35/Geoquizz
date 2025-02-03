<?php
namespace geoquizz\application\actions;

use geoquizz\application\provider\auth\AuthProviderInterface;
use geoquizz\application\renderer\JsonRenderer;
use geoquizz\core\dto\auth\CredentialsDTO;
use geoquizz\core\services\auth\AuthentificationServiceBadDataException;
use geoquizz\core\services\auth\AuthentificationServiceInternalServerErrorException;
use geoquizz\core\services\auth\AuthentificationServiceNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;

class SigninAction extends AbstractAction {
    private AuthProviderInterface $authnProviderInterface;

    public function __construct(AuthProviderInterface $authnProviderInterface){
        $this->authnProviderInterface = $authnProviderInterface;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface{
        $token = $rq->getHeader('Authorization')[0] ?? throw new HttpUnauthorizedException($rq, 'missing Authorization Header');
        $authHeader = sscanf($token, "Basic %s")[0] ;

        $decodedAuth = base64_decode($authHeader);
        list($email, $password) = explode(':', $decodedAuth, 2);

        try {
            $authDTO = $this->authnProviderInterface->signin(new CredentialsDTO($email, $password));
            $res = [
                'id' => $authDTO->id,
                'email' => $authDTO->email,
                'role' => $authDTO->role,
                'token' => $authDTO->token,
                'token_refresh' => $authDTO->token_refresh
            ];
            return JsonRenderer::render($rs, 200, $res);
        } catch (AuthentificationServiceNotFoundException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        } catch (AuthentificationServiceBadDataException $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        } catch (AuthentificationServiceInternalServerErrorException $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
    }
}
