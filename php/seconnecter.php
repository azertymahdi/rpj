<?php
include_once("connexion.php") ;
include_once ("Class.php") ;
?>

<!doctype html>
<html lang = "fr">
<head>
    <meta charset="UTF-8">
    <title>Connection</title>
    <!-- Bootstrap 4 CSS -->
    <link  rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" >
    <link rel="stylesheet" href="seconnecter.css">
    <title>ENRGESTRER</title>

</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-d offset-md-4 form-div">
            <form  method = "post" action="seconnecter.php">

                <h3 class = "text-center" > créez votre compte </h3>


                <div class = "form-groupe">
                    <label for = "username">username</label><br/>
                    <input type="text"  required="required" name ="username" class = "form-control  form-control-lg"  size  = "20">
                </div>

                <div class="form-groupe">
                <label for ="email">Email</label><br/>
                <input type="email"  required="required" name="email" class="form-control form-control-lg">
                </div>

                <div class = "form-groupe">
                    <label for = "passwordConf">mot de passe </label><br/>
                    <input type="password"  required="required" name="passwordConf" class="form-control form-control-lg">
                </div>

                <div class = "form-groupe">
                    <label for = "passwordConf"> confirmer le mot de passe </label><br/>
                    <input type="password"  required="required" name="confpasswordConf" class="form-control form-control-lg">
                </div>

                <br/>
                <div class = "form-groupe">
                    <input type = "submit"  required="required" name="amine" class="btn btn-primary btn-block btn-lg">Connexion</input>
                </div>

                <br/>

                <p class="text-center">Vous avez déjà un compte ?<a href="login.php">connexion</a></p>
            </form>
        </div>
    </div>
</div>
            <?php
            session_start() ;

            if(isset($_POST['amine'])) {

                if($_POST['passwordConf'] == $_POST['confpasswordConf']) {
                    $nomc = $_POST['username'];
                    $email = $_POST['email'];
                    $hached = hash("sha512", $_POST['passwordConf']);
                    $mdp = $hached;
                    $date = date("d-m-y");
                    $solde = 1 ;
                    $serveur = "EUR";
                    $idcompte = maxIdCompte($cnx);
                    $_SESSION['LOGIN'] = $idcompte;
                    AJOUTERCompte($cnx, $idcompte, $nomc, $date, $serveur, $email, $mdp, $solde );
                }else{
                    echo "<script>alert(\"lees mots de passe sont pas identique\")</script>";
                }
            }
             ?>

 </body>
</html>

