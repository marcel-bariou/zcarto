<?php

	/**
	 * 	constantes de travail
	 *	Accélération gravité
	 *	Masse linéique cable 6, 8, 9, 11 mm
	 *	Masse linéique filet
	 *	Masse linéique plaquette 
	 *	$To Tension (Composante horizontale qui est constante pouunr flèche donnée) 
	 */
	//Exemple 1
	/*
	$_POST['typeCable']= "11";
	$_POST['distancePoteau']="40";
	$_POST['hauteurPoteau']="5";
	$_POST['fleche']="0.5";
	$_POST['echantillon']="40";
	*/
	
	//Exemple 2
	/*
	$_POST['typeCable']= "8";
	$_POST['distancePoteau']="13";
	$_POST['hauteurPoteau']="5";
	$_POST['fleche']="0.33";
	$_POST['echantillon']="100";
	*/
	
	//Exemple 3
	/*
	$_POST['typeCable']= "8";
	$_POST['distancePoteau']="200";
	$_POST['hauteurPoteau']="5";
	$_POST['fleche']="2.0";
	$_POST['echantillon']="100";
	*/
	//Exemple 4
	
	$_POST['typeCable']= "8";
	$_POST['distancePoteau']="200";
	$_POST['hauteurPoteau']="5";
	$_POST['fleche']="2";
	$_POST['echantillon']="100";
	
	
	$typeCable= $_POST['typeCable'];
	$distancePoteau= (float)$_POST['distancePoteau'];
	$fleche = (float)$_POST['fleche'];
	$echantillon = (float)$_POST['echantillon'];
	$hauteurPoteau=  (float)$_POST['hauteurPoteau'];
	$g = 9.81;
	$µ = array("6" => 0.174, "8" => 0.318, "9" => 0.3976, "11" => 0.75);
	$mfilet = 0.117;
	$mplaquette = 0.03;
	$µTot = array("6" => 0.294, "8" => 0.438, "9" => 0.5176, "11" => 0.75);
	
	/**
	 *	Calcul tension $To horizontale pour cable entre A et B
	 *	Situation statique au 1/4 de la longueur de B
	 *	sigma F = 0
	 *	Sigma Moment/B= 0
	 */
	
	 function horizTension ($linearDensity, $distancePoteau, $fleche ) {
	 	 $valInit=0;
	 	 $g = 9.81;
	 	 $longApprox=$distancePoteau/2.00;
	 	 $To= ($linearDensity * $g *$longApprox)*($distancePoteau/4.00)/$fleche;
	 	 $argL =($linearDensity*$g/$To)*$distancePoteau/2.0;
	 	 $longcalc=($To/($linearDensity*$g))*sinh($argL);	
	 	 $valtest= $longcalc-$longApprox;
	 	 //print $longcalc." => ".$valtest."<BR/>";
	 	 //Calcul de la longueur à -1.4219946606886E-6
	 	 $ii=0;
	 	 while($ii<100 && ($valtest <= -0.0000001 || $valtest >= 0.0000001)){
	 	 	 //		print $longcalc." => ".$valtest."<BR/>";
	 	 	 $longApprox=$longcalc;
	 	 	 $To= ($linearDensity * $g *$longApprox)*($distancePoteau/4.00)/$fleche;
	 	 	 $argL =($linearDensity*$g/$To)*$distancePoteau/2.0;
	 	 	 $longcalc=($To/($linearDensity*$g))*sinh($argL);
	 	 	 $valtest= $longcalc-$longApprox;
	 	 	 $ii++;
	 	 }
	 	 
	 	 return $To;
	 }
	 
	 $flch =0.10;
	 while ($flch < 5.00){
	 	 //print ." Flèche => ".$flch."<BR/>"; 
	 	 $yTo[]=horizTension ($µTot[$typeCable], $distancePoteau, $flch );
	 	 $xTo[] = $flch;
	 	 $flch +=0.10;
	 }
	 
	 $flch =1.0;
	 while ($flch < 5.00){
	 	 //print ." Flèche => ".$flch."<BR/>"; 
	 	 $y1To[]=horizTension ($µTot[$typeCable], $distancePoteau, $flch );
	 	 $x1To[] = $flch;
	 	 $flch +=0.10;
	 }
	 
	 $To= horizTension ($µTot[$typeCable], $distancePoteau, 2.0 );
	 $argL =($µTot[$typeCable]*$g/$To)*$distancePoteau/2.0;
	 $longcalc=($To/($µTot[$typeCable]*$g))*sinh($argL);
	/**
	 *	Evaluation Tension horizontale To suite à mise en place de support intermédiaire
	 *	Support dichotomique (Division par deux)
	 *	Hypothèse globale retenue longueur totale constante
	 *	Le solveur recherche la solution To par convergence sur le calcul de la longueur
	 *	Pour chaque nouvelle valeur To on évalue la longueur correspondante 
	 *
	 */
	
	$Tnew =0.6*$To;
	$argL =($µTot[$typeCable]*$g/$Tnew)*$distancePoteau/4.0;
	$longcalc2=2*($Tnew/($µTot[$typeCable]*$g))*sinh($argL);
	$valtest = $longcalc - $longcalc2;
	$k=0;
	while($k<1000 && ($valtest <= -0.00001 || $valtest >= 0.00001)){
		$Tnew *=0.999;
		$argL =($µTot[$typeCable]*$g/$Tnew)*$distancePoteau/4.0;
		$longcalc2=2*($Tnew/($µTot[$typeCable]*$g))*sinh($argL);
		$valtest = $longcalc - $longcalc2;
//		print $To." => ".$Tnew." => ".$valtest."<BR/>";
		$k++;
	}
