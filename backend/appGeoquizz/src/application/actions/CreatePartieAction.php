<?php

namespace geoquizz\application\actions;

use geoquizz\core\services\partie\ServicePartieInternalServerError;
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
use geoquizz\application\renderer\JsonRenderer;

//services
use geoquizz\core\services\partie\ServicePartieInterface;
use geoquizz\core\services\directus\DirectusInfoServiceInterface;

//dto
use geoquizz\core\dto\partie\InputPartieDTO;
use geoquizz\application\actions\AbstractAction;

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
                ->key('token', Validator::stringType()->notEmpty())
                ->key('nb_photos', Validator::intType()->notEmpty())
                ->key('score', Validator::intType())
                ->key('theme', Validator::stringType()->notEmpty())
                ->key('temps', Validator::intType()->notEmpty());
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

            $dto = new InputPartieDTO($data["nom"], $data["token"], $data["nb_photos"], $data["score"], $data["theme"], $data["temps"]);
            $partie = $this->partieService->createPartie($dto);
            $urlPartie = $routeParser->urlFor('getPartieById', ['id' => $partie->id]);
            $serieId = $this->directusService->getSerieIdByTheme($partie->theme);
            $images = $this->directusService->getImagesBySerieId($serieId, $partie->nb_photos);

            $response = [
                "type" => "resource",
                "locale" => "FR-fr",
                "partie" => $partie,
                "images" => $images,
                "links" => [
                    "self" => ['href' => $urlPartie],
                ]
            ];

            return JsonRenderer::render($rs, 201, $response);
        } catch (ServicePartieInternalServerError $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
    }
}
