<?php

namespace App;

use PDO;
use App\Model;

class Messenger_messages extends Model {

 // Déclarez explicitement le type string pour la propriété $table
 protected string $table = 'messenger_messages';
 
 					
  // Déclarez explicitement les propriétés
  public $id;
  public $body;
  public $headers;
  public $queue_name;
  public $created_at;
  public $available_at;
  public $delivered_at;





public function getCreateAt() {
  // // Crée un objet DateTime à partir de la chaîne de caractères
  // $date = new \DateTime($this->Create_at);

  // // Formate la date en une chaîne lisible
  // $formattedDate = $date->format('d/m/Y H:i');

  // // Retourne la chaîne formatée
  // return 'This is the creation Time about this product: ' . $formattedDate;

  return 'This is the creation Time about this product: ' .(new \DateTime($this->created_at))->format('d/m/Y H:i');
}



public function getExtract() {
  // Vérifie si $this->num_siret n'est pas null et est une chaîne de caractères
  if ($this->available_at !== null && is_string($this->available_at)) {
      return substr($this->available_at, 0, 6) . '...';
  } else {
      // Retourne une chaîne vide ou un texte par défaut si num_siret est null
      return 'Please record something';
  }
}

public function getActivites($id) {
  return $this->query("SELECT a.id, a.nom_activite
                       FROM activite a
                       JOIN association_activite aa ON a.id = aa.activite_id
                       WHERE aa.association_id = ?", [$id]);
}


public function getMessengerMessagesAll() {
  return $this->query("SELECT *
                       FROM messenger_messages") ;
}


public function associationUpdated(int $id, array $data, ?array $relation = null)
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

   
   

}

