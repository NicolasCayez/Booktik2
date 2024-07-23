<?php
// Démarrage de la session
session_start();

// Vérification: si l'utilisateur est connecté (logged), si oui redirection vers la page d'accueil
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != true || $_SESSION["util_droit_id"] != 1){
    header("location: ../index.php");
    exit;
} ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booktik - Admin</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>    <link rel="stylesheet" href="css/style.css"> -->
    

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- JS -->
    <script src="../js/functions.js" async></script>
    <script src="../js/structure_admin.js" async></script>
    <!-- CSS -->
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/admin.css">

</head>
<body class="container-fluid min-vh-100 p-0 d-flex flex-column">
    <?php include_once('./header_admin.php'); ?>


    <!-- CORPS DE LA PAGE -->
    <div class="row flex-grow-1 g-0" id="pageBody">
        <div id="pageContent" class="position-relative justify-content-center px-lg-5">
            Bienvenue sur la page d'administration
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>