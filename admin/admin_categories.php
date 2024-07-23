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
$categorie_id = $categorie_libelle = $suppr_possible = "";
// Action quand le formulaire est soumis
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // récupération des inputs
    if(isset($_POST['categorie_id'])){
        $categorie_id = $_POST['categorie_id'];
    }
    $categorie_libelle = $_POST['categorie_libelle'];
    if(isset($_POST['suppr_possible'])){
        $suppr_possible = $_POST['suppr_possible'];
    }
}

//************************** */
//* CATEGORIES
//************************** */
//add
if (isset($_POST['add'])) {
    $categorie_libelle = valid_donnees($categorie_libelle);
    if (!empty($categorie_libelle) && strlen($categorie_libelle) <= 20){
        try{
            //On insère les données reçues
            $sth = $pdo->prepare("INSERT INTO categories (categorie_libelle) VALUES (:categorie_libelle)");
            $sth->bindParam(':categorie_libelle',$categorie_libelle);
            $sth->execute();
            //On renvoie l'utilisateur vers la page de remerciement
            header('Location: ./admin_categories.php');
        }
        catch(PDOException $e){
            echo 'Erreur : '.$e->getMessage();
        }
    }else{
        header('Location: ./admin_categories.php');
    }
// modify
} else if (isset($_POST['modify'])) {
    $categorie_id = valid_donnees($categorie_id);
    $categorie_libelle = valid_donnees($categorie_libelle);
    if (!empty($categorie_libelle) && strlen($categorie_libelle) <= 50 && !empty($categorie_id)){
        try{
            //On insère les données reçues
            $sth = $pdo->prepare("UPDATE categories
                                SET categorie_libelle = :categorie_libelle
                                WHERE categorie_id = :categorie_id");
            $sth->bindParam(':categorie_id',$categorie_id);
            $sth->bindParam(':categorie_libelle',$categorie_libelle);
            $sth->execute();
            //On renvoie l'utilisateur vers la page de remerciement
            header('Location: ./admin_categories.php');
        }
        catch(PDOException $e){
            echo 'Erreur : '.$e->getMessage();
        }
    }else{
        header('Location: ./admin_categories.php');
    }
    // delete
} else if (isset($_POST['delete'])) {
    // Performing delete query execution
    $categorie_id = valid_donnees($categorie_id);
    if (!empty($categorie_id) && $suppr_possible){
        try{
            //On insère les données reçues
            $sth = $pdo->prepare("DELETE FROM categories WHERE categorie_id = :categorie_id");
            $sth->bindParam(':categorie_id',$categorie_id);
            $sth->execute();
            //On renvoie l'utilisateur vers la page de remerciement
            header('Location: ./admin_categories.php');
        }
        catch(PDOException $e){
            echo 'Erreur : '.$e->getMessage();
        }
    }else{
        header('Location: ./admin_categories.php');
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
            <!-- //* CATEGORIES -->
            <!-- //************************** */ -->
            <section id="categories" class="container-fluid ">
                <div class="accordion accordion-flush mt-3 w-75 mx-auto" id="accordion">
                    <!-- Ajout / Modification genres -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button bg-custom-red" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Ajout catégorie
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordion">
                            <div class="accordion-body">
                                <!-- Formulaire -->
                                <form class="py-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                    <div class="pb-2 row">
                                        <label for="genre_libelle" class="form-label col-2">Libellé catégorie</label>
                                        <input type="text" class="form-control col" id="categorie_libelle" name="categorie_libelle" required pattern="^[A-Za-z-]" maxlength="20">
                                    </div>
                                    <div class="pt-2 pb-4">
                                        <input type="submit" class="btn btn-primary float-end" value ='Enregistrer' name='add'></input>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Liste catégories -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Liste des catégories et sous-catégories
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse show" data-bs-parent="#accordion">
                            <div class="accordion-body">
                                <?php
                                    // Catégories
                                    try{
                                        //requête
                                        $sth_categories = $pdo->prepare("SELECT categorie_id, categorie_libelle FROM categories ORDER BY categorie_libelle");
                                        $sth_categories->execute();
                                        $result_categories = $sth_categories->fetchAll();
                                        $data_categories = null;
                                        foreach ($result_categories as $row_categories) {
                                            $data_categories[] = $row_categories;
                                        }
                                    }
                                    catch(PDOException $e){
                                        echo 'Erreur : '.$e->getMessage();
                                    }
                                    if (isset($data_categories[0])) {
                                        ?>
                                        <div class='row'>
                                            <h2>Catégories</h2>
                                        </div>
                                        <?php foreach ($data_categories as $row_categories) {
                                            $categorie_libelle = $row_categories["categorie_libelle"];
                                            $categorie_id = $row_categories["categorie_id"];
                                            ?>
                                            <div class='card mx-5 my-1 p-1'>
                                                <div class='row'>
                                                    <div class='row py-1 g-0'>
                                                        <form method='post' name='modify_form' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                                            <input class='col-6' type='text' name='categorie_libelle' value="<?php echo $categorie_libelle; ?>" required pattern='^[A-Za-z-]' maxlength='50'>
                                                            <input type='hidden' name='categorie_id' value="<?php echo $categorie_id; ?>">
                                                            <input class='col-1 float-end mx-1' type='submit' class='btn btn-outline-primary' value ='Modifier' name='modify'></input>
                                                            <input class='col-1 float-end' type='submit' class='btn btn-outline-primary' value ='Supprimer' name='delete' onclick="return confirm('Suppression définitive !\nConfirmez ou annulez.');"></input>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        };
                                    } else { ?>
                                        <div class="card w-75 mx-auto my-2 py-1">
                                            <div class="row px-3 py-2 fs-3">
                                                <div class="col-4">Aucune catégorie enregistrée</div>
                                            </div>
                                        </div>
                                    <?php } ?>
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