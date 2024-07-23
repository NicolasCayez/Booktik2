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
$serie_id = $serie_libelle = $suppr_possible = "";
// Action quand le formulaire est soumis
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // récupération des inputs
    if(isset($_POST['serie_id'])){
        $serie_id = $_POST['serie_id'];
    }
    $serie_libelle = $_POST['serie_libelle'];
    if(isset($_POST['suppr_possible'])){
        $suppr_possible = $_POST['suppr_possible'];
    }
}
//************************** */
//* SERIES
//************************** */
//add
if (isset($_POST['add'])) {
    $serie_libelle = valid_donnees($serie_libelle);
    if (!empty($serie_libelle) && strlen($serie_libelle) <= 50){
        try{
            //On insère les données reçues
            $sth = $pdo->prepare("INSERT INTO series (serie_libelle) VALUES (:serie_libelle)");
            $sth->bindParam(':serie_libelle',$serie_libelle);
            $sth->execute();
            //On renvoie l'utilisateur vers la page de remerciement
            header('Location: ./admin_series.php');
        }
        catch(PDOException $e){
            echo 'Erreur : '.$e->getMessage();
        }
    }else{
        header('Location: ./admin_series.php');
    }
// modify
} else if (isset($_POST['modify'])) {
    $serie_id = valid_donnees($serie_id);
    $serie_libelle = valid_donnees($serie_libelle);
    if (!empty($serie_libelle) && strlen($serie_libelle) <= 50 && !empty($serie_id)){
        try{
            //On insère les données reçues
            $sth = $pdo->prepare("UPDATE series SET serie_libelle = :serie_libelle WHERE serie_id = :serie_id");
            $sth->bindParam(':serie_id',$serie_id);
            $sth->bindParam(':serie_libelle',$serie_libelle);
            $sth->execute();
            //On renvoie l'utilisateur vers la page de remerciement
            header('Location: ./admin_series.php');
        }
        catch(PDOException $e){
            echo 'Erreur : '.$e->getMessage();
        }
    }else{
        header('Location: ./admin_series.php');
    }
    // delete
} else if (isset($_POST['delete'])) {
    // Performing delete query execution
    $serie_id = valid_donnees($serie_id);
    if (!empty($serie_id) && $suppr_possible){
        try{
            //On insère les données reçues
            $sth = $pdo->prepare("DELETE FROM series WHERE serie_id = :serie_id");
            $sth->bindParam(':categserie_idorie_id',$serie_id);
            $sth->execute();
            //On renvoie l'utilisateur vers la page de remerciement
            header('Location: ./admin_series.php');
        }
        catch(PDOException $e){
            echo 'Erreur : '.$e->getMessage();
        }
    }else{
        header('Location: ./admin_series.php');
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
            <!-- //* SERIES -->
            <!-- //************************** */ -->
            <section id="series" class="container-fluid ">
                <div class="accordion accordion-flush mt-3 w-75 mx-auto" id="accordion">
                    <!-- Ajout série -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button bg-custom-red" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Ajout série
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordion">
                            <div class="accordion-body">
                                <!-- Formulaire -->
                                <form class="py-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                    <div class="pb-2 row">
                                        <label for="serie_libelle" class="form-label col-2">Titre série</label>
                                        <input type="text" class="form-control col" id="serie_libelle" name="serie_libelle" required pattern="^[A-Za-z-]" maxlength="50">
                                    </div>
                                    <div class="pt-2 pb-4">
                                        <input type="submit" class="btn btn-primary float-end" value ='Enregistrer' name='add'></input>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Liste des séries -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Liste des séries
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse show" data-bs-parent="#accordion">
                            <div class="accordion-body">
                                <div class="card mx-1 my-2 py-1">
                                    <?php // séries
                                    try{
                                        //requête
                                        if(ISSET($_REQUEST['submit_search'])){
                                            $query = $_REQUEST['query'];
                                            $sth_series = $pdo->prepare("SELECT serie_id, serie_libelle FROM series HAVING serie_libelle LIKE '%".$query."%' ORDER BY serie_libelle");
                                        } else {
                                            $sth_series = $pdo->prepare("SELECT serie_id, serie_libelle FROM series ORDER BY serie_libelle");
                                        }
                                        $sth_series->execute();
                                        $result_series = $sth_series->fetchAll();
                                        $data_series = null;
                                        foreach ($result_series as $row_series) {
                                            $data_series[] = $row_series;
                                        }
                                    }
                                    catch(PDOException $e){
                                        echo 'Erreur : '.$e->getMessage();
                                    }
                                    if (isset($data_series[0])) { ?>
                                        <div class='row'>
                                            <h2>Séries</h2>
                                        </div>
                                        <?php foreach ($data_series as $row_series) {
                                            $serie_libelle = $row_series["serie_libelle"];
                                            $serie_id = $row_series["serie_id"];
                                            ?>
                                            <div class='card mx-5 my-1 p-1'>
                                                <div class='row'>
                                                    <div class='row py-1 g-0'>
                                                        <form method='post' name='modify_form' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                                            <input class='col-8' type='text' name='serie_libelle' value="<?php echo $serie_libelle; ?>" required pattern='^[A-Za-z-]' maxlength='50'>
                                                            <input type='hidden' name='serie_id' value="<?php echo $serie_id; ?>">
                                                            <input class='col-1 float-end' type='submit' class='btn btn-outline-primary' value ='Supprimer' name='delete' onclick="return confirm('Suppression définitive !\nConfirmez ou annulez.');"></input>
                                                            <input class='col-1 float-end mx-1' type='submit' class='btn btn-outline-primary' value ='Modifier' name='modify'></input>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        };
                                    } else { ?>
                                        <div class="card mx-auto my-2 py-1">
                                        <div class="row px-3 py-2 fs-3">
                                            <?php if($query != null) {
                                                ?>
                                                <div class='col'>Aucune série avec le critère "<?php echo $query; ?>"</div>
                                            <?php
                                            } else { ?>
                                                <div class="col">Aucune série enregistrée</div>
                                            <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
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