<?php

set_time_limit(0);

$myVar = getenv("NOM_VARIABLE"); // Ou $_ENV["MY_ENV_VAR"]

$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uploadDir = "uploads/";
    $outputDir = "output_hls/";

    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    if (!is_dir($outputDir)) mkdir($outputDir, 0777, true);

    $fileInfo = pathinfo($_FILES["video"]["name"]);
    $fileExtension = strtolower($fileInfo['extension']);
    $allowedExtensions = ['mp4', 'm4v', 'webm', 'ogg', 'mkv'];

    if (!isset($_FILES["video"]) || $_FILES["video"]["error"] !== UPLOAD_ERR_OK) {
        die("Erreur lors de l'upload du fichier : " . $_FILES["video"]["error"]);
    }
    
    if (!in_array($fileExtension, $allowedExtensions)) {
        die("Format de fichier non autorisé.");
    }

    if ($_POST["type"] === "film") {
        
        $outputDir .= "films/" . preg_replace('/[^a-zA-Z0-9_-]/u', '', $_POST["film_name"]) . "/";
    } else {
        $serieName = preg_replace('/[^a-zA-Z0-9_-]/u', '', $_POST["serie_name"]);
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
    if (!move_uploaded_file($_FILES["video"]["tmp_name"], $videoPath)) {
        die("Erreur lors du déplacement du fichier !");
    }
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
    <div class = "main">

        <div class = "mainSidebar">
             <?php  include('sideBar.html')?>
        </div>

        <div class = "mainContainer">
                <div class = "formContainer">
                        <h2>Conversion réussie ! 🎉 6/6</h2>
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
                        <a href="index.php" class="back-btn">⬅ Retour à l'accueil</a>
                </div>
                    
        </div>

        
    </div>

    <script>
        document.getElementById("uploadB2").addEventListener("click", async function() {

            const myVar = <?php echo json_encode($myVar); ?>;

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

               

                // Définir le chemin HLS correct
                let hlsFolder = isFilm 
                ? `${basePath}films/${nettoyerTitre(filmName)}/`
                : `${basePath}series/${nettoyerTitre(serieName)}/Saison_${season}/Episode_${episode}/`;

                console.log('hlsFolder',hlsFolder)

              
                // Appeler le script PHP pour récupérer la liste des fichiers HLS
                let files = await fetch(`list_hls_files.php?folder=${encodeURIComponent(hlsFolder)}`)
                .then(res => res.json());

                if (!files.length) throw new Error("Aucun fichier HLS trouvé");

                 
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
                console.log( uploadData)
                // Étape 4 : Upload du fichier

               
              
                 let totalFiles = files.length; // Nombre total de fichiers
               

                

                     
                        for (let index = 0; index < totalFiles; index++){


                                let file = files[index];


                                let filePath = hlsFolder + file; // Chemin local du fichier
                                let cloudPath = file; // Garde la structure d'origine


                                let checkExist = await fetch("check_file_exists.php", {
                                method: "POST",
                                headers: { "Content-Type": "application/json" },
                                body: JSON.stringify({
                                authToken: authData.authorizationToken,
                                apiUrl: authData.apiUrl,
                                bucketId:authData.bucketId,
                                bucketName: "fullrencontre-hls",
                                fileName: hlsFolder+cloudPath
                                })
                                });

                                let checkResult = await checkExist.json();

                                
                                if (checkResult.exists) {
                                console.log(`⏭️ Fichier ${cloudPath} déjà présent, on passe.`);

                                let percent = ((index + 1) / totalFiles) * 100;
                                document.getElementById("uploadProgress").value = percent;
                                document.getElementById("progressText").textContent = Math.round(percent) + "%";

                                console.log(percent)

                                continue; // skip
                                }


                                
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
                     
                     

                     
                    if( isFilm == false){


                                                    

                                const urlParams = new URLSearchParams(window.location.search);
                                const filmID = urlParams.get("filmID");
                               
                                window.location.href = "https://belleckflix.com/set_serie?filmID=" +filmID + "&hlsFolder="+hlsFolder+"&type=Serie&season=" +season+ "&episode="+episode;

                                 /*
                                  try {
                                    const response = await fetch(`${myVar}/serie/serieurl`, {
                                    method: "POST",
                                    headers: {
                                        "Accept": "application/json",
                                        "Content-Type": "application/json"
                                    },
                                    body: JSON.stringify({ 
                                        filmID:filmID,
                                        type: "Serie" ,
                                        hlsFolder:hlsFolder,
                                        season:season,
                                        episode:episode,
                                    }) 
                                    });

                                    if (!response.ok) {
                                    throw new Error("Erreur lors de la récupération des acteurs !");
                                    }

                                    const resutat = await response.json(); // Convertir la réponse en JSON



                                    if(resutat.status == 200){
                                    alert("Tous les fichiers HLS ont été envoyés avec succès sur Backblaze !");
                                    }


                                    } catch (error) {
                                        console.error("Erreur :", error);
                                    } 
                                    */


                                }else{
                                    

                                const urlParams = new URLSearchParams(window.location.search);
                                const filmID = urlParams.get("filmID");

                               
                                window.location.href = "https://belleckflix.com/set_film?filmID=" +filmID + "&hlsFolder="+hlsFolder+"&type=Film";
 
                                /*
                               
                                try {
                                const response = await fetch(`${myVar}/film/filmsurl`, {
                                    method: "POST",
                                    headers: {
                                        "Accept": "application/json",
                                        "Content-Type": "application/json"
                                    },
                                    body: JSON.stringify({ 
                                        filmID:filmID,
                                        type: isFilm ? "Film" : "Série",
                                        hlsFolder:hlsFolder,
                                    }) 
                                });

                                if (!response.ok) {
                                    throw new Error("Erreur lors de la récupération des acteurs !");
                                }

                                const resutat = await response.json(); // Convertir la réponse en JSON



                                if(resutat.status == 200){
                                    alert("Tous les fichiers HLS ont été envoyés avec succès sur Backblaze !");
                                }


                                } catch (error) {
                                console.error("Erreur :", error);
                                }

                                */
                                

                    }


         
          
               
            } catch (error) {
                alert("Erreur : " + error);
            }
        });

        function nettoyerTitre(titre) {
         return titre.replace(/[^a-zA-Z0-9_-]/g, '');
             }
    </script>
</body>
</html>
