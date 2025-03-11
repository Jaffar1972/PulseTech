<?php

namespace Controllers;

use App\Model;
use Exception;
use App\Activite;
use src\Renderer;
use App\Annonceur;
use App\Association;


class HomeController extends Model
{

    protected string $table;  // Assure que $table est toujours une chaîne de caractères

    // Constructeur qui définit la table
    public function __construct(string $table = 'formulaire')
        {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            parent::__construct(); // Assurez-vous que Model a un constructeur qui initialise la connexion DB
            $this->table = $table;
        }
    





    public function information($entity): Renderer
{
 
       // Vérifier l'authentification de l'utilisateur
  

    // Construire dynamiquement le nom complet de la classe
    $classe = "App\\" . $entity;

    // Vérifier que la classe existe
    if (!class_exists($classe)) {
        throw new \Exception("La classe $classe n'existe pas !"); // Utiliser une exception plutôt qu'un die()
    }

    // Instancier la classe avec la connexion PDO
    $requete = new $classe($this->db);

    // Récupérer toutes les données de la table
    try {
        $tableauComplet = $requete->all();
    } catch (\Exception $e) {
        throw new \Exception("Erreur lors de la récupération des données : " . $e->getMessage());
    }

    // Extraire les colonnes et les données
    $columns = $tableauComplet['columns'] ?? [];
    $data = $tableauComplet['data'] ?? [];

    
    // Limiter le nombre d'enregistrements affichés (par exemple, 5 premiers)
    $tableau = array_slice($data, 0, 5);

    // Retourner le rendu de la vue avec les données nécessaires
    return Renderer::make('Entity', compact('columns', 'tableau', 'classe', 'entity'));
}
    public function EntityCreate($id = null, $AutreVariable = null): Renderer
    {
        // Vérification de la classe associée à la table
        $classe = "App\\" . $this->table;
        $entity = $this->table;
     

        if (empty($classe)) {
            throw new Exception("Erreur : la classe associée à la table '{$this->table}' est introuvable.");
        }

        // Instanciation de la classe associée
        $requete = new $classe($this->db);

        // Récupérer les colonnes de la table
        $columns = $requete->getTableColumns($this->db);

        if (!$columns || !is_array($columns)) {
            throw new Exception("Erreur : impossible de récupérer les colonnes pour la table '{$this->table}'.");
        }

        // Si $AutreVariable est vide ou non définie, on la met à null
        $AutreVariable = !empty($AutreVariable) ? $AutreVariable : null;

        // Initialisation de $tableau
        $tableau = null;

        // Si AutreVariable est 'ajouter', on ne fait pas de recherche par ID
        if ($id === '1') {
            // On n'effectue aucune recherche ici
            $tableau = null;
        } else if (!empty($id)) {
            // Si ce n'est pas un ajout, on fait une recherche par ID
            $tableau = $requete->findById($id);
            

            // Si l'ID est fourni mais introuvable, on peut lever une exception
            if (!$tableau) {
                throw new Exception("Aucun enregistrement trouvé avec l'id {$id}");
            }
        }

        // Renvoyer les données à la vue
        return Renderer::make('EntityCreate', [
            'columns' => $columns,
            'tableau' => $tableau,  // Peut être null si pas d'ID ou si AutreVariable == 'ajouter'
            'entity' => $entity,
            'AutreVariable' => $AutreVariable,
            'id' => $id // Peut être null aussi
        ]);
    }

    public function search()
    {
        // Si un terme de recherche est passé dans l'URL, le récupérer
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
        }

        // Connexion à la base de données
        $db = $this->db; // Assurez-vous que `getPDO()` est correctement défini dans votre classe
        $association = new Association($db);

        // Rechercher dans toutes les colonnes de la table
        $tableau = $association->searchInAllColumns($search);

