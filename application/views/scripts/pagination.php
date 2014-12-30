<?php
/*
 * Created on Jun 5, 2009
 * PAPWEB
 *
 * LICENSE
 *
 * This source file is subject to the lpgl.txt license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.brasnah.com/lgpl.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to papweb@brasnah.com so we can send you a copy immediately.
 *
 * @category   Papweb
 * @package    WebServices
 * @author	   Marcel Bariou	
 * @copyright  Copyright (c) 2005-2009 Brasnah sarl (http://www.brasnah.com)
 * @license    http://www.brasnah.com/lpgl.txt    
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
  
 if ($this->pageCount){
    $tpl3 = Zend_Registry::get('tpl3');
    $htmltpl3="views/pagination.tpl";
    $tpl3->setFile("MyFileHandle", $htmltpl3);
    $tpl3->setBlock("MyFileHandle", "list", "lists");
    if (isset($this->previous))    {
    	$tpl3->setVar("PREVIOUS", "<a href=\"".$this->url(array('page' => $this->previous))."\">&lt; Précédent</a>");
    	
    }else{
    	$tpl3->setVar("PREVIOUS", "Précédent");
    }
    
    foreach ($this->pagesInRange as $page){
    	if ($page != $this->current){
    		$s=" | <a href=\"".$this->url(array('page' => $page))."\">".$page."</a>";	
    		$tpl3->setVar("PAGE", $s );
 			   		
    	}else{
    		$tpl3->setVar("PAGE", " | ".$page);
    	}
    	$tpl3->parse('lists', 'list', true);
    }
    
    if (isset($this->next))    {
    	$tpl3->setVar("NEXT", "<a href=\"".$this->url(array('page' => $this->next))."\"> | Suivant &gt;</a>");
   	
    }else{
    	$tpl3->setVar("NEXT", "Suivant");
    } 
      
    $tpl3->parse( "MyOutput", "MyFileHandle");
    $tpl3->p("MyOutput") ; 
 } 

?>
