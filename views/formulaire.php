<?php

$title = "formulaire";
$actionUrl = "/Add/" . htmlspecialchars($entity, ENT_QUOTES, 'UTF-8'); // Valeur par défaut pour ajouter
?>



<div class="form-container-parent">
    <div class="form-container-formulaire">
        <h2>Contactez PulseTech</h2>
        <form id="contactForm" action="<?= $actionUrl ?>" method="POST" onsubmit="return validateForm(event)">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required minlength="2" maxlength="100">

            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" required minlength="2" maxlength="100">

            <label for="numero">Numéro de téléphone :</label>
            <input type="tel" id="numero" name="numero" required pattern="^0[1-9]\d{8}$" placeholder="06 12 34 56 78">

            <label for="courriel">Email :</label>
            <input type="email" id="courriel" name="courriel" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">

            <label for="message">Message :</label>
            <textarea id="message" name="message" required minlength="10" maxlength="500"></textarea>

          

            <label for="service_demande">Service demandé :</label>
            <select id="service_demande" name="service_demande" required>
                <option value="site">Création de site web</option>
                <option value="software">Développement logiciel</option>
                <option value="maintenance">Maintenance informatique</option>
                <option value="repair">Réparation de téléphone</option>
            </select>

            <button type="submit">Envoyer</button>
            <div id="errorMessage" class="error"></div>
        </form>
    </div>
    </div>
        <!-- Footer -->
        <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-3"><a href="/path/to/app_rgpd">&copy; Les mentions légales</a></div>
                <div class="col-12 col-md-6 col-lg-3"><a href="/path/to/app_rgpd_cgu">Conditions générales d'utilisation</a></div>
                <div class="col-12 col-md-6 col-lg-3"><a href="/path/to/app_contact">Politiques des Cookies</a></div>
                <div class="col-12 col-md-6 col-lg-3"><a href="/path/to/app_contact">&hearts; Contact Moi</a></div>
            </div>
        </div>
    </div>
