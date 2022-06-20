<?php
session_start() ;

$_SESSION = array();
/* on détruit toutes les sessions qui sont activées */
session_destroy() ;
/*et on renvoie l'utilisateur vers la page login.Php*/
header("location: login.php");


?>
