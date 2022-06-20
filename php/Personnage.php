<?php
include_once('connexion.php');
session_start();


/*récuperation de nom de personnage*/
$getId = $_GET['nomper'];



$id =  $_SESSION['LOGIN'] ;

?>


<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Projet PHP</title>
    <link rel="stylesheet" href="Classement.css">

</head>
<body>

<div class="wrapper">
    <div class="menu">
        <h2>Menu </h2>
        <ul class="menu">
            <li><a  href="accueil.php">accueil</a></li>
            <li><a href='Classement.php'>classement</a></li>
            <li><a href="compte.php">Compte</a></li>
            <li><a  href="Objet.php">Equipement</a></li>
            <li><a href="déconnexion.php">déconnexion</a> </li>


        </ul>

    </div>

    <div class="main_content">
        <div class="header">
            <h1>Les personnages de votre Compte</h1>
        </div>

    </div>
</div>

<div style="margin-left:15%;padding:1px 16px;height:100px;">
    <h2 class="head1">Personnages</h2>
    <form method="post" action="Personnage.php"  >
<?php
;
        echo"<table border = 1px>\n";
            echo"<tr><th>nom de perspnnage </th><th>niveau</th><th>exprience</th><th>vie de base</th></tr>\n" ;

            $requete = "SELECT nomper, niveau, experience, viedebase from personnage WHERE nomper = '$getId';";
            $ptrQuery = pg_query($cnx,$requete);
            if ($ptrQuery) {


            while($ligne = pg_fetch_array($ptrQuery )) {



            echo "<tr><td >".$ligne[0]."</td>\n ";
                echo "<td> ".$ligne[1]."</td>\n";
                echo "<td>".$ligne[2]."</td>\n";
                echo "<td>".$ligne[3]."</td></tr>\n";


            }




        }

        ?>


    </form>
</div>
</body>
</html>
