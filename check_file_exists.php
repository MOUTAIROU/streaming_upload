<?php
header('Content-Type: application/json');

// Récupération des données JSON envoyées
$data = json_decode(file_get_contents("php://input"), true);

// Variables nécessaires
$accountId = "6d1230aae13c";
$authToken = isset($data["authToken"]) ? $data["authToken"] : null;
$apiUrl = isset($data["apiUrl"]) ? $data["apiUrl"] : null;
$bucketName = isset($data["bucketName"]) ? $data["bucketName"] : null;
$bucketId = isset($data["bucketId"]) ? $data["bucketId"] : null;
$fileName = isset($data["fileName"]) ? $data["fileName"] : null;

// Vérification des paramètres
if (!$authToken || !$apiUrl || !$bucketName || !$fileName) {
    echo json_encode(["exists" => false, "error" => "Paramètres manquants"]);
    exit;
}

// Étape 1 : Récupérer la liste des buckets
$ch = curl_init("{$apiUrl}/b2api/v2/b2_list_buckets");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: {$authToken}",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "accountId" => $accountId
]));
curl_setopt($ch, CURLOPT_CAINFO, __DIR__ . '/cacert.pem');

$response = curl_exec($ch);
if ($response === false) {
    echo json_encode(["exists" => false, "error" => "Erreur curl b2_list_buckets: " . curl_error($ch)]);
    exit;
}

$buckets = json_decode($response, true);
if (!isset($buckets["buckets"])) {
    echo json_encode([
        "exists" => false,
        "error" => "Erreur API b2_list_buckets: " . (isset($buckets["message"]) ? $buckets["message"] : "Réponse invalide")
    ]);
    exit;
}

// Trouver le bucketId correspondant
$bucketId = null;
foreach ($buckets["buckets"] as $bucket) {
    if ($bucket["bucketName"] === $bucketName) {
        $bucketId = $bucket["bucketId"];
        break;
    }
}

if (!$bucketId) {
    echo json_encode(["exists" => false, "error" => "Bucket non trouvé"]);
    exit;
}

// Étape 2 : Vérifier l'existence du fichier
$ch = curl_init("{$apiUrl}/b2api/v2/b2_list_file_names");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: {$authToken}",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "bucketId" => $bucketId,
    "startFileName" => $fileName,
    "maxFileCount" => 1
]));
curl_setopt($ch, CURLOPT_CAINFO, __DIR__ . '/cacert.pem');

$response = curl_exec($ch);
if ($response === false) {
    echo json_encode(["exists" => false, "error" => "Erreur curl b2_list_file_names: " . curl_error($ch)]);
    exit;
}

$result = json_decode($response, true);
$exists = false;

if (isset($result["files"][0]["fileName"]) && $result["files"][0]["fileName"] === $fileName) {
    $exists = true;
}

// DEBUG : écrire dans un fichier temporaire
file_put_contents("debug_result.json", json_encode($result, JSON_PRETTY_PRINT));
echo json_encode(["exists" => $exists]);
?>
