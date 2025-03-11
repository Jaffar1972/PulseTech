<?php

namespace App;

use App\Model;

class Formulaire extends Model {

protected string $table = 'formulaire';
  // Déclarez explicitement les propriétés



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
  if ($this->message !== null && is_string($this->message)) {
      return substr($this->message, 0, 3) . '...';
  } else {
      // Retourne une chaîne vide ou un texte par défaut si num_siret est null
      return 'Please record something';
  }
}

public function getAssociations() {
  return $this->query(
      "SELECT ass.* 
       FROM association ass 
       JOIN association_activite aa ON ass.id = aa.association_id 
       WHERE aa.activite_id = ?", 
      [$this->id]
  );
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

}