<!DOCTYPE html>
<html lang="fr">
   
<?php 
$filmID = $_GET['filmID'] ;
$title = $_GET['title'] ;
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload d'un Film</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class = "main">

        <div class = "mainSidebar">
             <?php  include('sideBar.html')?>
        </div>
        <div class = "mainContainer">

        <h2>Choisissez la vidéo du film Etape: 5/6</h2>
        
        <div class = "formContainer">
            <form id="uploadForm" action="<?php echo 'convert.php?filmID=' . urlencode($filmID); ?>" method="POST" enctype="multipart/form-data" onsubmit="showLoader()">
                <input type="hidden" name="type" value="film">

                <input type="text" name="film_name" id="film_id" hidden>
                
                <label>Nom du film :</label>
                <input type="text" name="film_name" id="film_name" placeholder="Ex: Avengers" readonly>

                <label>Choisissez la vidéo du film:</label>
                <input type="file" name="video" accept="video/mp4,video/x-m4v,video/*" required>

                <button type="submit">Convertir</button>
            </form>
        </div>
        

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
        document.getElementById("film_name").value = getQueryParam("title") || "";
        document.getElementById("film_id").value = getQueryParam("filmID") || "";


        

    </script>
</body>
</html>
