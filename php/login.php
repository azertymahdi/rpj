<?php
include_once("connexion.php");
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
            <form  method = "post" action ="login.php">

                <h3 class = "text-center" >Conncetez vous </h3>




                <div class="form-groupe">
                    <label for ="email">Email</label><br/>
                    <input type="email" required="required" name="email" class="form-control form-control-lg">
                </div>


                <div class = "form-groupe">
                    <label for = "mot de passe"> mot de passe</label><br/>
                    <input type="password" required="required"  name="motdepasse" class="form-control form-control-lg">
                </div>


                <br/>

                <div class = "form-groupe">
                    <input type = "submit" name="singnup-btn" class="btn btn-primary btn-block btn-lg">Connexion</input>
                </div>

                <br/>

                <p class="text-center"> creez un compte ?<a href="seconnecter.php">creez</a></p>
            </form>
        </div>
    </div>
</div>

<?php
session_start() ;



if (isset($_POST['singnup-btn'])){
    $hashed = hash("sha512", $_POST['motdepasse']);
    $emailtmp = htmlspecialchars($_POST['email']);
    $email = $emailtmp;
    $mdp = $hashed;
    if (!empty($email) and !empty($mdp)){

        $verifUser = pg_query($cnx, "SELECT idcompte  FROM compte WHERE email = '$email' AND mdp = '$mdp' ; ");
        $userdata = pg_fetch_assoc($verifUser);
        if (pg_num_rows($verifUser) == 1) {
            print("je suis amine mansour");
            $_SESSION['LOGIN'] = $userdata['idcompte'];
            header("Location: accueil.php");
        } else {
            echo "<script>alert(\"ce compte n'existe pas Vérifier votre mot de passe\")</script>";
        }

    }else{
        print("ereur pour renter ");
    }
}
?>
</body>
</html>

