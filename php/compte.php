<?php
include_once('connexion.php');
session_start() ;
session_start() ;
if(!$_SESSION['LOGIN']){
    header("Location: login.php");
}
$id = $_SESSION['LOGIN'];

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
            <h1>
                Compte personnel
            </h1>
        </div>

    </div>
</div>

<div style="margin-left:15%;padding:1px 16px;height:100px;">
    <h1 class="head1">Compte</h1>
    <form  method="post" action="Personnage.php" >
        <br/>
        <br/>
        <?php
        echo"<table border='1px'>\n";
        echo"<tr><th>nom de compte </th><th>date de Creation</th><th>serveur</th><th>email</th><th>solde</th><th>Nom de Guidlde</th>\n" ;

        $requete = "SELECT nomC,dateCreation,serveur, eMail, solde, nomGuilde  FROM compte WHERE idcompte= '$id';";
        $ptrQuery = pg_query($cnx,$requete);
        if ($ptrQuery) {


            while($ligne = pg_fetch_array($ptrQuery )) {


                $intg = $ligne[0];


                echo"<tr><td>$ligne[0]</td>";
                echo"   <td>$ligne[1]</td>";
                echo"   <td>$ligne[2]</td>";
                echo"   <td>$ligne[3]</td>";
                echo"   <td>$ligne[4]</td>";
                
                /* lien vers la tablde Guilde */
                echo "<td><a href = 'Guilde.php?nomGuilde=$ligne[5]' > $ligne[5] </a></td></tr>";
              
            }


            echo"</table><br/><br/><br/><br/>";

        }





        echo"<table border='1px'>\n";
        echo"<tr><th>Nom de Personnage </th></tr>\n" ;

        $requete1 = "SELECT  nomper FROM personnage as pr, compte as c WHERE c.idcompte = pr.idcompte and c.idcompte= '$id';";
        $ptrQuery1 = pg_query($cnx,$requete1);
        if ($ptrQuery1) {


            while($ligne1 = pg_fetch_array($ptrQuery1)) {

                /*lien vers les personnade du compte actuel */
                echo "<tr><td><a href = 'Personnage.php?nomper=$ligne1[0]' > $ligne1[0] </a></td></tr>";

            }


            echo"</table>";

        }




        ?>

    </form>
</div>



</body>
</html>
