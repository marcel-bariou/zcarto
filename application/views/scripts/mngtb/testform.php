<?php
    $tpl1 = Zend_Registry::get('tpl1');  
    $htmltpl1="views/mngtb/testform.tpl";
    $tpl1->setFile("MyFileHandle", $htmltpl1);
     $tpl1->setBlock("MyFileHandle", "theform", "theforms");
    $tpl1->setBlock("MyFileHandle", "theresult", "theresults"); 
    $tpl1->setBlock("theresult", "listTitle", "listTitles");
    $tpl1->setVar("LANGUE", $this->langue);
 
  if(isset($this->result)){
    	foreach($this->result as $title){
    		$tpl1->setVar("VAL", $title);
    		$tpl1->parse("listTitles", "listTitle", true);
    	}
    	$tpl1->parse("theresults", "theresult", true);
    	$tpl1->setVar("THEFORM","");
    	$tpl1->setVar("theforms", "");
  }else{
  	$tpl1->setVar("theresults", "");
  	$tpl1->setVar("THEFORM", $this->form);
  }
   
   $tpl1->parse( "MyOutput", "MyFileHandle");
   $tpl1->p("MyOutput");
?>