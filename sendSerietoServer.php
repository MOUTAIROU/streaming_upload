<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uploadDir = "uploads/";
    $outputDir = "output_hls/";

    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    if (!is_dir($outputDir)) mkdir($outputDir, 0777, true);

    $fileInfo = pathinfo($_FILES["video"]["name"]);
    $fileExtension = strtolower($fileInfo['extension']);
    $allowedExtensions = ['mp4', 'm4v', 'webm', 'ogg', 'mkv'];
    
    if (!in_array($fileExtension, $allowedExtensions)) {
        die("Format de fichier non autorisé.");
    }

    if ($_POST["type"] === "film") {
        $outputDir .= "films/" . preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST["film_name"]) . "/";
    } else {
        $serieName = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST["serie_name"]);
        $season = str_pad((int)$_POST["season"], 2, '0', STR_PAD_LEFT);
        $episode = str_pad((int)$_POST["episode"], 2, '0', STR_PAD_LEFT);
        $outputDir .= "series/{$serieName}/Saison_{$season}/Episode_{$episode}/";
    }

    if ($_POST["type"] == "film") {
        $filmName = $_POST["film_name"];
        $outputPath = $outputDir . $filmName . ".m3u8";
        $successMessage = "Film \"$filmName\" bien enregistré et converti ! 🎬";
    } else {
        $outputPath = $outputDir . "E" . $episode . ".m3u8";
        $successMessage = "Série \"$serieName\" Saison $season Épisode $episode bien enregistré et converti ! 📺";
    }

    if (!is_dir($outputDir)) mkdir($outputDir, 0777, true);
    
    $videoPath = $uploadDir . basename($_FILES["video"]["name"]);
    move_uploaded_file($_FILES["video"]["tmp_name"], $videoPath);
    $outputPath = $outputDir . "index.m3u8";

    
    

    $ffmpegCmd = "ffmpeg -i \"$videoPath\" -codec: copy -start_number 0 -hls_time 10 -hls_list_size 0 -f hls \"$outputPath\"";
    exec($ffmpegCmd);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversion Terminée</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
</head>
<body>
    <div class="container">
        <h2>Conversion réussie ! 🎉</h2>
        <h2 class="success"><?= $successMessage ?> 🎉</h2>
        <video id="videoPlayer" controls autoplay></video>
        <script>
            var video = document.getElementById('videoPlayer');
            if (Hls.isSupported()) {
                var hls = new Hls();
                hls.loadSource('<?= $outputPath ?>');
                hls.attachMedia(video);
            } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                video.src = '<?= $outputPath ?>';
            }
        </script>
        <a href="<?= $outputPath ?>" class="download-btn" target="_blank">Télécharger la vidéo</a>
        <button id="uploadB2" class="upload-btn">📤 Envoyer sur Backblaze</button>
        <div class="progress-container">
    <progress id="uploadProgress" value="0" max="100"></progress>
    <span id="progressText">0%</span>
