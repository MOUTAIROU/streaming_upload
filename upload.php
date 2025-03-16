<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $output_name = $_POST['output_name']; // Nom de la vidéo
    $output_dir = $_POST['output_dir']; // Répertoire de destination

    // Vérification si le fichier a été uploadé
    if (isset($_FILES['video']) && $_FILES['video']['error'] == 0) {
        // Créer le répertoire si nécessaire
        if (!file_exists($output_dir)) {
            mkdir($output_dir, 0777, true);
        }

        $file_name = $_FILES['video']['name'];
        $file_tmp = $_FILES['video']['tmp_name'];
        $file_size = $_FILES['video']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Définir une taille maximale pour les fichiers
        $max_size = 2 * 1024 * 1024 * 1024; // 2 Go en octets
        if ($file_size > $max_size) {
            die('Erreur : Le fichier dépasse la taille maximale de 2 Go.');
        }

        var_dump($output_name);
        var_dump( $file_ext);
        
        // Déplacer le fichier vers le répertoire de destination
        $file_dest = $output_dir . DIRECTORY_SEPARATOR . $output_name . '.' . $file_ext;
        if (move_uploaded_file($file_tmp, $file_dest)) {
            echo "Fichier téléchargé avec succès !<br>";

            // Exécuter un script shell pour traiter la vidéo (par exemple, conversion)
            // Nous appelons un fichier shell ou PowerShell ici pour le traitement
            $script_path = "convert.sh"; // ou "convert.ps1" pour PowerShell
            $command = "bash $script_path $file_dest"; // Commande pour exécuter le script .sh

            // Si vous utilisez PowerShell (sur Windows), remplacez par :
            // $command = "powershell -ExecutionPolicy Bypass -File convert.ps1 $file_dest";

            // Exécuter le script
            $output = shell_exec($command);

            // Afficher le résultat de l'exécution du script
            echo "<pre>$output</pre>";
        } else {
            echo "Erreur : Impossible de déplacer le fichier téléchargé.";
        }
    } else {
        echo "Erreur : Aucun fichier téléchargé ou une erreur est survenue.";
    }
}
?>
