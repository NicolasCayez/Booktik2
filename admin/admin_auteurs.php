<?php
// Démarrage de la session
session_start();

// Vérification: si l'utilisateur est connecté (logged), si oui redirection vers la page d'accueil
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != true || $_SESSION["util_droit_id"] != 1){
    header("location: ../index.php");
    exit;
} ?>
<?php
// fonction de validation des données
function valid_donnees($donnees){
    $donnees = trim($donnees);
    $donnees = stripslashes($donnees);
    $donnees = htmlspecialchars($donnees);
    return $donnees;
}
// Include du fichier de config
require_once "../pdo/config.php";

// Définition variables avec valeurs par défaut
$auteur_id = $auteur_nom = $auteur_prenom = "";
// Action quand le formulaire est soumis
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // récupération des inputs
    if(isset($_POST['auteur_id'])){
        $auteur_id = $_POST['auteur_id'];
    }
    $auteur_nom = $_POST['auteur_nom'];
    $auteur_prenom = $_POST['auteur_prenom'];
}

//add
if (isset($_POST['add'])) {
    $auteur_nom = valid_donnees($auteur_nom);
    $auteur_prenom = valid_donnees($auteur_prenom);
    if (!empty($auteur_nom) && strlen($auteur_nom) <= 20 &&  strlen($auteur_prenom) <= 20){
        try{
            //On insère les données reçues
            $sth = $pdo->prepare("INSERT INTO auteurs (auteur_nom, auteur_prenom) VALUES (:auteur_nom, :auteur_prenom)");
            $sth->bindParam(':auteur_nom',$auteur_nom);
            $sth->bindParam(':auteur_prenom',$auteur_prenom);
            $sth->execute();
            //On renvoie l'utilisateur vers la page de remerciement
            header('Location: ./admin_auteurs.php');
        }
        catch(PDOException $e){
            echo 'Erreur : '.$e->getMessage();
        }
    }else{
        header('Location: ./admin_auteurs.php');
    }
// modify
} else if (isset($_POST['modify'])) {
    $auteur_id = valid_donnees($auteur_id);
    $auteur_nom = valid_donnees($auteur_nom);
    $auteur_prenom = valid_donnees($auteur_prenom);
    if (!empty($auteur_nom) && strlen($auteur_nom) <= 20 && strlen($auteur_prenom) <= 20 && !empty($auteur_id)){
        try{
            //On insère les données reçues
            $sth = $pdo->prepare("UPDATE auteurs SET auteur_nom = :auteur_nom, auteur_prenom = :auteur_prenom  WHERE auteur_id = :auteur_id ");
            $sth->bindParam(':auteur_id',$auteur_id);
            $sth->bindParam(':auteur_nom',$auteur_nom);
            $sth->bindParam(':auteur_prenom',$auteur_prenom);
            $sth->execute();
            //On renvoie l'utilisateur vers la page de remerciement
            header('Location: ./admin_auteurs.php');
        }
        catch(PDOException $e){
            echo 'Erreur : '.$e->getMessage();
        }
    }else{
        header('Location: ./admin_auteurs.php');
    }
    // delete
} else if (isset($_POST['delete'])) {
    // Performing delete query execution
    $auteur_id = valid_donnees($auteur_id);
    if (!empty($auteur_id)){
        try{
            //On insère les données reçues
            $sth = $pdo->prepare("DELETE FROM auteurs WHERE auteur_id = :auteur_id");
            $sth->bindParam(':auteur_id',$auteur_id);
            $sth->execute();
            //On renvoie l'utilisateur vers la page de remerciement
            header('Location: ./admin_auteurs.php');
        }
        catch(PDOException $e){
            echo 'Erreur : '.$e->getMessage();
        }
    }else{
        header('Location: ./admin_auteurs.php');
    }
}
?>

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
        <?php
        $query = '';
        if (isset($_REQUEST['query'])) {
            $query = $_REQUEST['query'];
        } ?>
            <!-- //************************** */ -->
            <!-- //* AUTEURS -->
            <!-- //************************** */ -->
            <section id="auteurs" class="container-fluid ">
                <div class="accordion accordion-flush mt-3 w-75 mx-auto" id="accordion">
                    <!-- Ajout / Modification auteur -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button bg-custom-red" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Ajout Auteur
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordion">
                            <div class="accordion-body">
                                <!-- Formulaire -->
                                <form class="py-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                    <div class="pb-2 row">
                                        <label for="auteur_nom" class="form-label col-2">Nom auteur</label>
                                        <input type="text" class="form-control col" id="auteur_nom" name="auteur_nom" required pattern="^[A-Za-z-]" maxlength="50">
                                        <label for="auteur_prenom" class="form-label col-2 offset-1">Prénom auteur</label>
                                        <input type="text" class="form-control col" id="auteur_prenom" name="auteur_prenom" pattern="^[A-Za-z-]" maxlength="50">
                                    </div>
                                    <div class="pt-2 pb-4">
                                        <input type="submit" class="btn btn-primary float-end" value ='Enregistrer' name='add'></input>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Liste auteur -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Liste des auteurs
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse show" data-bs-parent="#accordion">
                            <div class="accordion-body">
                                <?php
                                    // récupération des inputs
                                    try{
                                        //On insère les données reçues
                                        $sth_auteurs = $pdo->prepare("SELECT auteur_id, auteur_nom, auteur_prenom FROM auteurs ORDER BY auteur_nom, auteur_prenom");
                                        $sth_auteurs->execute();
                                        $result_auteurs = $sth_auteurs->fetchAll();
                                        $data_auteurs = null;
                                        foreach ($result_auteurs as $row_auteurs) {
                                            $data_auteurs[] = $row_auteurs;
                                        }
                                    }
                                    catch(PDOException $e){
                                        echo 'Erreur : '.$e->getMessage();
                                    }
                                    if (isset($data_auteurs[0])) {
                                        ?>
                                        <div class="card w-75 mx-auto my-2 py-1">
                                            <div class="row px-3 py-2 fs-3">
                                                <div class="col-4">Nom</div>
                                                <div class="col-3 offset-1">Prénom</div>
                                            </div>
                                        </div>
                                        <?php foreach ($data_auteurs as $row_auteurs) {
                                            $auteur_nom = $row_auteurs["auteur_nom"];
                                            $auteur_prenom = $row_auteurs["auteur_prenom"];
                                            $auteur_id = $row_auteurs["auteur_id"];
                                            ?>
                                            <div class='card w-75 mx-auto my-2 p-3'>
                                                <div class='row'>
                                                    <form method='post' name='modify_form' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                                        <input class='col-4' type='text' name='auteur_nom' value="<?php echo $auteur_nom; ?>" required pattern='^[A-Za-z-]' maxlength='20'>
                                                        <input class='col-3 offset-1' type='text' name='auteur_prenom' value="<?php echo $auteur_prenom; ?>" pattern='^[A-Za-z-]' maxlength='20'>
                                                        <input type='hidden' name='auteur_id' value="<?php echo $auteur_id; ?>">
                                                        <input class='col-1 float-end mx-1' type='submit' class='btn btn-outline-primary' value ='Modifier' name='modify'></input>
                                                        <input class='col-1 float-end' type='submit' class='btn btn-outline-primary' value ='Supprimer' name='delete' onclick="return confirm('Suppression définitive !\nConfirmez ou annulez.');"></input>
                                                        <!-- <a class='btn btn-outline-primary' onclick="return confirm('Are you sure you want to submit this form?');" >Supprimer</a> -->
                                                    </form>
                                                </div>
                                            </div>
                                        <?php
                                        };
                                        // Close connection
                                        $pdo = null;
                                    } else {
                                        ?>
                                        <div class="card w-75 mx-auto my-2 py-1">
                                            <div class="row px-3 py-2 fs-3">
                                                <div class="col-4">Aucun auteur enregistré</div>
                                            </div>
                                        </div>
                                    <?php }
                                ?>
                            </div>
                        </div>
                    </div>
                </div> 
            </section>
            <!-- //************************** */ -->
            <!-- //* MESSAGES -->
            <!-- //************************** */ -->
            <div class="container-fluid row">
                <h2 id="message" class="col-4 offset-4 position-absolute mt-5 bg-success text-light">
                </h2>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>