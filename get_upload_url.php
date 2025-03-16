<?php
/*
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Vérifie si l'authToken est fourni
if (!isset($_GET['authToken'])) {
    echo json_encode(["error" => "Token d'authentification manquant"]);
    exit;
}

$authorizationToken = $_GET['authToken'];
$bucketId = "362db1921340da8a9e41031c"; // Remplace par ton Bucket ID

$url = "https://api.backblazeb2.com/b2api/v2/b2_get_upload_url";

$options = [
    "http" => [
        "header" => [
            "Authorization: Bearer $authorizationToken",
            "Content-Type: application/json"
        ],
        "method" => "POST",
        "content" => json_encode(["bucketId" => $bucketId]),
        "ignore_errors" => true // Pour capturer les erreurs de Backblaze
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);

// Vérifie si la réponse est valide
if ($response === FALSE) {
    echo json_encode(["error" => "Erreur lors de la récupération de l'URL d'upload"]);
} else {
    echo $response;
}
    */
?>


<?php
/*
header("Content-Type: application/json");

if (!isset($_GET['authToken']) || !isset($_GET['apiUrl'])) {
    echo json_encode(["error" => "Paramètres manquants"]);
    exit;
}

$authorizationToken = $_GET['authToken'];
$apiUrl = $_GET['apiUrl']; // Récupéré depuis b2auth.php
$bucketId = "362db1921340da8a9e41031c"; // Remplace par ton vrai bucket ID

$url = "$apiUrl/b2api/v2/b2_get_upload_url";

$options = [
    "http" => [
        "header" => [
            "Authorization: $authorizationToken",
            "Content-Type: application/json"
        ],
        "method" => "POST",
        "content" => json_encode(["bucketId" => $bucketId]),
        "ignore_errors" => true
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);

if ($response === FALSE) {
    echo json_encode(["error" => "Erreur lors de la récupération de l'URL d'upload"]);
} else {
    echo $response;
}
*/
?>


<?php





header("Content-Type: application/json");

// Vérification des données envoyées
$data = json_decode(file_get_contents("php://input"), true);





if (!isset($data['authToken']) || !isset($data['apiUrl'])) {
    echo json_encode(["error" => "Paramètres manquants"]);
    exit;
}


$authorizationToken = $data['authToken'];
$apiUrl = $data['apiUrl'];
$bucketId = "362db1921340da8a9e41031c"; // Remplace par ton vrai bucket ID

$url = "$apiUrl/b2api/v2/b2_get_upload_url";

$options = [
    "http" => [
        "header" => [
            "Authorization: $authorizationToken",
            "Content-Type: application/json"
        ],
        "method" => "POST",
        "content" => json_encode(["bucketId" => $bucketId]),
        "ignore_errors" => true
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);

if ($response === FALSE) {
    echo json_encode(["error" => "Erreur lors de la récupération de l'URL d'upload"]);
} else {
    echo $response;
}

?>