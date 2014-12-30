<?php
    $tpl1 = Zend_Registry::get('tpl1');  
    $htmltpl1="views/mngtb/partslist.tpl";
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
    			$tpl1->setVar("VALUE", $this->escape($record->$value));
    			$tpl1->parse("listValues", "listValue", true); 
    		}    	
        	$tpl1->parse('lists', 'list', true);
        	$tpl1->setVar("listValues", "");
    	}
    	
    }else{
    	$tpl1->setVar("listTitles", "");
    	$tpl1->setVar("lists", "");
    }
   
   $tpl1->setVar("PAGINATOR", $this->paginationControl($this->paginator, 'Sliding', 'pagination.php'));
   $tpl1->parse( "MyOutput", "MyFileHandle");
   $tpl1->p("MyOutput");
?>