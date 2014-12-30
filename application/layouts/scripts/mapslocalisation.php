<?php
	$tpl2 = Zend_Registry::get('tpl2');
	$tpl3 = Zend_Registry::get('tpl3');
	$config = Zend_Registry::get('config');
	$translator = Zend_Registry::get('translator');
	
	include('menugandi.php');
		
	/**
	 *	Main layout Management 
	 */
    
	$htmltpl2="layouts/mapslocalisation.tpl";
	$base_url=$this->baseUrl;
	$tpl2->setFile("MyFileLayoutHandle", $htmltpl2);    
	$tpl2->setBlock("MyFileLayoutHandle", "vignette", "vignettes");
	$tpl2->setBlock("MyFileLayoutHandle", "ics", "icss");
	$tpl2->setBlock("MyFileLayoutHandle", "lati", "latis");
	$tpl2->setBlock("MyFileLayoutHandle", "longi", "longis");
	$tpl2->setBlock("MyFileLayoutHandle", "map", "maps");
	$tpl2->setBlock("MyFileLayoutHandle", "role", "roles");
	$tpl2->setBlock("MyFileLayoutHandle", "totsurfchp", "totsurfchps");
	$tpl2->setBlock("totsurfchp", "surfchp", "surfchps");
	$tpl2->setBlock("MyFileHandle", "calcperiTree", "calcperiTrees");
	$tpl2->setBlock("MyFileLayoutHandle", "totsurfchp2", "totsurfchp2s");
	$tpl2->setBlock("totsurfchp2", "surfchp2", "surfchp2s");
	$tpl2->setBlock("MyFileLayoutHandle", "lstproject", "lstprojects");
	$tpl2->setBlock("MyFileLayoutHandle", "lst2project", "lst2projects");
	$tpl2->setBlock("MyFileLayoutHandle", "traceperimeter", "traceperimeters");
	$tpl2->setBlock("traceperimeter", "pointperimeter", "pointperimeters");
	$tpl2->setBlock("MyFileLayoutHandle", "tracealignment", "tracealignments");
	$tpl2->setBlock("tracealignment", "pointalignment", "pointalignments");
	$tpl2->setBlock("MyFileLayoutHandle", "tracetransverse", "tracetransverses");
	$tpl2->setBlock("tracetransverse", "pointtransverse", "pointtransverses");
	$tpl2->setBlock("MyFileLayoutHandle", "deviat", "deviats");
	$tpl2->setBlock("MyFileLayoutHandle", "calcperiTree", "calcperiTrees");
	$tpl2->setBlock("MyFileLayoutHandle", "tracesuplayer", "tracesuplayers");
	
	$htmltpl3="kml/lac.tpl";
	$tpl3->setFile("MyFileKmlHandle", $htmltpl3);    
	$tpl3->setBlock("MyFileKmlHandle", "PolygoneExterne", "PolygoneExternes");
	$tpl3->setBlock("MyFileKmlHandle", "PolygoneInterne", "PolygoneInternes");
	$tpl2->setVar("URLBASE",$config->url->localhost);
	$tpl2->setvar("CHARSET", "UTF-8");
    
	if(is_object($this->form) && $this->form->has_TinyMce){
	$tpl2->setVar("TINYMCE", $this->getHelper('BuildForm')->get_tinyMce());
	}else{
	$tpl2->setVar("TINYMCE", "");
	}
	
	$tpl2->setVar("DOCTYPE", $this->doctype());
	$tpl2->setVar("HEADTITLE", $this->headTitle());
	$tpl2->setVar("HEADLINK", $this->headLink());
	$tpl2->setVar("HEADSTYLE", $this->headStyle());
	$tpl2->setVar("HEADSCRIPT", $this->headScript());
	$tpl2->setVar("HEADERMENU", $buffer);
	 
	/**
	*		Site authentification aund autorization getting
	*
	*/
	
	$auth = Zend_Auth::getInstance();		
	if ($auth->hasIdentity()) 
	{
	    $tpl2->setVar("INVITE", "Vous trouverez ci-dessous les règles de mise en place de votre application");    
	    $this->user = Zend_Auth::getInstance()->getIdentity();
	    $tpl2->setVar("CURUSER", $translator->_('Current user'));
	    $tpl2->setVar("USR", $this->escape($this->user->c_username));
	    $tpl2->setVar("LGO", $base_url.'/'.$config->name->root.'/public/auth/logout');
	    $logout_profile= $translator->_('Logout').' => <a href="/'.$config->name->root.'/mngtb/perso/base/tUser/tUserID/'.$this->user->id.'"><span>'.$translator->_('Your profile').'</span></a>';
	    $tpl2->setVar("LGX", $logout_profile);
	}else{
	   $tpl2->setVar("INVITE", "Authentifiez vous ici pour découvrir les règles de mise en oeuvre du cadriciel Zend et assurer la mise en place d'application");    
	   $tpl2->setVar("CURUSER","Not connected");
	   $tpl2->setVar("USR", 'Anonymous');
	   $tpl2->setVar("LGO", $base_url.'/'.$config->name->root.'/public/auth/login');
	   $tpl2->setVar("LGX", 'Login !');
	}
	
	/**
	*	Dojo settings
	*/
	
	$this->dojo()->enable();
	$tpl2->setVar("HEADDOJO", $this->dojo()->__toString());
	$tpl2->setVar("CLASSBODY", "tundra");
	$tpl2->setVar("TITLEBODY", $this->escape($this->title));
	
	/**
	 *	Traitement des données cartographiques
	 */
	 
	$base_url=$config->url->localhost;	
	$icon_letter=Array(10 => array('R', 'Respite'), 12 => array('V', 'LEISURE'), 9 => array('B', 'CALL'));
	$minipeg=Array(10 => 'mini-respite.jpg', 12 => 'mini-recreation.jpg', 9 => 'mini-helplines.jpg');	
	$tpl2->setvar("WELCOME", $translator->_('WELCOME'));
	$tpl2->setvar("WELMSG", $translator->_('Welcome to visitor from'));
	$tpl2->setvar("VISITOR", $translator->_('Visitor'));
	$tpl2->setvar("VCOMMENTS", $translator->_('You seem to be located somewhere around here !'));
	$tpl2->setvar("TITLE", $translator->_('Les aventures de la libre cartographie'));
	$tpl2->setvar("TYPETERRAIN", "HYBRID");		
	
