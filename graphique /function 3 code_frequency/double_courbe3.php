<?php // content="text/plain; charset=utf-8"
require_once ('jpgraph/src/jpgraph.php');
require_once ('jpgraph/src/jpgraph_line.php');
require_once ('jpgraph/src/jpgraph_scatter.php');
include ("../betav3.php");

#modif
$parsed_json = json_decode(my_get_json("rate_limit?access_token=a6f162fe9dd5745cfaa1e387321b3ce59ede3a27"),true);
	if ($parsed_json['rate']['remaining'] != 0)
	{
		$repos_orgarnization = my_get_repo("etnascc");
		//test
		//var_dump($repos_orgarnization[$i]['full_name']);
		//$weeks = get_nbr_contrib($repos_orgarnization);
		//$weeks = get_commit_activity($repos_orgarnization);
		$weeks = get_code_frequency($repos_orgarnization);
		//get_punch_card($repos_orgarnization);
		//get_participation($repos_orgarnization);
	}
	else
	{
		echo "Une erreur est apparue (limite ou autres)<br/>";
	}


// $datay1 = array(15,21,24,10,37,29,47);
// $datay2 = array(8,6,11,26,10,4,2);

	$datay1 = array(0 => 0);
 foreach ($weeks as $key => $value) {
 	$datay1[] = $value['d'];
 }


	$datay2 = array(0 => 0);
 foreach ($weeks as $key => $value) {
 	$datay2[] = $value['a'];
 }
// Setup the graph
$graph = new Graph(500,300);

$graph->SetScale("textlin");

//$theme_class=new DefaultTheme;
//$graph->SetTheme($theme_class);s

$graph->title->Set("Les Ajouts et Délétions");

$graph->SetBox(false);
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);
$graph->yaxis->HideZeroLabel();

// $graph->xaxis->SetTickLabels(array('A','B','C','D','E','F','G'));
$graph->xaxis->SetTickLabels(array('0','1'));

// Create the plot
$p1 = new LinePlot($datay1);
$graph->Add($p1);

$p2 = new LinePlot($datay2);
$graph->Add($p2);

// Use an image of favourite car as marker
$p1->mark->SetType(MARK_IMG,'rose.gif',1.0);
$p1->SetLegend('Délétions');
$p1->SetColor('#CD5C5C');

$p2->mark->SetType(MARK_IMG,'sunflower.gif',1.0);
$p2->SetLegend('Ajouts');
$p2->SetColor('green');

$graph->Stroke();

?>