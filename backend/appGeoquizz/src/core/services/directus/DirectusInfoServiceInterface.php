<?php 
namespace geoquizz\core\services\directus ; 

interface DirectusInfoServiceInterface{

    public function getSerieIdByTheme(string $theme):string;
    public function getImagesBySerieId(string $serieId, int $nb_photos): array;
}