<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? htmlspecialchars($title) : "Titre par défaut" ?></title>


    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link href="<?= SCRIPTS_css ?>fichier.php?v=<?= time() ?>" rel="stylesheet" media="screen" />

</head>

<body>
    <img id="chat" src="<?= SCRIPTS_css ?>../img/photo2.jpg?v=<?= time() ?>" alt="Chat mignon" />
    <div id="centrer">
        <nav>



            <ul>
                <li>
                    <a href="/">
                        <img id="home-icon" src="<?= SCRIPTS_css ?>../img/photo.webp?v=<?= time() ?>" alt="Icône maison" width="20" height="20">
                    </a>
                </li>
                <li class="nav-item d-none d-md-block"><a href="/Depanner">Dépanner un site internet</a></li>
                <li class="nav-item d-none d-md-block"><a href="/All/Association">Assistance et dépannage informatique<span class="sr-only">(current)</span></a></li>
                <li class="nav-item d-none d-md-block"><a href="/All/Adresse">Réparation Smartphones</a></li>
                <li class="nav-item d-none d-md-block"><a href="/Formulaire/formulaire">Formulaire</a></li>
                <li class="nav-item d-none d-md-block"><a href="/Administrateur/formulaire">Suivi Client</a></li>

                <div class="dropdown">
                    <button class="dropbtn">Menu Déroulant</button>
                    <div class="dropdown-content">
                        <a href="#">Dépanner un site internet</a>
                        <a href="#">Assistance et dépannage informatique</a>
                        <a href="#">Réparation Smartphones</a>
                    </div>
                </div>
                <li>

                    <button id="searchButton">
                        <img src="<?= SCRIPTS_css ?>../img/chercher2.png?v=<?= time() ?>" alt="Search Icon">
                    </button>


                </li>
                <?php if (isset($_SESSION['auth'])): ?>
                    <li><a class="" href="/logOut">LogOut</a></li>
                <?php endif ?>

            </ul>


    </div>

    </nav>
    <div class="search-container hidden">
        <input type="text" id="searchInput" placeholder="Rechercher...">
    </div>
    </div>
    <div class="container-fluid">
        <?= isset($content) ? $content : ''; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous" defer></script>
    <script src="<?= SCRIPTS_css ?>../js/animation.js?v=<?= time() ?>" defer></script>
    <script src="<?= SCRIPTS_css ?>../js/script.js?v=<?= time() ?>"></script>

</body>

</html>