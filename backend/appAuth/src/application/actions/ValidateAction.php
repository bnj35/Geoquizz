<?php

namespace geoquizz\application\actions;

use geoquizz\application\provider\auth\AuthProviderBeforeValidException;
use geoquizz\application\provider\auth\AuthProviderInterface;
use geoquizz\application\provider\auth\AuthProviderSignatureInvalidException;
use geoquizz\application\provider\auth\AuthProviderTokenExpiredException;
use geoquizz\application\renderer\JsonRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpUnauthorizedException;

class ValidateAction extends AbstractAction
{
    private AuthProviderInterface $authProvider;

    public function __construct(AuthProviderInterface $authProvider)
    {
        $this->authProvider = $authProvider;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try{
            $token = $rq->getHeader('Authorization')[0] ?? null;
            if (empty($token)) {
                throw new HttpUnauthorizedException($rq, 'Token not found');
            }
            $token = str_replace('Bearer ', '', $token);
            $this->authProvider->getSignedInUser($token);
            return JsonRenderer::render($rs, 204);
        }catch (AuthProviderTokenExpiredException $e) {
            throw new HttpUnauthorizedException($rq, 'Token expired');
        }catch (AuthProviderBeforeValidException $e) {
            throw new HttpUnauthorizedException($rq, 'Token not yet valid');
        }catch (AuthProviderSignatureInvalidException $e) {
            throw new HttpUnauthorizedException($rq, 'Token signature invalid');
        }

    }
}
