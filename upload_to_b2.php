<?php
header("Content-Type: application/json");

// Vérifie que le fichier est bien envoyé
if (!isset($_POST["filePath"])) {
    echo json_encode(["error" => "Fichier non spécifié"]);
    exit;
}

$filePath = $_POST["filePath"];

// 1️⃣ Obtenir le token et l'apiUrl
$authResponse = file_get_contents("b2auth.php");

echo json_encode($authResponse );
$authData = json_decode($authResponse, true);

if (!isset($authData["authorizationToken"]) || !isset($authData["apiUrl"])) {
    echo json_encode(["error" => "Échec de l'authentification Backblaze"]);
    exit;
}

// 2️⃣ Obtenir l'uploadUrl et authToken
$uploadData = json_decode(file_get_contents("get_upload_url.php"), true);
if (!isset($uploadData["uploadUrl"]) || !isset($uploadData["authorizationToken"])) {
    echo json_encode(["error" => "Échec de la récupération de l'upload URL"]);
    exit;
}

echo json_encode($uploadData);
// 3️⃣ Lire le fichier
$fileContent = file_get_contents($filePath);
$fileName = basename($filePath);

// 4️⃣ Envoyer le fichier à Backblaze
$options = [
    "http" => [
        "header" => [
            "Authorization: " . $uploadData["authorizationToken"],
            "X-Bz-File-Name: " . urlencode($fileName),
            "Content-Type: b2/x-auto",
            "X-Bz-Content-Sha1: " . sha1_file($filePath)
        ],
        "method" => "POST",
        "content" => $fileContent,
        "ignore_errors" => true
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($uploadData["uploadUrl"], false, $context);

if ($response === FALSE) {
    echo json_encode(["error" => "Échec de l'upload sur Backblaze"]);
} else {
    echo json_encode(["success" => true, "response" => json_decode($response, true)]);
}
?>
