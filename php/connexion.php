
<?php

/* partie connexion avec la base de donées */

/*; /* Par sécurité, il ne faut pas écrire directement
        les informations de connexions dans la chaine $strConnex. Il est
        préférable d'inclure ces variables depuis un script séparé même
        si ça ne résout pas tout les risques.*/
$dbHost = "postgresql-amineee.alwaysdata.net";
$dbName = "amineee_lambdaa";
$dbUser = "amineee";
$dbPassword = "";

$strConnex = "host=$dbHost dbname=$dbName user=$dbUser password=$dbPassword";
$cnx = pg_connect($strConnex);
if ($cnx) {
   //print("connexion etablie avec succes");
} else {
    print "<p> Erreur lors de la connexion ...</p>";
    exit;
}


?>
