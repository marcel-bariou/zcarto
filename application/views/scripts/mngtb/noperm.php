<?php
	$translator=Zend_Registry::get('translator');
	$tpl1 = Zend_Registry::get('tpl1');
	$config = Zend_Registry::get('config');
	$htmltpl1="views/error/noperm.tpl";
	$tpl1->setFile("MyFileHandle", $htmltpl1);
	$base_url=$this->baseUrl.'/auth/login/';
	$tpl1->setVar("URLAPPLI", $config->url->appli);
	$tpl1->setVar("URLBASE",$config->url->localhost);
	$tpl1->setvar("CHARSET", "UTF-8");
	$tpl1->setVar("TITLE", "Login");
	$tpl1->setVar("TITLEPAGE", "PAPWEB specific access");
	$tpl1->setVar("PRECISIONERROR", $this->nopermMsg);
	$tpl1->parse( "MyOutput", "MyFileHandle");
	$tpl1->p( "MyOutput");
?>
