<?php
    $tpl1 = Zend_Registry::get('tpl1');  
    $htmltpl1="views/mngtb/index.tpl";
    $tpl1->setFile("MyFileHandle", $htmltpl1);
   $tpl1->parse( "MyOutput", "MyFileHandle");
   $tpl1->p("MyOutput");
?>