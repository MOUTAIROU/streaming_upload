
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
           <div class = "mainSidebar"><?php  include('sideBar.html')?></div>
           <div class = "mainContainer">

                <h2>Enregistrer un Film 4/6</h2>

                <div class = "formContainer">
                <form id="filmForm" action="javascript:void(0);" method="POST">
                    <label for="genre">Genre du Film :</label>
                    <select id="genre" name="genre"  class="formContainerSelect" required>
                        <option value="">Choisir un genre</option>
                        <option value="Action">action</option>
                        <option value="Animation">animation</option>
                        <option value="Aventure">Aventure</option>
                        <option value="Comédie">comédie</option>
                        <option value="Crime">crime</option>
                        <option value="Romance">Romance</option>
                        <option value="Documentaire">documentaire</option>
                        <option value="Drame">drame</option>
                        <option value="Familial">familial</option>
                        <option value="Suspense">suspense</option>
                        <option value="Fantastique">fantastique</option>
                        <option value="Guerre">guerre</option>
                        <option value="Histoire">histoire</option>
                        <option value="Horreur">horreur</option>
                        <option value="Musical">musical</option>
                        <option value="Mystère">mystère</option>
                        <option value="Policer">policer</option>
                        <option value="Science-fiction">science-fiction</option>
                        <option value="Thriller">thriller</option>
                        <option value="Wertern">wertern</option>
                    </select><br><br>

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
                                <div><input type="checkbox" id="scene4" name="scenes[]" value="Romance"> Romance<br></div>
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

                    <label for="nomFilm">Duréé du Film :</label>
                    <input type="text" id="nomFilm" name="nomFilm" required><br><br>

                    <label for="NoteFilm">Note du Film :</label>
                    <input type="text" id="noteFilm" name="noteFilm" required><br><br>

                    <label for="nomFilm">Bande d'annonce :</label>
                    <input class="formContainerSelect" type="text" id="annonceFilm" name="annonceFilm" required><br><br>

                    <label for="dateCreation">Date de création :</label>
                    <input  class="formContainerSelect" type="date" id="dateCreation" name="dateCreation" required><br><br>

                    <h3>Récapitulatif :</h3>
                    <div id="filmDetails">
                    <p><strong>Bande d'annonce</strong> <span id="annonceName"></span></p>
                    <p><strong>Duréé du Film</strong> <span id="filmName"></span></p>
                    <p><strong>Genre:</strong> <span id="filmGenre"></span></p>
                    <p><strong>Note du Film</strong> <span id="noteFilmText"></span></p>
                    <p><strong>Scènes:</strong> <span id="filmScenes"></span></p>
                    <p><strong>Langue:</strong> <span id="filmLanguage"></span></p>
                    <p><strong>Date de création:</strong> <span id="filmDate"></span></p>

                    <button type="submit">Soumettre</button>
                    </form>

                    
                </div>
    
                   


          </div>
    </div>
    
    <script>
        $(document).ready( function() {

            async function fetchActeurs() {     

                // Récupérer l'ID du film depuis l'URL
                const urlParams = new URLSearchParams(window.location.search);
                        const filmID = urlParams.get("filmID");

                try {
                const response = await fetch("http://localhost:8090/film/getfilmscene", {
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

                    document.getElementById('genre').value = data.message[0].genre
                    document.getElementById('langue').value = data.message[0].langue
                    document.getElementById('dateCreation').value = data.message[0].dateCreation
                    document.getElementById('annonceFilm').value = data.message[0].BandeAnnonce
                    document.getElementById('nomFilm').value = data.message[0].time
                    document.getElementById('noteFilm').value = data.message[0].note

                    

                    const tableau = data.message[0].scenes.split(',');

                    // Parcourir chaque case à cocher
                
                    $('input[name="scenes[]"]').each(function() {
                    // Si la valeur de la case à cocher est dans la liste, la cocher
                    if (tableau.includes($(this).val())) {
                    $(this).prop('checked', true);
                    }
                    });



                

                
                } catch (error) {
                console.error("Erreur :", error);
                }

                }

                fetchActeurs()



            $('#filmForm').on('submit',  async function(event) {
                event.preventDefault();

                // Récupération des données du formulaire
                var genre = $('#genre').val();
                var langue = $('#langue').val();
                var nomFilm = $('#nomFilm').val();
                var dateCreation = $('#dateCreation').val();
                var bandeAnnonce = $('#annonceFilm').val();
                var noteFilm = $('#noteFilm').val();
                  
                
                // Récupérer les scènes sélectionnées
                var scenes = [];
                $('input[name="scenes[]"]:checked').each(function() {
                    scenes.push($(this).val());
                });


                // Affichage des données récupérées
                $('#filmName').text(nomFilm);
                $('#filmGenre').text(genre);
                $('#filmScenes').text(scenes.join(', ')); // Joindre les scènes sélectionnées
                $('#filmLanguage').text(langue);
                $('#filmDate').text(dateCreation);
                $('#annonceName').text(bandeAnnonce);
                $('#noteFilmText').text(noteFilm);

                // Récupérer l'ID du film depuis l'URL
                const urlParams = new URLSearchParams(window.location.search);
                const filmID = urlParams.get("filmID");

            try {
                const response = await fetch("http://localhost:8090/film/changefilmscene", {
                    method: "POST",
                    headers: {
                        "Accept": "application/json",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ filmID:filmID,Durree: nomFilm,bna:bandeAnnonce,genre:genre,dateCreation:dateCreation,langue:langue,scenes:scenes,noteFilm:noteFilm }) 
                });

                if (!response.ok) {
                    throw new Error("Erreur lors de la récupération des acteurs !");
                }

                

                const resutat = await response.json(); // Convertir la réponse en JSON

                const {data} = resutat.message

             alert('Le film a été modifié avec succès !')

                

            } catch (error) {
                console.error("Erreur :", error);
            }

                
            });
        });


       

    </script>
</body>
</html>
