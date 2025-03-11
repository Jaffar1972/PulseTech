<?php
header("Content-Type: text/css; charset=UTF-8");
require_once '../../App/Config.php'; // Adapte le chemin si nécessaire
?>

body {
    background-image: linear-gradient(to bottom, rgba(255, 128, 255, 0.5), rgba(0, 0, 128, 0.5));
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    background-attachment: fixed;
    position: relative; 
    margin: 0;
    padding: 0;
    font-family: 'Kingsguard', Arial, sans-serif; 
}

#chat {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    object-fit: cover;
    z-index: -1;
    opacity: 0.02;
    filter: brightness(1) blur(3px);
    transition: opacity 1s ease, filter 0.5s ease;
}

nav {
    display: flex;
    justify-content: center;
    align-items: center;
    max-width: 1400px; /* Largeur maximale du menu */
    width: 100%; /* Prend toute la largeur disponible jusqu'à max-width */
    margin: 0 auto; /* Centre le nav horizontalement */
    margin-top: 50px;
    border: 3px solid gold;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    padding: 30px;
    z-index: 1; /* Pour qu'il soit au-dessus de l'arrière-plan */
    background-color: white; /* Fond blanc */

}

nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%; /* Prend toute la largeur disponible du nav */
}

nav ul li {
    margin: 0 10px !important; /* Réduisez la marge si nécessaire */
    font-size: 20px !important; /* Ajustez la taille de la police si nécessaire */
    color: rgb(19, 18, 19);
}

nav ul li a {
    font-size: 20px !important;
    text-decoration: none;
    color: rgb(8, 8, 8);
    font-weight: normal;
}

nav ul li a:hover {
    color: #ffcc00;
}

.table-container {
    max-width: 1400px; /* Largeur maximale de la table */
    margin: 0 auto; /* Centrage horizontal */
    padding: 20px;
    box-sizing: border-box;
}


#kamel {
    width: 100%; /* La table prend toute la largeur du conteneur */
    max-width: 100%;
    margin: 0 auto;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
    z-index: 1; /* Pour qu'il soit au-dessus de l'arrière-plan */
    background-color: white; /* Fond blanc */
}



#kamel th {
    font-size: 20px; /* Réduction de la taille des entêtes */
    padding: 12px;
}

#kamel td {
    font-size: 16px;
    padding: 8px;
}

/* Ajustement du bouton "Charger plus" */
#load-more {
    display: block;
    margin: 20px auto;
}

.navbar-nav .nav-link:hover {
    background-color: rgba(0, 0, 0, 0.3);
}

@media (max-width: 768px) {
    .form-container {
        width: 80%;
    }
}

#home-icon {
    position: absolute;
    left: 40px;
   top:40px;
    transform: translateY(-50%);
    width: 80px;
    height: 80px;
}

.dropdown {
    position: relative;
    display: inline-block;
}

.dropbtn {
    background-color: #3498db;
    color: white;
    padding: 16px;
    font-size: 16px;
    border: none;
    cursor: pointer;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    z-index: 1;
}

.dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

.dropdown-content a:hover {
    background-color: #f1f1f1;
}

.dropdown:hover .dropdown-content {
    display: block;
}

.dropdown:hover .dropbtn {
    background-color: #2980b9;
}

/* Déclaration des polices */
@font-face {
    font-family: "Imperial";
    src: url("<?php echo SCRIPTS_fonts; ?>Imperial.ttf") format("truetype");
    font-display: swap;
}

@font-face {
    font-family: "Kingsguard";
    src: url("<?php echo SCRIPTS_fonts; ?>Kingsguard.otf") format("opentype");
    font-display: swap;
}

@font-face {
    font-family: "OldLondon";
    src: url("<?php echo SCRIPTS_fonts; ?>OldLondon.ttf") format("truetype");
    font-display: swap;
}

@font-face {
    font-family: "Khiara";
    src: url("<?php echo SCRIPTS_fonts; ?>Khiara.woff2") format("opentype");
    font-weight: normal;
    font-style: normal;
    font-display: swap;
}

.OldLondon {
    font-family: "OldLondon", "Times New Roman", serif;
}

.Kingsguard {
    font-family: "Kingsguard", "Times New Roman", serif;
}

.Khiara {
    font-family: "Khiara", "Times New Roman", serif;
    color: red;
    font-size: 105px;
}

.Imperial {
    font-family: "Imperial" !important;
    font-size: 10em;
    color: rgb(15, 248, 65);
    border: 2px solid rgb(41, 39, 39);
}

/* Ajout de l'effet hover pour les boutons */
button:hover, input[type="submit"]:hover, input[type="text"]:hover, input[type="search"]:hover {
    background-color: rgb(89, 69, 160);
}

/* Conteneur de recherche */
.search-container {
    display: none;
    position: relative;
    width: 50%;
    margin: 0 auto;
    background-color: white;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    padding: 10px;
    top: 10px;
}

.search-container.visible {
    display: block;
}

#searchInput {
    border: none;
    outline: none;
    flex-grow: 1;
    padding: 5px;
}

#searchButton {
    background: none;
    border: none;
    cursor: pointer;
    padding: 5px;
}

#searchButton img {
    width: 40px;
    height: 40px;
}

.hidden {
    display: none;
}

.form-container-parent {
    display: flex;
    justify-content: center; /* Centre horizontalement */
    align-items: flex-start; /* Déplace le formulaire vers le haut */
    height: 100vh; /* Prend toute la hauteur de la fenêtre */
    padding-top: 70px; /* Ajoute un espace en haut pour le remonter */
  
}

.form-container-formulaire {
    max-width: 650px;
  
    margin: 20px auto; /* Peut être ajusté ou supprimé */
    padding: 15px;
    background: rgba(255, 255, 255, 0.6);
    border: 3px solid gold;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    z-index: 1; /* Pour qu'il soit au-dessus de l'arrière-plan */
    background-color: white; /* Fond blanc */
}




.form-container-formulaire h2 {
    font-family: 'Kingsguard';
    font-size: 1.2em;
    margin: 0 0 10px;
    color: #333;
    text-align: center;
}

.form-container-formulaire label {
    display: inline-block;
    width: 120px;
    font-family: 'Kingsguard';
    font-size: 0.9em;
    color: #555;
    vertical-align: top;
    margin: 5px 0;
}

.form-container-formulaire input,
.form-container-formulaire select,
.form-container-formulaire textarea {
    width: calc(100% - 130px);
    padding: 5px;
    margin-bottom: 8px;
    border: 1px solid #ccc;
    border-radius: 3px;
    font-size: 0.9em;
    font-family: 'Kingsguard', Arial, sans-serif;
    box-sizing: border-box;
}

.form-container-formulaire textarea {
    height: 60px;
    resize: none;
}

.form-container-formulaire button {
    background: #007bff;
    color: white;
    padding: 6px 12px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    font-family: 'Kingsguard';
    font-size: 0.9em;
    width: 100%;
}

.form-container-formulaire button:hover {
    background: #0056b3;
}

.error {
    color: red;
    font-size: 0.8em;
    margin-top: 5px;
    display: none;
    font-family: 'Kingsguard';
}

