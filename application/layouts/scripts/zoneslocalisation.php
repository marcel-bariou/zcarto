<?php
	$tpl2 = Zend_Registry::get('tpl2');
	$tpl3 = Zend_Registry::get('tpl3');
	$config = Zend_Registry::get('config');
	$translator = Zend_Registry::get('translator');
	
	include('menugandi.php');
		
	/**
	 *	Main layout Management 
	 */
    
	$htmltpl2="layouts/zoneslocalisation.tpl";
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
	$tpl2->setBlock("MyFileLayoutHandle", "tracealldperimeter", "tracealldperimeters");
	$tpl2->setBlock("MyFileLayoutHandle", "traceallperimeter", "traceallperimeters");
	$tpl2->setBlock("traceallperimeter", "traceperimeter", "traceperimeters");
	$tpl2->setBlock("traceperimeter", "pointperimeter", "pointperimeters");
	$tpl2->setBlock("MyFileHandle", "calcperiTree", "calcperiTrees");
	$tpl2->setBlock("MyFileLayoutHandle", "lstproject", "lstprojects");
	$tpl2->setBlock("MyFileLayoutHandle", "lst2project", "lst2projects");
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
				}else{
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
			$records = new Model_DbTable_GenTb('tZones');
			$dataGeometry = array('alignments' => serialize($LigneTab));
			$records->updateRecord($_POST['project'], $dataGeometry);
			
		}
		
		if(isset($_POST['items']) && $_POST['items'] =='4' ){
			$records = new Model_DbTable_GenTb('tZones');
			$dataGeometry = array('perimeters' => serialize($LigneTab));
			$records->updateRecord($_POST['project'], $dataGeometry);
			
		}
		
		if(isset($_POST['items']) && $_POST['items'] =='3' ){
			$records = new Model_DbTable_GenTb('tZones');
			$dataGeometry = array('transverses' => serialize($LigneTab));
			$records->updateRecord($_POST['project'], $dataGeometry);
			
		}
	}

	 
