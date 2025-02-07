<?php

$MAPILLARY_ACCESS_TOKEN = "MLY|28351144127864531|e36963327cfce707cfdb4d24b49155ff";
$DIRECTUS_ACCESS_TOKEN = getenv("DIRECTUS_ACCESS_TOKEN");
$DIRECTUS_API_URL = getenv("DIRECTUS_API_URL");
$IMG_DIR = "images";

if (!is_dir($IMG_DIR)) {
    mkdir($IMG_DIR, 0777, true);
}

function getMapillaryImages($bbox, $limit = 5)
{
    global $MAPILLARY_ACCESS_TOKEN;

    $url = "https://graph.mapillary.com/images?bbox=$bbox&access_token=$MAPILLARY_ACCESS_TOKEN&fields=id,make,captured_at,thumb_2048_url,computed_geometry&limit=$limit";

    $response = file_get_contents($url);
    if ($response === FALSE) {
        die("❌ Erreur lors de la requête API Mapillary !");
    }

    $data = json_decode($response, true);
    return $data["data"] ?? [];
}

function downloadImage($imageUrl, $imageId)
{
    global $IMG_DIR;

    $filePath = "$IMG_DIR/$imageId.jpg";
    $imageData = file_get_contents($imageUrl);

    if ($imageData === FALSE) {
        echo "❌ Impossible de télécharger $imageId\n";
        return null;
    }

    file_put_contents($filePath, $imageData);
    echo "✅ Image $imageId téléchargée.\n";

    return $filePath;
}

function uploadToDirectus($filePath, $mapillaryId, $lat, $lon, $serieId)
{
    global $DIRECTUS_ACCESS_TOKEN, $DIRECTUS_API_URL;

    $fileMimeType = mime_content_type($filePath);
    $fileData = [
        "file" => new CURLFile($filePath, $fileMimeType, basename($filePath))
    ];

    $ch = curl_init("$DIRECTUS_API_URL/files");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $DIRECTUS_ACCESS_TOKEN"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fileData);

    $uploadResponse = curl_exec($ch);
    curl_close($ch);

    $uploadResult = json_decode($uploadResponse, true);
    if (!isset($uploadResult["data"]["id"])) {
        echo "❌ Erreur lors de l'upload de l'image: $uploadResponse\n";
        return;
    }

    $fileId = $uploadResult["data"]["id"];
    echo "✅ Image $mapillaryId ajoutée à Directus (ID: $fileId).\n";

    $data = [
        "image" => $fileId,
        "mapillary_id" => $mapillaryId,
        "latitude" => $lat,
        "longitude" => $lon,
        "serie" => $serieId
    ];

    $ch = curl_init("$DIRECTUS_API_URL/items/images");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $DIRECTUS_ACCESS_TOKEN",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    if (isset($result["data"]["id"])) {
        echo "✅ Métadonnées de $mapillaryId enregistrées dans Directus.\n";
    } else {
        echo "❌ Erreur lors de l'ajout des métadonnées: $response\n";
    }
}

function checkSerieExists($serieId)
{
    global $DIRECTUS_API_URL, $DIRECTUS_ACCESS_TOKEN;

    $ch = curl_init("$DIRECTUS_API_URL/items/series/$serieId");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $DIRECTUS_ACCESS_TOKEN"
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $httpCode == 200;
}

function createSerie($name, $description)
{
    global $DIRECTUS_API_URL, $DIRECTUS_ACCESS_TOKEN;

    $data = [
        "nom" => $name,
        "description" => $description
    ];

    $ch = curl_init("$DIRECTUS_API_URL/items/series");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $DIRECTUS_ACCESS_TOKEN",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    return $result["data"]["id"] ?? null;
}


function processImages($args)
{
    list($name, $bbox, $serieId, $limit) = $args;
    if (!checkSerieExists($serieId)) {
        echo "❌ Série avec ID $serieId non trouvée, création de la série...\n";
        $serieId = createSerie($name, "Images de la ville de $name");
    }

    $images = getMapillaryImages($bbox, $limit);

    foreach ($images as $image) {
        $imageId = $image["id"];

        if (isset($image["thumb_2048_url"])) {
            $imageUrl = $image["thumb_2048_url"];
        } else {
            echo "❌ Image $imageId n'a pas de URL de vignette.\n";
            continue;
        }

        if (isset($image["computed_geometry"]["coordinates"])) {
            $coordinates = $image["computed_geometry"]["coordinates"];
            $filePath = downloadImage($imageUrl, $imageId);
            if ($filePath) {
                uploadToDirectus($filePath, $imageId, $coordinates[1], $coordinates[0], $serieId);
            }
        } else {
            echo "❌ Image $imageId n'a pas de coordonnées.\n";
        }
    }
}

function main()
{
    // ! Variables
    $limit = 40;
    $BBOX_NANCY = "6.147186,48.668404,6.205910,48.707171";
    $BBOX_PARIS = "2.227469,48.788140,2.450647,48.934965";
    $SERIE_ID_NANCY = 1;
    $SERIE_ID_PARIS = 2;

    $NANCY = ["Nancy", $BBOX_NANCY, $SERIE_ID_NANCY, $limit];
    $PARIS = ["Paris", $BBOX_PARIS, $SERIE_ID_PARIS, $limit];
    // ! Variables

    processImages($NANCY);
    processImages($PARIS);
}

main();
