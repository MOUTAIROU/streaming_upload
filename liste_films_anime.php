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
    
    <div class = "main">

        <div class = "mainSidebar">
             <?php  include('sideBar.html')?>
        </div>
        <div class = "mainContainer">
               
        <h2>Liste des film</h2>

<div class="searchFormContent">
        <form id="searchForm" class = "searchForm" method="POST" action="">
        <label for="search">Recherche :</label>
        <input type="text" id="search" name="search">

        <label for="category">Catégorie :</label>
        <select id="category" name="category">
        <option value="">recherche par Catégorie</option>
        <option value="univers animés">univers animés</option>
        <option value="univers manga"> univers manga</option>
        
        </select>

        <button type="submit">Rechercher</button>
        </form>
</div>

                <div class = "formContainer">

                   <div id="acteursContainer"></div>
                
                </div>
               
        </div>
   
    </div>

    


    <script>
   
   const myVar = <?php echo json_encode($myVar); ?>;

   async function fetchActeurs() {

      
        try {
            const response = await fetch(`${myVar}/film/getfilmlisteanime`, {
                method: "GET",
                headers: {
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                }
            });

            if (!response.ok) {
                throw new Error("Erreur lors de la récupération des acteurs !");
            }

            const data = await response.json(); // Convertir la réponse en JSON

            acteurUsersLists = data.message


            // Afficher les acteurs sur la page
            afficherActeurs(data.message.reverse());
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
                     <div class="acteurs-item"><img src="${myVar}/${acteur.image}" alt="${acteur.titre}" class="realisateur-image"/></div>
                     <div class="acteurs-info">
                     <h3> Nom : ${acteur.title}</h3>
                     
                     <div> ${acteur.description}</div>
                     <div>
                     <h3> Genre : ${acteur.genre}</h3>
                     <h3> scenes : ${acteur.scenes} / Langue : ${acteur.langue} / Time : ${acteur.time}</h3>
                       </div>
                        <div class="acteurs-container-a">
                            <a href ="regarde-video-film.php?filmID=${acteur.url}&title=${acteur.title}">Voir les video</a>
                            <a href ="${acteur.BandeAnnonce}">Voir la band d'annoce </a>
                            <a href ="enregistrer-un-film-modifier.php?filmID=${acteur.film_id}&title=${acteur.title}&image=${acteur.image}&image2=${acteur.image2}&des=${acteur.description}">Voir les information </a>
                            <a href ="upload_film.php?filmID=${acteur.film_id}&title=${acteur.title}">Modifier la video </a>
                            <a href ="enregistrer-un-film-etape-3.php?filmID=${acteur.film_id}">Les acteurs </a>
                            <a href ="enregistrer-un-realisateur-modifier.php?filmID=${acteur.film_id}"> Le realisateur </a>
                            <a href ="enregistrer-un-film-etape-4-modifier.php?filmID=${acteur.film_id}">Modifier scene </a>
                           
                            <span onclick="supprimerFilm('${acteur.film_id}')">Supprimer le film </span>


                         </div>                   
                    </div>
                  
                    </div>
                `;


                acteursContainer.appendChild(listItem);

               

            });
        }

        async function supprimerFilm(film) {
       
        if (confirm("Voulez-vous vraiment supprimer ce film ?")) {

        try {
                const response = await fetch(`${myVar}/film/delete_film`, {
                    method: "POST",
                    headers: {
                        "Accept": "application/json",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ filmID:film }) 
                });

                if (!response.ok) {
                    throw new Error("Erreur lors de la récupération des acteurs !");
                }

                alert("Le film a été supprimé avec succès !")
                

            } catch (error) {
                console.error("Erreur :", error);
            }

        
        }
        }  

    // Appeler la fonction immédiatement et toutes les 2 minutes (120 000 ms)
    fetchActeurs();
    setInterval(fetchActeurs, 20000);

    function formatNumber(n) {
      return n.toString().padStart(2, '0');
     }



     document.getElementById('searchForm').addEventListener('submit', async function(event) {
            event.preventDefault();
            
            const searchValue = document.getElementById('search').value;
            const categoryValue = document.getElementById('category').value;
            
        

            
            try {
                const response = await fetch(`${myVar}/film/searchanime`, {
                    method: "POST",
                    headers: {
                        "Accept": "application/json",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ searchValue:searchValue,categoryValue: categoryValue }) 
                });

                if (!response.ok) {
                    throw new Error("Erreur lors de la récupération des acteurs !");
                }

                const data = await response.json(); // Convertir la réponse en JSON

               

               acteurUsersLists = data.message


            // Afficher les acteurs sur la page
                afficherActeurs(data.message.reverse());

                

            } catch (error) {
                console.error("Erreur :", error);
            }

        });

                 
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
        object-fit: cover;
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
        font-size: 13px;
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

    .jssis{
        display:flex
    }

    .jssis h3{
        margin-right:10px
    }

    .acteurs-container-a a{
    padding: 2px;
    }


    .searchFormContent{
        display: flex;
        justify-content: center;
        gap: 10px;
    }
    .searchForm {
            display: inline-block;
            padding-bottom: 28px;
            align-items: center;
            gap: 10px;
        }
        .searchForm input, .searchForm select, .searchForm button {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin: 0px 10px;
        }
        .searchForm button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
            margin: 0px 10px;
        }
        .searchForm  button:hover {
            background-color: #0056b3;
        }

    </style>

    
    
    
</body>
</html>
