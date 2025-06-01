<?php
// Connexion à la base de données
$host = 'localhost';
$db   = 'Portefolio';
$user = 'root'; // à adapter selon votre configuration
$pass = '';     // à adapter selon votre configuration
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}

// Récupération des données du formulaire
$email = $_POST['email'] ?? '';
$objet = $_POST['objet'] ?? '';
$message = $_POST['message'] ?? '';

// Insertion dans la base de données
$sql = "INSERT INTO contacts (email, objet, message) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$email, $objet, $message]);

echo "Message envoyé avec succès !";
?>
