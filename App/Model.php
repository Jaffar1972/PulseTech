<?php

namespace App;

use PDO;
use PDOException;
use src\Constant;

class Model
{

    protected $db;
    protected ?\PDO $pdo = null;
    protected string $table = '';
    protected array $attributes = [];

    public function __construct()
    {
        $this->pdo = $this->getPDO();
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }
        throw new \Exception("La propriété $name n'existe pas.");
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function getPDO(): PDO
    {
        if ($this->pdo === null) {
            try {
                $dsn = "mysql:dbname=" . Constant::DB_NAME . ";host=" . Constant::DB_HOST . ";charset=utf8";
    
                $this->pdo = new PDO($dsn, Constant::DB_USERNAME, Constant::DB_PASSWORD, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                ]);
            } catch (PDOException $e) {
                throw new \Exception("Erreur de connexion à la base de données : " . $e->getMessage());
            }
        }
        return $this->pdo;
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->query($sql, [$id], true);
    }

    public function query($sql, $params = [], $single = false)
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        // Configuration du mode de récupération en utilisant FETCH_CLASS
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_class($this));

        if ($single) {
            return $stmt->fetch();
        } else {
            return $stmt->fetchAll();
        }
    }

    public function searchInAllColumns($search)
    {
        // Récupérer toutes les colonnes de la table
        $columns = $this->getTableColumns($this->getPDO());
    
        // Construire la requête dynamique pour rechercher dans toutes les colonnes
        $conditions = [];
        foreach ($columns as $column) {
            $conditions[] = "$column LIKE :search";
        }
    
        // Générer la requête SQL avec les conditions pour chaque colonne
        $sql = "SELECT * FROM {$this->table} WHERE " . implode(' OR ', $conditions);
    
        // Préparer la requête
        $stmt = $this->getPDO()->prepare($sql);
    
        // Exécuter la requête avec le paramètre de recherche
        $stmt->execute([':search' => "%$search%"]);
    
        // Retourner les résultats trouvés sous forme d'objets de la classe actuelle
        return $stmt->fetchAll(PDO::FETCH_CLASS, get_class($this));
    }
    
    public function all(): array
    {
        if (empty($this->table)) {
            throw new \Exception("La propriété \$table n'est pas définie dans " . get_class($this));
        }

        // Récupérer les colonnes de la table
        $columns = $this->getTableColumns($this->getPDO());

        // Récupérer toutes les données de la table
        $statement = $this->getPDO()->query("SELECT * FROM {$this->table}");

        // Utilisation de FETCH_CLASS pour retourner les objets de la classe actuelle
        $statement->setFetchMode(PDO::FETCH_CLASS, get_class($this));

        $data = $statement->fetchAll() ?: [];

        // Retourner un tableau contenant les colonnes et les données
        return [
            'columns' => $columns,
            'data' => $data,
        ];
    }


    public function delete(int $id)
    {

        $request = $this->getPDO()->prepare("DELETE FROM {$this->table} WHERE id = ?");
        // return $this->query("DELETE * FROM {$this->table} WHERE id = ?",$id,true);
        // // Execute the prepared statement
        $request->execute([$id]);

        // // Fetch the result
        // return $request->fetch();
    }



    public function getTableColumns()
    {
        $pdo = $this->getPDO(); // Assurez-vous que getPDO() est appelé ici
        $sql = "DESCRIBE " . $this->table; // Assurez-vous que $this->table contient un nom de table valide
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN); // Récupère les noms des colonnes
    }



    

    public function getByUsername(string $email)
    {

        //true pour dire que l'on ne veut qu'un seul enregistrement.

        return $this->query("select * from `user` WHERE email=?", [$email], true);
    }


    public function getExtract() {
        
        if (isset($this->attributes['url_site_association']) && is_string($this->attributes['url_site_association'])) {
            return substr($this->attributes['url_site_association'], 0, 6) . '...';
        }
        return 'No URL provided';
    }
    



    
}
