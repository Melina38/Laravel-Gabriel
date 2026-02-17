<?php
// migrate_hash_passwords.php
// À lancer une seule fois. Faire un backup DB AVANT.

// configuration de connexion - adapte si nécessaire
require_once 'connexion.php'; // $connexion = new PDO(...)

// regex qui détecte les hash courants (bcrypt/argon)
function sembleDejaHashe($p) {
    if (!is_string($p) || strlen($p) === 0) return false;
    return preg_match('/^\$(2y|2b|2a)\$[0-9]{2}\$.{53}$/', $p) // bcrypt
        || preg_match('/^\$argon2/', $p); // argon2...
}

try {
    // Récupère tous les utilisateurs
    $stmt = $connexion->query("SELECT id, identifiant, passe FROM r508_utilisateurs");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $updateStmt = $connexion->prepare("UPDATE r508_utilisateurs SET passe = :passe WHERE id = :id");

    $countUpdated = 0;
    foreach ($users as $u) {
        $id = $u['id'];
        $passe = $u['passe'];

        if (sembleDejaHashe($passe)) {
            // déjà hashé, on skip
            continue;
        }

        // s'il est vide ou null, on skip (ou tu peux forcer un hash d'une valeur temporaire)
        if ($passe === null || trim($passe) === '') {
            continue;
        }

        // Générer le hash
        $hash = password_hash($passe, PASSWORD_DEFAULT);

        // Mettre à jour en BDD
        $updateStmt->execute([
            ':passe' => $hash,
            ':id' => $id
        ]);
        $countUpdated++;
    }

    echo "Migration terminée. Nombre de mots de passe mis à jour : {$countUpdated}\n";

} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
    exit(1);
}
