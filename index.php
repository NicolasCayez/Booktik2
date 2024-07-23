<?php
// Démarrage de la session
session_start();
// Include du fichier de config
require_once "./pdo/config.php";
$query = "";
$filtre_categorie_id = $filtre_serie_id = $filtre_editeur_id = $filtre_auteur_id = "";
$livre_id = $serie_id = $editeur_id = "";
try{
    //requête
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        // récupération des inputs
        if(ISSET($_REQUEST['submit_search'])){
            $query = $_REQUEST['query'];
        } else {
            $query = "";
        }
        if(ISSET($_POST['filtre_categorie_id'])){
            $filtre_categorie_id = $_POST['filtre_categorie_id'];
        }
        if(ISSET($_POST['filtre_serie_id'])){
            $filtre_serie_id = $_POST['filtre_serie_id'];
        }
        if(ISSET($_POST['filtre_editeur_id'])){
            $filtre_editeur_id = $_POST['filtre_editeur_id'];
        }
        if(ISSET($_POST['filtre_auteur_id'])){
            $filtre_auteur_id = $_POST['filtre_auteur_id'];
        }
        if(ISSET($_REQUEST['reset'])){
            $filtre_categorie_id = $filtre_serie_id = $filtre_editeur_id = $filtre_auteur_id = "";
        }
    }

    $sth_livres = $pdo->prepare("SELECT livre_id, livre_titre
                                FROM livres
                                HAVING livre_titre
                                LIKE '%".$query."%' ORDER BY livre_titre");
    $sth_livres->execute();
    $result_livres = $sth_livres->fetchAll();
    $data_livres = null;
    foreach ($result_livres as $row_livres) {
        $data_livres[] = $row_livres;
    }
    $sth_series = $pdo->prepare("SELECT serie_id, serie_libelle
                                FROM series
                                HAVING serie_libelle
                                LIKE '%".$query."%' ORDER BY serie_libelle");
    $sth_series->execute();
    $result_series = $sth_series->fetchAll();
    $data_series = null;
    foreach ($result_series as $row_series) {
        $data_series[] = $row_series;
    }
    $sth_auteurs = $pdo->prepare("SELECT auteur_id, auteur_nom, auteur_prenom
                                FROM auteurs
                                HAVING auteur_nom
                                LIKE '%".$query."%' ORDER BY auteur_nom");
    $sth_auteurs->execute();
    $result_auteurs = $sth_auteurs->fetchAll();
    $data_auteurs = null;
    foreach ($result_auteurs as $row_auteurs) {
        $data_auteurs[] = $row_auteurs;
    }
    $sth_editeurs = $pdo->prepare("SELECT editeur_id, editeur_nom
                                FROM editeurs
                                HAVING editeur_nom
                                LIKE '%".$query."%' ORDER BY editeur_nom");
    $sth_editeurs->execute();
    $result_editeurs = $sth_editeurs->fetchAll();
    $data_editeurs = null;
    foreach ($result_editeurs as $row_editeurs) {
        $data_editeurs[] = $row_editeurs;
    }
    $sth_categories = $pdo->prepare("SELECT categorie_id, categorie_libelle
                                FROM categories
                                HAVING categorie_libelle
                                LIKE '%".$query."%' ORDER BY categorie_libelle");
    $sth_categories->execute();
    $result_categories = $sth_categories->fetchAll();
    $data_categories = null;
    foreach ($result_categories as $row_categories) {
        $data_categories[] = $row_categories;
    }
    $sth_ecrire = $pdo->prepare("SELECT livre_id, auteur_id
                                FROM ecrire");
    $sth_ecrire->execute();
    $result_ecrire = $sth_ecrire->fetchAll();
    $data_ecrire = null;
    foreach ($result_ecrire as $row_ecrire) {
    $data_ecrire[] = $row_ecrire;
}
}
catch(PDOException $e){
    echo 'Erreur : '.$e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booktik</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>    <link rel="stylesheet" href="css/style.css"> -->
    

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- JS -->
    <script src="./js/functions.js" async></script>
    <script src="./js/structure_booktik.js" async></script>
    <!-- CSS -->
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/index.css">

</head>
<body>
    <?php include_once('header.php');
    ?>

    <div class="container-fluid min-vh-100 p-0 d-flex flex-column bg-custom-beige">
        <!-- corps de la page -->
        <div class="row flex-grow-1" id="pageBody">
            <!-- Menu latéral gauche -->
            <div class="col-1 w-auto h-auto py-0 pt-5 px-auto custom-bg-blue custom-beige" id="menuLateral">
                <?php 
                if (isset($data_livres[0])) { ?>
                    <div class='row'>
                        <h2>Livres</h2>
                    </div>
                <?php
                }
                if (isset($data_series[0])) { ?>
                    <div class='row'>
                        <h2>Séries</h2>
                    </div>
                <?php
                }
                if (isset($data_auteurs[0])) { ?>
                    <div class='row'>
                        <h2>Auteurs</h2>
                    </div>
                <?php
                }
                if (isset($data_editeurs[0])) { ?>
                    <div class='row'>
                        <h2>Editeurs</h2>
                    </div>
                <?php
                }
                if (isset($data_categories[0])) { ?>
                    <div class='row'>
                        <h2>Catégories</h2>
                    </div>
                <?php
                }
                ?>
            </div>
            <!-- contenu de la page -->
            <div class="col h-100 p-0 mb-auto">
                <p class="row sousTitre custom-font-title text-start fs-5 mt-2 mb-2 mb-lg-5">Filtrer</p>
                <div class="row">
                <form class="py-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <!-- FILTRE CATEGORIES -->
                    <div class="pb-2 row">
                        <select class='col-3' id='categorie_id' name="filtre_categorie_id">
                            <?php
                            // Pas de catégorie en base
                            if (!isset($data_categories[0])) { ?>
                                <option value="">Aucune catégorie</option>
                            <?php
                            // catégorie en base pas de filtre
                            } else if (isset($data_categories[0]) && $filtre_categorie_id == "") { ?>
                                <option value="">Choisir une catégorie</option>
                                <?php
                                foreach ($data_categories as $row_categories) {
                                    $categorie_id = $row_categories['categorie_id'];
                                    $categorie_libelle = $row_categories['categorie_libelle'];?>
                                    <option value="<?php echo $categorie_id;?>"><?php echo $categorie_libelle;?></option>
                                <?php
                                }
                            // catégorie en base et filtre
                            } else {
                                // valeur par défaut
                                foreach ($data_categories as $row_categories) {
                                    $categorie_id = $row_categories['categorie_id'];
                                    $categorie_libelle = $row_categories['categorie_libelle'];
                                    if ($categorie_id == $filtre_categorie_id) { ?>
                                        <option value="<?php echo $categorie_id ?>"><?php echo $categorie_libelle ?></option>
                                    <?php
                                    }
                                }
                                // suite de la liste
                                foreach ($data_categories as $row_categories) {
                                    $categorie_id = $row_categories['categorie_id'];
                                    $categorie_libelle = $row_categories['categorie_libelle'];
                                    if ($categorie_id != $filtre_categorie_id) { ?>
                                        <option value="<?php echo $categorie_id ?>"><?php echo $categorie_libelle ?></option>
                                    <?php
                                    }
                                }
                            }
                            ?>
                        </select>
                        <!-- FILTRE EDITEURS -->
                        <select class='col-3' id='editeur_id' name="filtre_editeur_id">
                            <?php
                            // Pas d'éditeur en base
                            if (!isset($data_editeurs[0])) { ?>
                                <option value="">Aucun éditeur</option>
                            <?php
                            // éditeur en base pas de filtre
                            } else if (isset($data_editeurs[0]) && $filtre_editeur_id == "") { ?>
                                <option value="">Choisir un éditeur</option>
                                <?php
                                foreach ($data_editeurs as $row_editeurs) {
                                    $editeur_id = $row_editeurs['editeur_id'];
                                    $editeur_nom = $row_editeurs['editeur_nom'];?>
                                    <option value="<?php echo $editeur_id;?>"><?php echo $editeur_nom;?></option>
                                <?php
                                }
                            // éditeur en base et filtre
                            } else {
                                // valeur par défaut
                                foreach ($data_editeurs as $row_editeurs) {
                                    $editeur_id = $row_editeurs['editeur_id'];
                                    $editeur_nom = $row_editeurs['editeur_nom'];
                                    if ($editeur_id == $filtre_editeur_id) { ?>
                                        <option value="<?php echo $editeur_id ?>"><?php echo $editeur_nom ?></option>
                                    <?php
                                    }
                                }
                                // suite de la liste
                                foreach ($data_editeurs as $row_editeurs) {
                                    $editeur_id = $row_editeurs['editeur_id'];
                                    $editeur_nom = $row_editeurs['editeur_nom'];
                                    if ($editeur_id != $filtre_editeur_id) { ?>
                                        <option value="<?php echo $editeur_id ?>"><?php echo $editeur_nom ?></option>
                                    <?php
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <!-- FILTRE SERIES -->
                    <div class="pb-2 row">
                        <select class='col-3' id='serie_id' name="filtre_serie_id">
                            <?php
                            // Pas de série en base
                            if (!isset($data_series[0])) { ?>
                                <option value="">Aucune série</option>
                            <?php
                            // série en base pas de filtre
                            } else if (isset($data_series[0]) && $filtre_serie_id == "") { ?>
                                <option value="">Choisir une série</option>
                                <?php
                                foreach ($data_series as $row_series) {
                                    $serie_id = $row_series['serie_id'];
                                    $serie_libelle = $row_series['serie_libelle'];?>
                                    <option value="<?php echo $serie_id;?>"><?php echo $serie_libelle;?></option>
                                <?php
                                }
                            // série en base et filtre
                            } else {
                                // valeur par défaut
                                foreach ($data_series as $row_series) {
                                    $serie_id = $row_series['serie_id'];
                                    $serie_libelle = $row_series['serie_libelle'];
                                    if ($serie_id == $filtre_serie_id) { ?>
                                        <option value="<?php echo $serie_id ?>"><?php echo $serie_libelle ?></option>
                                    <?php
                                    }
                                }
                                // suite de la liste
                                foreach ($data_series as $row_series) {
                                    $serie_id = $row_series['serie_id'];
                                    $serie_libelle = $row_series['serie_libelle'];
                                    if ($serie_id != $filtre_serie_id) { ?>
                                        <option value="<?php echo $serie_id ?>"><?php echo $serie_libelle ?></option>
                                    <?php
                                    }
                                }
                            }
                            ?>
                        </select>
                        <!-- FILTRE AUTEURS -->
                        <select class='col-3' id='auteur_id' name="filtre_auteur_id">
                            <?php
                            // Pas d'auteur en base
                            if (!isset($data_auteurs[0])) { ?>
                                <option value="">Aucun auteur</option>
                            <?php
                            // auteur en base pas de filtre
                            } else if (isset($data_auteurs[0]) && $filtre_auteur_id == "") { ?>
                                <option value="">Choisir un auteur</option>
                                <?php
                                foreach ($data_auteurs as $row_auteurs) {
                                    $auteur_id = $row_auteurs['auteur_id'];
                                    $auteur_nom = $row_auteurs['auteur_nom'];
                                    $auteur_prenom = $row_auteurs['auteur_prenom']?>
                                    <option value="<?php echo $auteur_id;?>"><?php echo $auteur_nom." ".$auteur_prenom ?></option>
                                <?php
                                }
                            // auteur en base et filtre
                            } else {
                                // valeur par défaut
                                foreach ($data_auteurs as $row_auteurs) {
                                    $auteur_id = $row_auteurs['auteur_id'];
                                    $auteur_nom = $row_auteurs['auteur_nom'];
                                    $auteur_prenom = $row_auteurs['auteur_prenom'];
                                    if ($auteur_id == $filtre_auteur_id) { ?>
                                        <option value="<?php echo $auteur_id ?>"><?php echo $auteur_nom." ".$auteur_prenom ?></option>
                                    <?php
                                    }
                                }
                                // suite de la liste
                                foreach ($data_auteurs as $row_auteurs) {
                                    $auteur_id = $row_auteurs['auteur_id'];
                                    $auteur_nom = $row_auteurs['auteur_nom'];
                                    $auteur_prenom = $row_auteurs['auteur_prenom'];
                                    if ($auteur_id != $filtre_auteur_id) { ?>
                                        <option value="<?php echo $auteur_id ?>"><?php echo $auteur_nom." ".$auteur_prenom ?></option>
                                    <?php
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="pt-2 pb-4">
                        <input type="submit" class="btn btn-secondary" value ='Filtrer' name='filter'></input>
                        <input type="submit" class="btn btn-secondary" value ='Effacer les filtres' name='reset'></input>
                    </div>
                </form>






                <?php
                if ($filtre_categorie_id == "" && $filtre_serie_id == "" && $filtre_editeur_id == "" && $filtre_auteur_id == "") {
                    $sth_livres = $pdo->prepare("SELECT DISTINCT livre_id, livre_titre, serie_id, editeur_id
                                                FROM livres");
                } else {
                    $sth_livres = $pdo->prepare("SELECT DISTINCT livre_id, livre_titre, serie_id, editeur_id
                                                FROM livres
                                                WHERE livre_id NOT IN (
                                                                SELECT livre_id
                                                                FROM livres
                                                                WHERE categorie_id != '".$filtre_categorie_id."'
                                                                AND '".$filtre_categorie_id."' != '')
                                                AND livre_id NOT IN (
                                                                SELECT livre_id
                                                                FROM livres
                                                                WHERE serie_id != '".$filtre_serie_id."'
                                                                AND '".$filtre_serie_id."' != '')
                                                AND livre_id NOT IN (
                                                                SELECT livre_id
                                                                FROM livres
                                                                WHERE editeur_id != '".$filtre_editeur_id."'
                                                                AND '".$filtre_editeur_id."' != '')
                                                AND livre_id NOT IN (
                                                                SELECT livre_id
                                                                FROM livres
                                                                WHERE livre_id NOT IN (
                                                                    SELECT livres.livre_id
                                                                    FROM livres
                                                                    JOIN ecrire ON ecrire.livre_id = livres.livre_id 
                                                                    WHERE ecrire.auteur_id = '".$filtre_auteur_id."'
                                                                    AND '".$filtre_auteur_id."' != '')
                                                                )
                                                ");
                }
                $sth_livres->execute();
                $result_livres = $sth_livres->fetchAll();
                $data_livres = null;
                foreach ($result_livres as $row_livres) {
                $data_livres[] = $row_livres;
                }
                if (isset($data_livres[0])) {
                    foreach ($data_livres as $row_livres) {
                        $livre_id = $row_livres['livre_id'];
                        $livre_titre = $row_livres['livre_titre'];
                        $serie_id = $row_livres['serie_id'];
                        $editeur_id = $row_livres['editeur_id']; ?>
                        <card class="bg-light my-2 mx-2 p-3 col-4 rounded-3">
                            <strong><?php echo $livre_titre ?></strong>
                            <?php
                            //SERIE DU LIVRE
                            try{
                                //requête
                                $sth_series = $pdo->prepare(
                                        "SELECT serie_libelle
                                        FROM series
                                        WHERE series.serie_id = '".$serie_id."'"
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
                                    $serie_libelle = $row_series["serie_libelle"]; ?>
                                    <div class="row">Série : <?php echo $serie_libelle ?></div>
                                <?php
                                }
                            }
                            //EDITEUR DU LIVRE
                            try{
                                //requête
                                $sth_editeurs = $pdo->prepare(
                                        "SELECT editeur_nom
                                        FROM editeurs
                                        WHERE editeurs.editeur_id = '".$editeur_id."'"
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
                                    $editeur_nom = $row_editeurs["editeur_nom"]; ?>
                                    <div class="row">Editeur : <?php echo $editeur_nom ?></div>
                                <?php
                                }
                            }

                        ?>
                        </card>
                    <?php
                    }
                    //pas de livres a afficher
                } else { ?>
                        
                    <card class="bg-light my-2 mx-auto">Aucun livre ne correspond aux critères</card>
                <?php
                }
                ?>
            </div>
        </div>
    </div>





    <?php include_once('footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>