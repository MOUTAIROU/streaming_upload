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
                <h2>Enregistrer un film Etape: 1/6</h2>
                <div class = "formContainer">
                <form id="filmForm" method="post" enctype="multipart/form-data">

                    <div  class="film-prolfile">
                    <img id="filmPreview" class="preview" style="display:none;" />
                    </div>  

                    <label for="image" class="custom-file-upload">
                    Choisir une image
                    <input type="file" id="image" name="image" accept="image/*" required onchange="previewImage(event, 'realisateur')">
                    </label>
                    
                    <label for="filmName">Nom du film :</label>
                    <input type="text" id="filmName" name="filmName" required>

                     
                    <label for="filmName">Description du film:</label>
                     <textarea class="filmDescription" id="filmDescription" name="filmDescription" rows="4" cols="50" required></textarea><br><br>
                     
                     <button type="submit">Enregistrer</button>
                   
                    </form>
                </div>
               
        </div>
   
    </div>

    


    <script>


                    // Récupérer l'ID du film depuis l'URL
                    const urlParams = new URLSearchParams(window.location.search);
                    const filmID = urlParams.get("filmID");
                    const title = urlParams.get("title");
                    const image = urlParams.get("image");
                    const des = urlParams.get("des");
                    

                    // Récupération du paramètre "nom" et insertion dans l'input
                    document.getElementById("filmName").value = title || "";
                    document.getElementById("filmDescription").value = des || "";
                    document.getElementById("filmPreview").src =  "http://localhost:8090/"+image;
                    document.getElementById("filmPreview").style.display = "block";
                   


        
                function previewImage(event, where) {
                var preview = document.getElementById('filmPreview');
                preview.src = URL.createObjectURL(event.target.files[0]);
                preview.style.display = 'block';  // Afficher l'image
                console.log("Prévisualisation du réalisateur: " + where);
                }

                
                

                document.getElementById("filmForm").addEventListener("submit", async function(event) {
                    event.preventDefault(); // Empêcher le rechargement de la page
                   
                    let currentDate = Date.now()

                   
                    let formData = new FormData();

                    
                    formData.append("filmID", filmID);
                    formData.append("currentDate", currentDate);
                    formData.append("lien", removeSpecialCharatere(document.getElementById("filmName").value)+'Image'+currentDate+'.'+get_url_extension(document.getElementById("image").files[0].name));
                    formData.append("filmName", document.getElementById("filmName").value);
                    formData.append("filmDescription", document.getElementById("filmDescription").value);
                    formData.append("file", document.getElementById("image").files[0]);
                   

                    try {
                    let response = await fetch("http://localhost:8090/film/changefilm", {
                    method: "POST",
                    body: formData
                    });

                    if (response.ok) {
                    let result = await response.json();


                    if(result.status == 200){

                         window.location.href = "enregistrer-un-film-etape-2.php?filmID=" + result.message.id;

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
