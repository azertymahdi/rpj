<?php
function AJOUTERCompte($cnx, $idcompte, $nomc,  $datecreation, $serveur , $email, $mdp, $solde )
{

 /*
     cette fonction permet d'ajouter des comptes à notre base de données, elle prend comme paramètre les arguments suivants:
     
     $cnx: pour Etablir une connexion avec serveur (PostgreSQL). c'est une valeur (entier positif) qui peut être utilisée  pour dialoguer avec le serveur.
     
     $idcompte: L'identifiant  de compte actuel  de type chaine 
     
     $nomc : Variable de type chaîne qui représente le nom d'utilisateur de compte
     
     $datedecreation :  c'est une variable de type date qui présente la date de création d'un compte
     
     $serveur : une variable de type chaîne qui présente le serveur actuel 
     
     $email  : une variable de type chaîne qui présente l'email de compte
     
     $mdp : le mot de passe du compte de type chaîne
     
     $solde : un entier qui présente le solde actuel de du compte de type 
     
      
   */
   
   
   
    $count = 1;
    
    /*une requête Sql pour sélectionner tous les attribus de table compte*/
    $sql = "SELECT * FROM compte;";
    $result = pg_query($cnx, $sql);
   
    /* si la fonction pg_query retourne un resultat vrai alors on va vérifier les conditions suivantes  */
    if ($result) {
    
    /*  si le compte qu'on veut rajouter à notre base de donées a le même identifiant ou le même nomc d'utilisateurs 
         ou le même mail alors on change la valeur de variable $count à -1 après on quite la bouclee
        
        cette condition est faite pout éviter d'avoir des utilisateurs avec des mêmes nomc et des mêmes  emails
       
        
         */
        
        
    
        while ($row = pg_fetch_array($result)) {
            if (strcasecmp($row[0], $idcompte) == 0 | strcasecmp($row[1], $nomc) == 0 | strcasecmp($row[5], $email) == 0) {
                $count = -1;
                break;
                /* vérification que les informations insérer n'exsite pas déjà dans notre base de données */
            }
        }
        if ($count != -1) {

            /* si notre variable $count est différente de -1 alors on ajoute toutes les informations dans la table compte à l'aide de la requête suivante*/ 

            $sql1 = "INSERT INTO compte(idcompte ,nomc,datecreation,serveur ,email,mdp,solde ) VALUES('$idcompte', '$nomc','$datecreation',  '$serveur', '$email', '$mdp', '$solde') ;";
            $exec = pg_query($cnx, $sql1);
            
            /*une fois le compte est ajouté on envoie l'utilisateur vers la page accueil de notre site */ 
            header("location:accueil.php");
        } else {
        
           /*sinon si $count = -1 alors on lance une alerte pour informer l'utulisteur qu'il y a déjà un compte existe avec ces informations*/
            
            echo "<script>alert(\"Ce compte existe deja\")</script>";
        }

    }
}


function maxIdCompte($cnx){
/*
comme notre variable 'idcompte' est une chaîne de caractères et les insertions sont faites d'une manière croissante 
alors cette fonction nous permet de retourner l'id d'insertion d'un nouveau compte en format d'une chaîne de caractères
*/

    $requete = "SELECT * FROM compte;";
    $n = 0;
    $ptrQuery = pg_query($cnx, $requete);
    if ($ptrQuery) {
        while ($ligne = pg_fetch_array($ptrQuery)) {
            $n = $n +  1;
        }
         
        /*Lorsqu'on sort de la boucle $n va être l'id de dernier compte inséré dans notre base  */
    }
    
    /*si on veut insérer un nouveau compte il suffit juste d'incrementer $n*/
    $chaine = $n + 1 ;
    /* Après on renvoie la variable $chaine comme une chaîne de caractères */
    return (String) $chaine ;
}


function MODIFIERequipement($cnx,$nomC , $id , $solde, $prix){

/* cette fonction nous permet de modifier "idcompte" de table équipement par exemple une fois l'utilisateur va acheter

    un équipement on va associer "idcompte" de cet équipement avec "idcompte" de compte  d'utilisateur.
    
   Après une fois l'achat est effectué c'est essentiel de modifier le solde de compte d'utilisateur 
   
   
   $cnx : variable de connecxion avec le serveur Postresql 

   $nomc : variable de type chaîne (nom de l'équipement ) 
   
   $id  : l'identifiant de compte de type chaîne 
   
   $solde : le solde du compte de type entier 
   
   $prix : un entier qui présente le prix de l'équipement 
   
   
   */



    $count=0;
    $resultat = $solde - $prix ;
    $sql="SELECT * FROM Equipement where nomc  = '$nomC'; ";
    $result =pg_query($cnx,$sql);
    if ($result) {

        while($row = pg_fetch_array($result) ) {
            if(strcasecmp($row[4], $id) ==  0 ){
                $count=-1;
                break;
                /* vérification que cet équipement n'existe pas sur le compte d'utilisateur */
            }
        }


        if($count!=-1){
        
        /*si $count!= -1 alors on va effectuer les changements*/
            $sql1="UPDATE Equipement SET idcompte = '$id' WHERE nomc = '$nomC' ;";
            $sql2 = "UPDATE compte SET solde = '$resultat' WHERE idcompte = '$id';" ;

            $exec=pg_query($cnx,$sql1);
            $exec1=pg_query($cnx,$sql2);
            header("location:accueil.php");
        }




    }

}





?>
