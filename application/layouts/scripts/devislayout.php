<?php
	$tpl2 = Zend_Registry::get('tpl2');
	$config = Zend_Registry::get('config');
	$translator = Zend_Registry::get('translator');
		
	/**
	 *	Main layout Management 
	 */
    
	$htmltpl2="layouts/propsdevis.tpl";
	$base_url=$this->baseUrl;
	$tpl2->setFile("MyFileLayoutHandle", $htmltpl2);    
	$tpl2->setVar("URLBASE",$config->url->localhost);
	$tpl2->setvar("CHARSET", "UTF-8");
	
	$tpl2->setVar("DOCTYPE", $this->doctype());
	$tpl2->setVar("HEADTITLE", $this->headTitle());
	$tpl2->setVar("HEADLINK", $this->headLink());
	$tpl2->setVar("HEADSTYLE", $this->headStyle());
	$tpl2->setVar("HEADSCRIPT", $this->headScript());
	 
	
	$tpl2->setvar("NAMEAPPLI",  $config->name->root);
	$tpl2->parse( "maps", "map", true);
	//die('Echu yo');
	
	/**
	*	Mise en place de la partie calculée selon le contrôleur utilisé 
	*	Ici pourrait ête mis en place les différents types de formulaire
	*	en fonction des traitements cartographiques à faire
	*/
	
	//print $this->layout()->content;die();
	
	$tpl2->setVar("CONTENT", $this->layout()->content);
	
	$tpl2->parse( "MyOutput", "MyFileLayoutHandle");
	$tpl2->p("MyOutput");