<?php
    $tpl1 = Zend_Registry::get('tpl1');
    $htmltpl1="views/mngtb/affichemots.tpl";
    $tpl1->setFile("MyFileHandle", $htmltpl1);
    $tpl1->setBlock("MyFileHandle", "list", "lists");
    $tpl1->setVar("TITLE", "QUALIFICATIFS ACTIVITES ET OCCURRENCES DES MOTS");
    $expressions= 0;
    $usageExp =0;
    foreach($this->tbOrderMots as $k=>$v){
    	 $tpl1->setVar("COLORBG", $this->cycle(array("#BDD3EA","#FFFFFF"))->next());
    	 $tpl1->setVar("MOTS", $v[0]);
    	 $tpl1->setVar("OCCURRENCES", $v[1]);
    	 $tpl1->parse("lists", "list", true);  
    	 $expressions += 1;
    	 $usageExp += $v[1];    	 
    } 
    $tpl1->setVar("COLORBG", $this->cycle(array("#BDD3EA","#FFFFFF"))->next());
    $tpl1->setVar("BILAN",  $expressions." expressions disctinctes qualifiantes employées ".$usageExp." fois");
    $tpl1->parse("lists", "list", true);  
    
    $tpl1->parse( "MyOutput", "MyFileHandle");
    $tpl1->p("MyOutput");
?>