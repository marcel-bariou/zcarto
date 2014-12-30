<?php
    $tpl1 = Zend_Registry::get('tpl1');
    if($_SERVER["HTTP_HOST"]=="www.markethon-po.cat"){
			$tplate = "welcome-mkt.tpl";
		}else{
			$tplate = "welcome.tpl";
		}
    $htmltpl1="views/index/".$tplate;
    $tpl1->setFile("MyFileHandle", $htmltpl1);
   $tpl1->parse( "MyOutput", "MyFileHandle");
   $tpl1->p("MyOutput");
?>