/**
 * 	Data capture for process to carry out :
 *	- Geodesic area,
 *	- Tree lines processing,
 *	- Center and zooming on the geolocation data,
 *	- Perimeters computation, and so on ....
 */ 
	if(isset($this->champ)){
		$val_id =$this->champ;
	}
	if(isset($_POST['project'])){
		$val_id =$_POST['project'];
	}
	
	if(isset($_POST['items']) && $_POST['items']== "12"){
		if(isset($_POST['devounom'])){	
			$tpl2->setvar("NOMDEVIS", "checked");
		}else{
			$tpl2->setvar("NOMDEVIS", "unchecked");
		}
	
		if(isset($_POST['support'])){
			$tpl2->setvar("SUPPORTCHECK", "checked");
		}else{
			$tpl2->setvar("SUPPORTCHECK", "unchecked");
		}
		
		if(isset($_POST['filet'])){
			$tpl2->setvar("FILETCHECK", "checked");
		}else{
			$tpl2->setvar("FILETCHECK", "unchecked");
		}
		
		if(isset($_POST['cables'])){
			$tpl2->setvar("CABLESCHECK", "checked");
		}else{
			$tpl2->setvar("CABLESCHECK", "unchecked");
		}
		
		if(isset($_POST['fildefer'])){
			$tpl2->setvar("FILDEFERCHECK", "checked");
		}else{
			$tpl2->setvar("FILDEFERCHECK", "unchecked");
		}
		
		if(isset($_POST['accessoires'])){
			$tpl2->setvar("ACCESSOIRESCHECK", "checked");
		}else{
			$tpl2->setvar("ACCESSOIRESCHECK", "unchecked");
		}
			
		if(isset($_POST['mo'])){
			$tpl2->setvar("MOCHECK", "checked");
		}else{
			$tpl2->setvar("MOCHECK", "unchecked");
		}
		if(isset($_POST['transport'])){
			$tpl2->setvar("TRANSPORTCHECK", "checked");
		}else{
			$tpl2->setvar("TRANSPORTCHECK", "unchecked");
		}
		if(isset($_POST['beton'])){
			$tpl2->setvar("BETONCHECK", "checked");
		}else{
			$tpl2->setvar("BETONCHECK", "unchecked");
		}
				
	}else{
		$tpl2->setvar("NOMDEVIS", "checked");
		$tpl2->setvar("SUPPORTCHECK", "checked");
		$tpl2->setvar("FILETCHECK", "checked");
		$tpl2->setvar("CABLESCHECK", "checked");
		$tpl2->setvar("FILDEFERCHECK", "checked");
		$tpl2->setvar("ACCESSOIRESCHECK", "checked");
		$tpl2->setvar("MOCHECK", "checked");
		$tpl2->setvar("TRANSPORTCHECK", "checked");
		$tpl2->setvar("BETONCHECK", "checked");
	}
	
	/**
	 *	Script to try a re-direction from a layout
	 *	Don't work !
	 */
	 
	if(isset($_POST['SUBMIT']) && (isset($_POST['items']) && ($_POST['items']== "13")) ){
 		$out[0]= explode(",", $_POST['commentaires']);
 		$r = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
 		$r->gotoSimple('quote', $controller = 'mngtb', $module = null, array('notRectangleStandard' => 1, 'length'=> $out[0][0],'width' => $out[0][1], 'widthRank' => $out[0][2], 'widthTrans'=> $out[0][3]));
	}
	
 	if(isset($_POST['SUBMIT']) && (isset($_POST['items']) && ($_POST['items']== "4"|| $_POST['items']== "1" || $_POST['items']== "3")) ){		
		$tok = strtok($_POST['commentaires'], " \n");
		$i=0;
		$k=0;
		$LigneTab=Array();
		$altMini=10000.0;
		$out= Array();
		while ($tok !== false) {
			if (preg_match("/[0-9]/", $tok)==1) {
				//preg_match_all("/[-0-9]*\.[0-9]*|[-0-9]*/", $tok, $out);
				$out[0]= explode(",", $tok);
				if($i==0){
					$LigneTab[$k]['deb']['long']= (double)$out[0][0];
					$LigneTab[$k]['deb']['lat']= (double)$out[0][1];
					$LigneTab[$k]['deb']['dist']= (double)$out[0][3];
					$LigneTab[$k]['deb']['alt']= (double)$out[0][4];
					if($altMini>(float)$LigneTab[$k]['deb']['alt']){
						$altMini=(float)$LigneTab[$k]['deb']['alt'];
					}
					$i=1;
				}else{//var_dump($tok); print "<BR/><BR/>";var_dump($out);die();
					$LigneTab[$k]['fin']['long']= (double)$out[0][0];
					$LigneTab[$k]['fin']['lat']= (double)$out[0][1];
					$LigneTab[$k]['fin']['dist']= (double)$out[0][3];
					$LigneTab[$k]['fin']['alt']= (double)$out[0][4];
					if($altMini>(float)$LigneTab[$k]['fin']['alt']){
						$altMini=(float)$LigneTab[$k]['fin']['alt'];
					}
					$i=0;
					$k++;					
				}
			
			}
			$tok = strtok(" \n");
		}
			
		if(isset($_POST['items']) && $_POST['items'] =='1' ){
			$records = new Model_DbTable_GenTb('tChamp');
			$dataGeometry = array('alignments' => serialize($LigneTab));
			$records->updateRecord($_POST['project'], $dataGeometry);
			
		}
		
		if(isset($_POST['items']) && $_POST['items'] =='4' ){
			$records = new Model_DbTable_GenTb('tChamp');
			$dataGeometry = array('perimeters' => serialize($LigneTab));
			$records->updateRecord($_POST['project'], $dataGeometry);
			
		}
		
		if(isset($_POST['items']) && $_POST['items'] =='3' ){
			$records = new Model_DbTable_GenTb('tChamp');
			$dataGeometry = array('transverses' => serialize($LigneTab));
			$records->updateRecord($_POST['project'], $dataGeometry);
			
		}
	}

	 
