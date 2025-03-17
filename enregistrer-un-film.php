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
    
    <div class="main">
        <div class="mainSidebar">
            <?php include('sideBar.html') ?>
        </div>
        <div class="mainContainer">
            <h2>Enregistrer un film Etape: 1/6</h2>
            <div class="formContainer">
                <form id="filmForm" method="post" enctype="multipart/form-data">
                    <div class="film-profile">

                         <div class="list-img123">
                                <div class="list-img124">
                                <div > Image principale</div>
                                <div class="preview2 preview1_color"> <img id="filmPreview2" class="preview2" style="display:none;" /> </div>

                                </div>

                                <div class="list-img125">
                                <div > Image Secondaire</div>
                                <div class="preview1 preview1_color"> <img id="filmPreview" class="preview1" style="display:none;" /></div>

                                </div>
                        
                         </div>
                       
                       
                    </div>  

                    
                    
                    <label for="image2" class="custom-file-upload">
                        Choisir une image principale 
                        <input type="file" id="image2" name="image2" accept="image/*" required onchange="previewImage(event, 'filmPreview2')">
                    </label>

                    <label for="image" class="custom-file-upload">
                        Choisir une image secondaire
                        <input type="file" id="image" name="image" accept="image/*" required onchange="previewImage(event, 'filmPreview')">
                    </label>
                    
                    <label for="filmName">Nom du film :</label>
                    <input type="text" id="filmName" name="filmName" required>

                    <label for="genre">Catégorie du Film :</label>
                    <select id="genre" name="genre"  class="formContainerSelect" required>
                    <option value="">Choisir la Catégorie</option>

                    <option value="moments en famille">moments en famille </option>
                    <option value="Voyages epiques"> Voyages épiques</option>
                    <option value="actions palpitantes">actions palpitantes</option>
                    <option value="frissons assures">frissons assurés</option>
                    <option value="affaires enigmatiques">affaires énigmatiques</option>
                    <option value="coeurs_enflammes">coeurs enflammés</option>
                    <option value="l'enfer de_la_guerre">l'enfer de la guerre</option>
                    <option value="univers fantastiques">univers fantastiques</option>
                    <option value="suspense total">suspense total</option>
                    <option value="histoires mystérieuses">histoires mystérieuses</option>
                    <option value="histoires vraies">histoires vraies</option>
                        
                        
                    </select><br><br>

                    <label for="filmDescription">Description du film :</label>
                    <textarea class="filmDescription" id="filmDescription" name="filmDescription" rows="4" cols="50" required></textarea><br><br>
                    
                    <button type="submit">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>

    <script>

      const myVar = <?php echo json_encode($myVar); ?>;

        function previewImage(event, previewId) {
            var preview = document.getElementById(previewId);
            preview.src = URL.createObjectURL(event.target.files[0]);
            preview.style.display = 'block';
        }

        document.getElementById("filmForm").addEventListener("submit", async function(event) {
            event.preventDefault();
            
            let currentDate = Date.now();
            let formData = new FormData();

            var categorie = $('#genre').val();
            
            formData.append("currentDate", currentDate);
            formData.append("categorie", categorie);
            formData.append("lien1", removeSpecialCharatere(document.getElementById("filmName").value) + 'file1' + currentDate + '.' + get_url_extension(document.getElementById("image").files[0].name));
            formData.append("lien2", removeSpecialCharatere(document.getElementById("filmName").value) + 'file2' + currentDate + '.' + get_url_extension(document.getElementById("image2").files[0].name));
            formData.append("filmName", document.getElementById("filmName").value);
            formData.append("filmDescription", document.getElementById("filmDescription").value);
            formData.append("file1", document.getElementById("image").files[0]);
            formData.append("file2", document.getElementById("image2").files[0]);

            try {
                
                let response = await fetch(`${myVar}/film/createfilm`, {
                    method: "POST",
                    body: formData
                });

                if (response.ok) {
                    let result = await response.json();

                    if (result.status == 200) {
                      window.location.href = "enregistrer-un-film-etape-2.php?filmID=" + result.message.id;
                    } else {
                        alert("Ce nom est déjà utilisé pour un film. Consulte la liste des films pour vérifier.");
                    }
                } else {
                    alert("Erreur lors de l'enregistrement du film.");
                }
            } catch (error) {
                console.error("Erreur :", error);
                alert("Une erreur s'est produite.");
            }
        });

        function removeSpecialCharatere(text) {
            return text.replace(/[^a-zA-Z ]/g, "").replace(/\s/g, "");
        }

        function get_url_extension(url) {
            return url.split(/[?#]/)[0].split('.').pop().trim();
        }
    </script>
</body>
</html>
