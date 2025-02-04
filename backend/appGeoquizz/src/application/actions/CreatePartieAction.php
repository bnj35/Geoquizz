<?php

namespace geoquizz\src\action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
//validation
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;
//exceptions
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
//routing
use Slim\Routing\RouteContext;
//renderer
use geoquizz\src\renderer\JsonRenderer;
//services
use geoquizz\core\services\partie\ServicePartieInterface;
//dto
use geoquizz\core\dto\partie\InputPartieDTO;

class CreatePartieAction extends AbstractAction 
{
    private ServicePartieInterface $partieService;

    public function __construct(ServicePartieInterface $partieService)
    {
        $this->partieService = $partieService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try {
            $routeContext = RouteContext::fromRequest($rq);
            $routeParser = $routeContext->getRouteParser();

            $data = $rq->getParsedBody();
            $partieInputValidator = Validator::key('nom', Validator::stringType()->notEmpty())
                ->key('token', Validator::stringType()->notEmpty())
                ->key('nb_photos', Validator::intType()->notEmpty())
                ->key('score', Validator::intType()->notEmpty())
                ->key('theme', Validator::stringType()->notEmpty());
            try {
                $partieInputValidator->assert($data);
            } catch (NestedValidationException $e) {
                throw new HttpBadRequestException($rq, $e->getFullMessage());
            }
            if (filter_var($data["nom"], FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== $data["nom"]) {
                throw new HttpBadRequestException($rq, "Bad data format nom");
            }
            if (filter_var($data["token"], FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== $data["token"]) {
                throw new HttpBadRequestException($rq, "Bad data format token");
            }
            if (filter_var($data["nb_photos"], FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== $data["nb_photos"]) {
                throw new HttpBadRequestException($rq, "Bad data format nb_photos");
            }
            if (filter_var($data["score"], FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== $data["score"]) {
                throw new HttpBadRequestException($rq, "Bad data format score");
            }
            if (filter_var($data["theme"], FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== $data["theme"]) {
                throw new HttpBadRequestException($rq, "Bad data format theme");
            }

            $dto = new InputPartieDTO($data["nom"], $data["token"], $data["nb_photos"], $data["score"], $data["theme"]);
            $partie = $this->partieService->createPartie($dto);
            $urlPartie = $routeParser->urlFor('getPartieById', ['id' => $partie->getId()]);
            $response = [
                "type" => "resource",
                "locale" => "FR-fr",
                "partie" => $partie,
                "links" => [
                    "self" => ['href' => $urlPartie],
                ]
            ];

            return JsonRenderer::render($rs, 201, $response);
            
        } catch (HttpBadRequestException $e) {
            throw $e;
        } catch (HttpInternalServerErrorException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
    }
}
