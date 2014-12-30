<?php
	$tpl1 = Zend_Registry::get('tpl1');  
	$tpl2 = Zend_Registry::get('tpl2');  
	$htmltpl1="views/mngtb/quoteActionMulti.tpl";
	$tpl1->setFile("MyFileHandle", $htmltpl1);	
	$tpl1->setBlock("MyFileHandle", "groupedevis", "groupedeviss");
	$tpl1->setBlock("groupedevis", "lignedevis", "lignedeviss");
	$htmltpl2="views/mngtb/listquoteAction.tpl";
	$tpl2->setFile("MyFilelistHandle", $htmltpl2);	
	$tpl2->setBlock("MyFilelistHandle", "groupedevis", "groupedeviss");
	$tpl2->setBlock("groupedevis", "lignedevis", "lignedeviss");

	$this->b=array();
	if(!isset($this->distancePlace)){
		$this->distancePlace=60;
	}
	
	$tabQuantity[]= "Start";						//0
	$tabQuantity[]= $this->NbRgLg;						//1
	$tabQuantity[]= $this->NbRgT;						//2
	$tabQuantity[]= $this->NbPotPos;					//3
	$tabQuantity[]= $this->NbPotPerim;					//4
	$tabQuantity[]= $this->NbAncrage;					//5
	$tabQuantity[]= $this->qLongueurFilet;					//6
	$tabQuantity[]= $this->qLongueurCableF;					//7
	$tabQuantity[]= $this->qLongueurCableT;					//8
	$tabQuantity[]= $this->NbTotPot; 					//9
	$tabQuantity[]= $this->distancePlace;					//10
	$tabQuantity[]= ($this->NbTotPot) * 5; 	// Poids Transport		//11
	$tabQuantity[]= $this->qLongueurFilAcier;				//12
	$tabQuantity[]= 1;							//13
	$tabQuantity[]= $this->sarea;						//14
	$tabQuantity[] = $this->serreCableTrans; 				//15
	$tabQuantity[] = $this->serreCableLong; 				//16

	$groupDevis[] = "Start";
	$groupDevis[] = "Support & Ancrage";
	$groupDevis[] = "Filet";
	$groupDevis[] = "Cables";
	$groupDevis[] = "Fil de fer";
	$groupDevis[] = "Accessoires";
	$groupDevis[] = "Main d'oeuvre";
	$groupDevis[] = "Transport";
	$groupDevis[] = "Béton";
	
	/**
	 *	Chapitres de devis défini en base article
	 */
	 
	$chapitre[]   = "devis";
	$chapitre[]   = "support";
	$chapitre[]   = "filet";
	$chapitre[]   = "cables";
	$chapitre[]   = "fildefer";
	$chapitre[]   = "accessoires";
	$chapitre[]   = "mo";
	$chapitre[]   = "transport";
	$chapitre[]   = "beton";
	
	$db2= Zend_Registry::get('db2');		
	$query2 = "SELECT * FROM tOrganization WHERE id=".$this->Id_Client; 			
	$db2->setFetchMode(Zend_Db::FETCH_OBJ);
	$res = $db2->fetchAll($query2);
	//var_dump($res);
	//die("Line 69 =>".$query2);	
	
	$notreAdresse   ='<p style="font-weight:bold;"> Agro-tech Ingénierie Agricole<BR/> Quartier Mhiraz <BR/>31 000 Sefrou MAROC</p>';
	$donneesclient  ='<p style=font-weight:bold;">'.$res[0]->c_name.'<BR/>'.$res[0]->AddressLine1.'  '.$res[0]->AddressLine2.' <BR/>'.$res[0]->PostalCode.'  '.$res[0]->City.' '. $res[0]->country.'</p>';


	
	$donneesterrain = '<p style="font-size: 10px"> Latitude : '.$this->latitude.' <BR/> Longitude : '.$this->longitude.'  <BR/> Périmètre : '.$this->perimeter.' m <BR/> Distance au site : '.$this->distance.' Km <BR/> Surface : '.$this->sarea.'  m2 <BR/> Longueur filet : '.sprintf("%01.2f", $this->qLongueurFilet).' m <BR/> Nbre poteaux : '.$this->NbTotPot.' <BR/> Nbre rangs : '.$this->NbRgLg.'</p>';
	$tpl1->setVar("NOTREADRESSE", $notreAdresse);
	$tpl1->setVar("DONNEESCLIENT", $donneesclient);
	$tpl1->setVar("DONNEESTERRAIN", $donneesterrain);
	$tpl1->setVar("LOGO", "<img src='http://www.agro-tech.org/zcarto/public/images/agro-tech.jpg' border='0'>");
	$tpl1->setVar("CLTCHP", $this->CltChp);
	$tpl1->setVar("SAREA", sprintf("%01.0f",$this->sarea));
	/**
	 *	Tri des groupes de cotation
	 *	$a[$ii] contient la liste désordonnée des enregistrements de chaque article de la nomenclature
	 *	La liste est ici ordonnée par chapitre de devis
	 */

	 $i=1;
	 while(isset($groupDevis[$i])){
	 	 $ii=0;	
	 	 while(isset($this->a[$ii])){
	 	 	 if($this->a[$ii]['goupQuote'] == $i){
	 	 	 	$this->b[]= $this->a[$ii];
	 	 	 }
	 	 	 $ii++;
	 	 }
	 	 $i++;
	 }

	$total=0.0;
	$totalGen=0.0;
	$i=1;
	$k=0;
	$nl=1;
	
	
	/**
	 *	Construction des lignes de devis
	 *	Les chapitres de devis étant ordonnés
	 *	Chaque chapitre est analysé dans la nomenclature afin d'extraire, les lignes de nomenclature ou de devis
	 */
	 
	while(isset($groupDevis[$i])){
		if( isset($this->params[$chapitre[$i]])){
			$k=0;
			$tpl1->setVar("CHAPITRE", $groupDevis[$i]);
			$tpl2->setVar("CHAPITRE", $groupDevis[$i]);
			while(isset($this->b[$k])){
				if($this->b[$k]['goupQuote'] == $i ){
					
					$tpl1->setVar("NL", $nl);
					$nl++;
					$tpl1->setVar("REF", $this->b[$k]['codeArticle']);
					$tpl1->setVar("DESIGNATION", $this->b[$k]['name']);
					$tpl1->setVar("QUTE",  (int)($tabQuantity[$this->b[$k]['baseCalcul']] * $this->b[$k]['poidsCalcul'] * $this->b[$k]['poidsVolume']));
					
					$tpl2->setVar("REF", $this->b[$k]['codeArticle']);
					$tpl2->setVar("DESIGNATION", iconv("UTF-8", "ISO-8859-1", $this->b[$k]['name']));
					$tpl2->setVar("QUTE",  (int)($tabQuantity[$this->b[$k]['baseCalcul']] * $this->b[$k]['poidsCalcul'] * $this->b[$k]['poidsVolume']));
					
					/**
					 *	Nomenclature ou devis 
					 */
					 
					if(!isset($this->sancout)){
						$tpl1->setVar("PUHT", $this->b[$k]['price']);
						$t=  $this->b[$k]['price'] * $tabQuantity[$this->b[$k]['baseCalcul']] * $this->b[$k]['poidsCalcul'] * $this->b[$k]['poidsVolume'];
						$tpl1->setVar("PTOTAL", sprintf("%01.2f", $t));
					}else{
						$tpl1->setVar("PUHT", 0.0);
						$t=  0.0;
						$tpl1->setVar("PTOTAL", sprintf("%01.2f", $t));					
					}
					$tpl1->parse("lignedeviss", "lignedevis", true);				
					$tpl2->parse("lignedeviss", "lignedevis", true);				
					$total +=$t;
				}
				$k++;
			}
			if(isset($this->sancout)){
				$tpl1->setVar("DOCUMENT", "NOMENCLATURE EQUIPEMENT/TACHES CLES EN MAIN");
			}else{
				$tpl1->setVar("DOCUMENT", "FACTURE PROFORMA D'EQUIPEMENT CLES EN MAIN");
			}
			$tpl1->setVar("NOMCHAMP", $this->nomChamp);
			$tpl1->setVar("DATEJOUR", date("d-M-Y", time()));
			$totalGen += $total;
			$tpl1->setVar("VALCHAP", sprintf("%01.2f", $total));
			$total=0.0;
			$tpl1->parse("groupedeviss", "groupedevis", true);
			$tpl2->parse("groupedeviss", "groupedevis", true);
			$tpl1->setvar("lignedeviss", "");
			$tpl2->setvar("lignedeviss", "");
		}
		$i++;
	}
	
	/** 
	 *	Données de vérification
	 */
	 
	$tpl1->setVar("TOTGEN", sprintf("%01.2f", $totalGen));
	$tpl2->setVar("FAITG", sprintf("%01.2f", $this->LgEfficaceL));
	$tpl2->setVar("SAREA", sprintf("%01.2f", $this->sarea));
	$tpl2->setVar("PVPOSE",$this->qpotVertical);
	$tpl2->setVar("PEXTR",$this->NbPotPerim);
	$tpl2->setVar("DEVIAT",$v=$this->NbPotDevL + $this->NbPotDevT);
	$tpl2->setVar("TRANSV",sprintf("%01.2f", $this->qlongueurTransverse));
	$tpl2->setVar("PERIM", sprintf("%01.2f", $this->perimeter));
		
	$tpl2->parse( "MylistOutput", "MyFilelistHandle");
	$tpl2->wr_buffer("MylistOutput", "listForQuote/list.html");	
	
	$tpl1->parse( "MyOutput", "MyFileHandle");
	$tpl1->p("MyOutput");
?>