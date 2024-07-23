<?php
// Démarrage de la session
session_start();
// Vérification: si l'utilisateur est connecté (logged), si oui redirection vers la page d'accueil
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}
// Include du fichier de config
require_once "pdo/config.php";
// Définition variables avec valeurs par défaut
$util_nom = $mdp = "";
$util_nom_err = $mdp_err = $login_err = "";
// Action quand le formulaire est soumis
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Vérification si le nom d'utilisateur est vide
    if(empty(trim($_POST["util_nom"]))){
        $util_nom_err = "Entrez un nom d'utilisateur.";
    } else{
        $util_nom = trim($_POST["util_nom"]);
    }
        // Vérification si le mot de passe est vide
    if(empty(trim($_POST["mdp"]))){
        $mdp_err = "Entrez un mot de passe.";
    } else{
        $mdp = trim($_POST["mdp"]);
    }
        // Validation du nom d'utilisateur et mot de passe
    if(empty($util_nom_err) && empty($mdp_err)){
        // Preparation de la requête SQL pour la déclaration PDO
        $sql = "SELECT util_id, util_nom, util_mdp, util_droit_id FROM utils WHERE util_nom = :util_nom";
        
        if($stmt = $pdo->prepare($sql)){
            // Lien avec les variables en paramètres de la déclaration PDO
            $stmt->bindParam(":util_nom", $param_util_nom, PDO::PARAM_STR);
            
            // Initialisation des paramètres
            $param_util_nom = trim($_POST["util_nom"]);
            
            // Exécution de la requête
            if($stmt->execute()){
                // Vérification si l'utilisateur existe, Si oui vérification du mot de passe
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetch()){
                        $util_id = $row["util_id"];
                        $util_nom = $row["util_nom"];
                        $hashed_mdp = $row["util_mdp"];
                        $util_droit_id = $row["util_droit_id"];
                        if(password_verify($mdp, $hashed_mdp)){
                            // Mot de passe ok, démarrage de la session
                            session_start();
                                                        // Enregistrement des variables de la session
                            $_SESSION["loggedin"] = true;
                            $_SESSION["util_id"] = $util_id;
                            $_SESSION["util_nom"] = $util_nom;
                            $_SESSION["util_droit_id"] = $util_droit_id;
                            
                            // Redirection vers la page d'accueil
                            header("location: index.php");
                        } else{
                            // Mot de passe non valide, afichege du message
                            $login_err = "Nom d'utilisateur ou mot de passe invalide.";
                        }
                    }
                } else{
                    // Le nom d'utilisateur saisi n'existe pas
                    $login_err = "Nom d'utilisateur ou mot de passe invalide.";
                }
            } else{
                echo "Oups! Quelque chose ne s'est pas déroulé comme prévu. Réessayez.";
            }
            // Fermeture de la déclaration PDO
            unset($stmt);
        }
    }
    // Fermeture de la connexion
    unset($pdo);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>    <link rel="stylesheet" href="css/style.css"> -->
    <!-- JS -->
    <script src="./js/functions.js" async></script>
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <?php include_once('header.php'); ?>
    <div class="container min-vh-100 p-0 d-flex flex-column bg-custom-beige">
        <div class="row mt-5">
            <div class="col-6">
                <div class="card p-3 custom-bg-blue text-white">
                    <h2>Connexion</h2>
                    <p>Entrez votre nom d'utilisateur et votre mot de passe.</p>

                    <?php 
                    if(!empty($login_err)){
                        echo '<div class="alert alert-danger">' . $login_err . '</div>';
                    }        
                    ?>

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Nom d'utilisateur</label>
                            <input type="text" name="util_nom" class="form-control <?php echo (!empty($util_nom_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $util_nom; ?>">
                            <span class="invalid-feedback"><?php echo $util_nom_err; ?></span>
                        </div>    
                        <div class="form-group">
                            <label>Mot de passe</label>
                            <input type="password" name="mdp" class="form-control <?php echo (!empty($mdp_err)) ? 'is-invalid' : ''; ?>">
                            <span class="invalid-feedback"><?php echo $mdp_err; ?></span>
                        </div>
                        <div class="form-group mt-3">
                            <input type="submit" class="btn btn-light" value="Connexion">
                        </div>
                        <p class="mt-1">Vous n'avez pas encore de compte ? <a href="enregistrement.php">Créez un compte</a>.</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include_once('footer.php'); ?>
</body>
</html>