/**
 *	Data storage for surface area and perimeter :
 *	- Deviation
 */
	 if(isset($_POST['items']) && $_POST['items'] =='11'){
	 	$records = new Model_DbTable_GenTb('tChamp');
		$dataGeometry = array('sarea' => $_POST['sarea'], 'perimeter' => $_POST['perimeter']);
		$records->updateRecord($_POST['project'], $dataGeometry);
	 }

	 
/**
 *	Data capture for process to carry out :
 *	- Deviation
 */
	 if(isset($_POST['items']) && $_POST['items'] =='2' ){
	 	$tok = strtok($_POST['commentaires'], " \n");
		$i=0;
		$k=0;
		$LigneTob=Array();		
		while ($tok !== false) {
			if (preg_match("/[0-9]/", $tok)==1) {
				//preg_match_all("/[-0-9]*\.[0-9]*|[-0-9]*/", $tok, $out);
				$out[0]= explode(",", $tok);
					$LigneTob[$k]['long']= (double)$out[0][0];
					$LigneTob[$k]['lat']= (double)$out[0][1];
					$k++;			
			}
			$tok = strtok(" \n");
		}
		$records = new Model_DbTable_GenTb('tChamp');
		$dataGeometry = array('deviations' => serialize($LigneTob));
		$records->updateRecord($_POST['project'], $dataGeometry);

	 }
	 
/**
 *	Data capture for process to carry out :
 *	- Deviationst
 */
	 if(isset($_POST['items']) && $_POST['items'] =='14' ){
	 	$tok = strtok($_POST['commentaires'], " \n");
		$i=0;
		$k=0;
		$LigneTob=Array();		
		while ($tok !== false) {
			if (preg_match("/[0-9]/", $tok)==1) {
				//preg_match_all("/[-0-9]*\.[0-9]*|[-0-9]*/", $tok, $out);
				$out[0]= explode(",", $tok);
					$LigneTob[$k]['long']= (double)$out[0][0];
					$LigneTob[$k]['lat']= (double)$out[0][1];
					$k++;			
			}
			$tok = strtok(" \n");
		}
		$records = new Model_DbTable_GenTb('tChamp');
		$dataGeometry = array('deviationst' => serialize($LigneTob));
		$records->updateRecord($_POST['project'], $dataGeometry);

	 }
	 
/**
 *	Data capture for process to carry out :
 *	- End chapel cable
 */
	 if(isset($_POST['items']) && $_POST['items'] =='16' ){
	 	$tok = strtok($_POST['commentaires'], " \n");
		$i=0;
		$k=0;
		$LigneTob=Array();		
		while ($tok !== false) {
			if (preg_match("/[0-9]/", $tok)==1) {
				//preg_match_all("/[-0-9]*\.[0-9]*|[-0-9]*/", $tok, $out);
				$out[0]= explode(",", $tok);
					$LigneTob[$k]['long']= (double)$out[0][0];
					$LigneTob[$k]['lat']= (double)$out[0][1];
					$k++;			
			}
			$tok = strtok(" \n");
		}
		$records = new Model_DbTable_GenTb('tChamp');
		$dataGeometry = array('cabfinchap' => serialize($LigneTob));
		$records->updateRecord($_POST['project'], $dataGeometry);

	 }

	
