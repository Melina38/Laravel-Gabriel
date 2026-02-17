<?php

// Connexion

require_once("../connexion.php");

// Fonctions PHP

require_once("../fonctions.php");

// Variables externes

$action=$_GET["action"];

// Variables

$message="Outils de base de données";

$tables=array("utilisateurs","ventes");
$donnees=array("prenoms","noms","produits");

// Acquisition des données

foreach($donnees as $donnee)
{	${$donnee}=file($donnee.".txt");
}

// Traitements

if ($action=="utilisateurs")
{	if (!tablexiste($connexion,"r508_utilisateurs"))
	{	$impact = $connexion -> query("CREATE TABLE r508_utilisateurs (id MEDIUMINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, identifiant VARCHAR(40), passe VARCHAR(40))");
	}
	$impact = $connexion -> query("TRUNCATE TABLE r508_utilisateurs");
	$impact = $connexion -> query("INSERT INTO r508_utilisateurs (identifiant,passe) VALUES ('davidrochaix','davidrochaix')");
	for ($n=1; $n<rand(5,10); $n++)
	{	$impact = $connexion -> query("INSERT INTO r508_utilisateurs (identifiant,passe) VALUES ('".alpha(minuscules(sansaccents(sansguillemets($prenoms[array_rand($prenoms,1)].$noms[array_rand($noms,1)]))))."','".motdepasse()."')");
	}
	$message="Liste des utilisateurs créée en base de données";
}

if ($action=="ventes")
{	if (!tablexiste($connexion,"r508_ventes"))
	{	$impact = $connexion -> query("CREATE TABLE r508_ventes (id MEDIUMINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, nom VARCHAR(40), prenom VARCHAR(40), article VARCHAR(200), prix FLOAT(8,2), dateheure DATETIME, statut TINYINT UNSIGNED)");
	}
	$impact = $connexion -> query("TRUNCATE TABLE r508_ventes");
	$dateheure=time();
	for ($n=1; $n<rand(100,200); $n++)
	{	$nom=sansguillemets($noms[array_rand($noms,1)]);
		$prenom=sansguillemets($prenoms[array_rand($prenoms,1)]);
		$statut=(rand(1,10)>9?rand(2,4):1);
		$dateheure+=(date("H",$dateheure)>"18"?50400:rand(30,120));
		for ($a=1; $a<rand(5,20); $a++)
		{	$impact = $connexion -> query("INSERT INTO r508_ventes (nom,prenom,article,prix,dateheure,statut) VALUES ('".$nom."','".$prenom."','".sansguillemets(trim($produits[array_rand($produits,1)]))."','".(rand(10,160)/20)."','".date("Y-m-d H:i:s",$dateheure)."','".$statut."')");
			$dateheure+=rand(3,8);
		}
	}
	$message="Liste des ventes créée en base de données";
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>R 508</title>
<link href="../style.css" type="text/css" rel="stylesheet">
</head>

<body>
<a id="sommet"></a>

<header>
<span class="entete">
<a href="#sommet">MMI.3</a>
R 508 | Cybersécurité
</span>
<img alt="MMI Chambéry" src="data:image/png;base64,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">
</header>

<?php

	// Affichage des boutons de choix du cursus

	echo("<section>\n");
	echo("<h1>".$message."</h1>\n");
	echo("</section>\n");

	echo("<section>\n");
	foreach($tables as $table)
	{	echo("<a href='?action=".$table."' class='champ'>".(tablexiste($connexion,"r508_".$table)?"Réinitialiser les ".$table:"Créer la table des ".$table)."</a>\n");
	}
	echo("</section>\n");
?>
</body>
</html>