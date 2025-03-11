<?php 
header('Content-Type: application/json');

// Informations de connexion à la base de données
$host = 'localhost';
$dbName = 'pulseTech'; // Nom de votre base de données
$user = 'root'; // Remplacez par votre nom d'utilisateur
$password = ''; // Mot de passe
$entity = $_GET['entity'] ?? 'formulaire';

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Récupération de la page demandée
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5; // Nombre d'éléments par page
$offset = ($page - 1) * $limit;
$tableName = strtolower($entity); // Utiliser le nom de l'entité en minuscules pour la table

$sql = "SELECT * FROM $tableName LIMIT :limit OFFSET :offset";

// Préparation et exécution de la requête SQL pour récupérer les données
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

// Récupération des résultats
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Appliquer getExtract sur la colonne 'url_site_association' si elle existe
foreach ($results as &$row) {
    if (isset($row['url_site_association']) && is_string($row['url_site_association'])) {
        $row['url_site_association'] = substr($row['url_site_association'], 0, 6) . '...';
    }
}
// Retourner les résultats en JSON
echo json_encode($results);
?>