//	die();
	$valDeb= -$distancePoteau/2.0;
	$valFin= $distancePoteau/2.0;
	$delta=$distancePoteau/$echantillon;
	$x= $valDeb;
	$i=0;
	while($x<=$valFin){
		$xdata[]=(int)$x;
		$Parg=($µTot[$typeCable]*$g/$To);
		$invParg =$To/($µTot[$typeCable]*$g);
		$arg=($µTot[$typeCable]*$g/$To)*$x; 
		if($x <= $valDeb){
			$valInit=($To/($µTot[$typeCable]*$g))*cosh($arg); 
		}
		$y[] = $hauteurPoteau - $valInit + ($To/($µTot[$typeCable]*$g))*cosh($arg);
		if($x>=0.0){
			$vertTension[] = $To*sinh($arg);
		}else{
			$vertTension[] = $To*sinh(-$arg);
		}
		$horizTension[] = $To*cosh($arg);
		$argL =($µTot[$typeCable]*$g/$To)*$distancePoteau/2.0;
//		print $valtest ." => ".$To ." => ".$x ." => ".$arg." => ".$Parg." => ".$invParg." => val fonction ".sprintf("%01.4f",  $y[$i])." => longueur totale ".sprintf("%01.4f",  (2*$To/($µTot[$typeCable]*$g))*sinh($argL))."<BR/>" ;
		$i++;		
		$x = $x + $delta;
	}

require_once ('jpgraph35/src/jpgraph.php');
require_once ('jpgraph35/src/jpgraph_line.php');
require_once ('jpgraph35/src/jpgraph_utils.inc.php');


// Setup the basic graph
function computeGraph($datay, $datax, $title, $sousTitre, $nomImg, $scale, $mrgLeft ){
	$graph = new Graph(850,350);
	$graph->SetScale($scale);
	$graph->img->SetMargin($mrgLeft,10,60,30);	
	$graph->SetBox(true,'blue',2);	
	$graph->SetMarginColor('black');
	$graph->SetColor('red');
	
	// ... and titles
	$graph->title->Set(iconv("UTF-8", "ISO-8859-1", $title));
	$graph->title->SetFont(FF_FONT1,FS_BOLD);
	$graph->title->SetColor('blue');
	$graph->subtitle->Set(iconv("UTF-8", "ISO-8859-1", $sousTitre));
	$graph->subtitle->SetFont(FF_FONT1,FS_NORMAL);
	$graph->subtitle->SetColor('blue');
	
	$graph->xgrid->Show();
	$graph->xgrid->SetColor('darkblue');
	$graph->ygrid->SetColor('darkblue');
	
	$graph->yaxis->SetPos(0);
	$graph->yaxis->SetWeight(2);
	$graph->yaxis->HideZeroLabel();
	$graph->yaxis->SetFont(FF_FONT1,FS_BOLD);
	$graph->yaxis->SetColor('blue','blue');
	$graph->yaxis->HideTicks(true,true);
	$graph->yaxis->HideFirstLastLabel();
	
	$graph->xaxis->SetWeight(2);
	$graph->xaxis->HideZeroLabel();
	$graph->xaxis->HideFirstLastLabel();
	$graph->xaxis->SetFont(FF_FONT1,FS_BOLD);
	$graph->xaxis->SetColor('blue','blue');
	$lp2 = new LinePlot($datay,$datax);
	list($xm,$ym)=$lp2->Max();
	$lp2->SetWeight(2);
	
	//Legend::SetPos(0,0,$aHAlign='center',$aVAlign='top');	
	
	
	$graph->Add($lp2);
	$lp2->SetColor("#FF0000");
	$graph->Stroke("ImgChainette/".$nomImg);
}

computeGraph($y, $xdata, "CALCUL DES CONTRAINTES SUR CABLE", "Répartition spaciale du cable chargé", "chainette.png", "linlin", 5);
echo "<img border='0' src='ImgChainette/chainette.png' /><BR/><BR/>";
computeGraph($vertTension, $xdata, "CALCUL DES CONTRAINTES SUR CABLE", "Tension horizontale constante :".sprintf("%01.2f",  $To)." Newtons \nContrainte verticale En Newtons", "vertContrainte.png", "linlin", 5);
echo "<img border='0' src='ImgChainette/vertContrainte.png' /><BR/><BR/>";
computeGraph($horizTension, $xdata, "CALCUL DES CONTRAINTES SUR CABLE", "Tension horizontale constante :".sprintf("%01.2f",  $To)." Newtons \nContrainte horizontale En Newtons", "horizContrainte.png", "linlin", 5);
echo "<img border='0' src='ImgChainette/horizContrainte.png' /><BR/><BR/>";
computeGraph($yTo, $xTo, "Tension horizontale en fonction de la flèche en Newtons", "Flèche de 10 cm à 5m ", "horizVsFleche.png", "linlin", 65);
echo "<img border='0' src='ImgChainette/horizVsFleche.png' /><BR/><BR/>";
computeGraph($y1To, $x1To, "Tension horizontale en fonction de la flèche en Newtons", "Flèche de 1m à 5m ", "horizVsFleche1.png", "linlin", 255);
echo "<img border='0' src='ImgChainette/horizVsFleche1.png' /><BR/><BR/>";

?>
