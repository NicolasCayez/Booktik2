<?php
// Démarrage de la session
session_start();

// Vérification: si l'utilisateur est connecté (logged), si non redirection vers la page de connexion
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: connexion.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>    <link rel="stylesheet" href="css/style.css"> -->
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>

</head>
<body>
    <?php include_once('header.php'); ?>
    <div class="container-fluid min-vh-100 p-0 d-flex flex-column bg-custom-beige">
        <!-- corps de la page -->
        <div class="row" id="pageBody">
            <div class="col-2 justify-content-center mx-auto mt-5">
                <h2 class="text-center">Cliquez ci-dessous pour réinitialiser votre mot de passe</h2>
                <a href="deconnexion.php" class="row justify-content-center custom-bg-blue text-white">
                    <div class="row justify-content-center mx-auto">
                        <img class="icone" src="img/sign_in.png" alt="logo">
                    </div>
                    <div class="row justify-content-center">
                        Déconnexion
                    </div>
                </a>
            </div>
        </div>
    </div>
    <?php include_once('footer.php'); ?>
</body>
</html>