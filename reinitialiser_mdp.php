<?php
// Démarrage de la session
session_start();

// Vérification: si l'utilisateur est connecté (logged), si non redirection vers la page de connexion
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: connexion.php");
    exit;
}

// Include du fichier de config
require_once "pdo/config.php";

// Définition variables avec valeurs par défaut
$nouvel_mdp = $confirm_mdp = "";
$nouvel_mdp_err = $confirm_mdp_err = "";

// Action quand le formulaire est soumis
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validation du nouveau mot de passe
    if(empty(trim($_POST["nouvel_mdp"]))){
        $nouvel_mdp_err = "Entrez votre mot de passe.";     
    } elseif(strlen(trim($_POST["nouvel_mdp"])) < 8){
        $nouvel_mdp_err = "Le mot de passe doit faire au moins 8 caractères.";
    } else{
        $nouvel_mdp = trim($_POST["nouvel_mdp"]);
    }
    
    // Validation de la confirmation du nouveau mot de passe
    if(empty(trim($_POST["confirm_mdp"]))){
        $nouvel_mdp_err = "Merci de confirmer le mot de passe.";
    } else{
        $confirm_mdp = trim($_POST["confirm_mdp"]);
        if(empty($nouvel_mdp_err) && ($nouvel_mdp != $confirm_mdp)){
            $confirm_mdp_err = "Le mot de passe ne correspond pas.";
        }
    }
        
    // Nettoyage des informations avant insertion en base
    if(empty($nouvel_mdp_err) && empty($confirm_mdp_err)){
        // Preparation de la requête SQL pour la déclaration PDO
        $sql = "UPDATE utils SET util_mdp = :util_mdp WHERE util_id = :util_id";
        
        if($stmt = $pdo->prepare($sql)){
            // Lien avec les variables en paramètres de la déclaration PDO
            $stmt->bindParam(":util_mdp", $param_util_mdp, PDO::PARAM_STR);
            $stmt->bindParam(":util_id", $param_util_id, PDO::PARAM_INT);
            
            // Initialisation des paramètres
            $param_util_mdp = password_hash($nouvel_mdp, PASSWORD_DEFAULT);
            $param_util_id = $_SESSION["util_id"];
            
            // Exécution de la requête
            if($stmt->execute()){
                // Mot de passe changé avec succès. Destruction de la session. Redirection vers la page de connexion.
                session_destroy();
                header("location: connexion.php");
                exit();
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
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>    <link rel="stylesheet" href="css/style.css"> -->
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Réinitialisation du mot de passe</h2>
        <p>Entrez le nouveau mot de passe.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group">
                <label>Nouveau mot de passe</label>
                <input type="password" name="nouvel_mdp" class="form-control <?php echo (!empty($nouvel_mdp_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $nouvel_mdp_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirmez le mot de passe</label>
                <input type="password" name="confirm_mdp" class="form-control <?php echo (!empty($confirm_mdp_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $confirm_mdp_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Valider">
                <a class="btn btn-link ml-2" href="welcome.php">Annuler</a>
            </div>
        </form>
    </div>    
</body>
</html>