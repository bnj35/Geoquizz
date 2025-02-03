<?php

$MAPILLARY_ACCESS_TOKEN = "MLY|28351144127864531|e36963327cfce707cfdb4d24b49155ff";
$DIRECTUS_ACCESS_TOKEN = "7VFjISvSwdi3OqhQ_CKojjrZxZfaq_vF";
$DIRECTUS_API_URL = "http://host.docker.internal:8055";
$IMG_DIR = "images";
$SERIES_ID = 1;
$BBOX_NANCY = "-74.260381,40.554459,6.207352,48.896195";

if (!is_dir($IMG_DIR)) {
    mkdir($IMG_DIR, 0777, true);
}

function getMapillaryImages($limit = 5)
{
    global $MAPILLARY_ACCESS_TOKEN, $BBOX_NANCY;

    $url = "https://graph.mapillary.com/images?bbox=$BBOX_NANCY&access_token=$MAPILLARY_ACCESS_TOKEN&fields=id,make,captured_at,thumb_2048_url,latitude,longitude,computed_geometry&limit=$limit";

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

function uploadToDirectus($filePath, $mapillaryId, $lat, $lon)
{
    global $DIRECTUS_ACCESS_TOKEN, $DIRECTUS_API_URL, $SERIES_ID;

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
        "serie" => $SERIES_ID
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

function main()
{
    $images = getMapillaryImages(5);

    foreach ($images as $image) {
        $imageId = $image["id"];
        $imageUrl = $image["thumb_2048_url"];
        $coordinates = $image["computed_geometry"]["coordinates"];

        $filePath = downloadImage($imageUrl, $imageId);
        if ($filePath) {
            uploadToDirectus($filePath, $imageId, $coordinates[1], $coordinates[0]);
        }
    }
}

main();
?>