/**
 *	Introduction des éléments de calcul de surface
 *	Calcul du périmètre, Affichage de la surface et du périmètre
 */
	 if(isset($_POST['items']) && $_POST['items'] =='4' ){
	 	$tok = strtok($_POST['commentaires'], " \n");
		$i=0;
		$k=0;
		$LigneTob=Array();
		
		while ($tok !== false) {
			if (preg_match("/[0-9]/", $tok)==1) {
				//preg_match_all("/[-0-9]*\.[0-9]*|[-0-9]*/", $tok, $out);
				$out[0]= explode(",", $tok);
					$LigneTob[$k]['long']= (double)$out[0][0];
					$LigneTob[$k]['lat']= (double)$out[0][1];
					$LigneTob[$k]['dist']= (double)$out[0][3];
					$LigneTob[$k]['alt']= (double)$out[0][4];
					$k++;			
			}
			$tok = strtok(" \n");
		}

		$dist = (double)0.0;
		$k=0;
		while( isset($LigneTob[$k])){
			$tpl2->setvar("LONGICHP", $LigneTob[$k]['long']);
			$tpl2->setvar("LATICHP", $LigneTob[$k]['lat']);
			$tpl2->parse( "surfchps", "surfchp", true);
			$lonlatalt = $LigneTob[$k]['long'].','.$LigneTob[$k]['lat'].','.$LigneTob[$k]['alt'];
			$tpl3->setvar("ELONGLATALT", $lonlatalt);
			$tpl3->parse( "PolygoneExternes", "PolygoneExterne", true);			
			/*
			$lonlatalt = $LigneTob[$k]['long'].','.$LigneTob[$k]['lat'].','.$altMini;
			$tpl3->setvar("ILONGLATALT", $lonlatalt);
			$tpl3->parse( "PolygoneInternes", "PolygoneInterne", true);
			*/			
			if(isset($LigneTob[$k]['dist']) ){
				$dist +=(double)$LigneTob[$k]['dist'];
			}
			$k++;		
		}
		$tpl2->parse( "totsurfchps", "totsurfchp", true);
		$tpl2->setvar("PERIMETER",  sprintf("%0.2f", $dist));
		$lonlatalt = $LigneTob[0]['long'].','.$LigneTob[0]['lat'].','.$LigneTob[0]['alt'];
		$tpl3->setvar("ELONGLATALT", $lonlatalt);
		$tpl3->parse( "PolygoneExternes", "PolygoneExterne", true);
		/*
		$lonlatalt = $LigneTob[0]['long'].','.$LigneTob[0]['lat'].','.$altMini;
		$tpl3->setvar("ILONGLATALT", $lonlatalt);
		$tpl3->parse( "PolygoneInternes", "PolygoneInterne", true);
		*/
		$tpl3->parse( "MyKmlOutput", "MyFileKmlHandle");
		$tpl3->wr_buffer("MyKmlOutput", "kml/lac.kml");	
	 }else{
	 	$tpl2->setvar("totsurfchps", "");
	 }

	
	
