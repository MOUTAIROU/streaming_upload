<!DOCTYPE html>
<html lang="fr">
   
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enregistrer un film</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    
    <div class = "main">

        <div class = "mainSidebar">
             <?php  include('sideBar.html')?>
        </div>
        <div class = "mainContainer">
                <h2>Enregistrer une serie Etape: 1/3</h2>
                <div class = "formContainer">
                <form id="filmForm" method="post" enctype="multipart/form-data">

                    <div  class="film-prolfile2">
                    <img id="filmPreview" class="preview3" style="display:none;" />
                    </div>  

                    <label for="image" class="custom-file-upload">
                    Choisir une image
                    <input type="file" id="image" name="image" accept="image/*" required onchange="previewImage(event, 'realisateur')">
                    </label>
                    
                    <label for="filmName">Nom du film :</label>
                    <input type="text" id="filmName" name="filmName" readonly>

                    <label for="filmName">intituler de l'episode :</label>
                    <input type="text" id="intituler" name="intituler" required>

                    <label for="filmName">Durré de la video  :</label>
                    <input type="text" id="time" name="time" required>

                    
                    <label for="filmName">Saison :</label>
                    <input type="number" id="season" name="season" min="1" readonly>

                    <label for="filmName">Episode :</label>
                    <input type="number" id="episode" name="season" min="1" required>

                     
                    <label for="filmName">Description du film:</label>
                     <textarea class="filmDescription" id="filmDescription" name="filmDescription" rows="4" cols="50" required></textarea><br><br>
                     
                     
                    
                    <label for="langue">Langue :</label>
                    <select class="formContainerSelect" id="langue" name="langue" required>
                        <option value="">Choisir une langue</option>
                        <option value="Français">Français</option>
                        <option value="Anglais">Anglais</option>
                        <option value="Espagnol">Espagnol</option>
                        <option value="Allemand">Allemand</option>
                        <option value="Italien">Italien</option>
                    </select><br><br>

                     <button type="submit">Enregistrer</button>
                   
                    </form>
                </div>
               
        </div>
   
    </div>

    


    <script>

        
        
            async function fetchActeurs() {

            // Récupérer l'ID du film depuis l'URL
            const urlParams = new URLSearchParams(window.location.search);

            let filmID = urlParams.get("filmID");
            saisson = urlParams.get("saisson"),
            episode = urlParams.get("episode"),
            title = urlParams.get("title")
            console.log(filmID,saisson,episode,title)

            try {
            const response = await fetch("http://localhost:8090/serie/getserieurlOne", {
            method: "POST",
            headers: {
            "Accept": "application/json",
            "Content-Type": "application/json"
            },
            body: JSON.stringify({ film_id: filmID,season:saisson,episode:episode }) 
            });

            if (!response.ok) {
            throw new Error("Erreur lors de la récupération des acteurs !");
            }

            const data = await response.json(); // Convertir la réponse en JSON



            document.getElementById("filmName").value = title || "";
            document.getElementById("season").value = saisson || "";
            document.getElementById("episode").value = episode || "";
            document.getElementById('langue').value = data.message[0].langue
            document.getElementById("time").value = data.message[0].time || "";
            document.getElementById("intituler").value = data.message[0].intituler || "";
            document.getElementById("filmDescription").value =  data.message[0].des || "";
            document.getElementById("filmPreview").src =  "http://localhost:8090/"+data.message[0].image;
            document.getElementById("filmPreview").style.display = "block";

            // Afficher les acteurs sur la page
            afficherActeurs(data.message);
            } catch (error) {
            console.error("Erreur :", error);
            }

            }

            fetchActeurs()    

                function previewImage(event, where) {
                var preview = document.getElementById('filmPreview');
                preview.src = URL.createObjectURL(event.target.files[0]);
                preview.style.display = 'block';  // Afficher l'image
                console.log("Prévisualisation du réalisateur: " + where);
                }

                
                
                        // Fonction pour récupérer les paramètres d'URL
                        function getQueryParam(param) {
                        const urlParams = new URLSearchParams(window.location.search);
                        return urlParams.get(param);
                        }


                        // Récupération du paramètre "nom" et insertion dans l'input

                        document.getElementById("filmName").value = getQueryParam("titre") || "";
                        document.getElementById("season").value = getQueryParam("saison") || "";


                document.getElementById("filmForm").addEventListener("submit", async function(event) {
                    event.preventDefault(); // Empêcher le rechargement de la page

                        // Récupérer les scènes sélectionnées
                      

                        var langue = $('#langue').val();
                        var season = $('#season').val();
                        var episode = $('#episode').val();
                        var title =  document.getElementById("filmName").value 

                   
                    let currentDate = Date.now()

                   
                    let formData = new FormData();
                    
                    console.log(getQueryParam("filmID"),season,episode,document.getElementById("filmDescription").value,langue)
                   
          
                    formData.append("currentDate", currentDate);
                    formData.append("film_id", getQueryParam("filmID"));
                    formData.append("intituler", document.getElementById("intituler").value);
                    formData.append("time", document.getElementById("time").value);

                    
                    formData.append("langue", langue);
                    formData.append("season", season);
                    formData.append("episode", episode);
                    formData.append("lien", removeSpecialCharatere(document.getElementById("filmName").value)+'Image'+currentDate+'.'+get_url_extension(document.getElementById("image").files[0].name));
                    formData.append("filmName", document.getElementById("filmName").value);
                    formData.append("filmDescription", document.getElementById("filmDescription").value);
                    formData.append("file", document.getElementById("image").files[0]);
                   
                   
                    try {
                    let response = await fetch("http://localhost:8090/serie/serieurlOne", {
                    method: "POST",
                    body: formData
                    });

                    if (response.ok) {
                    let result = await response.json();
                    

                    if(result.status == 200){

                         window.location.href = `upload_serie.php?filmID=${getQueryParam("filmID")}&saison=${season}&episode=${episode}&titre=${title}`;

                    }
                    } else {
                    alert("Erreur lors de l'enregistrement du film.");
                    }
                    } catch (error) {
                    console.error("Erreur :", error);
                    alert("Une erreur s'est produite.");
                    }
                    
                    
             });

                // Gestionnaire d'événements pour l'input de l'acteur
                document.getElementById("filmImage").addEventListener("change", function(event) {
                                    previewImage(event, 'filmImage');
                });

        function removeSpecialCharatere (text)  {

            return text
                    .replace(/[^a-zA-Z ]/g, "")
                    .replace(/\s/g, "")

        }

                function get_url_extension( url ) {
                return url.split(/[#?]/)[0].split('.').pop().trim();
                }


    </script>
    

    

    
    
    
</body>
</html>
