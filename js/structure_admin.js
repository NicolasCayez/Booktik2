//********************************************/
//* HEADER                                   */
//********************************************/
// Recherche de l'id #header
const divHeader = document.getElementById("header");
// Création navbar
let navbarHeader = document.createElement("nav");
navbarHeader.id = "navbarHeader";
navbarHeader.classList.add("navbar");
navbarHeader.classList.add("row");
// intégration navbar dans le header
divHeader.append(navbarHeader);
    // Création logo
    let logo = document.createElement("img");
    logo.src = "../img/logo_blanc.png";
    logo.alt = "logo Blanc";
    logo.classList.add("logo");
    logo.classList.add("col");
    navbarHeader.appendChild(logo);
    // Création div titre et menu
    let divTitreMenu = document.createElement("div");
    divTitreMenu.classList.add("row");
    divTitreMenu.classList.add("row-cols-1");
    divTitreMenu.classList.add("text-center");
    divTitreMenu.classList.add("m-0");
    navbarHeader.appendChild(divTitreMenu);
        // Création titre
        let titreDiv = document.createElement("div");
        divTitreMenu.appendChild(titreDiv);
        let titre = document.createElement("h1");
        titre.innerText = "Administration";
        titre.classList.add("fs-3");
        titre.classList.add("pb-2");
        titre.classList.add("custom-font-title");
        titre.classList.add("text-white");
        titre.id = "titre_Admin";
        titreDiv.appendChild(titre);
        // Création menuHeader
        let menuHeader = document.createElement("div");
        menuHeader.id = "menuHeader";
        menuHeader.classList.add("m-0");
        divTitreMenu.appendChild(menuHeader);
            //Bouton Livres
            creerElementMenuAdmin("btn_livres","Livres","../img/logo_tomes.png", menuHeader);
            //Bouton Séries
            creerElementMenuAdmin("btn_series","Séries","../img/logo_tomes.png", menuHeader);
            //Bouton Auteurs
            creerElementMenuAdmin("btn_auteurs","Auteurs","../img/logo_auteurs.png", menuHeader);
            //Bouton Editeurs
            creerElementMenuAdmin("btn_editeurs","Editeurs","../img/logo_editeurs.png", menuHeader);
            //Bouton Types & genres
            creerElementMenuAdmin("btn_categories","Catégories","", menuHeader);
            //Bouton Déconnexion
            creerElementMenuAdmin("btn_deconnexion","Déconnexion","../img/sign_out.png", menuHeader);

    // Création checkbox
    let checkbox = document.createElement("input");
    checkbox.type = "checkbox";
    navbarHeader.appendChild(checkbox);
    // Création lignes hamburger
    let hamburgerLines = document.createElement("div");
    hamburgerLines.classList.add("hamburger-lines");
    navbarHeader.appendChild(hamburgerLines);
    for (let i=1; i<=3; i++) {
        let line = document.createElement("span");
        line.classList.add("line");
        line.classList.add("line"+i);
        hamburgerLines.appendChild(line);
    };
    //création menu caché hamburger
    let hiddenMenu = document.createElement("div");
    hiddenMenu.classList.add("hiddenMenu");
    hiddenMenu.classList.add("text-center");

    navbarHeader.appendChild(hiddenMenu);
            //Bouton Livres
            creerElementMenuAdmin("btn_livres","Livres","", hiddenMenu);
            //Bouton Séries
            creerElementMenuAdmin("btn_series","Séries","", hiddenMenu);
            //Bouton Auteurs
            creerElementMenuAdmin("btn_auteurs","Auteurs","", hiddenMenu);
            //Bouton Editeurs
            creerElementMenuAdmin("btn_editeurs","Editeurs","", hiddenMenu);
            //Bouton Types & genres
            creerElementMenuAdmin("btn_categories","Catégories","", hiddenMenu);





//********************************************/
//* CLICS MENU
//********************************************/
// récupération boutons menu
let btn_livres = document.getElementById("btn_livres");
let btn_series = document.getElementById("btn_series");
let btn_auteurs = document.getElementById("btn_auteurs");
let btn_editeurs = document.getElementById("btn_editeurs");
let btn_categories = document.getElementById("btn_categories");
let btn_deconnexions = document.getElementById("btn_deconnexion");

//Gestion des éléments actifs selon clic bouton
btn_livres.addEventListener('click', () => {
    window.location.replace("admin_livres.php");
});
btn_series.addEventListener('click', () => {
    window.location.replace("admin_series.php");
});
btn_auteurs.addEventListener('click', () => {
    window.location.replace("admin_auteurs.php");
});
btn_editeurs.addEventListener('click', () => {
    window.location.replace("admin_editeurs.php");
});
btn_categories.addEventListener('click', () => {
    window.location.replace("admin_categories.php");
});
btn_deconnexions.addEventListener('click', () => {
    window.location.replace("../deconnexion.php");
});

//********************************************/
//* CLICS MODIFIER
//********************************************/
function clic_detail_livre (livre_id) {
    const section_detail_livre = document.getElementById(livre_id);
    if (section_detail_livre.style.display == "block") {
        section_detail_livre.style.display = "none";
    } else {
        section_detail_livre.style.display = "block";
    }
}

