<?php

// Variables

$message="Suppression de l'utilisateur #".$id;

// Traitements

if ($id>0)
{	$impact = $connexion -> query("DELETE FROM r508_utilisateurs WHERE id='".$id."'");
	foreach($resultats as $rang => $resultat)
	{	if ($resultat["id"]==$id)
		{	unset($resultats[$rang]);
		}
	}
}

?>