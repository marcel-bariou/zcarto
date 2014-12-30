<?php
	$tpl2 = Zend_Registry::get('tpl2');
	$config = Zend_Registry::get('config');
	$translator = Zend_Registry::get('translator');
	
	include('menugandi.php');
		
	/**
	 *	Main layout Management 
	 */
    
	$htmltpl2="layouts/mapsApi3test.tpl";
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
	$tpl2->setBlock("MyFileLayoutHandle", "totsurfchp2", "totsurfchp2s");
	$tpl2->setBlock("totsurfchp2", "surfchp2", "surfchp2s");
	$tpl2->setBlock("MyFileLayoutHandle", "lstproject", "lstprojects");
	//$tpl2->setBlock("MyFileLayoutHandle", "polyline", "polylines");
	//$tpl2->setBlock("polyline", "point", "points");	
	
	$tpl2->setBlock("MyFileLayoutHandle", "calcperiTree", "calcperiTrees");
	//$tpl2->setBlock("MyFileLayoutHandle", "dspsurf", "dspsurfs");
	//$tpl2->setBlock("MyFileLayoutHandle", "dspitems", "dspitemss");
	$tpl2->setVar("URLBASE",$config->url->localhost);
	$tpl2->setvar("CHARSET", "UTF-8");
	//$tpl2->setvar("KEYMAP", $config->google->key->local);
    
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
	$tpl2->setVar("TITLE", "PapWeb - ZF - Doctrine");
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
		
	
/**
 * 	Data capture for process to carry out :
 *	- Geodesic area,
 *	- Tree lines processing,
 *	- Center and zooming on the geolocation data,
 *	- Perimeters computation.
 */ 

	if(isset($_POST['SUBMIT']) || isset($_POST['AREA'])|| isset($_POST['items']) ){		
		$tok = strtok($_POST['commentaires'], " \n");
		$i=0;
		$k=0;
		$LigneTab=Array();
		while ($tok !== false) {
			if (preg_match("/[0-9]/", $tok)==1) {
				preg_match_all("/[-0-9]*\.[0-9]*/", $tok, $out);
			
				if($i==0){
					$LigneTab[$k]['deb']['long']= (double)$out[0][0];
					$LigneTab[$k]['deb']['lat']= (double)$out[0][1];
					$LigneTab[$k]['deb']['dist']= (double)$out[0][3];
					$i=1;
				}else{
					$LigneTab[$k]['fin']['long']= (double)$out[0][0];
					$LigneTab[$k]['fin']['lat']= (double)$out[0][1];
					$LigneTab[$k]['fin']['dist']= (double)$out[0][3];
					$i=0;
					$k++;					
				}
			
			}
			$tok = strtok(" \n");
		}		
	}

	
/**
 *	Introduction des éléments de calcul de surface
 *	Calcul du périmètre, Affichage de la surface et du périmètre
 */
	 if(isset($_POST['AREA'])){
		$dist = 0.0;
		$k=0;
		while( isset($LigneTab[$k])){
			$tpl2->setvar("LONGIPOINT", $LigneTab[$k]['deb']['long']);
			$tpl2->setvar("LATIPOINT", $LigneTab[$k]['deb']['lat']);
			$tpl2->parse( "points", "point", true);
			$tpl2->setvar("LONGICHP", $LigneTab[$k]['deb']['long']);
			$tpl2->setvar("LATICHP", $LigneTab[$k]['deb']['lat']);
			$tpl2->parse( "surfchps", "surfchp", true);
			if(isset($LigneTab[$k]['fin']['long'])){
				$tpl2->setvar("LONGIPOINT", $LigneTab[$k]['fin']['long']);
				$tpl2->setvar("LATIPOINT", $LigneTab[$k]['fin']['lat']);
				$tpl2->parse( "points", "point", true);
				$tpl2->setvar("LONGICHP", $LigneTab[$k]['fin']['long']);
				$tpl2->setvar("LATICHP", $LigneTab[$k]['fin']['lat']);
				$tpl2->parse( "surfchps", "surfchp", true);
			}
			
			if(isset($LigneTab[$k]['fin']['dist']) ){
				$dist +=$LigneTab[$k]['fin']['dist'];
			}	
			if(isset($LigneTab[$k]['deb']['dist']) ){
				$dist +=$LigneTab[$k]['deb']['dist'];
			}		
			$k++;		
		}
		$tpl2->parse( "totsurfchps", "totsurfchp", true);
		$tpl2->setvar("LONGIPOINT", $LigneTab[0]['deb']['long']);
		$tpl2->setvar("LATIPOINT", $LigneTab[0]['deb']['lat']);
		$tpl2->parse( "points", "point", true);
		$tpl2->setvar("LONGIPOINT", $LigneTab[0]['fin']['long']);
		$tpl2->setvar("LATIPOINT", $LigneTab[0]['fin']['lat']);
		$tpl2->parse( "points", "point", true);
		$tpl2->setvar("PERIMETER",  sprintf("%0.2f", $dist));
		//$tpl2->parse( "dspsurfs", "dspsurf", true);		
	 }else{
	 	$tpl2->setvar("totsurfchps", "");
	 }

	
	
