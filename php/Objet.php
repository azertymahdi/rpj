<?php

include_once('connexion.php');
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
            <h1>les objets de votre compte</h1>
        </div>

    </div>
</div>

<div style="margin-left:15%;padding:1px 16px;height:100px;">
    <h2 class="head1">Objet</h2>

    <form  method="post" action="Objet.php" >
 <div class="amine">

     <p>Selectionner les objets de votre compte : </p>
     <br/>
     <p> Objets :<select id="objets" name="objets">
             <option value="Arme">Arme</option>
             <option value="Armure">Armure</option>
         </select></p>
     <br/>

     <p><input type="submit" name="enregistrement" value="Envoyer" /></p><br/>

        <?php
        echo"<p>si vous voulez acheter des objets<p><br/>";

        echo "<p><input type='submit' name='move' value='Achter des Objets' /></p>" ;
        echo"</div>" ;
        if(isset($_POST['move'])) {

        header('Location: AcheterObjet.php');
        }





            if(isset($_POST["enregistrement"]) && isset($_POST["objets"])){
                $Objet = $_POST["objets"] ;


                if($Objet == "Arme") {
                    echo"<table border='1px'>\n";
                    echo"<tr><th>nom d'equipement</th><th>niveau</th><th>attaque </th><th>critchance</th><th>degatcrit</th><th>vitesse et attaque</th>\n" ;


                    $requete = "select nomC, niveau, attaque, critChance, degatCrit, vitesseAttaque  from Equipement AS Eq , Arme AS Ar WHERE  Eq.idEquipement = Ar.idArme  and idcompte = '$id';
                ";
                    $ptrQuery = pg_query($cnx, $requete);
                    if ($ptrQuery) {


                        while ($ligne = pg_fetch_array($ptrQuery)) {


                            $intg = $ligne[0];


                            echo "<tr><td>$ligne[0]</td>";
                            echo "   <td>$ligne[1]</td>";
                            echo "   <td>$ligne[2]</td>";
                            echo "   <td>$ligne[3]</td>";
                            echo "   <td>$ligne[4]</td>";
                            echo "   <td>$ligne[5]</td></tr>";

                        }


                        echo "</table>";
                        echo"<br/><br/>" ;

                        }


                }






                if(isset($_POST["enregistrement"]) && isset($_POST["objets"])){
                    $Objet = $_POST["objets"] ;


                    if($Objet == "Armure") {
                        echo"<table border = '1px'>\n";
                        echo"<tr><th>nom d'equipement</th><th>niveau</th><th>defence</th>\n" ;

                        $requete = "select nomC, niveau,  defence from Equipement AS Eq ,  Armure AS Ar WHERE  Eq.idEquipement = Ar.idArmure and idcompte = '$id';";
                        $ptrQuery = pg_query($cnx, $requete);


                        if ($ptrQuery) {


                            while ($ligne = pg_fetch_array($ptrQuery)) {


                                $intg = $ligne[0];


                                echo "<tr><td>$ligne[0]</td>";
                                echo "   <td>$ligne[1]</td>";
                                echo "   <td>$ligne[2]</td></tr>";

                            }


                            echo "</table>";
                            echo"<br/><br/>" ;


                        }

                    }

                }



        }


        ?>
 </div>

    </form>
</body>
</html>



