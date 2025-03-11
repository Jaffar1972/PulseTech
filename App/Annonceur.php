<?php

namespace App;

use App\Model;

class Annonceur extends Model {

protected string $table = 'annonceur';
  // Déclarez explicitement les propriétés
  public $id;
  public $nom_annonceur;
  public $compte_id;
  public $president_annonceur;
  public $suppleant_annonceur;
  public $numero_siret;
  public $numero_iban;
  public $url_site;
  public $Create_at;

  

public function getCreateAt() {
  // // Crée un objet DateTime à partir de la chaîne de caractères
  // $date = new \DateTime($this->Create_at);

  // // Formate la date en une chaîne lisible
  // $formattedDate = $date->format('d/m/Y H:i');

  // // Retourne la chaîne formatée
  // return 'This is the creation Time about this product: ' . $formattedDate;

  return 'This is the creation Time about this product: ' .(new \DateTime($this->Create_at))->format('d/m/Y H:i');
}



public function getExtract() {
  // Vérifie si $this->num_siret n'est pas null et est une chaîne de caractères
  if ($this->compte_id !== null && is_string($this->compte_id)) {
      return substr($this->compte_id, 0, 3) . '...';
  } else {
      // Retourne une chaîne vide ou un texte par défaut si num_siret est null
      return 'Please record something';
  }
}

public function getInfosCompte() {
  return $this->query(
      "SELECT c.* 
       FROM compte c 
       JOIN annonceur a ON c.annonceur_id = a.id 
       WHERE c.annonceur_id = ?", 
      [$this->id]
  );
}





public function updated(int $id, array $data, ?array $relation = null)
{
   
    // Retirer l'ID des données à mettre à jour
    if (isset($data['id'])) {
        unset($data['id']);
    }

    // Si aucune donnée à mettre à jour, retournez une erreur
    if (empty($data)) {
        throw new \Exception("Aucune donnée à mettre à jour.");
    }

    // Générer la partie de la requête pour les champs et les valeurs
    $updateFields = "";
    $i = 1;

    foreach ($data as $key => $value) {
        $comma = $i === count($data) ? "" : ", ";
        $updateFields .= "{$key} = :{$key}{$comma}";
        $i++;
    }

    // Mettre à jour les données dans la base de données
    $data['id'] = $id; // Ajoute l'ID pour l'utiliser dans la clause WHERE

    // Assurer que la table est définie
    if (!isset($this->table)) {
        throw new \Exception("La table à mettre à jour n'est pas définie.");
    }

    // Requête de mise à jour
    $sql = "UPDATE {$this->table} SET $updateFields WHERE id = :id";

    // Exécuter la requête
    $this->query($sql, $data);

    // Mettre à jour les relations (activités) si fournies
    if ($relation) {
        // Supprimer les anciennes relations
        $this->query("DELETE FROM association_activite WHERE association_id = :id", ['id' => $id]);

        // Insérer les nouvelles relations
        foreach ($relation as $relId) {
            // Utiliser le PDO existant, pas besoin de réinstancier l'objet PDO
            $sqlInsert = "INSERT INTO association_activite (activite_id, association_id) VALUES (?, ?)";
            $this->pdo->prepare($sqlInsert)->execute([$relId, $id]);
        }
    }

    return true;
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

