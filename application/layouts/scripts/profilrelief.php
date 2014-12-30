<?php
	$tpl2 = Zend_Registry::get('tpl2');
	$tpl3 = Zend_Registry::get('tpl3');
	$config = Zend_Registry::get('config');
	$translator = Zend_Registry::get('translator');
	
	include('menugandi.php');
	
	/**
	 *	Main layout Management 
	 */
    
	$htmltpl2="layouts/courbeDeNiveau.tpl";
	$tpl2->setFile("MyFileLayoutHandle", $htmltpl2);
	$base_url=$this->baseUrl;
	$tpl2->setVar("LATI", $this->latlongfield[0]);
	$tpl2->setVar("LONGI", $this->latlongfield[1]);
	$lt=(double)$this->latlongfield[0]+0.0009;
	$tpl2->setVar("LATI2", $lt);
	$lt=(double)$this->latlongfield[1]+0.0009;
	$tpl2->setVar("LONGI2", $lt);
	
	
		
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
	
?>
