<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload d'une Série</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php 
$filmID = $_GET['filmID'] ;
$title = $_GET['titre'] ;
$saison = $_GET['saison'] ;
?>

<div class = "main">

        <div class = "mainSidebar">
             <?php  include('sideBar.html')?>
        </div>

    <div class="mainContainer">
        <h2>Upload d'un Épisode de Série</h2>
        <div class = "formContainer">
        <form id="uploadForm" action="<?php echo 'convert.php?filmID=' . urlencode($filmID); ?>" method="POST" enctype="multipart/form-data" onsubmit="showLoader()">
            <input type="hidden" name="type" value="serie">

            <input type="hidden" id="film_id" name="type" value="film">

            <label>Nom de la série :</label>
            <input type="text" id="serie_name" name="serie_name" placeholder="Ex: GameOfThrones" readonly>

            <label>Saison :</label>
            <input type="number" id="season" name="season" min="1" readonly>

            <label>Épisode :</label>
            <input type="number" id="episode" name="episode" min="1" readonly>

             

            <label>Choisissez une vidéo :</label>
            <input type="file" name="video" accept="video/mp4,video/x-m4v,video/*" required>

            <button type="submit">Convertir</button>
        </form>
        </div>

    </div>

    <!-- Loader -->
    <div class="loader-container" id="loader">
        <div class="loader"></div>
    </div>

    <script>
        function showLoader() {
            document.getElementById('loader').style.display = "flex";
            document.getElementById('uploadForm').style.opacity = "0.5";
            document.querySelector("button").disabled = true;
        }

         // Fonction pour récupérer les paramètres d'URL
         function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
        }


        // Récupération du paramètre "nom" et insertion dans l'input


        document.getElementById("serie_name").value = getQueryParam("titre") || "";
        document.getElementById("season").value = getQueryParam("saison") || "";
        document.getElementById("episode").value = getQueryParam("episode") || "";
        document.getElementById("film_id").value = getQueryParam("filmID") || "";

    </script>
</body>
</html>
