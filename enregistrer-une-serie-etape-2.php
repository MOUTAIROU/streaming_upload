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

        <div class = "mainSidebar">   <?php  include('sideBar.html')?></div>
        <div  class = "mainContainer">

                <h2>Ajouter le réalisateur du serie Etape: 2/3</h2>

                <div class = "formContainer">
                    <form id="filmForm" method="post" enctype="multipart/form-data">

                            <div  class="film-prolfile-acteur">
                            <img id="filmPreview" class="preview" style="display:none;" />
                            </div>  

                            <label for="image" class="custom-file-upload">
                            Choisir une image
                            <input type="file" id="image" name="image" accept="image/*" required onchange="previewImage(event, 'realisateur')">
                            </label>

                            <label for="filmName">Nom du réalisateur :</label>
                            <input type="text" id="filmName" name="filmName" required>

                            <label for="filmName">Biographie du réalisateur:</label>
                            <textarea class="filmDescription" id="filmDescription" name="filmDescription" rows="4" cols="50" required></textarea><br><br>




                            <button type="submit">Enregistrer</button>
                    </form>
                </div>
                

                     
        </div>
    


    </div>

    <script>

        
        
                function previewImage(event, where) {
                var preview = document.getElementById('filmPreview');
                preview.src = URL.createObjectURL(event.target.files[0]);
                preview.style.display = 'block';  // Afficher l'image
                console.log("Prévisualisation du réalisateur: " + where);
                }

                
                

                document.getElementById("filmForm").addEventListener("submit", async function(event) {
                    event.preventDefault(); // Empêcher le rechargement de la page
                   
                    let currentDate = Date.now()

                     // Récupérer l'ID du film depuis l'URL
                    const urlParams = new URLSearchParams(window.location.search);
                    const filmID = urlParams.get("filmID");

                   
                    let formData = new FormData();

                    
                    formData.append("currentDate", currentDate);
                    formData.append("filmID", filmID);
                    formData.append("lien", removeSpecialCharatere(document.getElementById("filmName").value)+'Image'+currentDate+'.'+get_url_extension(document.getElementById("image").files[0].name));
                    formData.append("filmName", document.getElementById("filmName").value);
                    formData.append("filmDescription", document.getElementById("filmDescription").value);
                    formData.append("file", document.getElementById("image").files[0]);
                   

                    try {
                    let response = await fetch("http://localhost:8090/film/filmrealisateur", {
                    method: "POST",
                    body: formData
                    });

                    if (response.ok) {
                    let result = await response.json();


                    if(result.status == 200){

                         window.location.href = "enregistrer-une-serie-etape-3.php?filmID=" + result.message.id;

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
    

    

    
    <style>
    .modal-content{
        width: 100%;
        
        justify-self: anchor-center;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;    
    } 


     .modal-container{
       background-color: white;
       padding: 20px;
      }

      .modal-prolfile{
        height: 100px;
        width: 100px;
        background-color: #b9b7b7;
        border-radius: 500px;
        margin: 10px auto;
      }

      .custom-file-upload {
        display: inline-block;
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        text-align: center;
        margin: 0px auto;
        width: 50%;
    }

    .custom-file-upload:hover {
        background-color: #0056b3;
    }
   
    .custom-file-upload input {
        display: none;
    }

    .custom-imput-content{
    padding: 10px 30px;
    }

    .custom-imput-content label{
        padding-bottom: 10px;
    }

    .custom-imput-content input,.custom-imput-content textarea{
        width: 94%;
        margin-top: 10px;
        padding: 10px;
    }

    .custom-imput-content textarea{
        max-width: 397px;
        margin-top: 10px;
    }

    .close,.closeActeur{
        background-color: #252525;
        padding: 3px 13px;
        border-radius: 30px;
        font-size: 31px;
        color: #c4ebf7;
        cursor: pointer;
    }
    
    .modal-title {
    text-align: center;
    padding: 10px;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 23px;
    color: #0006f7;
    }

    .modal-form{
     gap:0px !important;
    }
     
    .preview{
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 500px;  
    }

    .realisateur-item img,.acteurs-item img{
        height: 100px;
        width: 100px; 
    }

    .realisateur-container,.acteurs-container {
        display: flex;   
        padding: 20px;
    }

    .film-prolfile{
      height: 100px;
    width: 100px;
    background-color: #b9b7b7;
    border-radius: 500px;
    margin: 10px auto;  
    }

    </style>
    
</body>
</html>