/**
 *	Calcul Des approvisionnements et de la surface sous arbre
 */
 
	 if (isset($_POST['items']) && $_POST['items']=="1"){
	 	$tbPerimLat=array();
	 	$tbPerimLong=array();
		$tabItems = array();
		$dist = 0.0;
		$k=0;
		$interval=10;
		$rang = 0;
		$potVertical=0;
		$potExtrem = 0;
		$LongueurFilet=0;
		
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
		
		$tpl2->setvar("LONGIPOINT", $LigneTab[0]['deb']['long']);
		$tpl2->setvar("LATIPOINT", $LigneTab[0]['deb']['lat']);
		$tpl2->parse( "points", "point", true);
		$tpl2->setvar("LONGIPOINT", $LigneTab[0]['fin']['long']);
		$tpl2->setvar("LATIPOINT", $LigneTab[0]['fin']['lat']);
		$tpl2->parse( "points", "point", true);
		
		/**
		 *	Préparation calcul du périmètre sous arbres
		 */
		 
		 $tpl2->setvar("LONGPREV", $tbPerimLong[0]);
		 $tpl2->setvar("LATPREV", $tbPerimLat[0]);
		 $tpl2->setvar("LONGIPOINT", $tbPerimLong[0]);
		 $tpl2->setvar("LATIPOINT", $tbPerimLat[0]);
		 $tpl2->parse( "points", "point", true);

		 $tpl2->setvar("LONGNEW", $tbPerimLong[1]);
		 $tpl2->setvar("LATNEW", $tbPerimLat[1]);
		 $tpl2->parse( "calcperiTrees", "calcperiTree", true);
		 $k=1;
		 $j=$k+1;
		 while(isset($tbPerimLong[$j])){
		 	 $tpl2->setvar("LONGPREV", $tbPerimLong[$k]);
		 	 $tpl2->setvar("LATPREV", $tbPerimLat[$k]);		
		 	 $tpl2->setvar("LONGIPOINT", $tbPerimLong[$k]);
		 	 $tpl2->setvar("LATIPOINT", $tbPerimLat[$k]);
		 	 $tpl2->parse( "points", "point", true);
		 	 $tpl2->setvar("LONGNEW", $tbPerimLong[$j]);
		 	 $tpl2->setvar("LATNEW", $tbPerimLat[$j]);
		 	 $tpl2->parse( "calcperiTrees", "calcperiTree", true);
		 	 $k++;
		 	 $j++;		 	 
		 }
		 
		 $tpl2->setvar("LONGIPOINT", $tbPerimLong[$k]);
		 $tpl2->setvar("LATIPOINT", $tbPerimLat[$k]);
		 $tpl2->parse( "points", "point", true);
		 $tpl2->setvar("LONGIPOINT", $tbPerimLong[0]);
		 $tpl2->setvar("LATIPOINT", $tbPerimLat[0]);
		 $tpl2->parse( "points", "point", true);
		 $tpl2->setvar("LONGPREV", $tbPerimLong[$k]);
		 $tpl2->setvar("LATPREV", $tbPerimLat[$k]);
		 $tpl2->setvar("LONGNEW", $tbPerimLong[0]);
		 $tpl2->setvar("LATNEW", $tbPerimLat[0]);
		 $tpl2->parse( "calcperiTrees", "calcperiTree", true);		 
		
		$nomenclature= "La nomenclature des besoins est :<table width= \"400\"><tr><td>Nbre de rangs arborés</td><td>".$rang."</td></tr><tr><td>Le nbombre de poteaux verticaux</td><td>".$potVertical."</td></tr><tr><td>Le nbombre de poteaux de fin de rang</td><td>".$potExtrem."</td></tr><tr><td>La longueur nécessaire de filet</td><td>". sprintf("%0.2f", $LongueurFilet)."</td></tr></table>";
		
		$tpl2->setvar("ITEMLST", $nomenclature);
		$tpl2->parse( "dspitemss", "dspitems", true);
		$tpl2->parse( "totsurfchp2s", "totsurfchp2", true);
		
	 }else{
	 	 $tpl2->setvar("calcperiTrees", "");
	 	 $tpl2->setvar("totsurfchp2s", "");
	 	 
	 }

	
/**
 *	Centrage, zoom et initialisation cartographique
 *	Toujours tracer le périmètre except on init centring and zooming
 *
 */
 
	if(isset($_POST['CENTRER'])){
		$tpl2->setvar("LATITUC", $_POST['latitudeCenter']);
		$tpl2->setvar("LONGITUC",  $_POST['longitudeCenter']);
		$tpl2->setvar("ZOOMC",  $_POST['zoomSize']);
		$tpl2->setvar("polylines", "");
	}else{
		$tpl2->setvar("LATITUC", '33.6634812498');
		$tpl2->setvar("LONGITUC", '-4.95578026768');
		$tpl2->setvar("ZOOMC",  '18');	
		$tpl2->parse( "polylines", "polyline", true);
	}
	
	$tpl2->setvar("NAMEAPPLI",  $config->name->root);
	//die('Echu yo');
	$tpl2->parse( "maps", "map", true);
	 
	 
	 
	
	/**
	*	Mise en place de la partie calculée selon le contrôleur utilisé 
	*	Ici pourrait ête mis en place les différents types de formulaire
	*	en fonction des traitements cartographiques à faire
	*/
	
	$tpl2->setVar("CONTENT", $this->layout()->content);
	$tpl2->setVar("CENTSEARCH", "");
	$tpl2->setVar("BSHBANNER", $this->translator->_('A PAPWEB application LAMPJ platform PAPWEB &copy; 1995-').date("Y",time()).$this->translator->_('. Contact for support'));
	$tpl2->setVar("SUPHREF", "http://www.icijepeux.es");
	
	$tpl2->parse( "MyOutput", "MyFileLayoutHandle");
	$tpl2->p("MyOutput");