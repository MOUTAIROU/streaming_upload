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
                <h2 id ="h2content"></h2>
                <div class = "formContainer">

                   <div id="acteursContainer"></div>
                
                </div>
               
        </div>
   
    </div>

    


    <script>
   
   async function fetchActeurs() {

            // Récupérer l'ID du film depuis l'URL
            const urlParams = new URLSearchParams(window.location.search);
            const filmID = urlParams.get("filmID");
            const saison = urlParams.get("saison");
            const titre = urlParams.get("titre");
      
            
            document.getElementById("h2content").innerText = "Série " + titre +" Saison " + formatNumber(saison) ;


        try {
            const response = await fetch("http://localhost:8090/serie/get_episode_saison", {
                method: "POST",
                headers: {
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ filmID:filmID,saison: saison,titre:titre}) 
            });

            if (!response.ok) {
                throw new Error("Erreur lors de la récupération des acteurs !");
            }

            const data = await response.json(); // Convertir la réponse en JSON

            console.log(data.message)

            acteurUsersLists = data.message
            // Afficher les acteurs sur la page
            afficherActeurs(data.message);
        } catch (error) {
            console.error("Erreur :", error);
        }

}

function afficherActeurs(acteurs) {

           console.log(acteurs)

            let acteursContainer = document.getElementById("acteursContainer");
            acteursContainer.innerHTML = ""; // Vider la liste précédente

            const urlParams = new URLSearchParams(window.location.search);
            const titre = urlParams.get("titre");

            acteurs.forEach(acteur => {


                let listItem = document.createElement("li");

                // Générer dynamiquement les saisons et épisodes
                
                listItem.innerHTML = `
                    <div class="acteurs-container">
                      <div class="acteurs-info">
                     <h3> Saison : ${acteur.season}</h3>
                     <h3>  episode : ${acteur.episode}</h3>
                     <h3> <a href ="regarde-video.php?filmID=${acteur.url}&saisson=${acteur.season}&episode=${acteur.episode}&title=${titre}">Voir la video <a/></h3>
                     <h3> <a href ="upload_serie-etape-1-modifier.php?filmID=${acteur.film_id}&saisson=${acteur.season}&episode=${acteur.episode}&title=${titre}">modifer la video <a/></h3>
                    </div>
                    
                    </div>
                `;


                acteursContainer.appendChild(listItem);

               

            });
        }

    // Appeler la fonction immédiatement et toutes les 2 minutes (120 000 ms)
    fetchActeurs();
    setInterval(fetchActeurs, 20000);

    function formatNumber(n) {
      return n.toString().padStart(2, '0');
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
        font-size:12px
    }

    #acteursContainer li:last-child {
    border-bottom: 2px solid #020101 !important; /* Bordure de 2px de couleur noire */
     }

    #acteursContainer{
     margin-bottom: 20px;   
    }

    .acteurs-info{
        padding: 0px 10px;  
        display:flex
    }

    .btnContainer button{
        width: 48%;
    }

    .jssis{
        display:flex
    }

    .acteurs-info h3{
        margin-right:10px
    }
    </style>

    
    
    
</body>
</html>
