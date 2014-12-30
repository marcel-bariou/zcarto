<?php
    $tpl1 = Zend_Registry::get('tpl1');
    $htmltpl1="views/index/index.tpl";
    $tpl1->setFile("MyFileHandle", $htmltpl1);
    $tpl1->setBlock("MyFileHandle", "list", "lists");
    $tpl1->setBlock("list", "listValue", "listValues");
    $tpl1->setBlock("MyFileHandle", "listTitle", "listTitles");
    $tpl1->setVar("LANGUE", $this->langue);
    
 
    if(($countRec=count($this->paginator))!=0){ 
    	foreach($this->display['title'] as $title){
    		$tpl1->setVar("TITLE", $title);
    		$tpl1->parse("listTitles", "listTitle", true);
    	}
    	foreach($this->paginator as $record)
    	{
    		
    		$tpl1->setVar("COLORBG", $this->cycle(array("#BDD3EA","#FFFFFF"))->next());
    		foreach($this->display['value'] as $value){    		
    			$tpl1->setVar("VALUE", html_entity_decode($this->escape($record->$value)));
    			$tpl1->parse("listValues", "listValue", true); 
    		}    	
        	$tpl1->setVar("EDIT",  $this->url(array('controller'=>'mngtb', 'action'=>'edit', 't' => 'tUser', 'id'=>$record->id)));
        	$tpl1->setVar("DELETE", $this->url(array('controller'=>'mngtb', 'action'=>'delete','t' => 'tUser',  'id'=>$record->id)));
        	$tpl1->setVar("ADD", $this->url(array('controller'=>'mngtb', 'action'=>'add','t' => $this->tableName)));
        	$tpl1->parse('lists', 'list', true);
        	$tpl1->setVar("listValues", "");
    	}
    	
    }else{
    	$tpl1->setVar("listTitles", "");
    	$tpl1->setVar("lists", "");
    }
   $tpl1->setVar("TAG", "");
   $tpl1->setVar("PAGINATOR", $this->paginationControl($this->paginator, 'Sliding', 'pagination.php'));
   $tpl1->parse( "MyOutput", "MyFileHandle");
   $tpl1->p("MyOutput");
?>