/**
 *	Calcul Des approvisionnements et de la surface sous arbre
 *	A partir des données d'alignement
 */
 
	 if (isset($_POST['items']) && $_POST['items']=="10"){
	 	$tbPerimLat=array();
	 	$tbPerimLong=array();
		$tabItems = array();
		$LigneTab=array();
		$dist = 0.0;
		$k=0;
		$interval=10;
		$rang = 0;
		$potVertical=0;
		$potExtrem = 0;
		$LongueurFilet=0;

		$db2= Zend_Registry::get('db2');		
		$query2 = 'SELECT alignments FROM tChamp WHERE id='.$_POST['project']; 			
    		$db2->setFetchMode(Zend_Db::FETCH_OBJ);
    		$res = $db2->fetchAll($query2);
		$LigneTab= unserialize($res[0]->alignments);
		
		while( isset($LigneTab[$k])){
			
			if(isset($LigneTab[$k]['fin'])){	
				$rang ++;		
				$tabItems[$rang]["potVertical"] = (int)($LigneTab[$k]['fin']['dist'] / $interval) -1;
				$potVertical +=  (int)($LigneTab[$k]['fin']['dist'] / $interval) -1;
				$tabItems[$rang]["potExtrem"] = 2;
				$potExtrem += 2;
				$tbPerimLat[]=$LigneTab[$k]['deb']['lat'];
				$tbPerimLong[]=$LigneTab[$k]['deb']['long'];
				$tpl2->setvar("LONGICHP2", $LigneTab[$k]['deb']['long']);
				$tpl2->setvar("LATICHP2", $LigneTab[$k]['deb']['lat']);
				$tpl2->parse( "surfchp2s", "surfchp2", true);

				$LongueurFilet += $LigneTab[$k]['fin']['dist']; 	
						
			}
			$k++;
		}
		
		$k--;
		while( isset($LigneTab[$k])){
			$tbPerimLat[]=$LigneTab[$k]['fin']['lat'];
			$tbPerimLong[]=$LigneTab[$k]['fin']['long'];			
			$tpl2->setvar("LONGICHP2", $LigneTab[$k]['fin']['long']);
			$tpl2->setvar("LATICHP2", $LigneTab[$k]['fin']['lat']);
			$tpl2->parse( "surfchp2s", "surfchp2", true);
			$k--;
		}
		$tpl2->parse( "totsurfchp2s", "totsurfchp2", true);
		
		
	/*	
		$tpl2->setvar("LONGIPOINT", $LigneTab[0]['deb']['long']);
		$tpl2->setvar("LATIPOINT", $LigneTab[0]['deb']['lat']);
		$tpl2->parse( "points", "point", true);
		$tpl2->setvar("LONGIPOINT", $LigneTab[0]['fin']['long']);
		$tpl2->setvar("LATIPOINT", $LigneTab[0]['fin']['lat']);
		$tpl2->parse( "points", "point", true);
	*/	
		/**
		 *	Préparation calcul du périmètre sous arbres
		 */
	/*	 
		 $tpl2->setvar("LONGPREV", $tbPerimLong[0]);
		 $tpl2->setvar("LATPREV", $tbPerimLat[0]);
		 $tpl2->setvar("LONGIPOINT", $tbPerimLong[0]);
		 $tpl2->setvar("LATIPOINT", $tbPerimLat[0]);
		 $tpl2->parse( "points", "point", true);
	*/
		 $tpl2->setvar("LONGNEW", $tbPerimLong[1]);
		 $tpl2->setvar("LATNEW", $tbPerimLat[1]);
		 $tpl2->parse( "calcperiTrees", "calcperiTree", true);
		 $k=1;
		 $j=$k+1;
		 while(isset($tbPerimLong[$j])){
		 	 $tpl2->setvar("LONGPREV", $tbPerimLong[$k]);
		 	 $tpl2->setvar("LATPREV", $tbPerimLat[$k]);		
		 	// $tpl2->setvar("LONGIPOINT", $tbPerimLong[$k]);
		 	// $tpl2->setvar("LATIPOINT", $tbPerimLat[$k]);
		 	// $tpl2->parse( "points", "point", true);
		 	 $tpl2->setvar("LONGNEW", $tbPerimLong[$j]);
		 	 $tpl2->setvar("LATNEW", $tbPerimLat[$j]);
		 	 $tpl2->parse( "calcperiTrees", "calcperiTree", true);
		 	 $k++;
		 	 $j++;		 	 
		 }
		 
		// $tpl2->setvar("LONGIPOINT", $tbPerimLong[$k]);
		// $tpl2->setvar("LATIPOINT", $tbPerimLat[$k]);
		// $tpl2->parse( "points", "point", true);
		// $tpl2->setvar("LONGIPOINT", $tbPerimLong[0]);
		// $tpl2->setvar("LATIPOINT", $tbPerimLat[0]);
		// $tpl2->parse( "points", "point", true);
		 $tpl2->setvar("LONGPREV", $tbPerimLong[$k]);
		 $tpl2->setvar("LATPREV", $tbPerimLat[$k]);
		 $tpl2->setvar("LONGNEW", $tbPerimLong[0]);
		 $tpl2->setvar("LATNEW", $tbPerimLat[0]);
		 $tpl2->parse( "calcperiTrees", "calcperiTree", true);		 
		
		$nomenclature= "La nomenclature des besoins est :<table width= \"400\"><tr><td>Nbre de rangs arborés</td><td>".$rang."</td></tr><tr><td>Le nombre de poteaux verticaux</td><td>".$potVertical."</td></tr><tr><td>Le nombre de poteaux de fin de rang</td><td>".$potExtrem."</td></tr><tr><td>La longueur nécessaire de filet</td><td>". sprintf("%0.2f", $LongueurFilet)."</td></tr></table>";
		
		$nomenclature .= "<BR/>Si cette évaluation sommaire vous satisfait, <a href=\"http://";
		$nomenclature .= $config->url->appli."public/mngtb/quote/t/tChamp/id/".$val_id."\">vous pouvez lancer le calcul final du devis</a>, ";
		$nomenclature .= "<a href=\"http://".$config->url->appli."public/mngtb/quote/t/tChamp/id/".$val_id."/sancout/0\">ou extraire la seule nomenclature</a>, sinon reprenez vos données.";
		
		$tpl2->setvar("MESSAGESRESULT", $nomenclature);
		//$tpl2->parse( "dspitemss", "dspitems", true);
		
	 }else{
	 	 $tpl2->setvar("calcperiTrees", "");
	 	 $tpl2->setvar("totsurfchp2s", "");
	 	 
	 }
	 
	 /**
	  *	Layers management 
	  */
	  
	  
	  if (isset($_POST['displayFields']) && $_POST['displayFields'] =="on"){
	  	 $tpl2->setvar("ICONECHECK", "checked");  
	  }else{
	  	 $tpl2->setvar("ICONECHECK", "unchecked");  
	  }
	  
	  if (isset($_POST['fillColor']) && $_POST['fillColor'] =="on"){
	  	 $tpl2->setvar("FILLCHECK", "checked");  
	  }else{
	  	 $tpl2->setvar("FILLCHECK", "unchecked");  
	  }

	  if (isset($_POST['perimetric']) && $_POST['perimetric'] =="on"){
	  	 $tpl2->setvar("PERICHECK", "checked");  
	  }else{
	  	 $tpl2->setvar("PERICHECK", "unchecked");  
	  }
	  
	  if (isset($_POST['alitric']) && $_POST['alitric'] =="on"){
	  	 $tpl2->setvar("ALICHECK", "checked");  
	  }else{
	  	 $tpl2->setvar("ALICHECK", "unchecked");  
	  }
	  
	  if (isset($_POST['transtric']) && $_POST['transtric'] =="on"){
	  	 $tpl2->setvar("TRANSCHECK", "checked");  
	  }else{
	  	 $tpl2->setvar("TRANSCHECK", "unchecked");  
	  }
	  
	  if (isset($_POST['deviatric']) && $_POST['deviatric'] =="on"){
	  	 $tpl2->setvar("DEVCHECK", "checked");  
	  }else{
	  	 $tpl2->setvar("DEVCHECK", "unchecked");  
	  }
	  	  
	  if (isset($_POST['sancout']) && $_POST['sancout'] =="on"){
	  	 $tpl2->setvar("COSTCHECK", "checked");  
	  }else{
	  	 $tpl2->setvar("COSTCHECK", "unchecked");  
	  }
	  
	  
	  /**
	   *	Tiles satellite image management
	   *	Complement layer on zones with out of date images
	   *
	   */
	   if(isset($_POST['project']) && $_POST['project']!="0"){
	   	   $db2= Zend_Registry::get('db2');		
	   	   $query2 = 'SELECT nomImage, latlongDroite, latlongGauche, opacity FROM tChamp WHERE id='.$_POST['project']; 			
	   	   $db2->setFetchMode(Zend_Db::FETCH_OBJ);
	   	   $res = $db2->fetchAll($query2);
	   	   if( $res[0]->nomImage != "NomImageSansExtension"){
	   	        $nomimg = $res[0]->nomImage;
	   	   	$LatLongSW = $res[0]->latlongGauche;
	   	   	$LatLongNE = $res[0]->latlongDroite;
	   	   }else{
	   	   	$LatLongSW =  "Lat, Long, S/W Inf/gauche";
	   	   	$LatLongNE = "Lat, Long, N/E Sup/droite";
	    	   	$nomimg = "Donnez le nom sans ext.";	   	   	   
	   	   }
    	   }else{
    	   	   $LatLongSW =  "Lat, Long, S/W Inf/gauche";
	    	   $LatLongNE = "Lat, Long, N/E Sup/droite";
	    	   $nomimg = "Donnez le nom sans ext.";
    	   }

	   $flagSupLayer=false;
	   if (isset($_POST['SUBMIT']) && isset($_POST['imagesat']) && $_POST['imagesat'] =="on"){
	   	    $tpl2->setvar("COUCHEIMAGE", "checked");
	    	    if(isset($_POST['latlongGauche']) && $_POST['latlongGauche'] !="" && $_POST['latlongGauche']!="Lat, Long, S/W Inf/gauche"){
	    	    	    $tpl2->setvar("LATLONSW", $_POST['latlongGauche']);
	    	    	    $flagSupLayer=true;
	    	    }else{
	    	    	    $flagSupLayer=false;
	    	    }
	    	    if(isset($_POST['latlongDroite']) && $_POST['latlongDroite'] !="" && $flagSupLayer){
	    	    	    $tpl2->setvar("LATLONNE", $_POST['latlongDroite']);    	    	    
	    	    }else{
	    	    	   $flagSupLayer=false; 
	    	    }
	    	    if(isset($_POST['nomImage']) && $_POST['nomImage'] !="" && $flagSupLayer){
	    	    	    $tpl2->setvar("NOMIMG", $_POST['nomImage']);	    	    	    
	    	    	    $tpl2->parse( "tracesuplayers", "tracesuplayer", true);
	    	    }else{
	    	    	   $tpl2->setvar("LATLONSW", $_POST['latlongGauche']);
	    	    	   $tpl2->setvar("LATLONNE", $_POST['latlongDroite']);
	    	    	   $tpl2->setvar("NOMIMG", $_POST['nomImage']); 
	    	    	   $flagSupLayer=false; 
	    	    	   $tpl2->setVar( "tracesuplayers", "");
	    	    }
	    }else{
	    	    $tpl2->setvar("COUCHEIMAGE", "unchecked");
	    	    $tpl2->setvar("LATLONSW", $LatLongSW );
	    	    $tpl2->setvar("LATLONNE", $LatLongNE );
	    	    $tpl2->setvar("NOMIMG", $nomimg);
	    	    $tpl2->setVar( "tracesuplayers", "");
	    }
	    
	 /**
	  *	Data suppress for later refresh 
	  */
	  
	   if ((isset($_POST['items']) && $_POST['items'] <="8" && $_POST['items'] >="5" && $_POST['project'] != "0") || (isset($_POST['items']) && $_POST['items'] =="15")){
	   	   
	   	   switch ($_POST['items']){
	   	   	case "5" :
	   	   		$f= 'perimeters';
	   	   	   break;
	   	   	case "6" :
	   	   		$f= 'alignments';
	   	   	   break;
	   	   	case "7" :
	   	   		$f= 'transverses';
	   	   	   break;
	   	   	case "8" :
	   	   		$f= 'deviations';
	   	   	   break;
	   	   	case "15" :
	   	   		$f= 'deviationst';
	   	   	   break;
	   	   }
	   	   
		$records = new Model_DbTable_GenTb('tChamp');
		$dataGeometry = array($f => "");
		$records->updateRecord($_POST['project'], $dataGeometry);   	   
	   }
	  
	/**
	 *	Setup flag location signalisation in every field
	 *	For every screen display
	 *	Trace perimeters, alignments, deviations for located field if asked
	 */
 
	$flaginit=false; 
	$perisurf=false;
	$tpl2->setvar("VALTZONE", Zend_Registry::get('tzone'));
	$db1= Zend_Registry::get('db1');
	$db2= Zend_Registry::get('db2');
	$query = 'SELECT id, c_name, comments, latitude, longitude FROM tChamp where culture ='.Zend_Registry::get('tzone');
    	$db1->setFetchMode(Zend_Db::FETCH_OBJ);
    	$result = $db1->fetchAll($query);
    	foreach($result as $key => $value){
    		$tpl2->setvar("NUMPROJECT", $result[$key]->id);
		$tpl2->setvar("PROJECT", $result[$key]->c_name);
    		if(isset($_POST['displayFields'])){
			$tpl2->setVar("ARRAYLATITUDE", $result[$key]->latitude);
			$tpl2->setVar("ARRAYLONGITUDE", $result[$key]->longitude);
			$tpl2->setVar("BULLEINFO", $result[$key]->c_name);
			$tpl2->setVar("MYINFOTITLE", $result[$key]->c_name);
			$tpl2->setVar("MYINFOBLAH", $result[$key]->comments);
			$tpl2->setVar("MYLASTVISIT", "To-day");
			$tpl2->setvar("IDX", $result[$key]->id);
			$tpl2->parse("vignettes", "vignette", true);
    		}else{
    			$tpl2->setvar("vignettes", "");
    		}
    		
    		/**
    		 *	Current active field data processing
    		 */
    		 
    		if(isset($val_id) && ($val_id== $result[$key]->id)){
    			$perisurf=true;
    			$query2 = 'SELECT latitude, longitude, perimeters, alignments, transverses, deviations FROM tChamp WHERE id='.$result[$key]->id; 			
    			$db2->setFetchMode(Zend_Db::FETCH_OBJ);
    			$res = $db2->fetchAll($query2);
    			$tpl2->setvar("INITLAT", $res[0]->latitude);
    			$tpl2->setvar("INITLONG", $res[0]->longitude);
    			$tpl2->setvar("INITZOOM", 18);
    			$tpl2->setvar("ISSELECT", "selected");
    			$flaginit=true;
    			
    			/**
    			 *	Construction contour de la surface
    			 */
    			
    			if(isset($_POST['perimetric']) && $_POST['perimetric']=='on' && $res[0]->perimeters!="" && is_array($t=unserialize($res[0]->perimeters))){
    				$valdist=0;
    				$k=0;
				while( isset($t[$k])){
					$tpl2->setvar("LONGSTORE", $t[$k]['deb']['long']);
					$tpl2->setvar("LATSTORE", $t[$k]['deb']['lat']);
					$tpl2->parse( "pointperimeters", "pointperimeter", true);
					if(isset($t[$k]['fin']['long'])){
						$tpl2->setvar("LONGSTORE", $t[$k]['fin']['long']);
						$tpl2->setvar("LATSTORE", $t[$k]['fin']['lat']);
						$tpl2->parse( "pointperimeters", "pointperimeter", true);
					}
					
					if(isset($t[$k]['fin']['dist']) ){
						$valdist +=$t[$k]['fin']['dist'];
					}	
					if(isset($t[$k]['deb']['dist']) ){
						$valdist +=$t[$k]['deb']['dist'];
					}		
					$k++;		
				} 
				$tpl2->setvar("LONGSTORE", $t[0]['deb']['long']);
				$tpl2->setvar("LATSTORE", $t[0]['deb']['lat']);
				$tpl2->parse( "pointperimeters", "pointperimeter", true);
   				$tpl2->parse( "traceperimeters", "traceperimeter", true);
    			}else{
    				$tpl2->setVar( "traceperimeters", "");
    			}
    			
     			/**
    			 *	Construction des alignements
    			 */
    			 
   			if(isset($_POST['alitric']) && $_POST['alitric']=='on' && $res[0]->alignments!="" && is_array($a=unserialize($res[0]->alignments))){  
    				$valdistal=0;
    				$k=0;
				while( isset($a[$k])){ 
					$tpl2->setvar("LONGSTOREAL", $a[$k]['deb']['long']);
					$tpl2->setvar("LATSTOREAL", $a[$k]['deb']['lat']);
					$tpl2->parse( "pointalignments", "pointalignment", true);
					$tpl2->setvar("LONGSTOREAL", $a[$k]['fin']['long']);
					$tpl2->setvar("LATSTOREAL", $a[$k]['fin']['lat']);
					$tpl2->parse( "pointalignments", "pointalignment", true);					
					$valdistal +=$a[$k]['fin']['dist'];							
					$k++;		
				}
				 
				$tpl2->parse( "tracealignments", "tracealignment", true);
    			}else{
    				$tpl2->setVar( "tracealignments", "");

    			}
     			/**
    			 *	Constructions transversales
    			 */
    			 
   			if(isset($_POST['transtric']) && $_POST['transtric']=='on' && $res[0]->transverses!="" && is_array($a=unserialize($res[0]->transverses))){  
    				$valdistal=0;
    				$k=0;
				while( isset($a[$k])){ 
					$tpl2->setvar("LONGTRANSV", $a[$k]['deb']['long']);
					$tpl2->setvar("LATTRANSV", $a[$k]['deb']['lat']);
					$tpl2->parse( "pointtransverses", "pointtransverse", true);
					$tpl2->setvar("LONGTRANSV", $a[$k]['fin']['long']);
					$tpl2->setvar("LATTRANSV", $a[$k]['fin']['lat']);
					$tpl2->parse( "pointtransverses", "pointtransverse", true);					
					$valdistal +=$a[$k]['fin']['dist'];							
					$k++;		
				}
				 
				$tpl2->parse( "tracetransverses", "tracetransverse", true);
    			}else{
    				$tpl2->setVar( "tracetransverses", "");
    			}
    				
    				
      			/**
    			 *	Constructions marqueurs de deviation
    			 */
    			 
   			if(isset($_POST['deviatric']) && $_POST['deviatric']=='on' && $res[0]->deviations!="" && is_array($a=unserialize($res[0]->deviations))){  
    				$k=0;
				while( isset($a[$k])){ 
					$tpl2->setvar("DEVX", $k);
					$tpl2->setvar("DEVIATLONGITUDE", $a[$k]['long']);
					$tpl2->setvar("DEVIATLATITUDE", $a[$k]['lat']);
					$tpl2->setvar("DEVINFO", "Poteau N° ".$k);
					$tpl2->parse( "deviats", "deviat", true);								
					$k++;		
				}
    			}else{
    				$tpl2->setVar( "deviats", "");

    			}
    		}else{
    			$tpl2->setvar("ISSELECT", "");
    		}
    		$tpl2->parse( "lstprojects", "lstproject", true);
    		$tpl2->parse( "lst2projects", "lst2project", true);
    	} 
    	
    	/**
    	 *	No area or perimeters to tracae
    	 */
    	 
    	 if(!$perisurf){
    	 	 $tpl2->setVar( "traceperimeters", "");
    	 	 $tpl2->setVar( "tracealignments", "");
    	 	 $tpl2->setVar( "tracetransverses", "");
    	 	 $tpl2->setVar( "deviats", "");
    	 }
    	 
	/**
	 *	In absence of any field
	 *	Setup default flag location for the field test
	 *	For every screen display
	 */
	
	if(!$flaginit){
		$tpl2->setvar("INITLAT", 33.663324893612874);
    		$tpl2->setvar("INITLONG",-4.956612347040505);
    		$tpl2->setvar("INITZOOM", 18);
	}
	
