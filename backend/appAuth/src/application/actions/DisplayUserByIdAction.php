<?php

namespace geoquizz\application\actions;

use geoquizz\application\renderer\JsonRenderer;
use geoquizz\core\services\auth\AuthentificationServiceBadDataException;
use geoquizz\core\services\auth\AuthentificationServiceInterface;
use geoquizz\core\services\auth\AuthentificationServiceInternalServerErrorException;
use geoquizz\core\services\auth\AuthentificationServiceNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;

class DisplayUserByIdAction extends AbstractAction
{
    private AuthentificationServiceInterface $authService;

    public function __construct(AuthentificationServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try{
            $id = $args['id'];
            $response = [
                "type" => "resource",
                "locale" => "fr-FR",
                "user" => $this->authService->getUserById($id)
            ];

            return JsonRenderer::render($rs, 200, $response);
        } catch (AuthentificationServiceBadDataException $e){
            throw new HttpBadRequestException($rq, $e->getMessage());
        }catch (AuthentificationServiceInternalServerErrorException $e){
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }catch (AuthentificationServiceNotFoundException $e){
            throw new HttpNotFoundException($rq, $e->getMessage());
        }
    }
}
