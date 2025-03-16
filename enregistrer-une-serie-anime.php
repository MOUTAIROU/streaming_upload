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

                    <div  class="film-prolfile12">
                    <img id="filmPreview" class="preview12" style="display:none;" />
                    </div>  

                    <label for="image" class="custom-file-upload">
                    Choisir une image
                    <input type="file" id="image" name="image" accept="image/*" required onchange="previewImage(event, 'realisateur')">
                    </label>
                    
                    <label for="filmName">Nom du film :</label>
                    <input type="text" id="filmName" name="filmName" required>

                    <label for="filmName">Note sur 10 :</label>
                    <input type="text" id="filmNote" name="filmNote" placeholder='Ex: 6.6' required>

                     
                    <label for="filmName">Description du film:</label>
                     <textarea class="filmDescription" id="filmDescription" name="filmDescription" rows="4" cols="50" required></textarea><br><br>
                     
                     <label for="scenes">Scènes :</label><br>

                    <div class="sceneContainer">
                           <div class="sceneContent">
                                <div><input type="checkbox" id="scene1" name="scenes[]" value="Action">Action<br></div>
                                <div><input type="checkbox" id="scene2" name="scenes[]" value="Animation"> Animation<br></div>
                                <div><input type="checkbox" id="scene3" name="scenes[]" value="Aventure"> Aventure<br></div>
                                <div><input type="checkbox" id="scene4" name="scenes[]" value="Comédie"> Comédie<br></div>
                                <div><input type="checkbox" id="scene4" name="scenes[]" value="Crime"> Crime<br></div>
                                <div><input type="checkbox" id="scene4" name="scenes[]" value="Documentaire"> Documentaire<br></div>
                                <div><input type="checkbox" id="scene4" name="scenes[]" value="Drame"> Drame<br></div>
                                <div><input type="checkbox" id="scene4" name="scenes[]" value="Familial"> Familial<br></div>
                                <div><input type="checkbox" id="scene4" name="scenes[]" value="Fantastique"> Fantastique<br></div>


                                
                           </div>
                           <div class="sceneContent">
                                <div><input type="checkbox" id="scene4" name="scenes[]" value="Suspense"> Suspense<br></div>
                                <div><input type="checkbox" id="scene4" name="scenes[]" value="Guerre"> Guerre<br></div>
                                <div><input type="checkbox" id="scene4" name="scenes[]" value="Histoire"> Histoire<br></div>
                                <div><input type="checkbox" id="scene4" name="scenes[]" value="Horreur"> Horreur<br></div>
                                <div><input type="checkbox" id="scene4" name="scenes[]" value="Musical"> Musical<br></div>
                                <div><input type="checkbox" id="scene4" name="scenes[]" value="Mystère"> Mystère<br></div>
                                <div><input type="checkbox" id="scene4" name="scenes[]" value="Policer"> Policer<br></div>
                                <div><input type="checkbox" id="scene4" name="scenes[]" value="Science-fiction">Science-fiction<br></div>
                                <div><input type="checkbox" id="scene4" name="scenes[]" value="Thriller"> Thriller<br></div>
                                <div><input type="checkbox" id="scene4" name="scenes[]" value="Wertern"> Wertern<br></div>
                           </div>
                    </div>

                  
                    <label for="langue">Langue du Film :</label>
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

        
        
                function previewImage(event, where) {
                var preview = document.getElementById('filmPreview');
                preview.src = URL.createObjectURL(event.target.files[0]);
                preview.style.display = 'block';  // Afficher l'image
                console.log("Prévisualisation du réalisateur: " + where);
                }

                
                

                document.getElementById("filmForm").addEventListener("submit", async function(event) {
                    event.preventDefault(); // Empêcher le rechargement de la page

                        // Récupérer les scènes sélectionnées
                        var scenes = [];
                        $('input[name="scenes[]"]:checked').each(function() {
                        scenes.push($(this).val());
                        });

                        var langue = $('#langue').val();
                        var note = $('#filmNote').val();

                   
                    let currentDate = Date.now()

                   
                    let formData = new FormData();

                           
                    formData.append("currentDate", currentDate);
                    formData.append("langue", langue);
                    formData.append("note", note);
                    formData.append("scenes", scenes);
                    formData.append("lien", removeSpecialCharatere(document.getElementById("filmName").value)+'Image'+currentDate+'.'+get_url_extension(document.getElementById("image").files[0].name));
                    formData.append("filmName", document.getElementById("filmName").value);
                    formData.append("filmDescription", document.getElementById("filmDescription").value);
                    formData.append("file", document.getElementById("image").files[0]);
                   
                   
                    try {
                    let response = await fetch("http://localhost:8090/serie/createserieanime", {
                    method: "POST",
                    body: formData
                    });

                    if (response.ok) {
                    let result = await response.json();


                    if(result.status == 200){

                         window.location.href = "enregistrer-une-serie-etape-2.php?filmID=" + result.message.id;

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
