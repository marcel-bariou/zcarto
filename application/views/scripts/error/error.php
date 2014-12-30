<?php
    $tpl1 = Zend_Registry::get('tpl1');
    $htmltpl1="views/error/index.tpl";
    $tpl1->setFile("MyFileHandle", $htmltpl1);
    $tpl1->setBlock("MyFileHandle", "msgerrordev", "msgerrordevs");
    $tpl1->setVar("LANGUE", $this->langue);
    
    $tpl1->setVar("MESSAGE", $this->nopermMsg);
    
    if ('development' == APPLICATION_ENV){
    		$tpl1->setVar("MSGEXCEPTION",$this->exception->getMessage() );
    		//$tpl1->setVar("RESTIT", "<h3>Restitution de la pile d'instruction</h3>"); 
    		//$tpl1->setVar("STACKTRACE", $this->exception->getTraceAsString());
    		$tpl1->setVar("RESTIT", "");
    		$tpl1->setVar("STACKTRACE", "");
    		$tpl1->setVar("ZONEERROR", "Passage en erreur ou exception");
		$tpl1->parse( "msgerrordevs", "msgerrordev", true);
   	 }
    
    $tpl1->parse( "MyOutput", "MyFileHandle");
    $tpl1->p("MyOutput");
?>