</div>

        <a href="index.php" class="back-btn">⬅ Retour à l'accueil</a>
    </div>

    <script>
        document.getElementById("uploadB2").addEventListener("click", async function() {
            const filePath = "<?= $outputPath ?>";
            const fileName = "<?= basename($outputPath) ?>";
            
            
            try {


                let basePath = "output_hls/";
              
                
               
                let isFilm = "<?= isset($_POST['type']) ? $_POST['type'] : '' ?>" === "film";

                // Vérification et récupération des valeurs en évitant les erreurs PHP
                let filmName = isFilm ? "<?= isset($_POST['film_name']) ? addslashes($_POST['film_name']) : '' ?>" : "";
                let serieName = !isFilm ? "<?= isset($_POST['serie_name']) ? addslashes($_POST['serie_name']) : '' ?>" : "";
                let season = !isFilm ? "<?= isset($_POST['season']) ? str_pad($_POST['season'], 2, '0', STR_PAD_LEFT) : '' ?>" : "";
                let episode = !isFilm ? "<?= isset($_POST['episode']) ? str_pad($_POST['episode'], 2, '0', STR_PAD_LEFT) : '' ?>" : "";

                // Nettoyage pour éviter les erreurs HTML et caractères spéciaux
                filmName = filmName.replace(/<\/?br\s*\/?>/g, "").replace(/\s+/g, "").trim();
                serieName = serieName.replace(/<\/?br\s*\/?>/g, "").replace(/\s+/g, "").trim();
                season = season.replace(/<\/?br\s*\/?>/g, "").replace(/\s+/g, "").trim();
                episode = episode.replace(/<\/?br\s*\/?>/g, "").replace(/\s+/g, "").trim();

                console.log("Type:", isFilm ? "Film" : "Série");
                console.log("Film Name:", filmName);
                console.log("Serie Name:", serieName);
                console.log("Season:", season);
                console.log("Episode:", episode);


                // Définir le chemin HLS correct
                let hlsFolder = isFilm 
                ? `${basePath}films/${filmName}/`
                : `${basePath}series/${serieName}/Saison_${season}/Episode_${episode}/`;

                  console.log(hlsFolder,basePath)
              
                // Appeler le script PHP pour récupérer la liste des fichiers HLS
                let files = await fetch(`list_hls_files.php?folder=${encodeURIComponent(hlsFolder)}`)
                .then(res => res.json());

                if (!files.length) throw new Error("Aucun fichier HLS trouvé");

                console.log(files);
                 
                // Étape 1 : Récupérer authToken et apiUrl
                let response = await fetch("b2auth.php");
                let authData = await response.json();
                if (!authData.authorizationToken) throw "Erreur d'authentification";

               
                
               
                // Étape 2 : Obtenir l'uploadUrl
                response = await fetch("get_upload_url.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        authToken: authData.authorizationToken,
                        apiUrl: authData.apiUrl
                    })
                });
                let uploadData = await response.json();

               
                if (!uploadData.uploadUrl) throw "Erreur d'obtention de l'upload URL";

                // Étape 3 : Lire le fichier en blob
                let file = await fetch(filePath);
                let blob = await file.blob();
                console.log( uploadData.uploadUrl)

                console.log( uploadData.authorizationToken)
                // Étape 4 : Upload du fichier

                /*
                  let formData = new FormData();
        formData.append("file", blob, fileName);
        formData.append("uploadUrl", uploadData.uploadUrl);
        formData.append("authToken", uploadData.authorizationToken);
        formData.append("path", hlsFolder);  // 🔥 Ajouter le chemin dans FormData

                 
        let uploadFileResponse = await fetch("upload_file.php", {
            method: "POST",
            body: formData
        });

        let result = await uploadFileResponse.json();

        console.log(result)

        
        if (result.error) throw new Error(result.error);

        alert("Fichier HLS envoyé avec succès sur Backblaze !");
                 */
              
                 let totalFiles = files.length; // Nombre total de fichiers


                for (let index = 0; index < totalFiles; index++){


                let file = files[index];


                let filePath = hlsFolder + file; // Chemin local du fichier
                let cloudPath = file; // Garde la structure d'origine

                console.log(`Envoi du fichier : ${filePath} => ${cloudPath}`);


               
                let fileBlob = await fetch(filePath).then(res => res.blob());

                let formData = new FormData();
                formData.append("file", fileBlob, cloudPath);
                formData.append("uploadUrl", uploadData.uploadUrl);
                formData.append("authToken", uploadData.authorizationToken);
                formData.append("path", hlsFolder); // On envoie le chemin de base

                let uploadFileResponse = await fetch("upload_file.php", {
                method: "POST",
                body: formData
                });

                let result = await uploadFileResponse.json();
                if (result.error) throw new Error(result.error);

                console.log(`✅ Fichier ${file} envoyé avec succès !`);
                

                // Calculer le pourcentage basé sur l'index
                let percent = ((index + 1) / totalFiles) * 100;
                document.getElementById("uploadProgress").value = percent;
                document.getElementById("progressText").textContent = Math.round(percent) + "%";

                   console.log(percent)

                }

        alert("Tous les fichiers HLS ont été envoyés avec succès sur Backblaze !");
          
               
            } catch (error) {
                alert("Erreur : " + error);
            }
        });
    </script>
</body>
</html>
