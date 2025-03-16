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
$filePath = $_POST['path'] . $fileName;  // 🔥 Conserver la structure sur B2
$fileContent = file_get_contents($file['tmp_name']);
$fileSize = filesize($file['tmp_name']);


$options = [
    "http" => [
        "header" => [
            "Authorization: $authorizationToken",
            "X-Bz-File-Name: " . rawurlencode($filePath),
            "Content-Type: b2/x-auto",
            "Content-Length: " . $fileSize,
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
    
?>
