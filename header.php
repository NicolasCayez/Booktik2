<!-- header.php -->
<head>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<div class="container-fluid custom-bg-blue text-white">

    <!-- LIGNE DU HAUT -->
    <div class="row">

        <!-- LOGO -->
        <a href="index.php" class="col-2 ms-5 my-2 py-2 px-1">
            <img class="logo" src="img/logo_blanc.png" alt="logo">
        </a>

        <!-- SEARCH BAR -->
        <div class="col my-auto">
            <div class="search-container">
                <form action="/action_page.php">
                    <input class="mx-0" type="text" placeholder="Search.." name="search">
                    <button class="mx-0" type="submit"><i class="fa fa-search"></i></button>
                </form>
            </div>
        </div>
        
        <!-- COMPTE & PANIER -->
        <div class="col ms-auto">
            <div class="row pt-2 ms-auto">
                <div class="col">
                    <!-- COMPTE -->
                    <?php
                    // Vérification: si l'utilisateur est connecté (logged), si non redirection vers la page de connexion
                    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
                    ?>
                        <!-- Non connecté -->
                        <a href="connexion.php" class="row justify-content-center">
                            <div class="row justify-content-center">
                                <img class="icone" src="img/sign_in.png" alt="logo">
                            </div>
                            <div class="row justify-content-center">
                                Connexion
                            </div>
                        </a>
                    <?php
                    } else {
                    ?>
                        <!-- Connecté -->
                        <!-- Non connecté -->
                        <a href="compte.php" class="row justify-content-center">
                            <div class="row justify-content-center">
                                <img class="icone" src="img/compte_blanc.png" alt="logo">
                            </div>
                            <div class="row justify-content-center">
                                Mon Compte
                            </div>
                        </a>
                    <?php
                    }
                    ?>
                </div>
                <!-- PANIER lien vide **************************************** -->
                <div class="col">
                    <a href="index.php" class="row justify-content-center">
                        <div class="row justify-content-center">
                            <img class="icone" src="img/panier_vide_blanc.png" alt="logo">
                        </div>
                        <div class="row justify-content-center">
                            Panier
                        </div>
                    </a>
                </div>
                <?php
                // Vérification: si l'utilisateur est connecté (logged), si non redirection vers la page de connexion
                if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true){
                    ?>
                    <!-- DECONNEXION -->
                    <div class="col">
                        <a href="deconnexion.php" class="texte-icone text-center">
                            <div class="row justify-content-center">
                                <img class="icone" src="img/sign_out.png" alt="logo">
                            </div>
                            <div class="row justify-content-center">
                                Déconnexion
                            </div>
                        </a>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <!-- LIGNE DU BAS -->
    <?php
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true){
    ?>
        <div class="row">        
            <div class="fs-5 text-end">Utilisateur :
                <?php
                echo $_SESSION["util_nom"];
                ?>
            </div>
            <a href="./admin/" class="fs-5 text-end">Accès Admin</a>
        </div>
    <?php
    }
    ?>
</div>