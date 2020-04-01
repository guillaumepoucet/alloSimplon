<?php
require_once 'functions/auth.php';
online();
header('Content-type: text/html; charset=utf-8');
require_once 'styleswitcher.php';
setlocale(LC_TIME, 'fr', 'fr_FR', 'fr_FR.ISO8859-1');
?>

<!DOCTYPE html>
<html lang="fr_FR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parasite</title>

    <!--SLICK-->

    <link rel="stylesheet" type="text/css" href="slick\slick\slick.css" />
    <link rel="stylesheet" type="text/css" href="slick\slick\slick-theme.css" />

    <!--CSS-->

    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" media="screen, projection" type="text/css" id="css" href="<?php echo $url; ?>" />


    <!--GOOGLE FONTS-->

    <link
        href="https://fonts.googleapis.com/css?family=Baloo+Tammudu+2:400,500,600,700,800|Ubuntu:300,300i,400,400i,500,500i,700,700i&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i|Rubik:300,300i,400,400i,500,500i,700,700i,900,900i&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Asap:400,400i,500,500i,600,600i,700,700i|Bellota+Text:300,300i,400,400i,700,700i&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Orbitron:700,800,900|Quicksand:300,400,500,600,700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">




</head>

<body>

    <?php
    include 'include/nav.php';

    include 'include/connectBDD.php';
    include './functions/filmDuration.php';

    $id = $_GET['id'];

    $req = $bdd->prepare("SELECT * FROM films WHERE id_film =" . $id);
    $req ->execute();
    
    $film = $req->fetch(); {
    ?>

    <h2 class="page-film"><?=$film['nom']?></h2>

    </div >
        <?php 
            $req = $bdd->prepare("SELECT * FROM est WHERE id_film =" . $id);
            $req ->execute();
            
            while( $id_genre = $req->fetch()) {

                $genres = $bdd->prepare( "SELECT * FROM types WHERE id_genre =" . $id_genre['id_genre']);
                $genres ->execute();
    
                while($genre = $genres->fetch() ) {
    
            ?>

            <a href="fiche_genre.php?id=<?=$genre['id_genre']?>" class="lien-genre"><?=$genre['genre']?></a>

            <?php 
                }
            }
                ?>
        
    </div>

    <!--SYNOPSIS-->

    <div class="img-resume">
        <img class="img-film" src="<?= $film['poster']?>" alt="<?=$film['nom']?>">

        <div class="synop">
            <p class="synop-title">Synopsis</p>
            <p><?=$film['synopsis']?></p>

        </div>
    </div>
        

        <!--INFO FILM-->


    <div class="rond-titre">Résumé</div>

    <h3 class="info-film"></h3>

    <div class="ronds-info">

        <div class="ronds-bis">
            <div class="ronds-ronds">
                <?=filmDuration($film['duree'],'%&2h %02dm')?>
            </div>
            Durée
        </div>


        <div class="ronds-bis">
            <div class="ronds-ronds">
                4.5/5
            </div>
            Note
        </div>


        <div class="ronds-bis">
            <div class="ronds-ronds">
                <?=strftime('%e %B %Y', strtotime($film['dateSortie']))?>
            </div>
            Date de sortie
        </div>


    </div>
    
    <?php
    }
    ?>

    <!--Liste acteurs-->

    <section class="liste-acteurs">

        <div class="acteurs-titre">Acteurs</div>

        <?php
        // On récupère les id des acteurs ayant le même id-film que le film
        $acteursListe = $bdd->prepare( "SELECT * FROM joue_dans WHERE id_film =" . $film['id_film']);
        $acteursListe ->execute();

        while($acteurListe = $acteursListe->fetch()) {

        // On récupère les infos des acteurs ayant le même id_acteur que $acteursListe
            $acteursFilm = $bdd->prepare( "SELECT id_acteur, nom, prenom, portrait FROM acteurs WHERE id_acteur =" . $acteurListe['id_acteur']);
            $acteursFilm ->execute();

            while($acteur = $acteursFilm->fetch() ) {
            ?>

            <a href="./fiche_acteur.php?id=<?=$acteur['id_acteur']?>" class="acteur">
                <img class="img-acteur" src="<?=$acteur['portrait']?>" alt="">
                <div><?=$acteur['prenom'] . " " . $acteur['nom']?></div>
            </a>

            <?php
            }
        }
        ?>

    </section>


    <!--REAL BA-->

    <div class="real-real">Réalisateur</div>

    <div class="real-ba">

        <?php

        $realsListe = $bdd->prepare( "SELECT * FROM a_realise WHERE id_film =" . $film['id_film']);
        $realsListe ->execute();

        while($realListe = $realsListe->fetch()) {

            // On récupère les infos des acteurs ayant le même id_acteur que $acteursListe
            $realFilm = $bdd->prepare( "SELECT * FROM realisateurs WHERE id_real =" . $realListe['id_real']);
            $realFilm ->execute();

            while($real = $realFilm->fetch() ) {
            ?>

            <div class="real">
                <div class="img-real">
                    <img src="./img/real.jfif" alt="">
                    <div><?=$real['prenom'] . " " . $real['nom']?></div>
                </div>
                <p class="text-real"><?=$real['bio']?></p>
            </div>

            <div class="ba-yt">
                <iframe width="400" height="250" src="<?=$film['trailer']?>" frameborder="0"
                    allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
            </div>

    </div>

        <?php
        }
    }
    ?>

    <div class="gallery">

    <?php

    $photosFilm = $bdd->prepare( "SELECT * FROM photofilm WHERE id_film =" . $film['id_film']);
    $photosFilm ->execute();

    while($photo = $photosFilm->fetch()) {
    ?>

        <img src="<?=$photo['path']?>" alt="<?=$photo['nom']?>">

    <?php 
    }
    $photosFilm->closeCursor();
    $realFilm->closeCursor();
    $realsListe->closeCursor();
    $acteurListe->closeCursor();
    $acteursFilm->closeCursor();
    $genres->closeCursor();
    $req->closeCursor();
    ?>

    </div>

    <?php include 'include/footer.php';?>

</body>

</html>