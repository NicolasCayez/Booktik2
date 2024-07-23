<?php
// Include du fichier de config
require_once "pdo/config.php";

// Définition variables avec valeurs par défaut
$util_nom = $util_mdp = $confirm_mdp = "";
$util_nom_err = $util_mdp_err = $confirm_mdp_err = "";

// Action quand le formulaire est soumis
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validation utilisateur
    if(empty(trim($_POST["util_nom"]))){
        $util_nom_err = "Entrez votre nom d'utilisateur.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["util_nom"]))){
        $util_nom_err = "Le nom utilisateur ne peut contenr que des lettres, nombres et underscores.";
    } else{
        // Preparation de la requête SQL pour la déclaration PDO
        $sql = "SELECT util_id FROM utils WHERE util_nom = :util_nom";
        
        if($stmt = $pdo->prepare($sql)){
            // Lien avec les variables en paramètres de la déclaration PDO
            $stmt->bindParam(":util_nom", $param_util_nom, PDO::PARAM_STR);
            
            // enregistrement des paramètres
            $param_util_nom = trim($_POST["util_nom"]);
            
            // Exécution de la requête
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $util_nom_err = "Ce nom d'utilisateur est déjà pris.";
                } else{
                    $util_nom = trim($_POST["util_nom"]);
                }
            } else{
                echo "Oups! Quelque chose ne s'est pas déroulé comme prévu. Réessayez.";
            }

            // Clôture
            unset($stmt);
        }
    }
    
    // Validation du mot de passe
    if(empty(trim($_POST["util_mdp"]))){
        $util_mdp_err = "Entrez votre mot de passe.";     
    } elseif(strlen(trim($_POST["util_mdp"])) < 8){
        $util_mdp_err = "Le mot de passe doit faire au moins 8 caractères.";
    } else{
        $util_mdp = trim($_POST["util_mdp"]);
    }
    
    // Validation de la confirmation du mot de passe
    if(empty(trim($_POST["confirm_mdp"]))){
        $confirm_mdp_err = "Merci de confirmer le mot de passe.";     
    } else{
        $confirm_mdp = trim($_POST["confirm_mdp"]);
        if(empty($util_mdp_err) && ($util_mdp != $confirm_mdp)){
            $confirm_mdp_err = "Le mot de passe ne correspond pas.";
        }
    }
    
    // Nettoyage des informations avant insertion en base
    if(empty($util_nom_err) && empty($util_mdp_err) && empty($confirm_mdp_err)){
        
        // Préparation de la requête d'insertion
        $sql = "INSERT INTO utils (util_nom, util_mdp) VALUES (:util_nom, :util_mdp)";
        
        if($stmt = $pdo->prepare($sql)){
            // Lien avec les paramètres
            $stmt->bindParam(":util_nom", $param_util_nom, PDO::PARAM_STR);
            $stmt->bindParam(":util_mdp", $param_util_mdp, PDO::PARAM_STR);
            
            // Initialisation des paramètres
            $param_util_nom = $util_nom;
            $param_util_mdp = password_hash($util_mdp, PASSWORD_DEFAULT); // Hash du mot de passe (sécurisation)
            
            // Exécution de la requête
            if($stmt->execute()){
                // Redirection vers la pase d'accueil'
                header("location: connexion.php");
            } else{
                echo "Oups! Quelque chose ne s'est pas déroulé comme prévu. Réessayez.";
            }

            // Clôture
            unset($stmt);
        }
    }
    
    // Cloture connexion
    unset($pdo);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>    <link rel="stylesheet" href="css/style.css"> -->
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Enregistrement nouvel utilisateur</h2>
        <p>Merci de remplir le formulaire.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Nom d'utilisateur</label>
                <input type="text" name="util_nom" class="form-control <?php echo (!empty($util_nom_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $util_nom; ?>">
                <span class="invalid-feedback"><?php echo $util_nom_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="util_mdp" class="form-control <?php echo (!empty($util_mdp_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $util_mdp; ?>">
                <span class="invalid-feedback"><?php echo $util_mdp_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirmez le mot de masse</label>
                <input type="password" name="confirm_mdp" class="form-control <?php echo (!empty($confirm_mdp_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_mdp; ?>">
                <span class="invalid-feedback"><?php echo $confirm_mdp_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Valider">
                <input type="reset" class="btn btn-secondary ml-2" value="Effacer">
            </div>
            <p>Vous avez déjà un compte? <a href="connexion.php">Connectez-vous</a>.</p>
        </form>
    </div>    
</body>
</html>