/**
 *	Center and zoom et init data supplied
 *	or setup default
 *
 */
 
	if(isset($_POST['items']) && $_POST['items']=="9"){
		$tpl2->setvar("INITLAT", $_POST['latitudeCenter']);
		$tpl2->setvar("INITLONG",  $_POST['longitudeCenter']);
		$tpl2->setvar("INITZOOM",  $_POST['zoomSize']);
	}

 /*
	if(isset($_POST['items']) && $_POST['items']=="9"){
		$tpl2->setvar("LATITUC", $_POST['latitudeCenter']);
		$tpl2->setvar("LONGITUC",  $_POST['longitudeCenter']);
		$tpl2->setvar("ZOOMC",  $_POST['zoomSize']);
		$tpl2->setvar("polylines", "");
	}else{
		$tpl2->setvar("LATITUC", '33.663324893612874');
		$tpl2->setvar("LONGITUC", '-4.95578026768');
		$tpl2->setvar("ZOOMC",  '18');	
		$tpl2->parse( "polylines", "polyline", true);
	}
*/	
	$tpl2->setvar("NAMEAPPLI",  $config->name->root);
	$tpl2->parse( "maps", "map", true);
	//die('Echu yo');
	
	/**
	*	Mise en place de la partie calculée selon le contrôleur utilisé 
	*	Ici pourrait ête mis en place les différents types de formulaire
	*	en fonction des traitements cartographiques à faire
	*/
	
	//$tpl2->setVar("CONTENT", $this->layout()->content);
	$tpl2->setVar("CENTSEARCH", "");
	$tpl2->setVar("BSHBANNER", $translator->_('A PAPWEB application LAMPJ platform PAPWEB &copy; 1995-').date("Y",time()).$translator->_('. Contact for support'));
	$tpl2->setVar("SUPHREF", "http://www.icijepeux.es");
	
	$tpl2->parse( "MyOutput", "MyFileLayoutHandle");
	$tpl2->p("MyOutput");