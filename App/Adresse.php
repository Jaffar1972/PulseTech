<?php

namespace App;

use PDO;
use App\Model;

class Adresse extends Model {

 // Déclarez explicitement le type string pour la propriété $table
 protected string $table = 'Adresse';
 
  // Déclarez explicitement les propriétés






  public function getCreateAt() {
    if (!empty($this->Create_at)) {
        try {
            return 'This is the creation time: ' . (new \DateTime($this->Create_at))->format('d/m/Y H:i');
        } catch (\Exception $e) {
            return 'Invalid date format';
        }
    }
    return 'Date not available';
}



public function getExtract() {
    if (!empty($this->url_site_association) && is_string($this->url_site_association)) {
        return substr($this->url_site_association, 0, 6) . '...';
    }
    return 'No URL provided';
}



public function getActivites($id) {
  return $this->query("SELECT a.id, a.nom_activite
                       FROM activite a
                       JOIN association_activite aa ON a.id = aa.activite_id
                       WHERE aa.association_id = ?", [$id]);
}


public function getActivitesAll() {
  return $this->query("SELECT *
                       FROM activite") ;
}


public function updated(int $id, array $data, ?array $relation = null) {
    if (isset($data['id'])) {
        unset($data['id']);
    }

    if (empty($data)) {
        throw new \Exception("No data provided for update.");
    }

    $updateFields = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));

    $data['id'] = $id; 

    $sql = "UPDATE {$this->table} SET $updateFields WHERE id = :id";

    try {
        $this->query($sql, $data);

        if ($relation) {
            $this->query("DELETE FROM association_activite WHERE association_id = :id", ['id' => $id]);
            foreach ($relation as $relId) {
                $this->pdo->prepare("INSERT INTO association_activite (activite_id, association_id) VALUES (?, ?)")
                    ->execute([$relId, $id]);
            }
        }
        return true;
    } catch (\Exception $e) {
        throw new \Exception("Failed to update: " . $e->getMessage());
    }
}

public function Insertion(array $data, ?array $relation = null) {

    if (empty($data)) {
        throw new \Exception("No data provided for insertion.");
    }

    // Préparer les champs et les valeurs pour l'insertion
    $columns = implode(', ', array_keys($data));
    $placeholders = implode(', ', array_map(fn($key) => ":$key", array_keys($data)));

    $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";

    try {
        // Exécuter la requête d'insertion
        $this->query($sql, $data);

        // Récupérer l'ID de la dernière insertion
        $lastInsertId = $this->pdo->lastInsertId();

        if ($relation) {
            // Insérer les relations si fournies
            foreach ($relation as $relId) {
                $this->pdo->prepare("INSERT INTO association_activite (activite_id, association_id) VALUES (?, ?)")
                    ->execute([$relId, $lastInsertId]);
            }
        }
        return true;
    } catch (\Exception $e) {
        throw new \Exception("Failed to insert: " . $e->getMessage());
    }
}
   

}

