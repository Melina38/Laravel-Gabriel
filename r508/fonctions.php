<?php

// Fonctions

function tablexiste($connexion, $table)
{	try
	{	$resultat = $connexion -> query("SELECT 1 FROM {$table} LIMIT 1");
    }
	catch (Exception $e)
	{	return false;
	}
    return $resultat !== false;
}

function majuscules($texte)
{	  return strtr(strtoupper($texte), array("à" => "À","è" => "È","ì" => "Ì","ò" => "Ò","ù" => "Ù","á" => "Á","é" => "É","í" => "Í","ó" => "Ó","ú" => "Ú","â" => "Â","ê" => "Ê","î" => "Î","ô" => "Ô","û" => "Û","ç" => "Ç"));
}

function minuscules($texte)
{	  return strtr(strtolower($texte), array_flip(array("à" => "À","è" => "È","ì" => "Ì","ò" => "Ò","ù" => "Ù","á" => "Á","é" => "É","í" => "Í","ó" => "Ó","ú" => "Ú","â" => "Â","ê" => "Ê","î" => "Î","ô" => "Ô","û" => "Û","ç" => "Ç")));
}

function sansaccents($texte)
{	$texte = htmlentities($texte, ENT_NOQUOTES,"utf-8");
	$texte = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $texte);
    $texte = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $texte);
    return preg_replace('#&[^;]+;#', '', $texte);
}

function sansguillemets($texte)
{	return str_replace('"','\"',str_replace("'","\'",$texte));
}

function alphanumerique($texte)
{	return preg_replace("#[^[:alnum:]-]#","",$texte);
}

function alpha($texte)
{	return preg_replace("#[^A-Za-z]#","",$texte);
}

function numerique($texte)
{	return preg_replace("#[^0-9]#","",$texte);
}

function motdepasse() {
    $alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $motdepasse = array();
    for ($n=1; $n<rand(5,10); $n++)
	{	$motdepasse[] = $alphabet[rand(0,strlen($alphabet)-1)];
    }
    return implode($motdepasse);
}

function date_sql_date($datetheure)
{	$elements=explode("-",substr($datetheure,0,10));
	return($elements[2]."/".$elements[1]."/".$elements[0]);
}

function date_fr_date($datetheure)
{	$elements=explode("/",substr($datetheure,0,10));
	return($elements[2]."-".$elements[1]."-".$elements[0]);
}

function date_sql_date_et_heure($datetheure)
{	$elements=explode("-",substr($datetheure,0,10));
	return($elements[2]."/".$elements[1]."/".$elements[0]." à ".substr($datetheure,11));
}

function date_fr_date_et_heure($datetheure)
{	$elements=explode("/",substr($datetheure,0,10));
	return($elements[2]."-".$elements[1]."-".$elements[0].substr($datetheure,10));
}

//contre XXS
// helper pour échapper les sorties HTML
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
function normaliserAccent($str) {
    // Convertit les caractères accentués en non accentués
    $translit = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
    // Supprime les espaces parasites, met en minuscule
    $str = strtolower(trim($translit));
    // Ne garde que les lettres
    return preg_replace('/[^a-z]/', '', $str);
}

?>