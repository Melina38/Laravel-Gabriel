<?php

// Variables

$message="Annulation de la vente #".$id;

// Traitements

if ($id>0)
{	$impact = $connexion -> query("DELETE FROM r508_ventes WHERE id='".$id."'");
}

?>