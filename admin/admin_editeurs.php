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
$editeur_id = $editeur_nom = "";
// Action quand le formulaire est soumis
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // récupération des inputs
    if(isset($_POST['editeur_id'])){
        $editeur_id = $_POST['editeur_id'];
    }
    $editeur_nom = $_POST['editeur_nom'];
}

//add
if (isset($_POST['add'])) {
    $editeur_nom = valid_donnees($editeur_nom);
    if (!empty($editeur_nom) && strlen($editeur_nom) <= 20){
        try{
            //On insère les données reçues
            $sth = $pdo->prepare("INSERT INTO editeurs (editeur_nom) VALUES (:editeur_nom)");
            $sth->bindParam(':editeur_nom',$editeur_nom);
            $sth->execute();
            //On renvoie l'utilisateur vers la page de remerciement
            header('Location: ./admin_editeurs.php');
        }
        catch(PDOException $e){
            echo 'Erreur : '.$e->getMessage();
        }
    }else{
        header('Location: ./admin_editeurs.php');
    }
// modify
} else if (isset($_POST['modify'])) {
    $editeur_id = valid_donnees($editeur_id);
    $editeur_nom = valid_donnees($editeur_nom);
    if (!empty($editeur_nom) && strlen($editeur_nom) <= 20 && !empty($editeur_id)){
        try{
            //On insère les données reçues
            $sth = $pdo->prepare("UPDATE editeurs SET editeur_nom = :editeur_nom WHERE editeur_id = :editeur_id");
            $sth->bindParam(':editeur_id',$editeur_id);
            $sth->bindParam(':editeur_nom',$editeur_nom);
            $sth->execute();
            //On renvoie l'utilisateur vers la page de remerciement
            header('Location: ./admin_editeurs.php');
        }
        catch(PDOException $e){
            echo 'Erreur : '.$e->getMessage();
        }
    }else{
        header('Location: ./admin_editeurs.php');
    }
    // delete
} else if (isset($_POST['delete'])) {
    // Performing delete query execution
    $editeur_id = valid_donnees($editeur_id);
    if (!empty($editeur_id)){
        try{
            //On insère les données reçues
            $sth = $pdo->prepare("DELETE FROM editeurs WHERE editeur_id = :editeur_id");
            $sth->bindParam(':editeur_id',$editeur_id);
            $sth->execute();
            //On renvoie l'utilisateur vers la page de remerciement
            header('Location: ./admin_editeurs.php');
        }
        catch(PDOException $e){
            echo 'Erreur : '.$e->getMessage();
        }
    }else{
        header('Location: ./admin_editeurs.php');
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
            <!-- //* EDITEURS -->
            <!-- //************************** */ -->
            <section id="editeurs" class="container-fluid ">
                <div class="accordion accordion-flush mt-3 w-75 mx-auto" id="accordion">
                    <!-- Ajout / Modification auteur -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button bg-custom-red" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Ajout Editeur
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordion">
                            <div class="accordion-body">
                                <!-- Formulaire -->
                                <form class="py-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                    <div class="pb-2 row">
                                        <label for="editeur_nom" class="form-label col-2">Nom éditeur</label>
                                        <input type="text" class="form-control col" id="editeur_nom" name="editeur_nom" required pattern="^[A-Za-z-]" maxlength="50">
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
                                Liste des éditeurs
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse show" data-bs-parent="#accordion">
                            <div class="accordion-body">
                                <?php
                                    // récupération des inputs
                                    try{
                                        //On insère les données reçues
                                        $sth_editeurs = $pdo->prepare("SELECT editeur_id, editeur_nom FROM editeurs ORDER BY editeur_nom");
                                        $sth_editeurs->execute();
                                        $result_editeurs = $sth_editeurs->fetchAll();
                                        $data_editeurs = null;
                                        foreach ($result_editeurs as $row_editeurs) {
                                            $data_editeurs[] = $row_editeurs;
                                        }
                                    }
                                    catch(PDOException $e){
                                        echo 'Erreur : '.$e->getMessage();
                                    }
                                    if (isset($data_editeurs[0])) {
                                        ?>
                                        <div class="card w-75 mx-auto my-2 py-1">
                                            <div class="row px-3 py-2 fs-3">
                                                <div class="col-4">Nom</div>
                                            </div>
                                        </div>
                                        <?php foreach ($data_editeurs as $row_editeurs) {
                                            $editeur_nom = $row_editeurs["editeur_nom"];
                                            $editeur_id = $row_editeurs["editeur_id"];
                                            ?>
                                            <div class='card w-75 mx-auto my-2 p-3'>
                                                <div class='row'>
                                                    <form method='post' name='modify_form' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                                        <input class='col-4' type='text' name='editeur_nom' value="<?php echo $editeur_nom; ?>" required pattern='^[A-Za-z-]' maxlength='20'>
                                                        <input type='hidden' name='editeur_id' value="<?php echo $editeur_id; ?>">
                                                        <input class='col-1 float-end mx-1' type='submit' class='btn btn-outline-primary' value ='Modifier' name='modify'></input>
                                                        <input class='col-1 float-end' type='submit' class='btn btn-outline-primary' value ='Supprimer' name='delete' onclick="return confirm('Suppression définitive !\nConfirmez ou annulez.');"></input>
                                                        <!-- <a class='btn btn-outline-primary' onclick="return confirm('Are you sure you want to submit this form?');" >Supprimer</a> -->
                                                    </form>
                                                </div>
                                            </div>
                                        <?php
                                        };
                                    } else { ?>
                                        <div class="card w-75 mx-auto my-2 py-1">
                                            <div class="row px-3 py-2 fs-3">
                                                <div class="col-4">Aucun éditeur enregistré</div>
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