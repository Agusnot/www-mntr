<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include ("jpgraph/inc/jpgraph.php");
	include ("jpgraph/inc/jpgraph_line.php");

$graph = new Graph(800, 350, "auto");    
$graph->SetScale( "textlin");

$graph ->yaxis->SetTitleMargin( 30); //El margen del titulo de la izquierda (Arriba)

$graph->img->SetMargin(50, 20, 20, 60);
$graph->title->Set("$TitleGraph");


$graph->yaxis->title->Set("Millones de Pesos" );

$graph->xaxis-> SetTickLabels($TitEjeX); 

$lineplot1 =new LinePlot($DatosY);

$lineplot1 ->SetColor("blue");

$lineplot1->mark ->SetType(MARK_DIAMOND);
$lineplot1->mark->SetWidth(4); 
$lineplot1->mark->SetFillColor('green'); 

//$lineplot1->mark->SetWeight(2); 

$lineplot1->value ->Show();
$graph ->SetShadow();
$graph ->xgrid->Show( true,true);
$graph ->ygrid->Show( true,true);
$graph->Add( $lineplot1);
//$lineplot1->SetStepStyle(); 
//$lineplot1->SetBarCenter(); 
//$lineplot1->AddArea(4,5,LP_AREA_FILLED,"indianred1"); //Marca rangos especiales
$graph ->footer->left-> Set("(C) 2002 KXY"); //Pie de Pagina a la izquierda
$graph->footer-> center->Set("$Compania[0]" );  //Pie de Pagina al Centro
$graph->footer-> center-> SetColor("red");  //Pie de Pagina al Centro (COlor rojo)
$graph->footer-> center-> SetFont( FF_FONT2, FS_BOLD);  //Pie de Pagina al Centro (Negrita)
$graph->footer-> right->Set("19 Aug 2002" ); //Pie de Pagina la derecha



$graph->Stroke();
?> 
?>
</table>