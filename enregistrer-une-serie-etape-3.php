<!DOCTYPE html>
<html lang="fr">
   

<?php
$myVar = getenv("NOM_VARIABLE"); // Ou $_ENV["MY_ENV_VAR"]
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enregistrer un film</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div  class = "main">

       <div class = "mainSidebar">
             <?php  include('sideBar.html')?>
        </div>
        <div class = "mainContainer">
             <h2>Ajouter aux moins 3 acteurs du film  3/3</h2>
            
             <div class = "formContainer">
               <div id = "acteursContainer"> </div>
                <div  class = "btnContainer">
                        <button type="button" id="addRealisateurBtn">Ajouter les acteurs</button>
                        <button type="button" id="btnSuivant">Suivant</button>
                </div>
             </div>
          
           

   
        <div>

       

   

     

       

            <div id="myModal"class="loader-container myModal" >
                    <div class="modal-content">
                            <div  class="modal-container">
                                    <span class="close">&times;</span>
                                    <div class="modal-title" id="modal-title"> <span>Ajouter le realisateur</span></div>
                                    <p id="statusMessage"></p>
                                    <form  id="realisateurForm" class="modal-form"  method="post" enctype="multipart/form-data" >
                                                <div  class="modal-prolfile">
                                                <img id="preview" class="preview" style="display:none;" />
                                                </div>  

                                                <label for="image" class="custom-file-upload">
                                                Choisir une image
                                                <input type="file" id="image" name="image" accept="image/*" required onchange="previewImage(event)">
                                                </label>



                                                <div class="custom-imput-content">
                                                    <label for="name">Nom:</label><br>
                                                    <input type="text" id="filmName" name="filmName" required><br><br>
                                                </div>
                                                <div class="custom-imput-content">
                                                <label for="bio">Biographie:</label><br>
                                                <textarea id="filmDescription" name="filmDescription" rows="4" cols="50" required></textarea><br><br>
                                                </div>

                                            
                                                <button type="submit" name="realisateur">Envoyer</button>
                                    </form>

                            </div>
                      </div>
                </div>

   

    </div>

    <script>

const myVar = <?php echo json_encode($myVar); ?>;

        let realisateurs = {} ; // Tableau pour stocker les réalisateurs
        let acteurUsersLists = []

        var modal = document.getElementById("myModal");
        var btn = document.getElementById("addRealisateurBtn");
        var btnSuivant = document.getElementById("btnSuivant");

        btnSuivant.onclick = function() {
            

           if(acteurUsersLists.length < 3) {

            alert("Vous devez ajouter aux moins trois acteurs")
           }else{
            alert("La série a été bien créée. Allez dans le menu des épisodes et recherchez la série pour créer les saisons, puis les épisodes.")
           }
           
        }


        var span = document.getElementsByClassName("close")[0];
        

        btn.onclick = function() {
            
            modal.style.display = "block";
        }

       
       
        
        span.onclick = function() {
            modal.style.display = "none";
        }
        
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
                modalActeur.style.display = "none";
            }
        }


        async function fetchActeurs() {

            // Récupérer l'ID du film depuis l'URL
            const urlParams = new URLSearchParams(window.location.search);
                    const filmID = urlParams.get("filmID");

            try {
                const response = await fetch(`${myVar}/film/getfilmacteur`, {
                    method: "POST",
                    headers: {
                        "Accept": "application/json",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ filmID: filmID }) 
                });

                if (!response.ok) {
                    throw new Error("Erreur lors de la récupération des acteurs !");
                }

                const data = await response.json(); // Convertir la réponse en JSON

                acteurUsersLists = data.message
                // Afficher les acteurs sur la page
                afficherActeurs(data.message);
            } catch (error) {
                console.error("Erreur :", error);
            }

        }

        function afficherActeurs(acteurs) {
            let acteursContainer = document.getElementById("acteursContainer");
            acteursContainer.innerHTML = ""; // Vider la liste précédente

            acteurs.forEach(acteur => {
                let listItem = document.createElement("li");

                listItem.innerHTML = `
                    <div class="acteurs-container">
                     <div class="acteurs-item"><img src="${myVar}/${acteur.image}" alt="${acteur.nom}" class="realisateur-image"></div>
                     <div class="acteurs-info">
                    <h3>${acteur.nom}</h3>
                    <p>${acteur.biographie}</p>
                    </div>
                    </div>
                `;


                acteursContainer.appendChild(listItem);

               

            });
        }


        // Appeler la fonction immédiatement et toutes les 2 minutes (120 000 ms)
        fetchActeurs();
        setInterval(fetchActeurs, 200000000);
        
        function previewImage(event, where) {

            console.log(event.target.files[0])
            var preview = document.getElementById('preview');
            preview.src = URL.createObjectURL(event.target.files[0]);
            preview.style.display = 'block';  // Afficher l'image
            console.log("Prévisualisation du réalisateur: " + where);
        }

          

        document.getElementById("realisateurForm").addEventListener("submit", async function(event) {
                    event.preventDefault(); // Empêche le rechargement de la page

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
                    let response = await fetch(`${myVar}/film/filmacteur`, {
                    method: "POST",
                    body: formData
                    });

                    if (response.ok) {
                    let result = await response.json();


                    if(result.status == 200){

                        //alert("La série a été bien créée. Allez dans le menu des épisodes et recherchez la série pour créer les saisons, puis les épisodes.")

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
 
        

       

        

                // Gestionnaire d'événements pour l'input du réalisateur
        document.getElementById("image").addEventListener("change", function(event) {
        previewImage(event, 'realisateur');
        });

        // Gestionnaire d'événements pour l'input de l'acteur
        document.getElementById("acteurImage").addEventListener("change", function(event) {
        previewActeur(event, 'acteur');
        });


        document.getElementById("image").addEventListener("change", function(event) {
        previewImage(event, 'realisateur');
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
    acteurs-container
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

    #acteursContainer li{
        list-style: none;
        border: 2px solid #020101;
        border-bottom: 0px;
    }

    #acteursContainer li:last-child {
    border-bottom: 2px solid #020101 !important; /* Bordure de 2px de couleur noire */
     }

    #acteursContainer{
     margin-bottom: 20px;   
    }

    .acteurs-info{
        padding: 0px 10px;  
    }

    .btnContainer button{
        width: 48%;
    }

    </style>
    
</body>
</html>
