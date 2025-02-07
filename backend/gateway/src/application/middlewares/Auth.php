<?php

namespace geoquizz\application\middlewares;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpUnauthorizedException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class Auth implements MiddlewareInterface
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $token = $request->getHeader('Authorization')[0] ?? null;

        if ($token === null) {
            throw new HttpUnauthorizedException($request, 'Missing Authorization Header');
        }

        try {
            $response = $this->client->request('POST', '/validate', [
                'headers' => ['Authorization' => $token]
            ]);

            if ($response->getStatusCode() !== 204) {
                throw new HttpUnauthorizedException($request, 'Invalid token');
            }
        } catch (ClientException $e) {
            throw new HttpUnauthorizedException($request, $e->getMessage());
        }

        return $handler->handle($request);
    }
}
