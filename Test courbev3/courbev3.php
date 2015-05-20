<?php
include ("jpgraph/src/jpgraph.php");
include ("jpgraph/src/jpgraph_line.php");
include ("../betav3.php");
 // $ydata = array(8,3,16,2,7,25,16);

#Modif
$parsed_json = json_decode(my_get_json("rate_limit?access_token=a6f162fe9dd5745cfaa1e387321b3ce59ede3a27"),true);
	if ($parsed_json['rate']['remaining'] != 0)
	{
		$repos_orgarnization = my_get_repo("etnascc");
		//test
		//var_dump($repos_orgarnization[$i]['full_name']);
		$weeks = get_nbr_contrib($repos_orgarnization);
		//get_commit_activity($repos_orgarnization);
		//get_code_frequency($repos_orgarnization);
		//get_punch_card($repos_orgarnization);
		//get_participation($repos_orgarnization);
	}
	else
	{
		echo "Une erreur est apparue (limite ou autres)<br/>";
	}
#modif
 $ydata = array(0 => 0);
 foreach ($weeks as $key => $value) {
 	$ydata[] = $value['c'];
 }
 // var_dump($ydata);
 // echo($ydata[0]);
 // echo '<br/>';
 // var_dump($weeks);
 // $ydata = array($weeks[$date]['c'],$weeks[$date]['d'],$weeks[$date]['a']   );
// print_r($weeks);
// Creation du graphique
$graph = new Graph(500,500);
$graph->SetScale("textlin");

// Création du système de points
$lineplot=new LinePlot($ydata);

// On rajoute les points au graphique
$graph->Add($lineplot);

// Affichage
$graph->Stroke();
?>