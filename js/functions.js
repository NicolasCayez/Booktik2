//********************************************/
//* Création d'un bouton du menu du header   */
//********************************************/
function creerElementMenuAdmin (idChild, libelle, urlLogo, cible) {
    //création div bouton
    let btn = document.createElement("div");
    btn.id = idChild;
    btn.classList.add("btnMenu");
    btn.classList.add("bg-custom-red");
    btn.classList.add("text-decoration-none");
    btn.classList.add("mx-3");
    btn.classList.add("mt-3");
    btn.classList.add("py-1");
    btn.classList.add("ps-2");
        //création logo dans la div
        if (urlLogo != ""){
            let logo = document.createElement("img");
            logo.src = urlLogo;
            logo.classList.add("logoBouton");
            btn.appendChild(logo);
        }
        //création texte du bouton
        let btnLien = document.createElement("span");
        btnLien.innerText = libelle;
        btnLien.classList.add("ps-md-1");
        btnLien.classList.add("ps-0");
        btn.style.display = "inline-block";
        btn.appendChild(btnLien);
    cible.appendChild(btn);
}

//********************************************/
//* Création d'un bouton du menu latéral     */
//********************************************/
function creerElementMenuLateral (idChild, libelle, urlLogo, cible) {
    //création div bouton
    let btn = document.createElement("div");
    btn.id = idChild;
    btn.classList.add("btnMenuLateral");
    btn.classList.add("bg-custom-beige");
    btn.classList.add("custom-bleu");
    btn.classList.add("text-decoration-none");
    btn.classList.add("mx-3");
    btn.classList.add("mt-3");
    btn.classList.add("py-1");
    btn.classList.add("ps-2");
        //création logo dans la div
        if (urlLogo != ""){
            let logo = document.createElement("img");
            logo.src = urlLogo;
            logo.classList.add("logoBouton");
            btn.appendChild(logo);
        }
        //création texte du bouton
        let btnLien = document.createElement("span");
        btnLien.innerText = libelle;
        btnLien.classList.add("ps-md-1");
        btnLien.classList.add("ps-0");
        btn.appendChild(btnLien);
    cible.appendChild(btn);
}

//********************************************/
//* Création d'un bouton sous-catégories     */
//********************************************/
function creerElementMenuSousCat (idChild, libelle, urlLogo, cible) {
    //création div bouton
    let btn = document.createElement("div");
    btn.id = idChild;
    btn.classList.add("btnSousCat");
    btn.classList.add("bg-custom-bleu");
    btn.classList.add("custom-beige");
    btn.classList.add("text-decoration-none");
    btn.classList.add("mx-3");
    btn.classList.add("mt-3");
    btn.classList.add("py-1");
    btn.classList.add("ps-2");
        //création logo dans la div
        if (urlLogo != ""){
            let logo = document.createElement("img");
            logo.src = urlLogo;
            logo.classList.add("logoBouton");
            btn.appendChild(logo);
        }
        //création texte du bouton
        let btnLien = document.createElement("span");
        btnLien.innerText = libelle;
        btnLien.classList.add("ps-md-1");
        btnLien.classList.add("ps-0");
        btn.style.display = "inline-block";
        btn.appendChild(btnLien);
    cible.appendChild(btn);
}

//********************************************/
//* Création d'un séparateur menu latéral     */
//********************************************/
function creerSeparateurMenuLateral (idChild, cible) {
    //création div bouton
    let line = document.createElement("div");
    line.id = idChild;
    line.classList.add("ligneMenuLateral");
    line.classList.add("my-5");
    cible.appendChild(line);
}

// récupération corps de la page sous le titre
const pageContent = document.getElementById("pageContent");

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


//********************************************/
//* CLICS CONNEXION
//********************************************/
const btnConnexion = document.getElementById("btn_connexion");
btnConnexion.addEventListener('click', () => {
    window.location.assign("connexion.php");
});
