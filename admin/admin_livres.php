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
$livre_id = $livre_titre = $livre_isbn = "";
$serie_id = "";
$auteur_id = $auteur_nom = $auteur_prenom = "";
// Action quand le formulaire est soumis
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // récupération des inputs
    if(isset($_POST['livre_id'])){
        $livre_id = $_POST['livre_id'];
    }
    if(isset($_POST['livre_titre'])){
        $livre_titre = $_POST['livre_titre'];
    }
    if(isset($_POST['livre_isbn'])){
        $livre_isbn = $_POST['livre_isbn'];
    }
    if(isset($_POST['serie_id'])){
        $serie_id = $_POST['serie_id'];
    }
    if(isset($_POST['editeur_id'])){
        $editeur_id = $_POST['editeur_id'];
    }
    if(isset($_POST['auteur_id'])){
        $auteur_id = $_POST['auteur_id'];
    }

    if(isset($_POST['suppr_possible'])){
        $suppr_possible = $_POST['suppr_possible'];
    }
}

//************************** */
//* LIVRES
//************************** */
if (isset($_POST['add'])) {
    $livre_titre = valid_donnees($livre_titre);
    if (!empty($livre_titre) && strlen($livre_titre) <= 50){
        try{
            //On insère les données reçues dans livres
            $sth = $pdo->prepare("INSERT INTO livres (livre_titre, livre_isbn, serie_id) VALUES (:livre_titre, :livre_isbn, :serie_id)");
            $sth->bindParam(':livre_titre',$livre_titre);
            $sth->bindParam(':livre_isbn',$livre_isbn);
            $sth->bindParam(':serie_id',$serie_id);
            $sth->execute();
            //On insère les données reçues dans livres
            $last_id = $pdo->lastInsertId();
            $sth = $pdo->prepare("INSERT INTO ecrire (livre_id, auteur_id) VALUES (:livre_id, :auteur_id)");
            $sth->bindParam(':livre_id',$last_id);
            $sth->bindParam(':auteur_id',$auteur_id);
            $sth->execute();
            //On renvoie l'utilisateur vers la page de remerciement
            header('Location: ./admin_livres.php');
        }
        catch(PDOException $e){
            echo 'Erreur : '.$e->getMessage();
        }
    }else{
        header('Location: ./admin_livres.php');
    }
// modify
} else if (isset($_POST['modify'])) {
    $livre_titre = valid_donnees($livre_titre);
    if (!empty($livre_titre) && strlen($livre_titre) <= 50 && !empty($livre_id)){
        try{
            //On insère les données reçues
            $sth = $pdo->prepare("UPDATE livres
                                SET livre_titre = :livre_titre, livre_isbn = :livre_isbn, serie_id = :serie_id, editeur_id = :editeur_id
                                WHERE livre_id = :livre_id");
            $sth->bindParam(':livre_id',$livre_id);
            $sth->bindParam(':livre_titre',$livre_titre);
            $sth->bindParam(':livre_isbn',$livre_isbn);
            $sth->bindParam(':serie_id',$serie_id);
            $sth->bindParam(':editeur_id',$editeur_id);
            $sth->execute();
            //On renvoie l'utilisateur vers la page de remerciement
            header('Location: ./admin_livres.php');
        }
        catch(PDOException $e){
            echo 'Erreur : '.$e->getMessage();
        }
    }else{
        header('Location: ./admin_livres.php');
    }
    // delete
} else if (isset($_POST['delete'])) {
    // Performing delete query execution
    $livre_id = valid_donnees($livre_id);
    if (!empty($livre_id)){
        try{
            //On insère les données reçues
            $sth = $pdo->prepare("DELETE FROM ecrire WHERE livre_id = :livre_id");
            $sth->bindParam(':livre_id',$livre_id);
            $sth->execute();
            $sth = $pdo->prepare("DELETE FROM livres WHERE livre_id = :livre_id");
            $sth->bindParam(':livre_id',$livre_id);
            $sth->execute();
            //On renvoie l'utilisateur vers la page de remerciement
            header('Location: ./admin_livres.php');
        }
        catch(PDOException $e){
            echo 'Erreur : '.$e->getMessage();
        }
    }else{
        header('Location: ./admin_livres.php');
    }
} else if (isset($_POST['retirer_auteur'])) {
    // Performing query execution
    $livre_id = valid_donnees($livre_id);
    $auteur_id = valid_donnees($auteur_id);
    if (!empty($livre_id) && !empty($auteur_id)){
        try{
            //On insère les données reçues
            $sth = $pdo->prepare("DELETE FROM ecrire WHERE livre_id = :livre_id AND auteur_id = :auteur_id");
            $sth->bindParam(':livre_id',$livre_id);
            $sth->bindParam(':auteur_id',$auteur_id);
            $sth->execute();
            //On renvoie l'utilisateur vers la page de remerciement
            header('Location: ./admin_livres.php');
        }
        catch(PDOException $e){
            echo 'Erreur : '.$e->getMessage();
        }
    }else{
        header('Location: ./admin_livres.php');
    }
} else if (isset($_POST['lier_auteur'])) {
    // Performing query execution
    $livre_id = valid_donnees($livre_id);
    $auteur_id = valid_donnees($auteur_id);
    if (!empty($livre_id) && !empty($auteur_id)){
        try{
            //On insère les données reçues
            $sth = $pdo->prepare("INSERT INTO ecrire(livre_id, auteur_id) VALUES (:livre_id,:auteur_id)");
            $sth->bindParam(':livre_id',$livre_id);
            $sth->bindParam(':auteur_id',$auteur_id);
            $sth->execute();
            //On renvoie l'utilisateur vers la page de remerciement
            header('Location: ./admin_livres.php');
        }
        catch(PDOException $e){
            echo 'Erreur : '.$e->getMessage();
        }
    }else{
        header('Location: ./admin_livres.php');
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
            <!-- //* LIVRES -->
            <!-- //************************** */ -->
            <section id="livres" class="container-fluid ">
                <div class="accordion accordion-flush mt-3 w-75 mx-auto" id="accordion">
                    <!-- Ajout livre -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button bg-custom-red" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseTwo">
                                Ajout livre
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordion">
                            <div class="accordion-body">
                                <!-- Formulaire -->
                                <form class="py-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                    <!-- TITRE -->
                                    <div class="pb-2 row">
                                        <label for="livre_titre" class="form-label col-2">Titre du livre</label>
                                        <input type="text" class="form-control col" id="livre_titre" name="livre_titre" required pattern="^[A-Za-z-]" maxlength="50">
                                    </div>
                                    <!-- SERIE -->
                                    <div class="pb-2 row">
                                        <label for="serie" class="form-label col-2">Choix série</label>
                                        <select class="form-control col" id="serie_id" name="serie_id">
                                            <option value=0>Choisir une série</option>
                                            <?php
                                                try{
                                                    //requête
                                                    $sth_series = $pdo->prepare(
                                                            "SELECT serie_id, serie_libelle
                                                            FROM series
                                                            ORDER BY serie_libelle"
                                                            );
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
                                                if (isset($data_series[0])) {
                                                    foreach ($data_series as $row_series) {
                                                        $serie_libelle = $row_series["serie_libelle"];
                                                        $serie_id = $row_series["serie_id"];
                                                        echo "<option value='".$serie_id."'>".$serie_libelle."</option>";
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <!-- EDITEUR -->
                                    <div class="pb-2 row">
                                        <label for="editeur" class="form-label col-2">Choix éditeur</label>
                                        <select class="form-control col" id="editeur_id" name="editeur_id">
                                            <option value=0>Choisir un éditeur</option>
                                            <?php
                                                try{
                                                    //requête
                                                    $sth_editeurs = $pdo->prepare(
                                                            "SELECT editeur_id, editeur_nom
                                                            FROM editeurs
                                                            ORDER BY editeur_nom"
                                                            );
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
                                                    foreach ($data_editeurs as $row_editeurs) {
                                                        $editeur_nom = $row_editeurs["editeur_nom"];
                                                        $editeur_id = $row_editeurs["editeur_id"];
                                                        echo "<option value='".$editeur_id."'>".$editeur_nom."</option>";
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <!-- AUTEUR -->
                                    <div class="pb-2 row">
                                        <label for="auteur" class="form-label col-2">Choix auteur</label>
                                        <select class="form-control col" id="auteur_id" name="auteur_id">
                                            <option value=0>Choisir un auteur</option>
                                            <?php
                                                try{
                                                    // requête
                                                    $sth_auteurs = $pdo->prepare(
                                                            "SELECT auteur_id, auteur_nom, auteur_prenom
                                                            FROM auteurs
                                                            ORDER BY auteur_nom, auteur_prenom"
                                                            );
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
                                                    foreach ($data_auteurs as $row_auteurs) {
                                                        $auteur_nom = $row_auteurs["auteur_nom"];
                                                        $auteur_prenom = $row_auteurs["auteur_prenom"];
                                                        $auteur_id = $row_auteurs["auteur_id"];
                                                        echo "<option value='".$auteur_id."'>".$auteur_nom.$auteur_prenom."</option>";
                                                    }
                                                } else {
                                                    echo "<option value=0>Aucun auteur</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <!-- ISBN -->
                                    <div class="pb-2 row">
                                        <label for="livre_isbn" class="form-label col-2">ISBN</label>
                                        <input type="text" class="form-control col" id="livre_isbn" name="livre_isbn" required pattern="[0-9-]" maxlength="17">
                                    </div>
                                    <!-- CATEGORIES -->

                                    <div class="pt-2 pb-4">
                                        <input type="submit" class="btn btn-primary float-end" value ='Enregistrer' name='add'></input>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Liste des livres et séries -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseThree">
                                Liste des livres
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse show" data-bs-parent="#accordion">
                            <div class="accordion-body">
                                <!-- /search bar -->
                                <div class="card mx-1 my-2 py-1">
                                    <div class="row px-3 py-2 fs-3">
                                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method='GET' target="_self">
                                            <?php if($query != null) {
                                                echo "<input id= 'query' name='query' type='text' placeholder=".$query." value=''>";
                                                echo "<input id='submit_search' name='submit_search' type='submit' value='Chercher'>";
                                            } else { ?>
                                                <input id= "query" name="query" type="text" placeholder="Titre livre" value="">
                                                <input id="submit_search" name="submit_search" type="submit" value="Chercher">
                                            <?php } ?>
                                        </form>
                                    </div>
                                </div>
                                <!-- Liste des livres -->
                                    <?php
                                    // Initialisation liste des titres de livres
                                    try{
                                        //requête
                                        if(ISSET($_REQUEST['submit_search'])){
                                            $query = $_REQUEST['query'];
                                            $sth_livres = $pdo->prepare("SELECT DISTINCT livre_id, livre_titre, livre_isbn, serie_id, editeur_id
                                                                        FROM livres
                                                                        WHERE livre_id IN (
                                                                            SELECT livre_id
                                                                            FROM livres
                                                                            WHERE livre_titre LIKE '%".$query."%'
                                                                                UNION
                                                                            SELECT livre_id
                                                                            FROM livres
                                                                            JOIN series ON series.serie_id = livres.serie_id
                                                                            WHERE series.serie_libelle LIKE '%".$query."%'
                                                                                UNION
                                                                            SELECT livre_id
                                                                            FROM livres
                                                                            JOIN editeurs ON editeurs.editeur_id = livres.editeur_id
                                                                            WHERE editeurs.editeur_nom LIKE '%".$query."%'
                                                                        )
                                                                        ORDER BY livre_titre");
                                        } else {
                                            $sth_livres = $pdo->prepare("SELECT livre_id, livre_titre, livre_isbn, serie_id, editeur_id
                                                                        FROM livres
                                                                        ORDER BY livre_titre");
                                        }
                                        $sth_livres->execute();
                                        $result_livres = $sth_livres->fetchAll();
                                        foreach ($result_livres as $row_livres) {
                                            $data_livres[] = $row_livres;
                                        }
                                    }
                                    catch(PDOException $e){
                                        echo 'Erreur : '.$e->getMessage();
                                    }
                                    // Pour chaque livre ************************************************************************************************************************
                                    if (isset($data_livres[0])) { ?>
                                        <?php
                                        foreach ($data_livres as $row_livres) {
                                            $livre_id = $row_livres["livre_id"];
                                            $livre_titre = $row_livres["livre_titre"];
                                            $livre_isbn = $row_livres["livre_isbn"];
                                            $serie_id_livre = $row_livres["serie_id"];
                                            $editeur_id_livre = $row_livres["editeur_id"];
                                            $suppr_possible = false;
                                            ?>
                                            <div class="card mx-1 my-2 py-1">
                                                <div class='row'>
                                                    <!-- bouton détail -->
                                                    <div class='col-1'>
                                                        <?php
                                                        echo "<button id='btn_detail_livre' class='float-start mx-1 btn btn-outline-primary' name='btn_detail_livre' onclick=clic_detail_livre(".$livre_id.")>Détail</button>";
                                                        ?>
                                                    </div>
                                                    <div class='col'>
                                                        <div class='row py-1 g-0'>
                                                            <!-- formulaire -->
                                                            <form method='post' name='modify_form' action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>>
                                                                <!-- titre -->
                                                                <div class="row py-1">
                                                                    <h3 class="col-2 fs-4">Titre :</h3>
                                                                    <?php
                                                                    echo "<input class='col-5' type='text' name='livre_titre' value='".$livre_titre."' required pattern='^[A-Za-z-]' maxlength='50'>"; 
                                                                    echo "<input type='hidden' name='livre_id' value='".$livre_id."'>";
                                                                    ?>
                                                                    <input class='col-1 float-end ms-auto btn btn-outline-primary' type='submit' value ='Modifier' name='modify'></input>
                                                                    <input class='col-1 float-end btn btn-outline-primary' type='submit' value ='Supprimer' name='delete' onclick="return confirm('Suppression définitive !\nConfirmez ou annulez.');"></input>
                                                                </div>
                                                                <!-- partie détails -->
                                                                <?php echo "<section id='".$livre_id."' class='spoiler'>"; ?>

                                                                <!-- SERIE -->
                                                                <div class="row py-1">
                                                                    <h3 class="col-2 fs-5">Série :</h3>
                                                                    <?php
                                                                    // On récupère la liste des séries
                                                                    try{
                                                                        //requête
                                                                        $sth_series = $pdo->prepare("SELECT serie_id, serie_libelle
                                                                                                FROM series
                                                                                                ORDER BY serie_libelle");
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
                                                                    // On récupère la série enregistrée pour le livre
                                                                    foreach ($data_series as $row_series) {
                                                                        if ($serie_id_livre == $row_series["serie_id"]) {
                                                                            $serie_libelle_livre = $row_series["serie_libelle"];
                                                                        }
                                                                    } ?>
                                                                    <!-- On crée la liste déroulante -->
                                                                    <select class='col-5' id='serie_id' name='serie_id'>
                                                                        <option value="<?php echo $serie_id_livre;?>"><?php echo $serie_libelle_livre; ?></option>
                                                                        <?php //on remplit la liste déroulante
                                                                        if (isset($data_series[0])) {
                                                                            foreach ($data_series as $row_series) {
                                                                                $serie_libelle = $row_series["serie_libelle"];
                                                                                $serie_id = $row_series["serie_id"];
                                                                                if ($serie_id != $serie_id_livre){?>
                                                                                    <option value="<?php echo $serie_id;?>"><?php echo $serie_libelle;?></option>
                                                                                <?php
                                                                                }?>
                                                                            <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                    <?php
                                                                    // $pdo_series = null
                                                                    ?>
                                                                </div>

                                                                <!-- EDITEUR -->
                                                                <div class="row py-1">
                                                                    <h3 class="col-2 fs-5">Editeur :</h3>
                                                                    <?php
                                                                    // On récupère la liste des éditeurs
                                                                    try{
                                                                        //requête
                                                                        $sth_editeurs = $pdo->prepare("SELECT editeur_id, editeur_nom
                                                                                                FROM editeurs
                                                                                                ORDER BY editeur_nom");
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
                                                                    // On récupère la série enregistrée pour le livre
                                                                    foreach ($data_editeurs as $row_editeurs) {
                                                                        if ($editeur_id_livre == $row_editeurs["editeur_id"]) {
                                                                            $editeur_nom_livre = $row_editeurs["editeur_nom"];
                                                                        }
                                                                    } ?>
                                                                    <!-- On crée la liste déroulante -->
                                                                    <select class='col-5' id='editeur_id' name='editeur_id'>
                                                                        <option value="<?php echo $editeur_id_livre;?>"><?php echo $editeur_nom_livre; ?></option>
                                                                        <?php //on remplit la liste déroulante
                                                                        if (isset($data_editeurs[0])) {
                                                                            foreach ($data_editeurs as $row_editeurs) {
                                                                                $editeur_nom = $row_editeurs["editeur_nom"];
                                                                                $editeur_id = $row_editeurs["editeur_id"];
                                                                                if ($editeur_id != $editeur_id_livre){?>
                                                                                    <option value="<?php echo $editeur_id;?>"><?php echo $editeur_nom;?></option>
                                                                                <?php
                                                                                }?>
                                                                            <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                    <?php
                                                                    // $pdo_series = null
                                                                    ?>
                                                                </div>

























                                                                <!-- AUTEUR -->
                                                                <?php
                                                                // On récupère la liste des auteurs du livre
                                                                try{
                                                                    //requête
                                                                    $sth_auteurs_livre = $pdo->prepare(
                                                                            "SELECT auteurs.auteur_id, auteurs.auteur_nom, auteurs.auteur_prenom
                                                                            FROM auteurs
                                                                            JOIN ecrire ON ecrire.auteur_id = auteurs.auteur_id
                                                                            WHERE ecrire.livre_id = ".$livre_id);
                                                                    $sth_auteurs_livre->execute();
                                                                    $result_auteurs_livre = $sth_auteurs_livre->fetchAll();
                                                                    foreach ($result_auteurs_livre as $row_auteurs_livre) {
                                                                        $data_auteurs_livre[] = $row_auteurs_livre;
                                                                    }
                                                                }
                                                                catch(PDOException $e){
                                                                    echo 'Erreur : '.$e->getMessage();
                                                                }
                                                                ?>
                                                                <div class="row py-1">
                                                                    <h3 class="col-2 fs-5">Auteurs :</h3>
                                                                    <!-- Liste des auteurs -->
                                                                    <div class="col p-0">
                                                                        <?php
                                                                        if (isset($data_auteurs_livre[0])) {
                                                                            foreach($data_auteurs_livre as $row_auteurs_livre) { 
                                                                                $auteur_id = $row_auteurs_livre["auteur_id"];
                                                                                $auteur_nom = $row_auteurs_livre["auteur_nom"];
                                                                                $auteur_prenom = $row_auteurs_livre["auteur_prenom"];
                                                                                ?>
                                                                                    <form method='post' name='retirer_auteur_form' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                                                                        <input class="col-6 mx-0" type="text" name='auteur_nom' value="<?php echo $auteur_nom." ".$auteur_prenom; ?>">
                                                                                        <input type='hidden' name='auteur_id' value="<?php echo $auteur_id; ?>">
                                                                                        <input type='hidden' name='livre_id' value="<?php echo $livre_id; ?>">
                                                                                        <input class='col float-end' type='submit' class='btn btn-outline-primary' value ='Retirer auteur' name='retirer_auteur'></input>
                                                                                    </form>
                                                                            <?php
                                                                            }
                                                                        } ?>
                                                                        <!-- Ajout d'auteur -->
                                                                        <!-- On récupère la liste des auteurs du livre tous les auteurs -->
                                                                        <?php try{
                                                                            //requête
                                                                            $sth_autres_auteurs = $pdo->prepare(
                                                                                    "SELECT auteurs.auteur_id, auteurs.auteur_nom, auteurs.auteur_prenom
                                                                                    FROM auteurs
                                                                                    WHERE auteurs.auteur_id NOT IN (
                                                                                            SELECT auteurs.auteur_id
                                                                                            FROM auteurs
                                                                                            JOIN ecrire ON ecrire.auteur_id = auteurs.auteur_id
                                                                                            WHERE ecrire.livre_id = ".$livre_id.")
                                                                                    ORDER BY auteurs.auteur_nom");
                                                                            $sth_autres_auteurs->execute();
                                                                            $result_autres_auteurs = $sth_autres_auteurs->fetchAll();
                                                                            foreach ($result_autres_auteurs as $row_autres_auteurs) {
                                                                                $data_autres_auteurs[] = $row_autres_auteurs;
                                                                            }
                                                                        }
                                                                        catch(PDOException $e){
                                                                            echo 'Erreur : '.$e->getMessage();
                                                                        } ?>
                                                                        <form method='post' name='lier_auteur_form' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                                                            <!-- On crée la liste déroulante -->
                                                                            <select class='col-6' id='auteur_id' name='auteur_id' value="<?php echo $auteur_nom." ".$auteur_prenom; ?>">
                                                                            <?php //on remplit la liste déroulante
                                                                                if (isset($data_autres_auteurs[0])) {
                                                                                    foreach ($data_autres_auteurs as $row_autres_auteurs) {
                                                                                        $auteur_id = $row_autres_auteurs["auteur_id"];
                                                                                        $auteur_nom = $row_autres_auteurs["auteur_nom"];
                                                                                        $auteur_prenom = $row_autres_auteurs["auteur_prenom"];?>
                                                                                        <option value="<?php echo $auteur_id;?>"><?php echo $auteur_nom." ".$auteur_prenom; ?></option>
                                                                                    <?php
                                                                                    }
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                            <input type='hidden' name='livre_id' value="<?php echo $livre_id; ?>">
                                                                            <input class='col float-end' type='submit' class='btn btn-outline-primary' value ='Lier auteur' name='lier_auteur'></input>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                                <div class="row py-1">
                                                                    <h3 class="col-2 fs-5">ISBN :</h3>
                                                                    <?php //ISBN
                                                                    echo "<input class='col-5' type='text' name='livre_isbn' value='".$livre_isbn."' pattern='[0-9-]' maxlength='17'>"; ?>
                                                                </div>
                                                                </section>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                    }
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