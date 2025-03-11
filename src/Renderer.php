<?php

namespace src;

class Renderer
{
    public function __construct(private string $viewPath, private ?array $params) {}



    public function View(): string
    {
        // Cela signifie que tout le contenu (HTML, texte, etc.) qui serait normalement envoyé directement au navigateur est mis en mémoire tampon. 
        // Il n'est pas immédiatement affiché. 

        ob_start();

        //La fonction extract() est utilisée pour convertir les clés du tableau $this->params en variables PHP.
        extract($this->params);

        //Cela crée une variable $tableau contenant la valeur de $valeur, 
        //que tu pourras ensuite utiliser directement dans la vue (au lieu de devoir toujours accéder à $this->params['tableau']).

        // Inclusion du layout (template principal)
        require Chemin . 'menu.php';

        
     // Recherche du fichier de vue avec n'importe quelle extension
     $viewFiles = glob(BASE_VIEW_PATH . $this->viewPath . '.*');

     if (empty($viewFiles)) {
         throw new \Exception("Aucun fichier de vue trouvé pour : " . BASE_VIEW_PATH . $this->viewPath);
     }
 
     // Inclusion du premier fichier trouvé (vous pouvez ajouter une logique supplémentaire si nécessaire)
     require $viewFiles[0];
 
     // Retourne le contenu généré
     return ob_get_clean();
    }
    // chemin du rendu avec tableau :

    public static function make(string $path, ?array $params = []): static

    {
        
        return new static($path, $params);
    }


    public function __toString()
    {
        return $this->View();
    }

// PHP va automatiquement appeler la méthode __toString().

// Plutôt que d'afficher l'objet $renderer directement (ce qui produirait normalement une erreur car un objet n'est pas une chaîne de caractères), 
// PHP exécute la méthode View(), qui renvoie le contenu généré de la vue sous forme de chaîne. 
// Ensuite, cette chaîne est affichée.

// Cela signifie que quand tu utilises echo $renderer; tu verras le contenu HTML généré par la méthode View().

}
