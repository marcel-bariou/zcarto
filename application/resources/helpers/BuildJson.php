<?php
/*
 * Created on Jun 4, 2009
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
 class Form_View_Helper_BuildJson extends Zend_View_Helper_Abstract
{ 
	private $_res;
	 
    public function buildJson($regs, $tab, $vectName)    
    { 
		$countList=count($regs);
		if(count($vectName)==1)
		{
			$naming0= '$reg->$vectName[0]';
		}elseif(count($vectName)==2){
			$naming0= '$reg->$vectName[0].\' \'.$reg->$vectName[1]';
		}else{
			//Raise an exception
		}
		
		$jsonString='{"identifier" : "id" , "label" : "username",  "items": [';
		$i=0;
		foreach($regs as $reg){
			$i++;
			//if(!ereg('xxx', $reg->$vectName[0])){
			if(preg_match('/xxx/', $reg->$vectName[0]) !=1){
				eval("\$naming =$naming0;");
				if($i<$countList){		
					$jsonString .= '{"id": "'.(string)$reg->id.'", "username": "'.$naming.' - '.$reg->id.'"},';
				}else{
					$jsonString .= '{"id": "'.(string)$reg->id.'", "username": "'.$naming.' - '.$reg->id.'"}';
				}
			}
		}
		$jsonString .=']}';
		$handle = fopen("listJson/".$tab.".json", "w+");
		$this->fwrite_stream($handle, $jsonString);
	}
	
	private function fwrite_stream($fp, $string) {
        $fwrite = fwrite($fp, $string, strlen($string));        
    }
    
    	
}
?>
