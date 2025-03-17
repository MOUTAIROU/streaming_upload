<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choix du Type de Contenu</title>
    <link rel="stylesheet" href="styles.css">

    <style>
        a{
            text-decoration: none;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 80% !important;
            margin: 0 auto;
            padding: 20px;
            max-width: none !important;
        }

        .bloc {
            margin-bottom: 40px;
        }

        .bloc div {
            background-color: #fff;
            width: 90%;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin: 20px;

        }

        .bloc h2 {
            text-align: center;
            color: #2c3e50;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        .btn:active {
            background-color: #1c6ea4;
        }
    </style>
</head>
<body>

<div class = "main">

        <div class = "mainSidebar">
             <?php  include('sideBar.html')?>
        </div>
        <div class = "mainContainer"> 
            
            
    <div class="container">
        <h1 class="text-center">Bienvenue 🏠</h1>
        
             
        <!-- Bloc des vidéos animées -->
        <div class="bloc">
            <div>
                <h2>Vidéos Animées</h2>
                <a  href="enregistrer-un-film-anime.php"><button class="btn">Ajouter un Film Animé</button></a>
                <a  href="enregistrer-une-serie-anime.php"><button class="btn">Ajouter une Série Animée</button></a>
                <a  href="ajouter-une-episode-amine.php"><button class="btn">Voir la Liste des Séries Animées</button></a>
                <a  href="liste_films_anime.php"><button class="btn">Voir la Liste des Films Animés</button></a>
            </div>
            
            <!-- Bloc des vidéos non animées -->
            <div>
                <h2>Vidéos Non Animées</h2>
                <a  href="enregistrer-un-film.php"><button class="btn">Ajouter un Film Non Animé</button></a>
                <a  href="enregistrer-une-serie.php"><button class="btn">Ajouter une Série Non Animée</button></a>
                <a  href="ajouter-une-episode.php"><button class="btn">Voir la Liste des Séries Non Animées</button></a>
                <a  href="liste_films.php"><button class="btn">Voir la Liste des Films Non Animés</button></a>
            </div>
        </div>

    </div>
        </div>
</div>


</body>
</html>
