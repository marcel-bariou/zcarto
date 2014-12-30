<?php
	$tpl1 = Zend_Registry::get('tpl1');
	$htmltpl1="views/maps/index.tpl";
	$tpl1->setFile("MyFileHandle", $htmltpl1);
	$tpl1->setBlock("MyFileHandle", "list", "lists");
	$tpl1->setBlock("list", "listValue", "listValues");
	$tpl1->setBlock("MyFileHandle", "listTitle", "listTitles");
	$tpl1->setVar("LANGUE", $this->langue);
	
	$tpl1->setVar("MNGTARTICLES", $this->url(array('controller'=>'mngtb', 'action'=>'index', 't' =>'tArticles')));
	$tpl1->setVar("MNGTALBUMS", $this->url(array('controller'=>'mngtb', 'action'=>'index', 't' =>'tAlbums')));
	$tpl1->setVar("MNGTUSER", $this->url(array('controller'=>'mngtb', 'action'=>'index', 't' =>'tUser')));
	$tpl1->setVar("MNGTROLE", $this->url(array('controller'=>'mngtb', 'action'=>'index', 't' =>'tRole')));
	$tpl1->setVar("MNGTGROUP", $this->url(array('controller'=>'mngtb', 'action'=>'index', 't' =>'tGroup')));
	$tpl1->setVar("HREFMEDIA", $this->url(array('controller'=>'mngtb', 'action'=>'index', 't' =>'tMedia')));
	$tpl1->setVar("HREFORG", $this->url(array('controller'=>'mngtb', 'action'=>'index', 't' =>'tOrganization')));
	$tpl1->setVar("HREFADD", $this->url(array('controller'=>'mngtb', 'action'=>'add')));
	
	
	$tpl1->setVar("PAGINATOR", $this->paginationControl($this->paginator, 'Sliding', 'pagination.php'));
	$tpl1->parse( "MyOutput", "MyFileHandle");
	$tpl1->p("MyOutput");
?>