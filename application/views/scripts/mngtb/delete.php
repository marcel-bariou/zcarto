<?php
    $tpl1 = Zend_Registry::get('tpl1');
    $htmltpl1="views/mngtb/delete.tpl";
    $tpl1->setFile("MyFileHandle", $htmltpl1);
    $sentence = 'Are you sure that you want to delete '. $this->escape($this->record[$this->display['value'][0]]).
    		    ' - '. $this->escape($this->record[$this->display['value'][1]]);

	$tpl1->setVar('SENTENCE', $sentence);
	$tpl1->setVar('ACTION', $this->url(array('action'=>'delete')));
	$tpl1->setVar('USERID', $this->record['id']);
   $tpl1->parse( "MyOutput", "MyFileHandle");
   $tpl1->p("MyOutput");
?>
