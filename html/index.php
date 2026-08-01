<?php
$host = 'db'; // Le nom du service dans docker-compose
$dbname = 'mon_site_db';
$username = 'root';
$password = 'rootpassword';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    echo "<h1>Bravo ! Connexion à la base de données MySQL réussie via Docker 🚀</h1>";
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>