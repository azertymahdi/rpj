<?php
include_once('connexion.php');
session_start();
if(!$_SESSION['LOGIN']){
/* condition si l'id de compte n'est pas active alors on reste toujours
               sur la page login */
    header("Location: login.php");
}
$id = $_SESSION['LOGIN'] ;


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
            <li><a href="déconnexion.php"> déconnexion</a></li>


        </ul>

    </div>

    <div class="main_content">
        <div class="header">
            <h1>page d'accueil</h1>
        </div>

    </div>
</div>

<div style="margin-left:15%;padding:1px 16px;height:100px;">
    <h2 class="head1">Accueil</h2>
    <form method="post" action="accueil.php"  >
        <?php


        $requete = "SELECT * FROM  compte WHERE idcompte= '$id';";
        
       
       /* cette fonction retourne vrai  si la requête réussit*/
        $ptrQuery = pg_query($cnx,$requete); 
        
        if ($ptrQuery) {

            /*Transforme la ligne courante en un tableau associatif*/
            $ligne = pg_fetch_assoc($ptrQuery);

            echo" <div class = 'amine'>";
            echo"</br:><p><b>Bonjour</b>"."  ".$ligne['nomc']."</p><br/>" ;
            echo"<p><b>titulaire de compte :</b>"."  ".$ligne['nomc']."</p><br/>" ;
            echo"<p><b> Numéro de compte :</b>"." ".$ligne['idcompte']."</p><br/>" ;
            echo"<p><b> solde de Compte:</b>"." ".$ligne['solde']."</p>" ;
            echo"</div>";

            /*Enregistrement du solde pour le récupérer dans les autres pages Php*/
            $_SESSION["solde"] = $ligne["solde"];
            }






        ?>

</form>
</div>
</body>
</html>
