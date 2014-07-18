<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	private $_ObjSession;
	private $_LocSession;
	private $_view;	
	private $_layout;
	public $default_lang ='en';
	private $_config;
	
	/**
	 * 	Setup loader
	 */
	
	
	public function _initAutoloader()
	{ 
	
		require_once 'Zend/Loader/Autoloader.php';
		$autoloader=Zend_Loader_Autoloader::getInstance();
		$autoloader->setFallbackAutoloader(true);
		$this->_config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini',APPLICATION_ENV);
		Zend_Registry::set('config', $this->_config);  		
		$moduleLoader = new Zend_Application_Module_Autoloader(array( 		
			'namespace' => '', 
			'basePath'  => APPLICATION_PATH));
		return $moduleLoader;  	
		
	}
	
	/**
	 * 	Setup the object views with some stuff
	 */
	 
	protected function _initView()
	{
		if($_SERVER["HTTP_HOST"]=="www.markethon-po.cat"){
			$logoCss = '/public/styles/serv-template-mkt.css';
		}else{
			$logoCss = '/public/styles/serv-template.css';
		}
		$this->bootstrap('layout');
		$layout = $this->getResource('layout');
		$this->_view = $view = $layout->getView(); 
		$view->doctype('XHTML1_STRICT');
		$view->headMeta()->appendHttpEquiv('Content-Type', 'text/html;charset=utf-8');
		$view->headTitle('Maps activities life cycle');
		$view->headScript()->prependFile('http://code.jquery.com/jquery-1.11.0.min.js')
		     ->headScript()->appendFile('http://code.jquery.com/jquery-migrate-1.2.1.min.js'); 		
		$view->headLink()->appendStylesheet('/'.$this->_config->name->root.'/public/styles/ExpZF.css')
		->appendStylesheet('/'.$this->_config->name->root.$logoCss)
		->appendStylesheet('/'.$this->_config->name->root.'/public/styles/blue_bg.css')
		->appendStylesheet('/'.$this->_config->name->root.'/public/styles/menudrop.css')
		->appendStylesheet('/'.$this->_config->name->root.'/public/styles/testform.css');
		if($_SERVER["HTTP_HOST"]=="www.markethon-po.cat"){
			$view->title = "Markethon Perpignan 2014 - Inscriptions en ligne et gestion zones entreprises. ";
		}else{
			$view->title = "Ingénierie des ressources d'activités et des espaces, cartes et géographie. ";
		}
		
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
		    'ViewRenderer'
		);
		/**
		 * 	Here you change the view's suffix and also inside
		 *  your controller initialisation
		 */
		$viewRenderer->setViewSuffix('php');
		$viewRenderer->setView($view);        
		$this->_layout=Zend_Layout::startMvc(array('layoutPath' => APPLICATION_PATH .'/layouts/scripts/'));
		$this->_layout->setInflectorTarget('/:script.:suffix');
		$this->_layout->setViewSuffix('php');
		Zend_View_Helper_PaginationControl::setDefaultViewPartial('/'.$this->_config->name->root.'/applications/views/scripts/mngtb/pagination.php');
		return $view;
	}
		     
	/**
	 *    Setup Session 
	 *    Create your Zend_Session_SaveHandler_DbTable and
	 *    Set the save handler for Zend_Session
	 *    Start the session!
	 * 
	 */
	
	protected function _initSession()
	{
		$conn=$this->getPluginResource('db');
		$conn->isDefaultTableAdapter(true);
		$conn->init();
		$sess_config = array(
			'name'           => 'session',
			'primary'        => 'id',
			'modifiedColumn' => 'modified',
			'dataColumn'     => 'data',
			'lifetimeColumn' => 'lifetime'
		); 
		Zend_Session::setSaveHandler($o=new Zend_Session_SaveHandler_DbTable($sess_config));
		$tsess=Zend_Session::start(array('name' => 'PAPWEB_for_Every_one', 'gc_maxlifetime' => 86400, 'cookie_lifetime' =>86400));
		$this->_ObjSession = new Zend_Session_Namespace('experimental'); 
		$this->_ObjSession->setExpirationSeconds(86400);
		$this->_ObjSession->inival = 'ExistenceObj';
		$this->_LocSession = new Zend_Session_Namespace('language');
		$this->_LocSession->setExpirationSeconds(86400);
		$this->_LocSession->inival = 'existenceLoc';
		$this->_MsgSession = new Zend_Session_Namespace('Messages');		
		$this->_MsgSession->setExpirationSeconds(86400);
		$this->_MsgSession->inival = 'existenceMsg';
	}
	
	/**
	 *			Setup dojo environment
	 */
	 
	protected function _initDojo(){
		
		Zend_Dojo::enableView($this->_view); 
		$this->_view->dojo()
			 ->setLocalPath('/libdojo/dojo/dojo.js')
			 ->addStyleSheetModule('dijit.themes.tundra')
			 ->setDjConfigOption('usePlainJson', true) 
			 ->setDjConfigOption('parseOnLoad', true)
			 ->disable();		
	}
	/**
	 *    Setup cache 
	 *    Define the cache parameters
	 *  
	 */
	
	protected function _initCache()
	{
		$frontendOptions = array( 'lifetime' => 7200,   'automatic_serialization' => true );
		$backendOptions = array('cache_dir' => $_SERVER['DOCUMENT_ROOT'].'/'.$this->_config->name->root.'/application/cache/');
		$cache = Zend_Cache::factory('Core',
					 'File',
					 $frontendOptions,
				     $backendOptions);
	Zend_Registry::set('cache',	$cache)	;
	}
	
	public function _initVarSession(){
		if(isset( $this->_LocSession->Mylang)){
			$sel_lang= $this->_LocSession->Mylang;
		}
	}
	
	public function _initPerm(){
		/**
		 *	Object list
		 */
		$create=$read=$update=$delete=$publish=$list=array();
		$listobject=array();
		$valo = scandir($_SERVER['DOCUMENT_ROOT'].'/'.$this->_config->name->root.'/application/forms/'); 
		 $i=0;
		 foreach($valo as $namefile) {
			 if ((substr($namefile, 0, 1)!=='t' && substr($namefile, 0,1)!=='f')||substr($namefile, strlen($namefile)-1, 1)=='~'){
			 }else{
			 	 if($i<10){
			 	 	 $j="0".(string)$i;
			 	 }else{
			 	 	$j="".(string)$i; 
			 	 }
				 $listobject[$j]= substr($namefile, 0, strlen($namefile)-4);
			 }
			 $i++;
		 }
		 
		 /**
		  *	Action for each role
		  * 	For fun not used
		  */
		$dbtemp = Zend_Db::factory($this->_config->resources->db->adapter, $this->_config->resources->db->params->toArray());
		$dbtemp->setFetchMode(Zend_Db::FETCH_OBJ);
		$query = 'SELECT id, entitle_smul FROM tRole ';
		$result = $dbtemp->fetchAll($query);	
		foreach($result as $key => $value){
			if (strpos($value->entitle_smul, 'create')!==false){
					$create[]= $value->id;
			}
			if (strpos($value->entitle_smul, 'read')!==false){
					$read[]= $value->id; 
			}
			if (strpos($value->entitle_smul, 'update')!==false){
					$update[]= $value->id;
			}
			if (strpos($value->entitle_smul, 'delete')!==false){
					$delete[]= $value->id; 
			}
			if (strpos($value->entitle_smul, 'publish')!==false){
					$publish[]= $value->id; 
			}
			if (strpos($value->entitle_smul,'list')!==false){
					$list[]= $value->id;
			}
			
    		}
    		$perm_action=Array($create, $read, $update, $delete, $publish, $list);
		Zend_Registry::set('dataperm', $listobject);
	}
	
	/**
	 *	Error message on perms object definition
	 */
	
	public function _initErroMsg(){
		$object = array(
				"fCsvup" => " le formulaire d'importation CSV",
				"fAffectation" =>  " le formulaire d'affectation des zones",
				"tFormtest" => " le formulaire de test",
				"fMapslocation" => " le formulaire de localisation",
				"tAppros" => " la table des approvisionnements agricoles",
				"tArticles" => "la table des articles d'informations",
				"tChamp" => " la table des vergers",
				"tEntrepriseslr" => " la table des entreprises du Languedoc-Roussillon",
				"tEntreprisespo" => " la table des entreprises des Pyrénées Orientales",
				"tGroup" => " la table des groupes d'utilisateurs",
				"tJobseeker" => " la table des chercheurs d'emploi",
				"tMoeuvre" => " la table de la main doeuvre agricole",
				"tNomenclatures" => " la table des nomenclatures types",
				"tOrganization" => " la table des propriétaires agricoles",
				"tRole" => " la table des rôles des utilisateurs",
				"tTools" => " la table des outillages",
				"tUser" => " la table des utilisateurs",
				"tZones" => " la table des zones d'activités");
		$codop = array(
				"create" => " de créer un nouvel enregistrement dans",
				"write" => " d'écrire dans",
				"publish" => " de publier",
				"list" => " de lister",
				"update" => " de mettre à jour",
				"read" => " de lire ",
				"delete" => " de supprimer un enregistrement dans");
		Zend_Registry::set('MsgErrorPerm', array($codop, $object));
	}
	
	
	static function getObjSession()
	{
		return $this->_ObjSession;
	}	
	
	
	/**
	 *	Setup I18n & L12n and language files access
	 */
	
	public function initLocalization()	{
		
		if(!isset($this->_LocSession->Mylang) || (isset($this->_LocSession->Mylang) && ($this->_LocSession->Mylang!=$this->preferedLanguage()) ))
		{ 
			$val_lang=$this->preferedLanguage();
			$this->_LocSession->Mylang=$sel_lang=$val_lang[0];	
		}else{ 
			$sel_lang= $this->_LocSession->Mylang;
		}    
		
		$translator = new Zend_Translate('gettext',$this->_config->path->web->app.'application/languages/'.$sel_lang.'.mo', $sel_lang);
		$translator ->addTranslation($this->_config->path->web->app.'application/languages/'.$sel_lang.'.mo', $sel_lang);
	
		$writer = new Zend_Log_Writer_Stream($_SERVER['DOCUMENT_ROOT'].'/'.$this->_config->name->root.'/application/languages/toTranslate/LangFile.log');
		$log    = new Zend_Log($writer);
	
		/**
		 * 	Log journal attached to translator
		 */
		$translator->setOptions(array('log' => $log,'logMessage' => "Missing '%message%' within locale '%locale%'",'logUntranslated' => true));
		Zend_Registry::set('translator', $translator);
		Zend_Form::setDefaultTranslator($translator);
		Zend_Registry::set('tloc', strtolower($sel_lang)); 
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
	 if (preg_match('/(en|fr)-?.*/', $curLang[0], $reg)) {
	     $lang = $reg[1];
	    break;
	}
	}
	return $lang;
	}
	
	/**
	 * 	Method to sort browser language preference from client navigator
	 *  Safari, Mozilla, Firefox, IE
	 *  Experimental limited to the UE languages
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
 
 	/**
 	 *	Active Templates engine 
 	 */
 	 
 	 public function getTemplates()
 	 {
 	 	require_once 'papweb/lib_bsh/PHPLIB.php';
 		$path_to_tpl=$this->_config->path->web->app.'public/templates';
 		$tpl1  = new HTML_Template_PHPLIB($path_to_tpl , "keep");	
 		$tpl2  = new HTML_Template_PHPLIB($path_to_tpl , "keep"); 	 	
 		$tpl3  = new HTML_Template_PHPLIB($path_to_tpl , "keep"); 	 	
 		Zend_Registry::set('tpl1', $tpl1);
 		Zend_Registry::set('tpl2', $tpl2);
 		Zend_Registry::set('tpl3', $tpl3);
 	 }
 
	
	/**
	 * 	Method to provide client country through IP identification
	 *  
	 *  @return String Country ID
	 */
	
	
	public function getConnectors(){
		$db1 = Zend_Db::factory($this->_config->resources->db->adapter, $this->_config->resources->db->params->toArray());
		Zend_Db_Table::setDefaultAdapter($db1);
		$db2 = Zend_Db::factory($this->_config->resources->db->adapter, $this->_config->resources->db->params->toArray());
		Zend_Registry::set('db1', $db1);
		Zend_Registry::set('db2', $db2);
		$mysqldirect = new mysqli("localhost", "manzf", "ReH46PeQ", "agrotechorg4");
		Zend_Registry::set('mysqldirect', $mysqldirect);

	}


}

