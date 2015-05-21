<?php // content="text/plain; charset=utf-8"
require_once ('jpgraph/src/jpgraph.php');
require_once ('jpgraph/src/jpgraph_line.php');
include ("../betav3.php");

#modif
$parsed_json = json_decode(my_get_json("rate_limit?access_token=a6f162fe9dd5745cfaa1e387321b3ce59ede3a27"),true);
	if ($parsed_json['rate']['remaining'] != 0)
	{
		$repos_orgarnization = my_get_repo("melonJS");
		//test

		$weeks = get_participation($repos_orgarnization);
	}
	else
	{
		echo "Une erreur est apparue (limite ou autres)<br/>";
	}

	$datay1 = array(0 => 0);
 	for ($i=0; $i <  count($weeks['owner']); $i++) { 
 	$datay1[] = $weeks['owner'][$i];
 }

 $datay2 = array(0 => 0);
 	for ($i=0; $i <  count($weeks['all']); $i++) { 
 	$datay2[] = $weeks['all'][$i];
 }
 





// $datay1 = array(20,7,16,46);
// $datay2 = array(6,20,10,22);

// Setup the graph
$graph = new Graph(1000,550);
$graph->SetScale("textlin");

$theme_class= new UniversalTheme;
$graph->SetTheme($theme_class);

$graph->title->Set('Les commits du propriétaire et des contributeurs sur un an');
$graph->SetBox(false);

$graph->yaxis->HideZeroLabel();
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);

// $graph->xaxis->SetTickLabels(array('','','',''));
$graph->ygrid->SetFill(false);
// $graph->SetBackgroundImage("logogit.png",BGIMG_FILLFRAME);

$p1 = new LinePlot($datay1);
$graph->Add($p1);

$p2 = new LinePlot($datay2);
$graph->Add($p2);

$p1->SetColor("#0080FF");
$p1->SetLegend('propriétaire');
$p1->mark->SetType(MARK_FILLEDCIRCLE,'',1.0);
$p1->mark->SetColor('#55bbdd');
$p1->mark->SetFillColor('#55bbdd');
$p1->SetCenter();

$p2->SetColor("#FE2E9A");
$p2->SetLegend('contributeurs');
$p2->mark->SetType(MARK_UTRIANGLE,'',1.0);
$p2->mark->SetColor('#aaaaaa');
$p2->mark->SetFillColor('#aaaaaa');
$p2->value->SetMargin(14);
$p2->SetCenter();

$graph->legend->SetFrameWeight(1);
$graph->legend->SetColor('#4E4E4E','#FF0000');
$graph->legend->SetMarkAbsSize(8);


// Output line
$graph->Stroke();

?>