        // Vérifier s'il y a des résultats
        if (!empty($tableau)) {
            // Passer les résultats à la vue avec le tableau trouvé
            return Renderer::make('search', ['tableau' => $tableau]);
        } else {
            // Aucun résultat trouvé
            return Renderer::make('search', ['message' => 'Aucun résultat trouvé pour votre recherche.']);
        }
    }

    public function isAdmin()
    {
        // Vérifier si l'utilisateur est connecté et s'il est administrateur
        if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== 1) {
            // Rediriger si non autorisé
            header('Location: /');
            exit;
        }

        return true; // L'utilisateur est un administrateur
    }

    public function loginPost()
    {
        
        $requete = new Model($this->db);

        $resultat = $requete->getByUsername($_POST['email']);

        if ($resultat && password_verify($_POST['password'], $resultat->password)) {

          

            $_SESSION['auth'] = (int) $resultat->admin;
            $_SESSION['user_id'] = $resultat->id;
            

            return $this->information('association');
           
        } else {

            // Renvoyer les données à la vue
            return Renderer::make('authentification', ['error' => 'Identifiants incorrects.']);
        }
    }


    public function login()
    {


        return Renderer::make('Authentification');
    }

    public function logout()
    {
        // Détruire toutes les données de session
       
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
    
        // Rediriger vers la page d'accueil
        header('Location: /');
        exit;
    }


    // public function Details($id)
    // {
    //     if (!is_numeric($id)) {
    //         // Si l'ID n'est pas un nombre, affiche une erreur ou redirige vers la page de création
    //         return Renderer::make($this->table, [
    //             'tableau' => [], // Nouvelle association
    //             'activite' => []
    //         ]);
    //     }

    //     //$requete = new Annonceur($this->getPDO());
    //     $classe = "App\\" . $this->table;
    //     $requete = new $classe($this->getPDO());

    //     $tableau = $requete->findById($id);
    //     $message = ' Fiche '.$classe;

    //     // Récupérer les infos compte liées à cette annonceur

    //     // Vérifier si la méthode getInfosCompte existe pour cette entité

    //         // Initialiser les variables à null
    //         $compte = null;
    //         $activite = null;
    //         $all = null;

    //     if (method_exists($requete, 'getInfosCompte')) {
    //         // Appeler la méthode uniquement si elle existe
    //         $compte = $requete->getInfosCompte($id);
    //     }
    //     if (method_exists($requete, 'getActivites')) { 
    //         // Appeler la méthode uniquement si elle existe
    //         $activite = $requete->getActivites($id);;
    //     }
    //     if (method_exists($requete, 'getActivitesAll')) {
    //         // Appeler la méthode uniquement si elle existe
    //             // Récupérer les activités liées à cette association
    //         $all = $requete->getActivitesAll();
    //     }

    //     // Pass the fetched result to the view
    //     return Renderer::make($this->table . 'ById', compact('tableau','compte','activite','all','message'));
    // }

    public function updated($id)
    {
        // Vérifier si le formulaire a été soumis via POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Récupérer les données POST
            $data = $_POST;
            unset($data['model']);

            // Si des relations (activités) sont soumises via un champ de formulaire "secteur_activite"
            // On les récupère dans un tableau
            $relations = isset($data['secteur_activite']) ? $data['secteur_activite'] : null;
            unset($data['secteur_activite']);

            // Nettoyage des données (échapper les caractères spéciaux si nécessaire)
            foreach ($data as $key => $value) {
                // Vérifier si $value est un tableau (comme pour secteur_activite)
                if (is_array($value)) {
                    // On laisse les tableaux intacts
                    $data[$key] = array_map('htmlspecialchars', $value);
                } else {
                    // Sinon on nettoie la valeur
                    $data[$key] = $value === '' ? null : htmlspecialchars($value);
                }
            }

            try {
                // Instancier le modèle Association
                $classe = "App\\" . $this->table;
                // Assurez-vous que ce modèle est correctement chargé.
                $requete = new $classe($this->db);

                // Appeler la méthode associationUpdated avec les données POST
                $requete->Updated($id, $data, $relations);

                $entity = $this->table;

                $message = $this->table . ' numero ' . $id . ' mis à jour avec succès !';

                // Récupérer toutes les données
                $tableauComplet = $requete->all();

                // Extraire les colonnes et les données
                $columns = $tableauComplet['columns'];
                $data = $tableauComplet['data'];

                // Prendre uniquement les 5 premiers enregistrements
                $tableau = array_slice($data, 0, 5);

                if (empty($tableau)) {
                    die("Aucune donnée trouvée !");
                }

                // Retourner le rendu en incluant le nom de la classe
                return Renderer::make('Entity', compact('columns', 'tableau', 'classe', 'entity'));
            } catch (\Exception $e) {
                // Gérer les erreurs
                echo "Erreur lors de la mise à jour de l'association : " . $e->getMessage();
            }
        } else {
            // Si la méthode n'est pas POST, afficher une erreur ou rediriger
            echo "Méthode non autorisée.";
        }
    }


    public function Deleted($id)
    {
        // On suppose que la classe Annonceur a une méthode delete qui retourne un booléen
        //$requete = new Association();
        $classe = "App\\" . $this->table;
        $requete = new $classe($this->db);

        $requete->delete($id);
        $entity = $this->table;

        $message = $this->table . ' numero ' . $id . ' mis à jour avec succès !';

        // Récupérer toutes les données
        $tableauComplet = $requete->all();

        // Extraire les colonnes et les données
        $columns = $tableauComplet['columns'];
        $data = $tableauComplet['data'];

        // Prendre uniquement les 5 premiers enregistrements
        $tableau = array_slice($data, 0, 5);

        

        // Retourner le rendu en incluant le nom de la classe
        return Renderer::make('Entity', compact('columns', 'tableau', 'classe', 'entity'));
    }

    public function insert()
    {
        // Vérifier si le formulaire a été soumis via POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Récupérer les données POST
            $data = $_POST;
            unset($data['model']);

            // Si des relations (activités) sont soumises via un champ de formulaire "secteur_activite"
            // On les récupère dans un tableau
            $relations = isset($data['secteur_activite']) ? $data['secteur_activite'] : null;
            unset($data['secteur_activite']);

            // Nettoyage des données (échapper les caractères spéciaux si nécessaire)
            foreach ($data as $key => $value) {
                // Vérifier si $value est un tableau (comme pour secteur_activite)
                if (is_array($value)) {
                    // On laisse les tableaux intacts
                    $data[$key] = array_map('htmlspecialchars', $value);
                } else {
                    // Sinon on nettoie la valeur
                    $data[$key] = $value === '' ? null : htmlspecialchars($value);
                }
            }

            try {
                // Instancier le modèle Association
                $classe = "App\\" . $this->table;
                // Assurez-vous que ce modèle est correctement chargé.
                $requete = new $classe($this->db);



                // Appeler la méthode associationUpdated avec les données POST
                $requete->Insertion($data, $relations);

                $entity = $this->table;

                $message = $this->table . ' mis à jour avec succès !';

                // Récupérer toutes les données
                $tableauComplet = $requete->all();

                // Extraire les colonnes et les données
                $columns = $tableauComplet['columns'];
                $data = $tableauComplet['data'];

                // Prendre uniquement les 5 premiers enregistrements
                $tableau = array_slice($data, 0, 5);

                if (empty($tableau)) {
                    die("Aucune donnée trouvée !");
                }

                // Retourner le rendu en incluant le nom de la classe
                return Renderer::make('administrateur', compact('columns', 'tableau', 'classe', 'entity'));
            } catch (\Exception $e) {
                // Gérer les erreurs
                echo "Erreur lors de la mise à jour du Formulaire : " . $e->getMessage();
            }
        } else {
            // Si la méthode n'est pas POST, afficher une erreur ou rediriger
            echo "Méthode non autorisée.";
        }
    }




    public function insert222(array $data)
    {
        // Préparer les colonnes et les valeurs à insérer
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        // Préparer la requête SQL
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $request = $this->db->prepare($sql);

        // Exécuter la requête avec les valeurs fournies
        try {
            $request->execute(array_values($data));
            return $this->getPDO()->lastInsertId(); // Retourne l'ID de la nouvelle insertion
        } catch (\Exception $e) {
            throw new \Exception("Insertion échouée : " . $e->getMessage());
        }
    }



    /* 

    public function associationByID($id)
    {
        if (!is_numeric($id)) {
            // Si l'ID n'est pas un nombre, affiche une erreur ou redirige vers la page de création
                      
            return Renderer::make($this->table, [
                'tableau' => [], // Nouvelle association
                'activite' => []
            ]);
        }

        //$requete = new Association($this->getPDO());
        //$requete = new $this->table($this->getPDO());
        $classe = "App\\" . $this->table;
        $requete = new $classe($this->getPDO());
        $tableau = $requete->findById($id);
        $message = ' Fiche '.$classe;

        // Récupérer les activités liées à cette association
        $activite = $requete->getActivites($id);
        $all = $requete->getActivitesAll();


        // Pass the fetched result to the view
        return Renderer::make($this->table . 'ById', compact('tableau', 'activite', 'message', 'all'));
    } */


    /*     public function association(): Renderer
    {
        $association = new Association();
        // Récupérer toutes les associations
        $tableau = $association->all();
        // Vérifier que $tableau n'est pas vide
        if (empty($tableau)) {
            die("Aucune donnée trouvée!");
        }
        // Vérifier que $tableau est bien un tableau
        if (!is_array($tableau)) {
            die("Les données ne sont pas disponibles ou sont incorrectes.");
        }
        // Envoyer les données à la vue
        return Renderer::make('association', compact('tableau'));
    } */
    /* 
    public function activite(): Renderer
    {
        $activite = new Activite();

        // Récupérer toutes les associations
        $tableau = $activite->all();

        // Vérifier que $tableau n'est pas vide
        if (empty($tableau)) {
            die("Aucune donnée trouvée!");
        }
        // Vérifier que $tableau est bien un tableau
        if (!is_array($tableau)) {
            die("Les données ne sont pas disponibles ou sont incorrectes.");
        }
        // Envoyer les données à la vue
        return Renderer::make($this->table , compact('tableau'));
    } */
}
