<?php
// index.php - version sécurisée

// Sécurisation de la session
$cookieParams = session_get_cookie_params();
session_set_cookie_params([
    'lifetime' => $cookieParams['lifetime'],
    'path' => $cookieParams['path'],
    'domain' => $cookieParams['domain'],
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'httponly' => true,
    'samesite' => 'Lax'
]);
// Crée un dossier "sessions" dans ton dossier r508 si ce n'est pas déjà fait
session_save_path(__DIR__ . '/sessions');

// Assure-toi que le dossier est accessible en écriture
if (!is_dir(__DIR__ . '/sessions')) {
    mkdir(__DIR__ . '/sessions', 0777, true);
}
session_start();

// CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Connexion
require_once("connexion.php");
require_once("fonctions.php");


// Variables POST sécurisées
$nom = $_POST['nom'] ?? '';
$prenom = $_POST['prenom'] ?? '';

// Variables et constantes
$message = "Suivi de mes achats";

$statuts = [
    "1" => "Livré",
    "2" => "En attente",
    "3" => "Repris",
    "4" => "Annulé"
];

// Traitement formulaire
$resultats = [];
if (!empty($nom) && !empty($prenom)) {
    $sql = "SELECT * FROM r508_ventes WHERE nom LIKE :nom AND prenom LIKE :prenom";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([
        ':nom' => $nom ,
        ':prenom' => $prenom
    ]);
    $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $message = !empty($resultats) ? "Mes " . count($resultats) . " achats" : "Aucun achat n'a été trouvé à partir de votre nom";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>R 508</title>
<link href="style.css" type="text/css" rel="stylesheet">
</head>

<body>
<a id="sommet"></a>

<header>
<span class="entete">
<a href="#sommet">MMI.3</a>
R 508 | Cybersécurité
<div>
    Espace pro
    <form action="pro.php" method="post">
    <input type="text" name="identifiant" required placeholder="Identifiant"><br>
    <input type="password" name="passe" required placeholder="Mot de passe"><br>
    <input type="submit" value="Connexion">
    </form>
</div>
</span>
<img alt="MMI Chambéry" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAXwAAADICAMAAADcFaovAAAC/VBMVEX...">
</header>

<h1><?php echo h($message); ?></h1>

<section>
<form method="post">
<input type="text" name="nom" class="champ" placeholder="Nom" value="<?php echo h($nom); ?>">
<input type="text" name="prenom" class="champ" placeholder="Prénom" value="<?php echo h($prenom); ?>">
<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
<input type="submit" class="champ selectionne" value="Recherche">
</form>
</section>

<?php if (!empty($resultats)): ?>
<table>
<thead>
<tr>
    <td width="20%">Date et heure</td>
    <td width="60%">Article</td>
    <td width="10%">Prix</td>
    <td width="10%">État</td>
</tr>
</thead>
<tbody>
<?php foreach ($resultats as $resultat): ?>
<tr>
    <td>Le <?php echo h(date_sql_date_et_heure($resultat["dateheure"])); ?></td>
    <td><?php echo h($resultat["article"]); ?></td>
    <td><?php echo number_format($resultat["prix"], 2, ",", "") ?>&nbsp;&euro;</td>
    <td><?php echo h($statuts[$resultat["statut"]] ?? "Inconnu"); ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php endif; ?>

</body>
</html>
