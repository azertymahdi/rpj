<?php
include_once('connexion.php');
session_start() ;

if(!$_SESSION['LOGIN']){
    header("Location: login.php");
}
$id = $_SESSION['idcomp'] ;
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
                Classement de Joueur en fonction des saisons
            </h1>
        </div>

    </div>
</div>



<div style="margin-left:15%;padding:1px 16px;height:100px;">
    <h2 class="head1">Classement</h2>
    <form  method = "post" action ="Classement.php">
        <div class="amine">
            <p><b>selectionner une saison : </b>
                <select id="saison" name="saison">
                    <option value="1"> 1</option>
                    <option value="2"> 2</option>
                    <option value="3"> 3</option>
                </select>

                <br/><br/>


        <p><input type="submit" name="envoyer" value="envoyer"/></p>

        </div>
        <?php
   
       /*une fois l'utilisateur va choisir la saison on va récupérer la valeur grace à la fonction $POST */  
        if(isset($_POST['envoyer']) && !empty($_POST['saison'])){
 
            $saison = $_POST['saison'];
            echo "<table border='1px'>\n";
            echo "<tr><th>nom de personnage </th><th>position</th><th>saison</th></tr>\n";
            
            /*cette requête est une jointure entre 3 tables une première jointure entre les tables personnage et estcalsse où ont récupére que les noms de personnage 
            et l'identifiant de classement après on va renommer cette table et on va faire une autre jointure avec la table classement on selecetionnant 
            que les noms de personnages leurs positions et les saisons*/ 

            $requete = "select nomper, position , saison from (SELECT nomper, idclassement  FROM personnage p, estclasse E WHERE p.idPer = E.idper) as tab, classement as c Where tab.idclassement = c.idclassement   and saison = '$saison';";
            $ptrQuery = pg_query($cnx, $requete);
            if ($ptrQuery) {


                while ($ligne = pg_fetch_array($ptrQuery)) {


                    $intg = $ligne[0];

                    echo "<tr><td >" . $ligne[0] . "</td>\n ";
                    echo "<td> " . $ligne[1] . "</td>\n";
                    echo "<td>" . $ligne[2] . "</td></tr>\n";


                }
                echo "</table>";
            }
        }
        ?>


    </form>
</div>


</body>
</html>
