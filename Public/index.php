<?php
//il suffit de taper !+Tabulation 
// lorem10 puis entrez
// p*5>lorem20



//define("PI", 3.14);
//echo constant("PI");
//__DIR__, dirname(__DIR__).... ne dépend que du chemin relatif entre le fichier inclus et celui dans lequel il est inclus
//dirname(__DIR__) : recule d'1 niveau
//faire un sépareteur, mais en quoi mettre .DIRECTORY_SEPARATOR. plutôt que '/' ou '\' 

use src\Run;
use Users\User;
use Users\Login;

use Routes\Route;
use src\Renderer;
use Vehicle\Peugeot;
use Vehicle\Autorisation;
use Controllers\HomeController;



require '../vendor/autoload.php';


define('BASE_VIEW_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR);
define('Chemin', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR);

$projectName = basename(dirname(__DIR__)); // Récupère "cnam12" dynamiquement
define('PROJECT_NAME', $projectName);
define('SCRIPTS_css', "http://localhost/" . PROJECT_NAME . "/Public/css/");






// Création du routeur et enregistrement des routes
// Initialiser le routeur
$router = new Route(); // Remplacez `Router` par le nom de la classe de votre routeur

$router->register('/', function () {

    //$hashedPassword = password_hash('Juin2080', PASSWORD_DEFAULT);
    //echo $hashedPassword;

    return Renderer::make('authentification');
});


$router->register('/Depanner', function () {

    //$hashedPassword = password_hash('Juin2080', PASSWORD_DEFAULT);
    //echo $hashedPassword;

    return Renderer::make('depanner');
});

$router->register('/Formulaire/:entity', function ($entity) {
    return Renderer::make('formulaire',['entity' => $entity]);
});


$router->register('/loginPost', function () {

    $controller = new \Controllers\HomeController();
    return $controller->loginPost();
});

$router->register('/logOut', function () {

    $controller = new \Controllers\HomeController();
    return $controller->logOut();
});


$router->register('/Administrateur/:entity', function ($entity) {
    // Démarrer la session pour accéder aux variables de session
    
    // Démarrer la session uniquement si elle n'est pas déjà active
   // if (session_status() === PHP_SESSION_NONE) {
        // session_start();
    // }

    // Vérifier si l'utilisateur est authentifié
    // if (isset($_SESSION['user_id'])) {
        // L'utilisateur est connecté, procéder avec le contrôleur
        $controller = new \Controllers\HomeController();
        return $controller->information($entity);
    //} else {
        // L'utilisateur n'est pas connecté, rediriger vers la page d'authentification
       // return Renderer::make('authentification');
        //exit();
    //}
});


$router->register('/Add/:entity', function ($entity) {
    $controller = new \Controllers\HomeController($entity);
    return $controller->insert();
});

// $router->register('/EntityCreate/:entity/:id?/:variable?', function ($entity, $id = null, $variable = null) {


//     $controller = new \Controllers\HomeController($entity);
//     return $controller->EntityCreate($id, $variable);
// });

$router->register('/EntityCreate/:entity/:id?/:variable?', function ($entity, $id = null, $variable = null) {
    
    // Vérifie si "ajouter" est dans l'URL (GET)
    $ajouter = isset($_GET['ajouter']) && $_GET['ajouter'] === 'true';

    $controller = new \Controllers\HomeController($entity);
    return $controller->EntityCreate($id, $variable, $ajouter);
});



$router->register('/updated/:entity/:id/:variable?', function ($entity, $id, $variable = null) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $model = $_POST['model'] ?? null;

        if ($model) {
            $controller = new \Controllers\HomeController($model);
            return $controller->updated($id);
        } else {
            // Si $model est null, retourne une erreur ou redirige
            header('Location: /error-page'); // À adapter selon ton application
            exit;
        }
    }

    // Pour les requêtes GET, on instancie uniquement si nécessaire
    $controller = new \Controllers\HomeController($entity);
    return $controller->EntityCreate($id, $variable);
});


$router->register('/search', function () {
    $controller = new \Controllers\HomeController();
    return $controller->search(); // On passe le paramètre $id à la méthode
});


//$router->register('/associationDeleted/:id', ['Controllers\HomeController','associationDeleted']);

$router->register('/entityDeleted/:entity/:id', function ($entity, $id) {
    $controller = new \Controllers\HomeController($entity);
    return $controller->Deleted($id); // On passe le paramètre $id à la méthode
});



try {
    (new Run($router, $_SERVER['REQUEST_URI']))->run();
} catch (\Exception $e) {
    echo $e->getMessage();
}


// Résolution de l'URI et exécution de l'action associée
//try {
    //echo "Requested URI: " . $_SERVER['REQUEST_URI'] . "<br>";
    //$result = $router->resolve($_SERVER['REQUEST_URI']);
    //echo $result;
//} catch (\Exception $e) {
    //secho $e->getMessage();}

    

//$router->register('/:entity/:id', function($entity,$id) {
    //$type = $_GET['type'] ?? null; 
    //$controller = new \Controllers\HomeController($entity);
   // return $controller->Details($id,$type);
//});



//$router->register('/association/:id', ['Controllers\HomeController','associationByID']);
//$router->register('/associationNew', function($id) {
    //$controller = new \Controllers\HomeController('Association');
    //return $controller->Details($id);
//});
