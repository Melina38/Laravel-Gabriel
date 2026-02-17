<?php
try {
    $connexion = new PDO(
        "mysql:host=192.168.135.113;dbname=bascolm;charset=utf8",
        "user",
        "rQUSxP2xUCxnzU45",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
