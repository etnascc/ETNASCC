<?php
require_once("include_path_inc.php");

require_once("jpgraph/src/jpgraph.php");
require_once("jpgraph/src/jpgraph_line.php");
require_once("jpgraph/src/jpgraph_bar.php");
include ("../betav3.php");

// $donnees = array(12,23,9,58,23,26,57,48,12);
#modif
$parsed_json = json_decode(my_get_json("rate_limit?access_token=a6f162fe9dd5745cfaa1e387321b3ce59ede3a27"),true);
	if ($parsed_json['rate']['remaining'] != 0)
	{
		$repos_orgarnization = my_get_repo("melonJS");
		//test
		//var_dump($repos_orgarnization[$i]['full_name']);
		//get_nbr_contrib($repos_orgarnization);
		//get_commit_activity($repos_orgarnization);
		$weeks = get_code_frequency($repos_orgarnization);
		//get_punch_card($repos_orgarnization);
		//get_participation($repos_orgarnization);
	}
	else
	{
		echo "Une erreur est apparue (limite ou autres)<br/>";
	}

	$donnees = array(0 => 0);
 foreach ($weeks as $key => $value) {
 	$donnees[] = $value['a'];
 }
$largeur = 5000;
$hauteur = 1000;

// Initialisation du graphique
$graphe = new Graph($largeur, $hauteur);
// Echelle lineaire ('lin') en ordonnee et pas de valeur en abscisse ('text')
// Valeurs min et max seront determinees automatiquement
$graphe->setScale("textlin");
$graphe->xaxis->SetTickLabels(array('1'));

// Creation de l'histogramme
$histo = new BarPlot($donnees);
// Ajout de l'histogramme au graphique
$graphe->add($histo);

// Creation de la courbe
//$courbe = new LinePlot($donnees);
// Ajout de la courbe au graphique
//$graphe->add($courbe);

// Ajout du titre du graphique
$graphe->title->set("Les Ajouts : ");

// Affichage du graphique
$graphe->stroke();
?>