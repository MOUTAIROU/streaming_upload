<?php
// Spécifier le dossier HLS
$hlsFolder = $_GET['folder'];  // Dossier passé en paramètre (par exemple, via l'URL)
$files = [];

if (is_dir($hlsFolder)) {
    $dir = opendir($hlsFolder);
    while (($file = readdir($dir)) !== false) {
        if ($file != "." && $file != "..") {
            $files[] = $file;
        }
    }

    // 🔥 TRIER LES FICHIERS PAR ORDRE NATUREL
    natsort($files);
    $files = array_values($files); // Réindexer le tableau après le tri

    
    closedir($dir);
}

header('Content-Type: application/json');
echo json_encode($files);
?>
