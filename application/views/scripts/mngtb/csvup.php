<?php
    $tpl1 = Zend_Registry::get('tpl1');  
    $htmltpl1="views/mngtb/csvup.tpl";
    $tpl1->setFile("MyFileHandle", $htmltpl1);
     $tpl1->setBlock("MyFileHandle", "theform", "theforms");
    $tpl1->setBlock("MyFileHandle", "theresult", "theresults"); 
    $tpl1->setBlock("theresult", "listTitle", "listTitles");
    $tpl1->setVar("LANGUE", $this->langue);

  if(isset($this->result)){
    	
	$tpl1->setVar("VAL", $this->result[0]);
	if ( $this->result[1]!=""){
		$tpl1->setVar("ERROR", ". ERREUR(S) => ".$this->result[1].".");	
	}else{
		$tpl1->setVar("ERROR", "");	
	}
	$tpl1->parse("listTitles", "listTitle", true);
    	
    	$tpl1->parse("theresults", "theresult", true);
    	$tpl1->setVar("THEFORM",$this->form);
  }else{
  	$tpl1->setVar("theresults", "");
  	$tpl1->setVar("THEFORM", $this->form);
  }
   
   $tpl1->parse( "MyOutput", "MyFileHandle");
   $tpl1->p("MyOutput");
?>