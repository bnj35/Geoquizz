<?php

namespace geoquizz\core\services\directus;

use GuzzleHttp\Client;
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
        } catch (\Exception $e) {
            return [
                'error' => 'SerieId not found',
                'message' => $e->getMessage()
            ];
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
        } catch (\Exception $e) {
            return [
                'error' => 'Images not found',
                'message' => $e->getMessage()
            ];
        }
    }
}
