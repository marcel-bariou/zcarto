<?php
    $tpl2 = Zend_Registry::get('tpl2');
    $tpl3 = Zend_Registry::get('tpl3');
    $this->translator = Zend_Registry::get('translator');
    $config=Zend_Registry::get('config');
    /**
     *		Dropdown menu construction
     *		To automaticated
     */
     
    /**
     *	Main layout Management 
     */
     
    $htmltpl2="layouts/courbeDeNiveau.tpl";
    $tpl2->setFile("MyFileLayoutHandle", $htmltpl2);
    $tpl2->setVar("LATI", $this->latlongfield[0]);
    $tpl2->setVar("LONGI", $this->latlongfield[1]);
    $lt=(double)$this->latlongfield[0]+0.0009;
    $tpl2->setVar("LATI2", $lt);
    $lt=(double)$this->latlongfield[1]+0.0009;
    $tpl2->setVar("LONGI2", $lt);
    $tpl2->setVar("ADRESSE", $this->adresse); 
    $tpl2->setVar("TBVALUE", $this->TableName);
    $tpl2->setVar("IDVALUE", $this->idRef); 
    $tpl2->setVar("BASEURL",$_SERVER["HTTP_HOST"]);
    //$tpl2->setVar("BASEURL",$config->url->localhost); 
    
      
    /**
     *	Mise en place de la partie calculée selon le contôleur utilisé 
     */
     
     $tpl2->setVar("CONTENT", $this->layout()->content);
    
	$tpl2->setVar("BSHBANNER", $this->translator->_('A PAPWEB application LAMPJ platform PAPWEB &copy; 1995-').date("Y",time()).$this->translator->_('. Contact for support'));
	$tpl2->setVar("SUPHREF", "http://www.icijepeux.es");
    
    $tpl2->parse( "MyOutput", "MyFileLayoutHandle");
    $tpl2->p("MyOutput");
?>