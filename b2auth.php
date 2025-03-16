<?php
header("Access-Control-Allow-Origin: *"); // Autorise toutes les origines (CORS)
header("Content-Type: application/json");

$accountId = "6d1230aae13c";
$applicationKey = "00502e72d83a71a2e24349607847201cd70bcf06b7";
$credentials = base64_encode("$accountId:$applicationKey");
$url = "https://api.backblazeb2.com/b2api/v2/b2_authorize_account";

$options = [
    "http" => [
        "header" => "Authorization: Basic $credentials",
        "method" => "GET"
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);

if ($response === FALSE) {
    echo json_encode(["error" => "Erreur de connexion à Backblaze B2"]);
} else {
    echo $response;
}
?>


