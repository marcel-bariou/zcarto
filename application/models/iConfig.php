<?php
/*
 * Created on Apr 27, 2009
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
 *mbariou
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 require_once("Zend/Config/Ini.php");
 
 /**
 * Class Localisation and internationalisation configuration.
 *
 * @category   PapWeb
 * @author 	Marcel Bariou
 * @package    Cruid
 * @subpackage $specific_Table
 * @copyright  Copyright (c) 2005-2007 Brasnah sarl. (http://www.brasnah.com)
 * @license    http://www.brasnah.com/lgpl.txt     New LGPL License
 */

class iConfig extends Zend_Config_Ini
{
	
	
  public $default_lang ='en';
 

	/**
 	 * 	Class constructor
 	 *  @param string $path Path to ini file
 	 *  @return string $section Section in ini File
 	 */
 
  public function __construct($path, $section) 
  {
  	parent::__construct($path, $section);
  }

	/**
 	 * 	Simplified method to detect browser language
 	 *  @param string $defaultlang
 	 *  @return string detected language
 	 */
 
  public function detectLanguage($defaultlang = 'en') 
  {
    $langlist = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
    $lang = $defaultlang;
  	foreach($langlist as $curLang) {
         $curLang = explode(';', $curLang);
         /* use regular expression for language detection */
         if (preg_match('/(en|fr)-?.*/', $curLang[0], $reg)) {
             $lang = $reg[1];
            break;
        }
     }
     return $lang;
  }

	/**
 	 * 	Method to sort browser language preference
 	 *  
 	 *  @return Array Ordered index prefered language
 	 */
 
	
  	public function preferedLanguage()
 	{
		if(preg_match('/AppleWebKit/', $_SERVER['HTTP_USER_AGENT'])==1){ 	
			if(preg_match('/AppleWebKit/', $_SERVER['HTTP_USER_AGENT'])==1){
				$agent='wbkt'; 		
			}
		}elseif(preg_match('/Mozilla/', $_SERVER['HTTP_USER_AGENT'])==1 || preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])==1){
			$agent='moz'; 
		}
	
		if($agent=='wbkt'){
			preg_match('/([a-zA-Z-,]*)*/', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $matches);
			$pref_lang=explode  ( ','  , $matches[0]  , 7 );
			$i=0;
			while(isset($pref_lang[$i])){	
				$sort_lng[$i]=$pref_lang[$i];
				$i++;
			}
		}
	
		if($agent=='moz'){
			preg_match('/([a-zA-Z,;-]*q=[0-9\.]*,)*/', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $matches);
			$pref_lang=explode  ( ','  , $matches[0]  , 7 );
			$i=0;
			while(isset($pref_lang[$i])){	 	
				preg_match('/(([a-zA-Z-]*)(;q=[0-9\.]*)?)/', $pref_lang[$i], $val_lang);
				$sort_lng[$i]=$val_lang[2];
				$i++;
			} 	
		}
	
		$lang_vector=Array("fr", "ca", "cs", "nl", "en", "et", "fi", "de", "mk", "gd", "ka", "el", "hu", "is", "it", "lv", "lt", "no", "pl", "pt", "ro", "ru", "sv", "es", "ls", "sk", "sv", "ar" );
		$i=0;
		while(isset( $sort_lng[$i])){
			$k=0;
			
			while(isset( $lang_vector[$k]) && preg_match('/'.$lang_vector[$k].'/', $sort_lng[$i])==0){
				$k++;
			}
			if(!isset($lang_vector[$k])){ 	
			}else{
				$norm_lng[]=$lang_vector[$k];
			}
			$i++; 	
		}
		if(!isset($norm_lng) || count($norm_lng)==0){
			$norm_lng[0]=$this->default_lang ;
		} 
		return $norm_lng;
 	}	
 
 
 
 
	/**
 	 * 	Method to provide client country through IP identification
 	 *  
 	 *  @return String Country ID
 	 */

 
 	public function clientCountry()
 	{
 		 $client= new SoapClient("http://www.webscope.org/WSmanual/WS_Services/Wsdl_dept/QuoteService.wsdl");
  		try {   
	  		//$sel_lang = $lang_loc = strtolower($client->getCountryCode($_SERVER["REMOTE_ADDR"]));
	  		$sel_lang = $lang_loc = strtolower($client->getCountryCode("213.41.181.45"));
  		} catch (SoapFault $exception) {
	  		echo $exception;      
  		}
  
  		if ($sel_lang !='fr'){
	  		$sel_lang='en';
  		}
 	}
 
	
}
 
?>
