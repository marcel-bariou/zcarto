<?php
/*
 * Created on Jun 4, 2012
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
 * @copyright  Copyright (c) 2005-2012 Brasnah sarl (http://www.brasnah.com)
 * @license    http://www.brasnah.com/lpgl.txt    
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class Form_View_Helper_ExtractPath extends Zend_View_Helper_Abstract
{ 
	private $_objForm;
	private $_config;
	private $_codeArticle = array();
	public $arrayPath=Array();



    
    /**
     *	Build automatically Path query to each element ofv the XML tree  
     *	$attrib not used
     */
    
  
    public function xmlRecurse($xmlObj,$depth,$xpath) {
	  $i=0;
	  foreach($xmlObj->children() as $child) {
	    array_push ($this->arrayPath, $xpath."/".$child->getName());
	    foreach($child->attributes() as $k=>$v){
		$attrib = "Attrib".str_repeat('-',$depth).">".$k." = ".$v."\n";
	    }
	    $this->xmlRecurse($child,$depth+1,$xpath.'/'.$child->getName());
	  }
	}


	
    /**
     *	Exploration de l'arbre XML de nomenclature 
     *	Afin de rechercher les codes Articles
     *	Pour chaque code article de la nomenclature est extrait l'enregistrement correspondant de la table articles/tAppros
     *	Cet enregistrement contient entre autres, le chapitre de devis (Son index numérique), dans lequel est coté cet articles 
     *
     */
	 
    public function extractPath($id, $className, $table)    
    { 
    	   $this->_config = Zend_Registry::get('config');
           $records = new $className($table);
	    if ($id > 0) {
		$row=$records->getRecord($id);
            	$records = new $className('tNomenclature');
            	$row=$records->getRecord($row['FKid_nomeXml']); 
            	//$tabpath=explode('<br />', $row['xmlpath']); //var_dump($tabpath);print "<BR/><BR/>";
            	$tabpath=preg_split("/<br \/>/", $row['xmlpath']);
		$torepalace =array('&lt;![[CDATA', ']]&gt;', '<br />','&lt;', '&gt;');
		$replacewith=array('', '', '' ,'<',  '>');
		$output  = str_replace($torepalace , $replacewith, $row['structQuote']);
		$dom = new Zend_Dom_Query();
		$dom->setDocumentXml($output);
            	
            	/**
            	 *	Build access path to element
            	 *	And place it in public $arrayPath as result
            	 */ 
            	$xmlfile = new SimpleXMLElement($output);              	
            	$this->xmlRecurse($xmlfile,0, '/paragrele');

		$kart=0;
		$messageError="";
		
		$className='Model_DbTable_GenTb' ;
		$approRecords = new $className('tAppros');
		
		/**
		 *	Récupération, de la totalité de l'entreistrement de chaque article 
		 *	Storage inside $this->_codeArticle array
		 *
		 */
		
		while(isset($this->arrayPath[$kart])){
			$res= $dom->queryXpath($this->arrayPath[$kart]); 
			foreach ($res as $p){
				if(preg_match("/[<> \/]/",$p->nodeValue )==0){
					$rowArt = $approRecords->fetchRow("codeArticle = '" . $p->nodeValue."'");
					if (is_object($rowArt)){
						$this->_codeArticle[] =$rowArt->toArray();
					}else{					
						$messageError .=  "Absence de => ".$p->nodeValue." en table appros<BR/>";
						die($messageError);
					}
				}
			}
			$kart++;
		}
		
		return $this->_codeArticle;
		
		}else{
			return false;
		}
	    }
       }
