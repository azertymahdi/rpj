<?php

include_once('connexion.php');
session_start();


$getId = $_GET['nomper'];


$id = $_SESSION['LOGIN'];

/* recupération de nom de guilde*/
$getNomguilde = $_GET['nomGuilde'] ;

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
            <li><a href="accueil.php">accueil</a></li>
            <li><a href='Classement.php'>classement</a></li>
            <li><a href="compte.php">Compte</a></li>
            <li><a href="Objet.php">Equipement</a></li>
            <li><a href="déconnexion.php">déconnexion</a></li>


        </ul>

    </div>

    <div class="main_content">
        <div class="header">
            <h1>La Guilde </h1>
        </div>

    </div>
</div>

<div style="margin-left:15%;padding:1px 16px;height:100px;">
    <h2 class="head1">Guilde</h2>
    <form method="post" action="Guilde.php">
        <?php

        echo"<table border = 1px>\n";
            echo"<tr><th>nom de Guilde </th><th>chef</th><th>membres</th></tr>\n" ;
            /*dans cette requête on va sélectionner le nom de guilde et le chef de cette guilde et le nombre de membres de  guilde:
         
            le chef de guilde est obtenu par la requête suivante :  (SELECT nomC FROM guilde g,compte c WHERE g.chef=c.idCompte and g.nomguilde= '$getNomguilde')
            
             Membres sont obtenus par la requête suivante : select nomguilde, count(idcompte) as members  FROM compte  WHERE nomguilde  = '$getNomguilde' GROUP BY nomguilde) 
                 
              */
            $requete = "select g.nomguilde,(SELECT nomC FROM guilde g,compte c WHERE g.chef=c.idCompte and g.nomguilde= '$getNomguilde') as chef, members from guilde as g ,  (select nomguilde, count(idcompte) as members  FROM compte  WHERE nomguilde  = '$getNomguilde' GROUP BY nomguilde) as tab where tab.nomguilde = g.nomguilde;";


            $ptrQuery = pg_query($cnx,$requete);

            if ($ptrQuery) {


            while($ligne = pg_fetch_array($ptrQuery )) {



                echo "<tr><td >".$ligne[0]."</td>\n ";
                echo "<td>$ligne[1]</td>" ;

                echo "<td>".$ligne[2]."</td><tr/>\n";



            }




            }

            ?>

    </form>
</div>
</body>
</html>
