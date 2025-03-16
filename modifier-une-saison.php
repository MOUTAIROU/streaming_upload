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

                    <div  class="film-prolfile">
                    <img id="filmPreview" class="preview" style="display:none;" />
                    </div>  

                    <label for="image" class="custom-file-upload">
                    Choisir une image
                    <input type="file" id="image" name="image" accept="image/*" required onchange="previewImage(event, 'realisateur')">
                    </label>
                    
                    <label for="filmName">Saison :</label>
                    <input  type="number" min="1" id="filmName" name="filmName" required>

                    <label for="filmName">Date de sorti de la saison :</label>
                    <input  type="date"  id="seasonDate" name="seasonDate" required>


                     <button type="submit">Enregistrer</button>
                   
                    </form>
                </div>
               
        </div>
   
    </div>

    


    <script>

        
                // Récupérer l'ID du film depuis l'URL
                const urlParams = new URLSearchParams(window.location.search);

                const filmID = urlParams.get("filmID");
                const saison = urlParams.get("saison");
                const titre = urlParams.get("titre");

        
                function previewImage(event, where) {
                var preview = document.getElementById('filmPreview');
                preview.src = URL.createObjectURL(event.target.files[0]);
                preview.style.display = 'block';  // Afficher l'image
                console.log("Prévisualisation du réalisateur: " + where);
                }

                
                async function fetchActeurs() {

      
                        try {
                            const response = await fetch("http://localhost:8090/serie/getSaison", {
                                method: "POST",
                                headers: {
                                    "Accept": "application/json",
                                    "Content-Type": "application/json"
                                },
                                body: JSON.stringify({ filmID:filmID,saison: saison })
                            });

                            if (!response.ok) {
                                throw new Error("Erreur lors de la récupération des acteurs !");
                            }

                            const data = await response.json(); // Convertir la réponse en JSON

                               

                                // Récupération du paramètre "nom" et insertion dans l'input
                                document.getElementById("seasonDate").value = data.message[0].seasonDate || "";
                                document.getElementById("filmName").value = data.message[0].numero || "";
                                document.getElementById("filmPreview").src =  "http://localhost:8090/"+data.message[0].image;
                                document.getElementById("filmPreview").style.display = "block";
                        
                            // Afficher les acteurs sur la page
                            afficherActeurs(data.message);
                        } catch (error) {
                            console.error("Erreur :", error);
                        }

                        }

                fetchActeurs()              

                document.getElementById("filmForm").addEventListener("submit", async function(event) {
                    event.preventDefault(); // Empêcher le rechargement de la page

                     // Récupérer l'ID du film depuis l'URL
                    const urlParams = new URLSearchParams(window.location.search);
                    const filmID = urlParams.get("filmID");
                    let currentDate = Date.now()
                    
                         
                    let formData = new FormData();
                    
                    formData.append("seasonDate", document.getElementById("seasonDate").value);
                    formData.append("currentDate", currentDate);
                    formData.append("filmID", filmID);
                    formData.append("lien", removeSpecialCharatere(document.getElementById("filmName").value)+'Image'+currentDate+'.'+get_url_extension(document.getElementById("image").files[0].name));
                    formData.append("filmName", document.getElementById("filmName").value);
                    formData.append("file", document.getElementById("image").files[0]);
                   
                    
                    try {
                    let response = await fetch("http://localhost:8090/serie/createsaison", {
                    method: "POST",
                    body: formData
                    });

                    if (response.ok) {
                    let result = await response.json();


                    if(result.status == 200){

                         window.location.href = "ajouter-une-episode.php";

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
