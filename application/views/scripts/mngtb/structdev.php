<?php
    $tpl1 = Zend_Registry::get('tpl1');  
    $htmltpl1="views/mngtb/structdev.tpl";
    $tpl1->setFile("MyFileHandle", $htmltpl1);
    $tpl1->setBlock("MyFileHandle", "listItem", "listItems");
    $tpl1->setVar("LANGUE", $this->langue);
    
  
    	$k=0;
   	while(isset($this->records[$k]))
    	{
    		$tpl1->setVar("NOMARTICLE", $this->records[$k]['codeArticle']);	
    		$tpl1->parse("listItems", "listItem", true);
    		$k++;
    		
     	}
    	
    
   
   $tpl1->parse( "MyOutput", "MyFileHandle");
   $tpl1->p("MyOutput");
?>