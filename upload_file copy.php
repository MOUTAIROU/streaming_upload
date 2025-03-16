<?php
header("Content-Type: application/json");

if (!isset($_POST['uploadUrl']) || !isset($_POST['authToken']) || !isset($_FILES['file'])) {
    echo json_encode(["error" => "Paramètres manquants"]);
    exit;
}

$uploadUrl = $_POST['uploadUrl'];
$authorizationToken = $_POST['authToken'];
$file = $_FILES['file'];
$fileName = basename($file['name']);
$fileContent = file_get_contents($file['tmp_name']);
$fileSize = filesize($file['tmp_name']); // Récupérer la taille du fichier

$options = [
    "http" => [
        "header" => [
            "Authorization: $authorizationToken",
            "X-Bz-File-Name: " . rawurlencode($fileName),
            "Content-Type: b2/x-auto",
            "Content-Length: " . $fileSize, // ✅ Ajout de Content-Length
            "X-Bz-Content-Sha1: " . sha1_file($file['tmp_name'])
        ],
        "method" => "POST",
        "content" => $fileContent,
        "ignore_errors" => true
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($uploadUrl, false, $context);

if ($response === FALSE) {
    echo json_encode(["error" => "Erreur lors de l'envoi du fichier"]);
} else {
    echo $response;
}