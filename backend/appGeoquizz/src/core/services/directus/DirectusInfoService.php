<?php

namespace geoquizz\core\services\directus;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use geoquizz\core\services\directus\ServiceDirectusInvalidDataException;
use geoquizz\core\services\directus\ServiceDirectusInternalServerError;
use geoquizz\core\services\directus\DirectusInfoServiceInterface;

class DirectusInfoService implements DirectusInfoServiceInterface
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getSerieIdByTheme(string $theme): string
    {
        try {
            $response = $this->client->get("/items/series?fields=id&filter={\"nom\":{\"_eq\":\"$theme\"}}");
            $data = json_decode($response->getBody()->getContents(), true);
            $serieId = $data['data'][0]['id'];

            return $serieId;
        } catch (ConnectException|ServerException $e) {
            throw new ServiceDirectusInternalServerError($e->getMessage());
        } catch (ClientException $e) {
            match ($e->getCode()) {
                400 => throw new ServiceDirectusInvalidDataException($e->getMessage()),
            };
        }
    }

    public function getImagesBySerieId(string $serieId, int $nb_photos): array
    {
        try {
            $response = $this->client->get("/items/images?fields=id,image,mapillary_id,longitude,latitude&filter={\"serie\":{\"_eq\":\"$serieId\"}}");
            $data = json_decode($response->getBody()->getContents(), true);
            shuffle($data['data']);
            $images = array_slice($data['data'], 0, $nb_photos);
            $images = array_map(function($image) {
                $image['image'] = '/assets/' . $image['image'];
                return $image;
            }, $images);

            return $images;
        } catch (ConnectException|ServerException $e) {
            throw new ServiceDirectusInternalServerError($e->getMessage());
        } catch (ClientException $e) {
            match ($e->getCode()) {
                400 => throw new ServiceDirectusInvalidDataException($e->getMessage()),
            };
        }
    }
}
