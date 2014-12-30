<?php
    $tpl2 = Zend_Registry::get('tpl2');
    $htmltpl2="layouts/auth-services.tpl";
    $tpl2->setFile("MyFileHandle", $htmltpl2);
    $tpl2->setVar("CONTENT", $this->layout()->content);
    $tpl2->parse( "MyOutput", "MyFileHandle");
    $tpl2->p("MyOutput");
 ?>