/**
 *	Data storage for surface area and perimeter :
 *	- Deviation
 */
	 if(isset($_POST['items']) && $_POST['items'] =='11'){
	 	$records = new Model_DbTable_GenTb('tZones');
		$dataGeometry = array('sarea' => $_POST['sarea'], 'perimeter' => $_POST['perimeter']);
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
	  *	Layers management 
	  */
	  
	  
	  if (isset($_POST['displayoneField']) && $_POST['displayoneField'] =="on"){
	  	 $tpl2->setvar("ICONECHECKONE", "checked");  
	  }else{
	  	 $tpl2->setvar("ICONECHECKONE", "unchecked");  
	  }
	  
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
	  
	  
	  
	  /**
	   *	Tiles satellite image management
	   *	Complement layer on zones with out of date images
	   *
	   */
	   if(isset($_POST['project']) && $_POST['project']!="0"){
	   	   $db2= Zend_Registry::get('db2');		
	   	   $query2 = 'SELECT nomImage, latlongDroite, latlongGauche, opacity FROM tZones WHERE id='.$_POST['project']; 			
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
	   	   
		$records = new Model_DbTable_GenTb('tZones');
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
	
	/**
	 *	Setting Zones project list
	 */
	 
	$db1= Zend_Registry::get('db1');
	$db2= Zend_Registry::get('db2');	
	$query = 'SELECT id, c_name, comments, latitude, longitude FROM tZones ';
	$db1->setFetchMode(Zend_Db::FETCH_OBJ);
	$result = $db1->fetchAll($query);
	foreach($result as $key => $value){
		$tpl2->setvar("NUMPROJECT", $result[$key]->id);
		$tpl2->setvar("PROJECT", $result[$key]->c_name);
		if(isset($val_id) && ($val_id== $result[$key]->id)){
    			$perisurf=true;
    			$query2 = 'SELECT c_name, comments, latitude, longitude, perimeters FROM tZones WHERE id='.$result[$key]->id; 			
    			$db2->setFetchMode(Zend_Db::FETCH_OBJ);
    			$res = $db2->fetchAll($query2);
    			$tpl2->setvar("INITLAT", $res[0]->latitude);
    			$tpl2->setvar("INITLONG", $res[0]->longitude);
    			$tpl2->setvar("INITZOOM", 18);
    			$tpl2->setvar("ISSELECT", "selected");
    			$flaginit=true;
    			
		/**
		 *	Icon location for current zone with infowindows
		 *
		 */
			if(isset($_POST['displayoneField'])){
				$namezone=trim($res[0]->c_name);
				$namezone=substr($namezone,0,2); 
				$tpl2->setVar("MYNUM", $namezone);				
				$tpl2->setVar("ARRAYLATITUDE", $res[0]->latitude);
				$tpl2->setVar("ARRAYLONGITUDE", $res[0]->longitude);
				$tpl2->setVar("BULLEINFO", $res[0]->c_name);
				$tpl2->setVar("MYINFOTITLE", $res[0]->c_name);
				$tpl2->setVar("MYINFOBLAH", $res[0]->comments);
				$tpl2->setVar("MYLASTVISIT", "To-day");
				$tpl2->setvar("IDX", $result[0]->id);
				$tpl2->parse("vignettes", "vignette", true);
			}elseif(!isset($_POST['displayFields'])){
				$tpl2->setvar("vignettes", "");
			}
			
			/**
			 *	Display Companies icon per zone
			 *      For map preparation
			 */
			 
			if(!isset($_POST['displayFields']) && isset($_POST['iconCpy'])){
				$query2 = 'SELECT id, c_name,latitude, AddressLine1, longitude FROM tEntreprisespo WHERE zones='.$result[$key]->id.' Order by AddressLine1'; 			
				$db2->setFetchMode(Zend_Db::FETCH_OBJ);
				$res = $db2->fetchAll($query2);
				
				//$tt=$res->ToArray();
				$tt=$res;

				foreach ($tt as $k=>$v){
					$string = $v->AddressLine1;
					$pattern = '/[0-9]*[ bis| Bis]*|[x ]*/';
					$replacement = '';
					//$rank[]=$k;
					$vaddLine[]=preg_replace($pattern, $replacement, $string);
					asort($vaddLine, SORT_FLAG_CASE | SORT_NATURAL);
		
				}				
				
				$mynum=1;
				foreach($vaddLine as $ii => $val){
					$cpy =$res[$ii];
					$tpl2->setVar("MYNUM", $mynum);
					$patterns = array("/é/","/É/", "/Â/", "/È/", "/Â/", "/ô/","/ê/", "/ù/", "/û/", "/ï/", "/î/","/'/", "/è/", "/à/");
					$replaces = array("/e/","/E/", "/A/", "/E/", "/A/", "/o/","/e/", "/u/", "/u/", "/i/", "/i/","/ /", "/e/", "/a/");
					$cpy->c_name = preg_replace($patterns, $replaces, $cpy->c_name);
					$tpl2->setVar("ARRAYLATITUDE", $cpy->latitude);
					$tpl2->setVar("ARRAYLONGITUDE", $cpy->longitude);
					$tpl2->setVar("BULLEINFO", $cpy->c_name);
					$tpl2->setVar("MYINFOTITLE", $cpy->c_name);
					$tpl2->setVar("MYINFOBLAH", "");
					$tpl2->setVar("MYLASTVISIT", "To-day");
					$tpl2->setvar("IDX", $cpy->id);
					$tpl2->parse("vignettes", "vignette", true);
					$mynum +=1;
				}
			
			
			}
		
    		  }else{
    			$tpl2->setvar("ISSELECT", "");
    		}
    		$tpl2->parse( "lstprojects", "lstproject", true);
    		$tpl2->parse( "lst2projects", "lst2project", true);
	
		/**
		 *	Icon location for all zones with infowindows
		 *
		 */
		 
		if(isset($_POST['displayFields'])){				
			$namezone=trim($result[$key]->c_name);
			$namezone=substr($namezone,0,2); 
			$tpl2->setVar("MYNUM", $namezone);
			$tpl2->setVar("ARRAYLATITUDE", $result[$key]->latitude);
			$tpl2->setVar("ARRAYLONGITUDE", $result[$key]->longitude);
			$tpl2->setVar("BULLEINFO", $result[$key]->c_name);
			$tpl2->setVar("MYINFOTITLE", $result[$key]->c_name);
			$tpl2->setVar("MYINFOBLAH", $result[$key]->comments);
			$tpl2->setVar("MYLASTVISIT", "To-day");
			$tpl2->setvar("IDX", $result[$key]->id);
			$tpl2->parse("vignettes", "vignette", true);
		}elseif(!isset($_POST['displayoneField'])){
			$tpl2->setvar("vignettes", "");
		}

		
	}
		/**
    		 *	Current active field data processing
    		 */
    		 
    			
    			/**
    			 *	Construction contour de Toutes zones
    			 */
    			
    			if((isset($_POST['perimetric']) && null !== $_POST['perimetric'] && $val_id!='')|| (isset($_POST['allperimetric']) && null !== $_POST['allperimetric'])){
    				if((isset($_POST['perimetric'])) && $_POST['perimetric']=='on'){
    					$queryall = 'SELECT latitude, longitude, perimeters FROM tZones WHERE id='.$val_id;
    				}else{
    					$queryall = 'SELECT latitude, longitude, perimeters FROM tZones';
    				}
    				$db2->setFetchMode(Zend_Db::FETCH_OBJ);
    				$resall = $db2->fetchAll($queryall);
    				$X=0;
    				foreach($resall as $row){   				
					 if($row->perimeters!="" && is_array($t=unserialize($row->perimeters))){
					 	$X++;
						$valdist=0;
						$k=0;
						$tpl2->setVar("TRACEPERIMETER", "tracePerimeter".$X);
						$tpl2->setVar("PATHPERIM", "pathperim".$X);
						$tpl2->setVar("TRACECLOSESHAPE", "traceCloseShape".$X);
						$tpl2->setVar("PATHCLOSESHAPE", "pathCloseShape".$X);
						while( isset($t[$k])){
							$tpl2->setvar("ALLONGSTORE", $t[$k]['deb']['long']);
							$tpl2->setvar("ALLATSTORE", $t[$k]['deb']['lat']);
							$tpl2->parse( "pointperimeters", "pointperimeter", true);
							$tpl2->setVar("PATHCLOSESHAPE", "pathCloseShape".$X);
							$tpl2->setVar("PATHPERIM", "pathperim".$X);
							if(isset($t[$k]['fin']['long'])){
								$tpl2->setvar("ALLONGSTORE", $t[$k]['fin']['long']);
								$tpl2->setvar("ALLATSTORE", $t[$k]['fin']['lat']);
								$tpl2->parse( "pointperimeters", "pointperimeter", true);
								$tpl2->setVar("PATHCLOSESHAPE", "pathCloseShape".$X);
								$tpl2->setVar("PATHPERIM", "pathperim".$X);
							}
							
							if(isset($t[$k]['fin']['dist']) ){
								$valdist +=$t[$k]['fin']['dist'];
							}	
							if(isset($t[$k]['deb']['dist']) ){
								$valdist +=$t[$k]['deb']['dist'];
							}		
							$k++;		
						}
						
						$tpl2->parse( "traceperimeters", "traceperimeter", true);
					}
					
				}
				//$tpl2->setvar("ALLONGSTORE", $t[0]['deb']['long']);
				//$tpl2->setvar("ALLATSTORE", $t[0]['deb']['lat']);
				//$tpl2->parse( "pointallperimeters", "pointallperimeter", true);
				//$tpl2->setVar("pointperimeters", "");
				//$tpl2->setVar("traceperimeters", "");
				$tpl2->parse( "traceallperimeters", "traceallperimeter", true);
			}else{
    				$tpl2->setVar( "traceallperimeters", "");
    			}
    			
    		//$tpl2->setVar( "traceallperimeters", "");
    
    	
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
		$tpl2->setvar("INITLAT", 42.687192659);
    		$tpl2->setvar("INITLONG",2.8664660453);
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
	
	$tpl2->setVar("CONTENT", $this->layout()->content);
	$tpl2->setVar("CENTSEARCH", "");
	$tpl2->setVar("BSHBANNER", $translator->_('A PAPWEB application LAMPJ platform PAPWEB &copy; 1995-').date("Y",time()).$translator->_('. Contact for support'));
	$tpl2->setVar("SUPHREF", "http://www.icijepeux.es");	
	$tpl2->parse( "MyOutput", "MyFileLayoutHandle");
	$tpl2->p("MyOutput");