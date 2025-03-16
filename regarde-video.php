<?php 
    // Ajouter les en-têtes CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");


$filmID = $_GET['filmID'];



     ?>


     
<!DOCTYPE html>
<html lang="fr">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecture de video </title>
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
</head>
<body>
    <div style="text-align: center;">
        <h1 id="h2content"> 🎬</h1>
        <video id="videoPlayer" controls style="width: 80%; max-width: 800px;">
            <source src="<?php echo $filmID; ?>" type="application/x-mpegURL">
        </video>
    </div>

    <script>
        var video = document.getElementById('videoPlayer');

        const urlParams = new URLSearchParams(window.location.search);
            const titre = urlParams.get("title");
            const video_url = urlParams.get("filmID");
            const saison = urlParams.get("saisson");
            const episode = urlParams.get("episode");


            document.getElementById("h2content").innerText = "Lecture " + titre + " Saison " + formatNumber(saison) + " episode " + episode;



        if (Hls.isSupported()) {
            var hls = new Hls();
            hls.loadSource(video_url);
            hls.attachMedia(video);

            hls.on(Hls.Events.MANIFEST_PARSED, function() {
                console.log("HLS Manifest chargé !");
            });

            hls.on(Hls.Events.ERROR, function(event, data) {
                if (data.fatal) {
                    switch(data.fatal) {
                        case Hls.ErrorTypes.NETWORK_ERROR:
                            console.error("Erreur réseau !");
                            break;
                        case Hls.ErrorTypes.MEDIA_ERROR:
                            console.error("Erreur média !");
                            break;
                        case Hls.ErrorTypes.OTHER_ERROR:
                            console.error("Autre erreur !");
                            break;
                        default:
                            console.error("Erreur inconnue");
                            break;
                    }
                }
            });
        } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
            // Pour les navigateurs qui supportent directement HLS (Safari)
            video.src = video_url;
        } else {
            alert('Votre navigateur ne supporte pas HLS');
        }

        function formatNumber(n) {
            return n.toString().padStart(2, '0');
        }

    </script>
</body>
</html>
