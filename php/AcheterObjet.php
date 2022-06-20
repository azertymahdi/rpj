<?php
include_once('connexion.php');
include_once('Class.php') ;
session_start() ;
if(!$_SESSION['LOGIN']){
    header("Location: login.php");
}
$id = $_SESSION['LOGIN'];
$solde = $_SESSION["solde"] ;

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
            <h1>Acheter des objets</h1>
        </div>

    </div>
</div>

<div style="margin-left:15%;padding:1px 16px;height:100px;">
    <h2 class="head1">Objet</h2>



    <form  method="post" action="AcheterObjet.php" >
        <div class="amine">
            <p><b>Nombre de modificateur : </b>
                <select id="modificateur" name="modificateur">
                    <option value="0"> >=0</option>
                    <option value="1"> >=1</option>
                    <option value="2"> >=2</option>
                    <option value="3"> >=3</option>
                </select>

                <br/><br/>
            <p><b>le prix maximum : </b>
                <input type="text" name="prix" size="20" placeholder="prix maximum" /></p>
            <br/><br/>


            <label for="objets">selectionner le type de l'objet que vous souhaitez acheter :</label>

            <p> Objets :<select id="objets" name="objets">
                    <option value="Arme">Arme</option>
                    <option value="Armure">Armure</option>
                </select></p>
            <br/>
            <p><input type="submit"    name="enregistrement" value="Chercher" /></p>
            <br/><br/>


            <?php

            echo"    <p><b>votre solde actuel:</b> $solde</p>
        </div>" ;

            if(isset($_POST["enregistrement"]) && isset($_POST["objets"])){
                $Objet = $_POST["objets"] ;
                $modificateur = $_POST["modificateur"];
                if(!empty($_POST["prix"])){
                    $prix = $_POST["prix"] ;
                }else{
                    $prix = 100000000 ;
                }


                if($Objet  == "Arme") {






                    $requete = "SELECT nomc, niveau, attaque,critchance, degatcrit,vitesseattaque,prix,a.idArme,count  FROM Equipement e, Arme a ,(SELECT s.idArme,count FROM (SELECT m.idArme, COUNT (*) as count FROM  modifiarme m GROUP BY m.idArme) as s WHERE s.count>='$modificateur') as b WHERE e.idEquipement= a.idArme AND a.idArme=b.idArme AND e.prix>0 AND e.prix<'$prix' and  e.idcompte != '$id' ;";
                    $ptrQuery = pg_query($cnx, $requete);
                    if ($ptrQuery) {
                        //echo"<tr><th>nomC</th><th>niveau</th><th>attaque </th><th>critchance</th><th>degatcrit</th><th>vitesseattaque</th><th>prix</th><th>nombre de modificateur</th>\n" ;

                        while ($ligne = pg_fetch_array($ptrQuery)) {
                            echo"<table border='1px'  style='margin-bottom:15px'>\n";

                            $intg = $ligne[0];
                            $idArme=$ligne[7];
                            echo "\n\t <tr><td> <input type='radio' name='id' value= '$intg'>$ligne[0] , équipement niveau $ligne[1] </td></tr>";
                            echo "\n\t<tr><td>attaque : $ligne[2] , chance coup critique:$ligne[3] dégats critiques: $ligne[4]  vitesse:$ligne[5] prix: $ligne[6]</td></tr>";
                            $modQuery= "SELECT tier, type, niveaumod ,valeur FROM modificateur , modifiarme WHERE modificateur.idmod=modifiarme.idMod AND idArme=$idArme;";
                            $ptrModQuery = pg_query($cnx, $modQuery);

                            while ($modLigne = pg_fetch_array($ptrModQuery)){
                                echo "\n\t <tr><td>  $modLigne[0] de $modLigne[1] , tier $modLigne[0] , niveau du modifieur $modLigne[2] </td></tr>  " ;

                            }
                            echo "</table>";

                        }



                    }
                    if(pg_num_rows($ptrQuery) == 0){
                        echo "<script>alert(\"aucune arme pour ce prix \")</script>";
                    }

                }






                if(isset($_POST["enregistrement"]) && isset($_POST["objets"])){
                    $Objet = $_POST["objets"] ;
                    $modificateur = $_POST["modificateur"];
                    if(!empty($_POST["prix"])){
                        $prix = $_POST["prix"] ;
                    }else{
                        $prix = 100000000 ;
                    }

                    if($Objet == "Armure") {
                        echo"<table BORDER='1px'>";


                        $requete = "SELECT nomc, niveau,defence,prix, count, a.idArmure  FROM Equipement e, Armure a ,(SELECT s.idArmure, count FROM (SELECT m.idArmure, COUNT (*) as count FROM  modifiarmure m GROUP BY m.idArmure) as s WHERE s.count>='$modificateur') as b WHERE e.idEquipement= a.idArmure AND a.idArmure=b.idArmure AND e.prix>0 AND e.prix<'$prix' and e.idcompte != '$id';
";
                        $ptrQuery = pg_query($cnx, $requete);
                        if ($ptrQuery) {
                            //echo"<tr><th>nomC</th><th>niveau</th><th>attaque </th><th>critchance</th><th>degatcrit</th><th>vitesseattaque</th><th>prix</th><th>nombre de modificateur</th>\n" ;

                            while ($ligne = pg_fetch_array($ptrQuery)) {
                                echo"<table border='1px'  style='margin-bottom:15px'>\n";

                                $intg = $ligne[0];
                                $idArmure=$ligne[5];
                                echo "\n\t <tr><td> <input type='radio' name='id' value= '$intg'>$ligne[0] , équipement niveau $ligne[1] </td></tr>";
                                echo "\n\t<tr><td>defence : $ligne[2] ,  prix: $ligne[3]</td></tr>";
                                $modQuery= "SELECT tier, type, niveaumod ,valeur FROM modificateur , modifiarmure WHERE modificateur.idmod= modifiarmure.idmod AND idArmure=$idArmure;";
                                $ptrModQuery = pg_query($cnx, $modQuery);

                                while ($modLigne = pg_fetch_array($ptrModQuery)){
                                    echo "\n\t <tr><td>  $modLigne[0] de $modLigne[1] , tier $modLigne[0] , niveau du modifieur $modLigne[2] </td></tr>  " ;

                                }
                                echo "</table>";

                            }



                        }

                        if(pg_num_rows($ptrQuery) == 0){
                            echo "<script>alert(\"aucune armure pour ce prix\")</script>";


                        }

                    }

                }
            }

            echo"<p><input type='submit' name='acheter' value='acheter' />\t" ;
            if(isset($_POST["acheter"]) && !empty($_POST["id"])){
                $nomC = $_POST["id"] ;
                $sql1="SELECT prix  FROM Equipement WHERE nomc = '$nomC' ;";
                $ptrQuery1 = pg_query($cnx, $sql1) ;
                $ligne1 = pg_fetch_array($ptrQuery1);
                $prix = $ligne1[0] ;

                if($solde > $prix) {
                    MODIFIERequipement($cnx, $nomC, $id, $solde, $prix);
                }else{
                    echo "<script>alert(\"votre solde est insuffisant\")</script>";
                }
            }


            ?>


    </form>
</div>


</body>
</html>


