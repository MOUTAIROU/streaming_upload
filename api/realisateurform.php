<?php
header("Content-Type: application/json");

$response = ["success" => false];



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
    $targetDir = "images/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileName = time() . "_" . basename($_FILES["image"]["name"]); // Nom unique
    $targetFilePath = $targetDir . $fileName;
    $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    $allowedTypes = ["jpg", "jpeg", "png", "gif"];
    if (in_array($imageFileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            $response["success"] = true;
            $response["imagePath"] = $targetFilePath;
        } else {
            $response["error"] = "Erreur lors de l'upload du fichier.";
        }
    } else {
        $response["error"] = "Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
    }
} else {
    $response["error"] = "Aucune image reçue.";
}

echo json_encode($response);
?>
