<?php
/*
 * Created on June 04, 2009
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


class MngtbController extends Zend_Controller_Action
{
	private $classTable;
	private $classForm;
	private $params;
	private $_config;
	private $_infoTitle;
	private $_result=array();
	
	private $_docPdf;	// Object doc Pdf
	private $_pagePdf;	// Object page Pdf
	private $_linePdf;	// Current line in Pdf document
	private $_nbPagePdf;	// Total number of Object page Pdf
	private $_numPagePdf;	// Current number of Object page Pdf
	private $_refdevis;	// Référence devis
	
	
	public $_redirector = null;
	protected $qrang = 0;
	protected $qrangTrans = 0;
	protected $qpotVertical=0;
	protected $qpotExtrem = 0;
	protected $qLongueurFilet=0;
	protected $qLongueurCable=0;
	public $arrayPath=Array();
	public $arraylonlat=Array();
	public $arraylonlatmoy=Array();
	

    public function init()
    {
        /* Initialize action controller here */
    	
        $this->_config = Zend_Registry::get('config');    	
        $tlang = Zend_Registry::get('tloc');
        if($tlang=='fr'){
        	$this->view->langue=" French";
        }
         if($tlang=='en'){
        	$this->view->langue=" English";
        }
        
        
        $this->params=$this->_request->getParams();
        if((null==$this->params['t']) && (null==$this->params['notRectangleStandard']) && ('noperm'!==$this->params['action']))
        { 
        	$this->_redirect('/');
    	}
    	
    	if(isset($this->params['tzone'])){
	      Zend_Registry::set('tzone', $this->params['tzone']);
	}elseif(isset($_POST['tzone'])){
	  Zend_Registry::set('tzone', $_POST['tzone']);
	  $this->params['tzone']=$_POST['tzone'];
	}else{
	  Zend_Registry::set('tzone', 0);
	  $this->params['tzone']=0;
	}
	
	

        $this->view->cache=Zend_Registry::get('cache');
        if(isset($this->params['t'])){
		switch($this->params['t']){
			case 'tUser' :
				$this->_infoTitle = " table des utilisateurs et clients. ";
				break;
			case 'tRole' :
				$this->_infoTitle = " table des rôles utilisateurs et clients. ";
				break;
			case 'tGroup' :
				$this->_infoTitle = " table des groupes d\'utilisateurs et clients. ";
				break;
			case 'tChamp' :
				$this->_infoTitle = " table des terres agricoles. ";
				break;
			case 'tUser' :
				$this->_infoTitle = " table des utilisateurs et client. ";
				break;
			case 'tArticles' :
				$this->_infoTitle = " table des contenus. ";			
				break;
			case 'tOrganization' :
				$this->_infoTitle = " table des organisations. ";
				break;
			case 'tEntreprisespo' :				
				if($this->params['action']=='alphalist'){
					$this->_infoTitle = " Statistique des mots qualifiant les activités des Pyrénées Orientales";
				}else{
					$this->_infoTitle = " table des Entreprises des P.O. ";
				}
				break;
			case 'tEntrepriseslr' :
				$this->_infoTitle = " table des Entreprises du Languedoc Roussillon. ";
				break;
			case 'tAppros' :
				 $this->_infoTitle= " table des pièces, appros. et unités d'oeuvre ";
				break;
			case 'tMoeuvre' :
				$this->_infoTitle = " table de main d'oeuvre. ";
				break;
			case 'tTools' :
				$this->_infoTitle = " table des outillages. ";
				break;
			case 'tNomenclature' :
				$this->_infoTitle = " table des nomenclatures types. ";
				break;
			case 'tJobseeker' :
				$this->_infoTitle = " table des Chercheurs d'emploi. ";
				break;
			case 'tJoboffer' :
				$this->_infoTitle = " table des offres d'emploi. ";
				break;
			case 'tZones' :
				$this->_infoTitle = " table des Zones d'activités. ";
				break;
			case 'tClientetc' :
				$this->_infoTitle = " table des Clients ECO-TECH CERAM. ";
				break;
			case 'tTacheetc' :
				$this->_infoTitle = " table des compétences ECO-TECH CERAM. ";
				break;
			case 'tProjectetc' :
				$this->_infoTitle =  " table des Projets ECO-TECH CERAM. ";
				break;
			case 'tDevisetc' :
				$this->_infoTitle = " table des devis de Projets ECO-TECH CERAM. ";
				break;
			case 'tLotchapetc' :
				$this->_infoTitle = " table des lots et chapitres Projets ECO-TECH CERAM. ";
				break;
			case 'fFormtest' :
				$this->_infoTitle = " Test de formulaire. ";
				break;
			case 'fAffectation' :
				$this->_infoTitle = " Affectation de Zones. ";
				break;
			case 'fCsvup' :
				$this->_infoTitle = " Chargement de table par fichier csv. ";
				break;
			case 'error' :
				$this->_infoTitle = " Opération non autorisée. ";
				break;
			default :			 	
			 	$this->params['t']='tUser';
			 	$this->_infoTitle = " table des utilisateurs et client. ";
				break;
		 } 
	}
	
	
	
        /**
         * 	Setup the path to specific helpers, activation of generic table
         *  	Class form definition
         *  	Indexer/Search engine activation 
         *	Perms management  
         */   

        $this->view->addHelperPath($_SERVER["DOCUMENT_ROOT"].'/'.$this->_config->name->root.'/application/resources/helpers', 'Form_View_Helper');
        $this->_redirector = $this->_helper->getHelper('Redirector');
        
        $this->classTable ='Model_DbTable_GenTb';
        $this->classForm = 'Form_'.$this->params['t'] ; 
        $this->view->tableName=$this->params['t'];
        if(isset($this->params['sancout'])){
        		$this->view->sancout="on";
        }
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) 
		{
		    $this->view->user = Zend_Auth::getInstance()->getIdentity();		    
		}
    }

    public function indexAction()
    {
    	/**
    	 * 	Exemple for a suffix change
    	 * 	Or a layout change
    	 */
 	
        $this->_helper->viewRenderer->setViewSuffix('php');
        $this->_helper->layout->setlayout('layout');
        $this->view->title .= "liste de la  ".$this->_infoTitle;
        $this->view->headTitle($this->view->title, 'PREPEND');
        $this->view->validPerm($this, $this->params['t'], 'list');
        $className=$this->classForm ;
        $form = new $className(); 
        $this->view->forms = $form;
        $this->view->display=$form->display;
        $className=$this->classTable ;
        $table = new $className($this->params['t']);
        $clause='';
        $newRequest = False;
        $prevclause= new Zend_Session_Namespace('experimental');
        $this->view->records='';
        if((null != $prevclause->prevtable && $prevclause->prevtable!= '') && ($prevclause->prevtable!=$this->params['t'])){
        	$prevclause->clauselst='';        	
        }
        $prevclause->prevtable=$this->params['t'];
        
        //print $prevclause->clauselst." => ".$prevclause->prevtable." => ".$this->params['t'];   die();
        /**
          *	Si Requête de recherche => construction de la requête et Mise en cache
           *    Détectée sur la présence du bouton rechercher
          */
        if($this->params['t']!='tUser' and $this->params['t']!= 'tJobseeker'){
        	$form->orderby = "c_name";
        }else{
        	$form->orderby = "c_lastname";
        }
        if(isset($_POST['SUBMITSEARCH'])){ 
        	$newRequest = True;	
		if (isset($_POST['searchName']) && $_POST['searchName']!= 'Rechercher' ){		    
		    $clause="c_name LIKE '%".$_POST['searchName']."%'";
		    if (isset($_POST['searchfield']) && $_POST['fieldtab']!= 'nofield' && $_POST['searchfield']!= 'Rechercher' ){
			     $clause .=" AND ".$_POST['fieldtab']." LIKE '%".$_POST['searchfield']."%'";
		    }
		}elseif (isset($_POST['searchfield']) && $_POST['fieldtab']!= 'nofield' && $_POST['searchfield']!= 'Rechercher' ){
			     $clause .=$_POST['fieldtab']." LIKE '%".$_POST['searchfield']."%'";
		}else{
		    throw new Exception('Anomalie de remplissage des champs de recherche, veuillez  S.V.P.respecter la procédure de remplissage de ces champs');
		}
		
		$prevclause->clauselst = $clause;
		$this->view->records = $table->fetchAll(
					$table->select()
					->where($clause)
					->order($form->orderby));		
	}elseif(isset($this->params['fkidzone'])){
		$clause = "zones LIKE '%".$this->params['fkidzone']."%'";
		$prevclause->clauselst = $clause;
		$this->view->records = $table->fetchAll(
					$table->select()
					->where($clause)
					->order($form->orderby));		
	}elseif(null != $prevclause->clauselst && $prevclause->clauselst!= ''){
		$clause = $prevclause->clauselst;
		//die($clause." => ".$form->orderby);
		$this->view->records = $table->fetchAll(
					$table->select()
					->where($clause)
					->order($form->orderby));		
	}else{
		 $this->view->records = $table->fetchAll(
				  	 $table->select()
				  	       ->order($form->orderby));	
	}
	$this->view->flagzone= preg_match("/zone/", $clause);
	if($this->view->flagzone!==1){
		$this->view->flagzone= preg_match("/comment/", $clause);
	} //die($clause);
	
	if ($this->params['t']=='tEntreprisespo'){
		if(preg_match("/zone/", $clause)===1){
			 $pattern = '/zones LIKE %([0-9]*)%/';
			 $klause = str_replace("'", "", $clause);	 
			 preg_match($pattern, $klause, $matches) ;
			 $this->view->texttitlePage = "Entreprises -> ZONE => ".$form->getLabelForm(24,2)[(int)$matches[1]];
		 }elseif(preg_match("/comment/", $clause)===1){
			 $pattern = '/comments LIKE %([a-zA-Z0-9âàèéêîôùû -]*)%/';
			 $klause = str_replace("'", "", $clause);	 
			 preg_match($pattern, $klause, $matches) ;
			 $this->view->texttitlePage = "Liste entreprises pour requête [activité = ".$matches[1]."]";
		 }elseif(null!=$clause && $clause!=''){
		 	$this->view->texttitlePage = "Liste des entreprises respectant la clause ".$clause; 
		 }else{
			 $this->view->texttitlePage = "Liste complete de toutes les entreprises "; 
		 }
	}
	
	
        $this->view->paginator= Zend_Paginator::factory($this->view->records);
    	$this->view->paginator->setItemCountPerPage(50);
    	$this->view->paginator->setPageRange(8);
    	if(!isset($this->params['page'])){$valpage=1;}else{ $valpage = $this->params['page'];} 
        $this->view->paginator->setCurrentPageNumber($this->_getParam('page'));
        Zend_Registry::set('page', $valpage);
        
    }


    public function georefAction()
    {
    	/**
    	 * 	Exemple for a suffix change
    	 * 	Or a layout change
    	 */
 	 
        $this->_helper->viewRenderer->setViewSuffix('php');
        $this->_helper->layout->setlayout('georeflayout');
        $this->view->title .= "Georerencement  ".$this->_infoTitle;
        $this->view->headTitle($this->view->title, 'PREPEND');
        $this->view->validPerm($this, $this->params['t'], 'list');
        $this->view->latlongfield=Array($this->params['latit'], $this->params['longit']);
        $this->view->TableName=$this->params['t'];
        $this->view->idRef=$this->params['id'];
        $this->view->adresse=base64_decode($this->params['adresse']);

    }

     /**
      *   	Reminder  if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {}
      *		It is a way to detect an AJAX processing GET method, but not necessary here !
      */
      
     public function updlatlongAction()
    {
    	    $dbdirect= Zend_Registry::get('mysqldirect');
    	    $this->_helper->layout->disableLayout();
    	    $this->_helper->viewRenderer->setNoRender(TRUE);
    	    $sql = 'UPDATE `tEntreprisespo` set `gpsok`= 2, `latitude` ='. $this->params['latit'].', `longitude` = '.$this->params['longi'].'  where `id` = '.$this->params['id'];
    	    $t=$dbdirect->query($sql);    	    	
    	    print "Mise à jour correcte de la base de données, latitude => ".$this->params['latit'].", longitude => ".$this->params['longi'];
    }
    
    public function testformAction(){
    	    
    	 /**
    	 * 	Exemple for a suffix change
    	 * 	Or a layout change
    	 */
    	 
        $this->_helper->viewRenderer->setViewSuffix('php');
        $this->_helper->layout->setlayout('layout');
        $this->view->title .= "Ajout en  ".$this->_infoTitle;
        $this->view->headTitle($this->view->title, 'PREPEND');
        
        /**
         * 	Call to GenForm extension
         */
         
	$className=$this->classForm ;
	$form = new $className();
	$this->view->buildForm($form);
        $form->submit->setLabel('Soumettre');
        $form->submit->setName($className);
        $this->view->form = $form;
        
        if ($this->getRequest()->isPost()) {
        
		if(isset($_POST['Annuler'])){
			$this->_redirect('/mngtb/index/t/'.$this->params['t']);
		}
		
                $formData = $this->getRequest()->getPost();
                if ($form->isValid($formData)) {
            	foreach($form->listElems as $vect)
            		{
            			$this->_result[] = $this->view->form->getValue($vect['name']); 
            			$this->view->result = $this->_result;
            		
            		}            
                
                if(isset($_FILES['filename'])){
        		$data['mime']=$_FILES['filename']['type'];						
			$data['filename']=$_FILES['filename']['name'];
        	}  
    			              
            } else {
                $form->populate($formData);
            }
        } 
    	    
    }
 
    
      
    public function alphalistAction(){
    	    
    	 /**
    	 * 	Exemple for a suffix change
    	 * 	Or a layout change
    	 */
    	 
        $this->_helper->viewRenderer->setViewSuffix('php');
        $this->_helper->layout->setlayout('layout');
        $this->view->title .= "Ajout en  ".$this->_infoTitle;
        $this->view->headTitle($this->view->title, 'PREPEND');
        $this->view->validPerm($this, $this->params['t'], 'write');         
        $query = 'SELECT id, comments FROM '.$this->params['t'];
        $db1= Zend_Registry::get('db1');
        $db1->setFetchMode(Zend_Db::FETCH_OBJ);
        $res = $db1->fetchAll($query);
        $this->view->tbMots=array();
        $this->view->tbOrderMots=array();
	foreach($res as $key => $value){
		$mots=array();
		if(preg_match("/,/", $res[$key]->comments)){
			$mots =explode(",", $res[$key]->comments);				
		}elseif(preg_match("/-/", $res[$key]->comments)){
			$mots =explode("-", $res[$key]->comments);
		}else{
			$mots[0]= $res[$key]->comments;
		}
		foreach($mots as $k => $v){
			$v = trim($v);
			if (isset($this->view->tbMots[$v])){
				$this->view->tbMots[$v] += 1;	
					
			}else{
				$this->view->tbMots[$v] = 1;
			}
		}		
	}
	
	$mots=array_keys($this->view->tbMots);	
	asort($mots);	
	foreach($mots as $k => $v){
		$this->view->tbOrderMots[]=array($v, $this->view->tbMots[$v]);
	}
	
    }
    
    public function affectationAction(){
    	    
    	 /**
    	 * 	Exemple for a suffix change
    	 * 	Or a layout change
    	 */
    	 
        $this->_helper->viewRenderer->setViewSuffix('php');
        $this->_helper->layout->setlayout('layout');
        $this->view->title .= "Ajout en  ".$this->_infoTitle;
        $this->view->headTitle($this->view->title, 'PREPEND');
        $this->view->validPerm($this, $this->params['t'], 'write');       
        /**
         * 	Call to GenForm extension
         */
         
	$className=$this->classForm ;
	$form = new $className();
	$this->view->buildForm($form,"", 'fAffectation');
        $form->submit->setLabel('Soumettre');
        $form->submit->setName($className);
        $this->view->form = $form;
        $dbdirect= Zend_Registry::get('mysqldirect');
        
        /**
        * 	Start here from POST
        */
        if ($this->getRequest()->isPost()) {          	
		if(isset($_POST['Annuler'])){
			$this->_redirect('/mngtb/affectation/t/fAffectation');
		}		
                $formData = $this->getRequest()->getPost(); 
                if(isset($formData['respzone_smul'])){
                	
                	$query = 'SELECT id, c_fname, c_lastname, City FROM tUser where `id` = '.$this->params['id'];
                	$db1= Zend_Registry::get('db1');
                	$db1->setFetchMode(Zend_Db::FETCH_OBJ);
                	$res = $db1->fetchAll($query);
                	$this->view->form->affected=Array();
                	foreach($res as $key => $value){            		
                		$identity=$res[$key]->c_lastname." ".$res[$key]->c_fname." ".$res[$key]->City."-". $res[$key]->id;
                	} 
                	
                	foreach($formData['respzone_smul'] as $ii){
                		$sql = 'UPDATE `tZones` set `enverif`= 1, FKid_Client="'.$identity.'" where `id` = '.$ii; //print $sql."<BR/>";
                		$t=$dbdirect->query($sql); 
                		$sql = 'UPDATE `tEntreprisespo` set `refcheck`="'.$identity.'"   where `zones` = '.$ii;
                		$result = $db1->query($sql);
                	}//die();
                }
                if ($form->isValid($formData)) {
                	if(isset($formData['respzone_smul'])){
                		$val = implode(',', $formData['respzone_smul']);                	
                		$sql = 'UPDATE `tUser` set `respzone_smul`= "'.$val.'"  where `id` = '.$this->params['id'];
                	}else{
                		$sql = 'UPDATE `tUser` set `respzone_smul`= ""  where `id` = '.$this->params['id'];
                	}
                	$t=$dbdirect->query($sql); 
            	} else {
            		$form->populate($formData);
            	}
    	
            	$query = 'SELECT id, c_fname, c_lastname, respzone_smul FROM tUser ';
            	$db1= Zend_Registry::get('db1');
            	$db1->setFetchMode(Zend_Db::FETCH_OBJ);
            	$res = $db1->fetchAll($query);
            	$this->view->form->affected=Array();
            	foreach($res as $key => $value){            		
            		$this->view->form->affected []=array( $res[$key]->c_fname." ".$res[$key]->c_lastname, $res[$key]->respzone_smul, $res[$key]->id);
            	} 
            	$this->view->result =true;
         
        /**
         * 	Start here from link Edition
         */
        }elseif($this->params['t']=='fAffectation' && isset($this->params['id'])){
            $id = $this->_request->getParam('id', 0);
             
            if ($id > 0) {
            	    
            	    /*
            	    $query = 'SELECT id, c_name FROM tZones where enverif=0';
            	    $db1->setFetchMode(Zend_Db::FETCH_OBJ);
            	    $result = $db1->fetchAll($query);
            	    foreach($result as $key => $value){
            	    	    $options1[$result[$key]->id]= $result[$key]->c_name;  
            	    }
           	    */
           	    
            	    $query = 'SELECT respzone_smul FROM tUser where id='.$id;
            	    $db1= Zend_Registry::get('db1');
            	    $db1->setFetchMode(Zend_Db::FETCH_OBJ);
            	    $result = $db1->fetchAll($query);
            	    foreach($result as $key => $value){
            	    	    $list_array=explode(",", $result[$key]->respzone_smul);
            	    }
            	    
            	    if($list_array[0]!=""){
            	    	foreach($list_array as $ii){
            	    	    $query = 'SELECT id, c_name FROM tZones where id='.$ii;
            	    	    $db1->setFetchMode(Zend_Db::FETCH_OBJ);
            	    	    $result = $db1->fetchAll($query);
            	    	    foreach($result as $key => $value){
            	    	    	    $form->_labelForm[0][2][$result[$key]->id]= $result[$key]->c_name;
            	    	    }
            	    	    $identity="Nobody Personne Tautavel - 999";
            	    	    $sql = 'UPDATE `tZones` set `enverif`= 0,  FKid_Client="'.$identity.'"   where `id` = '.$ii;
            	    	    $result = $db1->query($sql);
            	    	    $sql = 'UPDATE `tEntreprisespo` set `refcheck`="'.$identity.'"   where `zones` = '.$ii;
            	    	    $result = $db1->query($sql);
            	    	}
            	    }
            	    $form = new $className();
            	    $this->view->buildForm($form,"", 'fAffectation');
       	     	    $form->submit->setLabel('Soumettre');
       	     	    $form->submit->setName($className);
       	     	    $this->view->form = $form;
            	    $row=array('user' => $id, 'respzone_smul'=> $list_array, 'id' => $id);
            	    $form->populate($row);                
            }
            
        /**
         * 	Start here for list only
         */
            
        } else{
        	$this->view->result =true;
        }
    	    
    }
   
    public function csvupAction(){
    	    
    	 /**
    	 * 	Exemple for a suffix change
    	 * 	Or a layout change
    	 */
    	 
        $this->_helper->viewRenderer->setViewSuffix('php');
        $this->_helper->layout->setlayout('layout');
        $this->view->title .= "Ajout en  ".$this->_infoTitle;
        $this->view->headTitle($this->view->title, 'PREPEND');
        $this->view->validPerm($this, $this->params['t'], 'write');

        /**
         * 	Call to GenForm extension
         */
         
	$className=$this->classForm ;
	$form = new $className();	
	$this->view->buildForm($form);
        $form->submit->setLabel('Soumettre');
        $form->submit->setName($className);
        $this->view->form = $form;
        
        if ($this->getRequest()->isPost()) {
		if(isset($_POST['Annuler'])){
			$this->_redirect('/mngtb/index/t/'.$this->params['t']);
		}
		
                $formData = $this->getRequest()->getPost();
                if ($form->isValid($formData)) {
            	foreach($form->listElems as $vect)
            		{
            			$this->_result[] = $this->view->form->getValue($vect['name']); 
            			$this->view->result = $this->_result;
            		
            		}            
                if(isset($_FILES['filename'])){
        		$data['mime']=$_FILES['filename']['type'];						
			$data['filename']=$_FILES['filename']['name'];
        	}  
  
		$csvxrow = file( $_SERVER['DOCUMENT_ROOT'].'/zcarto/public/storefile/'.$this->_result[7]);   // ---- csv rows to array ----
		$rg=$this->_result[2];
		$i=0;
		   while($i<$rg){
		      unset($csvxrow[$i]);
		      $i++;
		   }
		   
		$sql = "INSERT INTO `".$this->_result[0]."` (`c_name`, `AddressLine1`, `AddressLine2`, `PostalCode`, `City`, `state`, `country`, `phone`, `fax`, `email`, `website`, `comments`, `refcheck`, `tOrgTypeID`, `latitude`, `longitude`, `c_lang`, `zones`) VALUES ";
		$sqldisplay = "INSERT INTO `".$this->_result[0]."` (`c_name`, `AddressLine1`, `AddressLine2`, `PostalCode`, `City`, `state`, `country`, `phone`, `fax`, `email`, `website`, `comments`, `refcheck`, `tOrgTypeID`, `latitude`, `longitude`, `c_lang`, `zones`) VALUES ";
		foreach($csvxrow as $rig){
		    $reg = explode($this->_result[1], $rig);
		    $j=0;
		    while($j<6){
			$reg[$j]= str_replace ("'", "\'", $reg[$j]);
			$reg[$j]= str_replace ("\"", "", $reg[$j]);
			$j++;
		      }
		      if ($reg[1]==''){
			$reg[1] = "x";
		      }
		      if ($reg[4]==''){
			$reg[4] = "Non défini";
		      }
		    $sql .= "('".trim($reg[3],'"')."', '".trim($reg[1],'"')." ".trim($reg[2],'"')."', '---', '". $this->_result['4']."', '". $this->_result['5']."', 'Languedoc', 'France', '".trim($reg[5],'"')."', '".trim($reg[5],'"')."', 'a@adefinir.fr', 'http://www.adefinir.fr', '".trim($reg[4])."', '".$this->_result[3]."',4, 43.0001, 2.5, 'Fr', ". $this->_result['6']."),";
		    $sqldisplay .= "('".trim($reg[3],'"')."', '".trim($reg[1],'"')." ".trim($reg[2],'"')."', '---', '". $this->_result['4']."', '". $this->_result['5']."', 'Languedoc', 'France', '".trim($reg[5],'"')."', '".trim($reg[5],'"')."', 'a@adefinir.fr', 'http://www.adefinir.fr', '".trim($reg[4])."', '".$this->_result[3]."',4, 43.0001, 2.5, 'Fr', ". $this->_result['6']."),";
		 }
		 
		$sql = substr ( $sql, 0, strlen($sql)-1);        	
        	
        	$dbdirect= Zend_Registry::get('mysqldirect'); //It's OK also
        	if ($this->view->result[8]=='0'){
        		$t=$dbdirect->query($sql);
        		$this->view->result=array($dbdirect->affected_rows, $dbdirect->error);
        	}else{
        		$this->view->result =array($sqldisplay);	
        	}
		
        	
            } else {
                $form->populate($formData);
            }
        } 
    	    
    }
    
    
    
    public function buildpdfAction()
    {
    	/**
    	 * 	Exemple for a suffix change
    	 * 	Or a layout change
    	 */
    	  $form= new $this->classForm();
    	  $prevclause= new Zend_Session_Namespace('experimental');
    	  $className=$this->classTable ;
    	  
    	  $table = new $className($this->params['t']);
    	 
    	 if(null != $prevclause->clauselst && $prevclause->clauselst!= ''){    	 	
		$clause = $prevclause->clauselst;
		$res = $table->fetchAll(
					$table->select()
					->where($clause)
					->order('AddressLine1')
					->order('c_name'));	
		/*
		foreach ($vaddLine as $k => $v){
			print "<BR/>".$tt[$k]['AddressLine1'];	
		}die();
		*/
		$tt=$res->ToArray(); 
		
	}else{    	
		$db2= Zend_Registry::get('db2');		
		$query2 = "SELECT id, c_name, AddressLine1, AddressLine2, City, phone, comments FROM tEntreprisespo WHERE zones=218 Order by AddressLine1, c_name"; 			
		$db2->setFetchMode(Zend_Db::FETCH_OBJ);
		$tt = $db2->fetchAll($query2);	
		foreach($tt as $k => $value){
			$tt[$k] = array('id' => $value -> id,'c_name' => $value -> c_name, 'AddressLine1' => $value->AddressLine1,'AddressLine2' => $value->AddressLine2,'City' => $value -> City, 'phone' => $value->phone,'comments' => $value->comments);
		}
	}
 
	$tt=$res->ToArray();

	foreach ($tt as $k=>$v){
		$string = $v['AddressLine1'];
		$pattern = '/[0-9]*[ bis| Bis]*|[x ]*/';
		$replacement = '';
		//$rank[]=$k;
		$vaddLine[]=preg_replace($pattern, $replacement, $string);
		asort($vaddLine, SORT_FLAG_CASE | SORT_NATURAL);
	}
	
	
	
    	if(!isset($this->params['page'])){$valpage=1;}else{ $valpage = $this->params['page'];} 
	$inum =1;
	

		
		try {
		  /**
		   *	Create PDF Document and define resources
		   */
		  $pdf = new Zend_Pdf();  
		  $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);//print $klause."<BR/>"; var_dump($matches); die("<BR/>Here I am");
	
		   	 
		  
		 if(preg_match("/zone/", $clause)===1){
			 $pattern = '/zones LIKE %([0-9]*)%/';
			 $klause = str_replace("'", "", $clause);	 
			 preg_match($pattern, $klause, $matches) ;
			 $pdf->properties['Title'] = "Liste entreprises zone ".$form->getLabelForm(24,2)[(int)$matches[1]];
			 $texttitlePage = "ZONE => ".$form->getLabelForm(24,2)[(int)$matches[1]];
			 $pdf->properties['Author'] = "COMIDER Markethon Perpignan"; 			 
		 }
		
		 
		 if(preg_match("/comment/", $clause)===1){
			 $pattern = '/comments LIKE %([a-zA-Z0-9âàèéêîôùû -]*)%/';
			 $klause = str_replace("'", "", $clause);	 
			 preg_match($pattern, $klause, $matches) ;
			 $pdf->properties['Title'] = "Liste entreprises pour requête [activité = ".$matches[1]."]";
			 $texttitlePage = "Liste entreprises pour requête [activité = ".$matches[1]."]";
			 $pdf->properties['Author'] = "PAPWEB "; 			 
		 }
		  
		 $y= 440.0; 
			 	
			  
			  /**
			   *	Columns and rows for current page
			   */
		 //foreach ($res as $cpy){ 
		 
		 //foreach ($vaddLine as $k => $v){
		//	$cpy=$res[$k];
		 foreach ($vaddLine as $k => $v){
		 	  $cpy = $tt[$k];
			  if(!isset($page) || $y < 40){ 
			  	  
			  	  if(isset($page) && $y < 40){		  
					  /**
					   *	Add current page to document
					   */					  
					  $pdf->pages[] = $page;
					  unset($page);			  	  	  
			  	  }
				  
			  	  /**
				   *	Create page
				   */
				    
				   $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4_LANDSCAPE);
				   $width  = $page->getWidth();
				   $height = $page->getHeight();
				   $x1=40.00;
				   $y1=40.00;
				   //$x2=680.00;
				   $x2=800.00;
				   $y2=500.00;
				   $grayLevel=1.0;
				   $color1 = new Zend_Pdf_Color_GrayScale($grayLevel);
				   $color1 = new Zend_Pdf_Color_Rgb(0, 0, 0);
				   $page->setLineColor($color1);
				   $page->setLineWidth(3.0);
				   $page->drawRectangle($x1, $y1, $x2, $y2,
				   $fillType = Zend_Pdf_Page::SHAPE_DRAW_STROKE);
				   $page->drawLine(5.0, 5.0, 830.0, 5.0);
				   if(preg_match("/zone/", $clause)===1){
				   	   $page->setFont($font, 18)
				   	   	->drawText('DEPART PERPIGNAN', 40, 560);
				   }
				   
				   if(preg_match("/comment/", $clause)===1){
				   	   $page->setFont($font, 18)
				   	   	->drawText('ENTREPRISES PAR ACTIVITE', 40, 560);
				   }
				   $page->setFont($font, 18)
					->drawText($texttitlePage, 190, 520, 'UTF-8//IGNORE')
					->drawText(count($res).' Entreprises.', 43, 510);
					
				   $filename=trim(substr( $form->getLabelForm(24,2)[(int)$matches[1]] , 0 ,5 ));	
				   $page->setFont($font, 14)     
					->drawText('N°', 43, 480, 'UTF-8')
					->setLineWidth(2.0)
					->drawLine(78, $y2, 78, $y1)
					->drawText('Adresse', 120, 480, 'UTF-8')
					->drawLine(228, $y2, 228, $y1)
					->drawText('NOM', 270, 480, 'UTF-8')
					->drawLine(428, $y2, 428, $y1)
					->drawText('VILLE',430, 480, 'UTF-8')					
					->drawLine(501, $y2, 501, $y1)
					->drawText('Téléphone', 503, 480, 'UTF-8')
					->drawLine(608, $y2, 608, $y1)
					->drawText('Activités', 610, 480, 'UTF-8')
					->drawLine(40.0, 460.0, 800.0, 460.0);
			       
				   $y= 440.0;
				   $ytext= 444.0;
				   $page->setLineWidth(2.0);
			  }
				
				 if($y >= 40){
				 	 
					       $page->setFont($font, 10)
							->drawText($inum, 43, $ytext, 'UTF-8//IGNORE')
							->drawText($cpy['AddressLine1'], 80, $ytext, 'UTF-8//IGNORE')
							->drawText( $cpy['c_name'], 230, $ytext, 'UTF-8//IGNORE')
							->drawText( substr($cpy['City'], 0, 15), 430, $ytext, 'UTF-8//IGNORE')
							->drawText($cpy['phone'], 503, $ytext, 'UTF-8//IGNORE')
							->drawText(html_entity_decode(trim(iconv_substr($cpy['comments'], 0 ,38, 'UTF-8'))), 610, $ytext, 'UTF-8//IGNORE')
							->drawLine(40.0, $y, 800.0, $y);
							
						
						$y -= 20.0;
						$ytext= $y + 5.0;
						$inum++;
					 
				  } 
				
			  }
			 
		//  if(isset($page)&& $y >= 40){		  
			$pdf->pages[] = $page;
			unset($page);	
			
		//}
		 
		  // save as file and download
		  $pdf->save('pdfFiles/'.$filename.'.pdf');
		  header("Content-type: application/pdf");
		  header("Content-Disposition: attachment; filename=\"".$filename.".pdf\"");
		  header("Content-length: ".filesize('pdfFiles/'.$filename.'.pdf'));
		  print file_get_contents('pdfFiles/'.$filename.'.pdf');
		  $this->_helper->layout->disableLayout();
		  $this->_helper->viewRenderer->setNoRender(TRUE);

		  //$this->_redirect('/mngtb/index/t/'.$this->params['t'].'/page/'. $valpage.'/tzone/'.Zend_Registry::get('tzone'));
		  
		} catch (Zend_Pdf_Exception $e) {
		  die ('PDF error: ' . $e->getMessage());  
		} catch (Exception $e) {
			$stringFault = "<BR/>Il se peut que vous ayiez une anomalie d'écriture en base sur :<BR/>". $cpy['c_name']."<BR/>".$cpy['AddressLine1']."<BR/>".trim(iconv_substr($cpy['comments'], 0 ,31))."<BR/>". "Contactez le Mécano de la Générale";
			throw new Exception($stringFault);

		  //print	$e->getTraceAsString();
		  //die ('Application error: ' . $e->getMessage());    
		}	 
    }

   public function lstzpdfAction()
    {
    	/**
    	 * 	Exemple for a suffix change
    	 * 	Or a layout change
    	 */
    	  $form= new $this->classForm();
    	  $prevclause= new Zend_Session_Namespace('experimental');
    	  $className=$this->classTable ;
    	  
    	  $table = new $className($this->params['t']);
    	  $totEnt=0;
    	
    	 if(null != $prevclause->clauselst && $prevclause->clauselst!= ''){    	 	
		$clause = $prevclause->clauselst;
		$res = $table->fetchAll(
					$table->select()
					->where($clause)
					->order('city')
					->order('c_name'));
		$tt=$res->ToArray(); 	
		
	}else{    	
		$db2= Zend_Registry::get('db2');		
		$query2 = "SELECT id, c_name, city FROM tZones order by city, c_name"; 			
		$db2->setFetchMode(Zend_Db::FETCH_OBJ);
		$tt = $db2->fetchAll($query2);	
		foreach($tt as $k => $value){
			$tt[$k] = array('id' => $value -> id,'c_name' => $value -> c_name,'city' => $value -> city);
		}
		
	}

		
	
    	if(!isset($this->params['page'])){$valpage=1;}else{ $valpage = $this->params['page'];} 
	$inum =1;
	

		
		try {
		  /**
		   *	Create PDF Document and define resources
		   */
		 $pdf = new Zend_Pdf();  
		 $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
		 $font2 = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD_ITALIC);
		 $pdf->properties['Author'] = "COMIDER Markethon Perpignan";
		 $y= 740.0; 
			 	
			  
			  /**
			   *	Columns and rows for current page
			   */
		 $numz=1;
		 foreach ($tt as $k => $cpy){
			
			
			  if(!isset($page) || $y < 40){ 
			  	  
			  	  if(isset($page) && $y < 40){		  
					  /**
					   *	Add current page to document
					   */					  
					  $pdf->pages[] = $page;
					  unset($page);			  	  	  
			  	  }
				  
			  	  /**
				   *	Create page
				   */
				   $texttitlePage = "Liste des zones"; 
				   $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
				   $width  = $page->getWidth();
				   $height = $page->getHeight();
				   $x1=40.00;
				   $y1=40.00;
				   //$x2=680.00;
				   $y2=760.00;
				   $x2=550.00;
				   $grayLevel=1.0;
				   $color1 = new Zend_Pdf_Color_GrayScale($grayLevel);
				   $color1 = new Zend_Pdf_Color_Rgb(0, 0, 0);
				   $page->setLineColor($color1);
				   $page->setLineWidth(3.0);
				   $page->drawRectangle($x1, $y1, $x2, $y2,
				   $fillType = Zend_Pdf_Page::SHAPE_DRAW_STROKE);
				   $page->drawLine(5.0, 5.0, 550.0, 5.0);
				  	
				   $page->setFont($font, 18)
				   	->drawText('DEPART PERPIGNAN', 40, 800);
				   
				   $page->setFont($font, 18)
				   	->drawText('LISTE DES ', 240, 800);			  
				   $page->setFont($font, 18)
					->drawText(count($tt).' ZONES / 2014.', 340, 800);
				
				   $filename="ListeZones";	
				   $page->setFont($font2, 12)     
					->drawText('IDENTIFICATION', 43, 745, 'UTF-8')
					->setLineWidth(2.0)
					->drawLine(275, $y2, 275, $y1)
					->drawText('VILLE', 278, 745, 'UTF-8')
					->drawLine(345, $y2, 345, $y1)
					->drawText('N° ', 348, 745, 'UTF-8')
					->drawLine(395, $y2, 395, $y1)
					->drawText('Nb/Ent', 398, 745, 'UTF-8')			
					->drawLine(490, $y2, 490, $y1)
					->drawText('GROUPE', 493, 745, 'UTF-8')
					->drawLine(40.0, 740, 550.0, 740);
			       
				   $y= 720.0;
				   $ytext= 724.0;
				   $page->setLineWidth(2.0); 
			  }
			
				 if($y >= 40){
				 	
						 
				 	 $db1= Zend_Registry::get('db1');		
				 	 $queryent = "SELECT count(*) as total FROM tEntreprisespo WHERE zones=".$cpy['id']; 			
				 	 $db1->setFetchMode(Zend_Db::FETCH_OBJ);
				 	 $nbEnt = $db1->fetchAll( $queryent);
				 	
				 	 $page->setFont($font, 10)
							->drawText( $cpy['c_name'], 43, $ytext, 'UTF-8//IGNORE')
							->drawText( substr($cpy['city'], 0, 15), 278, $ytext, 'UTF-8//IGNORE')
							->drawText( $numz, 348, $ytext, 'UTF-8//IGNORE')
							->drawText($nbEnt[0]->total, 398, $ytext, 'UTF-8//IGNORE')
							->drawLine(40.0, $y, 550.0, $y);
							
						
						$y -= 20.0;
						$ytext= $y + 5.0;
					 
				  }
				  $numz +=1;
				  $totEnt +=$nbEnt[0]->total;
				
			  }
		//  if(isset($page)&& $y >= 40){		  
			$pdf->pages[] = $page;
			unset($page);	
			$pdf->pages[0]->setFont($font, 14)
				   	->drawText('Nbre total entreprises => '.$totEnt, 40, 770);
			
		//}
		 
		  // save as file and download
		  
		  $pdf->save('pdfFiles/'.$filename.'.pdf');
		  header("Content-type: application/pdf");
		  header("Content-Disposition: attachment; filename=\"".$filename.".pdf\"");
		  header("Content-length: ".filesize('pdfFiles/'.$filename.'.pdf'));
		  print file_get_contents('pdfFiles/'.$filename.'.pdf');
		  $this->_helper->layout->disableLayout();
		  $this->_helper->viewRenderer->setNoRender(TRUE);

		  //$this->_redirect('/mngtb/index/t/'.$this->params['t'].'/page/'. $valpage.'/tzone/'.Zend_Registry::get('tzone'));
		  
		} catch (Zend_Pdf_Exception $e) {
		  die ('PDF error: ' . $e->getMessage());  
		} catch (Exception $e) {
			$stringFault = "<BR/>Il se peut que vous ayiez une anomalie d'écriture en base sur :<BR/>". $cpy->c_name."<BR/>".$cpy->city."<BR/>". "Contactez le Mécano de la Générale";
			throw new Exception($stringFault);

		  //print	$e->getTraceAsString();
		  //die ('Application error: ' . $e->getMessage());    
		}	 
    }
			  
 /**
  * 	$text_com=html_entity_decode ( $string )
  *	iconv('ISO-8859-1', ' UTF-8', html_entity_decode ($lotn['comments']))
  *	$a =iconv('ISO-8859-1', ' UTF-8', html_entity_decode ($lotn['comments'])); 
  *	Nombre moyen de caractères par ligne selon la taille de la police Font => nb Carac. :
  *	10 => 134, 12 => 111, 14 => 94, 18 => 75, 20=> 66, 
  *
  */
			  
    
   private function _writePdflineText($sentence, $db2, $tt, $font){
    	  $a = html_entity_decode ($sentence); 
	  $phrase=explode(' ', $a);			  
	  $display="";
	  foreach($phrase as $kph => $valueph){
		  if(strlen($display.$valueph) <= 114){			  	  	  
			  $display .= $valueph.' ';
		  }else{
			  $this->_pagePdf->drawText( $display, 10, $this->_linePdf, 'UTF-8');	  
			  $display = $valueph.' ';
			  $this->_linePdf -= 14;
			  $this->_checkPdfPageSetUp($db2, $tt, $font);			 
		  }
		  
	  }
	  $this->_pagePdf->drawText( $display, 10, $this->_linePdf, 'UTF-8');		  
	  $this->_linePdf -= 14;
	  $this->_checkPdfPageSetUp($db2, $tt, $font);
	  return true;
    }
    
   private function _checkPdfPageSetUp($db2, $tt, $font){
   	   if(isset($this->_pagePdf) && $this->_linePdf < 40){
   	   	   
		  /**
		   *	Add current finished page to document
		   * 	Create New Page
		   */
		   
		  $this->_docPdf->pages[] = $this->_pagePdf;
		  unset($this->_pagePdf);
		  $this->_nbPagePdf += 1;
		  $this->_numPagePdf +=1;		  
		  $this->_pagePdf = $this->view->buildHeadPdf($db2, $tt, $font,  $this->_numPagePdf);
		  $this->_linePdf = 700;
	  }
   	return true;   
   }
    
   public function devetcpdfAction()
    {
    	/**
    	 * 	Exemple for a suffix change
    	 * 	Or a layout change
    	 */
    	 /*
    	  $form= new $this->classForm();
    	  $prevclause= new Zend_Session_Namespace('experimental');
    	  $className=$this->classTable ;
    	  
    	  $table = new $className($this->params['t']);
    	  $totEnt=0;
*/
	  setlocale(LC_MONETARY, 'fr_FR');
    	  $db2= Zend_Registry::get('db2');
	  $query2 = "SELECT * FROM tDevisetc WHERE id=".$this->params['id']; 			
	  $db2->setFetchMode(Zend_Db::FETCH_OBJ);
	  $tt = $db2->fetchAll($query2);	
	  foreach($tt as $k => $value){
		$tt[$k] = array('id' => $value -> id,'c_name' => $value -> c_name,'projectid' => $value -> projectid, 'tasks_smul' => explode(",",$value -> tasks_smul), 'ref' =>  $value -> datehour );
		$this->_refdevis=str_replace(' ', '-', $value -> datehour);
		$this->_refdevis==str_replace(':', '-', $this->_refdevis);
	  }

	  $ArrayDevis=Array();
	  $db2= Zend_Registry::get('db2');		
	  $query2 = "SELECT * FROM tLotchapetc WHERE type=1 ORDER BY c_name"; 			
	  $db2->setFetchMode(Zend_Db::FETCH_OBJ);
	  $reslot = $db2->fetchAll($query2);
	  $kl=0;
	  $sel_lot=array();
	  $sel_chapter=array();
	  
	  /**
	   *	A l'identique du vecteur des tâches construction des vecteurs de lots et de chapitres
	   */

	  foreach($tt[0]['tasks_smul'] as $ks=>$val){
	  	  if($val!=''){
			  $queryx = "SELECT tLotchapetc.root as lot, tTacheetc.pkgchapter as chapitre FROM tTacheetc,tLotchapetc WHERE tTacheetc.id=".$val." AND tLotchapetc.id=tTacheetc.pkgchapter";
			  $db2->setFetchMode(Zend_Db::FETCH_OBJ);
			  $lc = $db2->fetchAll($queryx);
			  foreach($lc as $k => $value){
			  	  $keylot = array_search($value->lot,$sel_lot);
				  if( $keylot===false){
				  	  $sel_lot[]=$value->lot;
				  }
			  	  $keychap = array_search($value->chapitre,$sel_chapter);
				  if( $keychap===false){
				  	  $sel_chapter[]=$value->chapitre;
				  }
			  }
	  	  }
	  }

	  
	  foreach($reslot as $kL => $valueL){
	  	  $keylot = array_search($valueL->id, $sel_lot);
	  	  if( $keylot!== false){
			  $ArrayDevis[$kL]['c_name']=$valueL->c_name.' : '.$valueL->c_title ;
			  $ArrayDevis[$kL]['comments']=$valueL->comments;
			  $ArrayDevis[$kL]['cost']=0.0;	  	  
			  $query2 = "SELECT * FROM tLotchapetc WHERE root=".$valueL->id." ORDER BY c_name"; 			
			  $db2->setFetchMode(Zend_Db::FETCH_OBJ);
			  $reschap = $db2->fetchAll($query2);
			  
			  foreach($reschap as $kC => $valueC){
				  $keychap = array_search($valueC->id,$sel_chapter);
				  if( $keychap !== false){
					  $ArrayDevis[$kL]['Chap'][$kC]['c_name']=$valueC->c_name.' : '.$valueC->c_title ;
					  $ArrayDevis[$kL]['Chap'][$kC]['cost']=0.0;	  	  	 	  	  	  	  
					  $query2 = "SELECT * FROM tTacheetc WHERE pkgchapter=".$valueC->id;		
					  $db2->setFetchMode(Zend_Db::FETCH_OBJ);
					  $restask = $db2->fetchAll($query2);
					 
					   foreach($restask as $kT => $valueT){ 
						   $keywork = array_search($valueT->id,$tt[0]['tasks_smul']); 
						   if( $keywork !== false){ 
							$ArrayDevis[$kL]['Chap'][$kC]['Tasks'][$kT][0]=$valueT->c_name.' : '.$valueT->comments;
							// Introduire ici les coûts de calcul des tâches
							$queryC = "SELECT task_type, packg_cost, hourly_cost, volume,duration, follow_rate FROM tTacheetc WHERE id=".$valueT->id;		
							$db2->setFetchMode(Zend_Db::FETCH_OBJ);
							$resCost = $db2->fetchAll($queryC);
							if($resCost[0]->task_type==2){
								$ArrayDevis[$kL]['Chap'][$kC]['Tasks'][$kT][1]=(double)$resCost[0]->hourly_cost * $resCost[0]->volume;
							}else{
								$ArrayDevis[$kL]['Chap'][$kC]['Tasks'][$kT][1]=(double)$resCost[0]->packg_cost + (double)($resCost[0]->follow_rate*$resCost[0]->hourly_cost * $resCost[0]->duration)/800.0;
								
							}
							$ArrayDevis[$kL]['Chap'][$kC]['cost'] +=$ArrayDevis[$kL]['Chap'][$kC]['Tasks'][$kT][1];
							$ArrayDevis[$kL]['cost'] +=$ArrayDevis[$kL]['Chap'][$kC]['Tasks'][$kT][1];
						   }
					   }	  	  	  	  
				}			  
			  }
		}
			  
	  }
	
	try {
		  /**
		   *	Create PDF Document and define resources
		   */
		 $totalcost=0.0;  
		 $this->_docPdf = new Zend_Pdf();  
		 $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
		 $font2 = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD_ITALIC);
		 $this->_docPdf->properties['Author'] = "ECO-TECH Ceram";
		 $this->_linePdf= 0.0; 			
			  
		/**
		 *	First page creation
		*/
		 $this->_nbPagePdf = 1;
		 $this->_numPagePdf=1;
		 $this->_pagePdf = $this->view->BuildHeadPdf($db2, $tt, $font, $this->_numPagePdf);
		 $this->_linePdf= 700.0;
		 $nlot=1;
		 foreach ($ArrayDevis as $kl => $lotn){ 	 
				
			/** 
			 * New page needed ?
			 * If New Page needed :
			 *	- save current one in document
			 * 	- create new page,
			 */
			 
			  if(!isset($this->_pagePdf) || $this->_linePdf < 40){ 
				   
				  if(isset($this->_pagePdf) && $this->_linePdf < 40){		  
					$this->_docPdf->pages[] = $this->_pagePdf;
					unset($this->_pagePdf);			  	  	  
				  }
				 $this->_checkPdfPageSetUp($db2, $tt, $font);
			  }
			  
			  $b=$this->_linePdf-4;
			  $this->_pagePdf->setFont($font, 12);
			  $this->_pagePdf->drawText( $lotn['c_name'], 10, $this->_linePdf, 'UTF-8');
			  $this->_pagePdf->drawLine(10.0, $b , 585.0, $b);
			  $this->_linePdf -= 14;
			  $this->_checkPdfPageSetUp($db2, $tt, $font);

			  $b=$this->_linePdf-4;			  
			  $this->_pagePdf->setFont($font, 10)
			       ->setLineWidth(1.0);
			  $this->_writePdflineText($lotn['comments'], $db2, $tt, $font);			
			 $b=$this->_linePdf-4;
			 $this->_pagePdf->drawLine(10.0, $b , 585.0, $b);
			 
			 $nchap=1;
			 foreach($ArrayDevis[$kl]['Chap'] as $kc => $chapn){		 	 
			 	 $this->_pagePdf->setFont($font, 12);
			 	 $a = html_entity_decode ($chapn['c_name']);
			 	 $this->_pagePdf->drawText($a, 10, $this->_linePdf, 'UTF-8');
			 	 $this->_linePdf -= 14;
			 	 $this->_checkPdfPageSetUp($db2, $tt, $font);
			 	 $b=$this->_linePdf-4;
			 	 $this->_pagePdf->setFont($font, 10);
			 	 
			 	 	/** 
			 	 	 *  N° de tâche ou de poste
			 	 	 */
			 	 	 
			 	 $ntask=1;
			 	 foreach($ArrayDevis[$kl]['Chap'][$kc]['Tasks'] as $kt => $taskn){
			 	 	$a = html_entity_decode ($taskn[0]);			 	 	
			 	 	$this->_writePdflineText("Tâche ".$ntask." :".$a, $db2, $tt, $font);
			 	 	
			 	 	$puht = money_format('%i', $taskn[1]);
			 	 	$this->_pagePdf->drawText( "Prix HT : ".$puht, 450, $this->_linePdf, 'UTF-8');
			 	 	$ntask++;
			 	 	$this->_linePdf-=14;		 	 	
			 	 	$b = $this->_linePdf-4;
					$this-> _checkPdfPageSetUp($db2, $tt, $font);			 	 	
			 	 }
			 	 
			 	 //Ecriture du prix du Chapitre Courant
			 	 $puht =  money_format('%i', $ArrayDevis[$kl]['Chap'][$kc]['cost']);
			 	 $this->_pagePdf->drawText( "Chapitre n° ".$nchap." => Prix HT : ".$puht, 400, $this->_linePdf, 'UTF-8');
			 	 $nchap +=1;
			 	 $this->_linePdf-=14;		 	 	
			 	 $b = $this->_linePdf-4;
				 $this-> _checkPdfPageSetUp($db2, $tt, $font);
			 }
			 
			 //Ecriture du prix du Lot Courant
			  $puht =  money_format('%i', $ArrayDevis[$kl]['cost']);
			  $totalcost +=  $ArrayDevis[$kl]['cost'];
			  $this->_pagePdf->drawText( "Lot n° ".$nlot." => Prix HT : ".$puht, 350, $this->_linePdf, 'UTF-8');
			  $nlot +=1;
			  $this->_linePdf-=14;		 	 	
			  $b = $this->_linePdf-4;
			  $this-> _checkPdfPageSetUp($db2, $tt, $font);
			 	 
		   }
		   
		  $puht = money_format('%i', $totalcost);
		  $this->_pagePdf->drawText( "Coût Total HT de l'expertise: ".$puht, 200, $this->_linePdf, 'UTF-8');
		  $this->_linePdf-=14;		 	 	
		  $b = $this->_linePdf-4;
		  $this-> _checkPdfPageSetUp($db2, $tt, $font);	
		  $totalcost = $totalcost * 1.2;
		  $puht = money_format('%i', $totalcost);
		  $this->_pagePdf->drawText( "Coût Total TTC de l'expertise: ".$puht, 200, $this->_linePdf, 'UTF-8');
		  
		  $filename = 'devistec'.$this->_refdevis;
		  $this->_docPdf->pages[] = $this->_pagePdf;
		  foreach( $this->_docPdf->pages as $k => $page){
		  	  $page->drawText( $this->_nbPagePdf, 460, 725, 'UTF-8');
		  }
		  unset($this->_pagePdf);		
		  $this->_docPdf->save('pdfFiles/'.$filename.'.pdf');
		  header("Content-type: application/pdf");
		  header("Content-Disposition: attachment; filename=\"".$filename.".pdf\"");
		  header("Content-length: ".filesize('pdfFiles/'.$filename.'.pdf'));
		  print file_get_contents('pdfFiles/'.$filename.'.pdf');
		  $this->_helper->layout->disableLayout();
		  $this->_helper->viewRenderer->setNoRender(TRUE);	
	} catch (Zend_Pdf_Exception $e) {
		  die ('PDF error: ' . $e->getMessage());  
		} catch (Exception $e) {
			print	$e->getTraceAsString();
		  die ('Application error: ' . $e->getMessage()); 
			$stringFault = "<BR/>Il se peut que vous ayiez une anomalie d'écriture en base sur :<BR/><BR/><BR/>". "Contactez le Mécano de la Générale";
			throw new Exception($stringFault);

		  print	$e->getTraceAsString();
		  die ('Application error: ' . $e->getMessage());    
		}	 
    }




    
    public function addAction()
    {
    	/**
    	 * 	Exemple for a suffix change
    	 * 	Or a layout change
    	 */
    	 
        $this->_helper->viewRenderer->setViewSuffix('php');
        $this->_helper->layout->setlayout('layout');
        $this->view->title .= "Ajout en  ".$this->_infoTitle;
        $this->view->headTitle($this->view->title, 'PREPEND');
        $auth = Zend_Auth::getInstance();
        if($this->params['t'] !='tJobseeker' && !$auth->hasIdentity()){
        	$this->view->validPerm($this, $this->params['t'], 'create');
        }
        $this->view->Page = $this->_getParam('page');
        /**
         * 	Call to GenForm extension
         */
	$className=$this->classForm ;
	$form = new $className();
	$this->view->buildForm($form, $this->view->tableName);
        $form->submit->setLabel('Add');
        $form->submit->setName($className);
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
        	
		if ($this->params['t'] =='tNomenclature'){
			$this->noXmlRecurse($_POST['modelOption'], 0, '/paragrele');
			$_POST['xmlPath'] = $this->arrayPath;
			$search=array();
			$replace=array();
			$k = 0; 
			while (isset ($form->arrayOptionalArticle[$k])){
				$_POST['structQuote']=$_POST['modelOption'];
				foreach($form->arrayOptionalArticle[$k] as $key => $value){			
					if($key !== 0){
						$search[]='/{'.$key.'}/';
						$replace[]=$_POST[$key]; //print $key." => ".$_POST[$key]." <BR/> ";
					}    			
				}
				$k++;
			}
			$_POST['structQuote']= preg_replace($search, $replace, $_POST['structQuote']);
		}

            $formData = $this->_request->getPost();
            if ($this->view->form->isValid($formData)) {
            	foreach($this->view->form->listElems as $vect)
            	{
            		$data[$vect['name']]= $this->view->form->getValue($vect['name']);
            		if($vect['name']=='password'){
            			$input=$data[$vect['name']];
            			$key='abracadabra'; 
             		    $data[$vect['name']] = MD5($key.strrev($input)).'-'.MD5($input{0}.substr($input,(floor(strlen($input))/2),strlen($input)));           			
            		}
            		
            		if(preg_match('/_smul/', $vect['name'])==1){
						$data[$vect['name']]=implode(',', $data[$vect['name']]);
					}
	           	}
            	$className=$this->classTable ;
        		$records = new $className($this->params['t']);
        		
        		if(isset($_FILES['filename'])){
        			$data['mime']=$_FILES['filename']['type'];						
					$data['filename']=$_FILES['filename']['name'];
        		}
                $records->addRecord($data);
                                 
                /**
                 * 	Check if Indexing is needed
                 */
                
                if(isset($this->view->form->IsIndexed['Keyword']['0'])){
                	$filter= new Zend_Filter_StripTags();
                	$field_to_filter=array('summary', 'c_descript', 'comments');
                	foreach ($field_to_filter as $key => $value)
                	{
                		if(isset($data[$value])){
                			$data[$value]= $filter->filter($data[$value]);                		
                		}
					}
                	$data['id']=(string)$id;
                	$this->view->indexSearch($data, $this->view->indexor, $this->view->form->IsIndexed);                	        	
                }
                
		if($form->is_ListJson){    			
    			$res=$records->fetchAll();
    			$this->view->buildJson($res, $this->params['t'], $form->vectJson);    				
    		}  
    		if(!isset($this->params['page'])){$valpage=1;}else{ $valpage = $this->params['page'];}
    		Zend_Registry::set('page', $valpage);
                $this->_redirect('/mngtb/index/t/'.$this->params['t'].'/tzone/'.Zend_Registry::get('tzone').'/page/'.$valpage);
            } else {
                $this->view->form->populate($formData);
            }
        }
    }
    
    

    public function editAction()
    {
     	/**
    	 * 	Exemple for a suffix change
    	 * 	Or a layout change
    	 */
    	
        $this->_helper->viewRenderer->setViewSuffix('php');
        $this->_helper->layout->setlayout('layout');
        $this->view->title .= "Mise à jour  ".$this->_infoTitle;
	$this->view->headTitle($this->view->title, 'PREPEND');
	
	if (isset($this->params["private"])!==false){
		$private=$this->params["private"];
	}else{
		$private = '';	
	}
 	
	$this->view->validPerm($this, $this->params['t'], 'update', $private);
        
         /**
         * 	Call to GenForm extension
         */
        
	$className=$this->classForm ; 
        $form = new $className($this->params['t'], $private);       
        $this->view->form = $form;
        $className=$this->classTable ;
        $records = new $className($this->params['t']);
        $id = $this->_request->getParam('id', 0);
        $row=$records->getRecord($id);if (isset($row['locked']) && $row['locked']==1){$notLocked=False;}else{$notLocked=True;}
        $this->view->buildForm($form, $this->view->tableName,$xform= '', $notLocked);
        
        if(!isset($this->params['page'])){$valpage=1;}else{ $valpage = $this->params['page'];}
    	Zend_Registry::set('page', $valpage);
   
        if ($this->getRequest()->isPost()) {   
        	 
        	if ($this->params['t'] =='tNomenclature'){
        		$this->noXmlRecurse($_POST['modelOption'], 0, '/paragrele');
        		
        		$FieldXmlpath='';
        		foreach($this->arrayPath as $key => $value){
        			$FieldXmlpath .= $value.'<BR/>';
        		}
        		
        		$_POST['xmlpath'] = $FieldXmlpath;
        		
        		$search=array();
        		$replace=array();
			$k = 0; 
			while (isset ($form->arrayOptionalArticle[$k])){
				$_POST['structQuote']=$_POST['modelOption'];
				foreach($form->arrayOptionalArticle[$k] as $key => $value){			
					if($key !== 0){
						$search[]='/{'.$key.'}/';
						$replace[]=$_POST[$key]; //print $key." => ".$_POST[$key]." <BR/> ";
					}    			
				}
				$k++;
			}
			$_POST['structQuote']= preg_replace($search, $replace, $_POST['structQuote']);
		}
		if(isset($_POST['Annuler'])){
			//$this->_redirect('/mngtb/index/t/'.$this->params['t']);
			//$this->_redirect('/mngtb/index/t/'.$this->params['t'].'/page/'.$this->params['page'].'/tzone/'.Zend_Registry::get('tzone'));
			$t->_redirector->gotoSimple('index', 'mngtb',  null, array('t' => $this->params['t'], 'page' => $this->params['page'], 'tzone'=> Zend_Registry::get('tzone')));
		}
		
                $formData = $this->getRequest()->getPost();
                if ($form->isValid($formData)) {
            	foreach($form->listElems as $vect)
            	{
            		$data[$vect['name']]= $form->getValue($vect['name']);
            		if($vect['name']=='password' && $data[$vect['name']]!=''){
            			$input=$data[$vect['name']];
            			$key='abracadabra'; 
             		    $data[$vect['name']] = MD5($key.strrev($input)).'-'.MD5($input{0}.substr($input,(floor(strlen($input))/2),strlen($input)));           			
            		}elseif($vect['name']=='password' ){
            			unset($data[$vect['name']]);
            		}
       		
            		if((preg_match('/_smul/', $vect['name'])==1) && $data[$vect['name']]!=''){  //print $vect['name']."<BR/>"; var_dump($data);die();
							$data[$vect['name']]=implode(',', $data[$vect['name']]);
					}
            		
            	}
                $id = (int)$form->getValue('id');
               
                if(isset($_FILES['filename'])){
        			$data['mime']=$_FILES['filename']['type'];						
					$data['filename']=$_FILES['filename']['name'];
        		}
                $records->updateRecord($id, $data); 
                                
                /**
                 * 	Check if Indexing is needed
                 */
                
                if(isset($this->view->form->IsIndexed['Keyword']['0'])){
                	$filter= new Zend_Filter_StripTags();
                	$field_to_filter=array('summary', 'c_descript', 'comments');
                	foreach ($field_to_filter as $key => $value)
                	{
                		if(isset($data[$value])){
                			$data[$value]= $filter->filter($data[$value]);                		
                		}
					}
                	$data['id']=(string)$id;
                	$this->view->getHelper('IndexSearch')->upDateIndex($data, $this->view->indexor, $this->view->form->IsIndexed);                	        	
                } 
               
		if($form->is_ListJson){    			
			$res= $records->fetchAll();
    			$this->view->buildJson($res, $this->params['t'], $form->vectJson);    				
    		}  
		
    		/**
    		 *	Personal data update
    		 */
    		$usess= new Zend_Session_Namespace('experimental');
    		if(isset($usess->privateid) && (int)$usess->privateid == $this->view->user->id){
    			$this->_redirect('/');
    		}
    		if(!isset($this->params['page'])){$valpage=1;}else{ $valpage = $this->params['page'];}
    		Zend_Registry::set('page', $valpage);
                $this->_redirect('/mngtb/index/t/'.$this->params['t'].'/page/'. $valpage.'/tzone/'.Zend_Registry::get('tzone'));
            } else {
            	Zend_Registry::set('page', $this->_getParam('page'));
                $form->populate($formData);
            }
        } else {
            $id = $this->_request->getParam('id', 0);
            if ($id >= 0) {
            	$row=$records->getRecord($id);
            	foreach($row as $key => $value){            		
			if($key=='password')
			{
				$row['password']='';
			}elseif(preg_match('/_smul/', $key)==1){
				$row[$key]=explode(",", $row[$key]);
			}elseif(($key=='locked')&& $value!=1){				
					$form->submit->setLabel('Enregistrer');        
			}
			if(is_object($form->submit)){
				$form->submit->setLabel('Enregistrer');	
			}
			
            	}
                $form->populate($row);
            }
        }   
    }
    
    
    
    function copyAction()
    {
    	/**
    	 * 	Exemple for a suffix change
    	 * 	Or a layout change
    	 */
    	 
        $this->_helper->viewRenderer->setViewSuffix('php');
        $this->_helper->layout->setlayout('layout');
        $this->view->title .= "Mise à jour  ".$this->_infoTitle;
	$this->view->headTitle($this->view->title, 'PREPEND'); 
        $this->view->validPerm($this, $this->params['t'], 'write');

            /**
    	     * 	Copy similar record
    	     *  Accelerate database populating
    	     */ 	     
    	     
    	     
    	$className=$this->classTable ;
        $records = new $className($this->params['t']);    
    	
            	$row=$records->getRecord($this->params['id']);
            	foreach($row as $key => $value){
            		if($key!='id' && $key!='c_username' && $key!='email'){
            			if($key=='name' || $key=='c_name' || $key=='c_lastname'){
            				$data[$key]="NEW ".$value;
            			}else{
            				$data[$key]=$value;
            			}
            		}
            	}
            	$records->addRecord($data);
       $this->_redirect('/mngtb/index/t/'.$this->params['t'].'/tzone/'.Zend_Registry::get('tzone'));
    }

    
    
    public function deleteAction()
    {
    	/**
    	 * 	Exemple for a suffix change
    	 * 	Or a layout change
    	 */
    	 
        $this->_helper->viewRenderer->setViewSuffix('php');
        $this->_helper->layout->setlayout('layout');
        $this->view->title .= "retrait  ".$this->_infoTitle;
        $this->view->headTitle($this->view->title, 'PREPEND');
        
        if (isset($this->params["private"])!==false){
		$private=$this->params["private"];
	}else{
		$private = '';	
	}
	
        $this->view->validPerm($this, $this->params['t'], 'delete', $private);
        $className=$this->classTable ;        
        $records = new $className($this->params['t']); 
        $className=$this->classForm ;
        $form = new $className();        
        $this->view->display=$form->display;
            
        if ($this->getRequest()->isPost()) {
            $del = $this->getRequest()->getPost('del');
            if ($del == 'Yes') { 
                $id = $this->getRequest()->getPost('id');
                $records->deleteRecord($id);
                                
                /**
                 * 	Check if Index cleaning is needed
                 */
                
                if(isset($this->view->form->IsIndexed['Keyword']['0'])){
                	$data['id']=(string)$id;
                	$this->view->getHelper('IndexSearch')->deleteIndex($this->view->indexor, $id);                	        	
                }
                
				if(	$form->is_ListJson){    			
    					$res= $records->fetchAll();
    					$this->view->buildJson($res, $this->params['t'], $form->vectJson);    				
    			}  
            }
            $this->_redirect('/mngtb/index/t/'.$this->params['t'].'/tzone/'.Zend_Registry::get('tzone'));
        } else {
            $id = $this->_getParam('id', 0);
            $this->view->record = $records->getRecord($id);
        } 
    }

    
    public function structdevAction()
    {
    	/**
    	 * 	Exemple for a suffix change
    	 * 	Or a layout change
    	 */
 	 
        $this->_helper->viewRenderer->setViewSuffix('php');
        $this->_helper->layout->setlayout('layout'); 
        $this->view->title .= "tableau de structuration du devis rattaché";
        $this->view->headTitle($this->view->title, 'PREPEND');
        $this->view->validPerm($this, $this->params['t'], 'list');
        $className=$this->classTable ;
        $table = new $className('tAppros');
        
        $this->view->records =$table->fetchAll(
        			$table->select()
        			->order('codeArticle ASC'));
        
        $this->view->records = $this->view->records->toArray(); 
        
    }
    
    
    /**
     *	Build automatically Path query to each element of the XML tree
     *  $attrib not used
     *	@return $this->arrayPath
     */
    
  
    public function xmlRecurse($xmlObj,$depth,$xpath) {
        $this->view->validPerm($this, $this->params['t'], 'update');
	  $i=0;
	  $att = 'attributeName';
	  $val=array();
	  $this->arraylonlatmoy['latmoy']="";
	  $this->arraylonlatmoy['lonmoy']="";
	  foreach($xmlObj->children() as $child) {
	    array_push ($this->arrayPath, $xpath."/".$child->getName());
	    foreach($child->attributes() as $v){
		//echo "Attrib".str_repeat('-',$depth).">".$k." = ".$v."\n";
		if($i==0){
			$t=(array)($v);
			$val['lat']=$t[0];
			$i=1;
		}else{
			$t=(array)($v);
			$val['lon']=$t[0];
			array_push ($this->arraylonlat, $val);
			$i=0;
		}
	    }
	    $this->xmlRecurse($child,$depth+1,$xpath.'/'.$child->getName());
	  }
	  
	}

	
	
	/**
	*  Build automatically an XML object from dataObject
	*  Create automatically Path query to each element of of the built XML tree
	*  $attrib not used
	*  @return $this->arrayPath
	*/

   public function noXmlRecurse($dataObject, $depth, $xpath) {
           $this->view->validPerm($this, $this->params['t'], 'list');
   	   
   	   $torepalace =array('&lt;![[CDATA', ']]&gt;', '<br />','&lt;', '&gt;', '&quot;');
           $replacewith=array('', '', '' ,'<',  '>', '"');
           $output  = str_replace($torepalace , $replacewith, $dataObject);
           $dom = new Zend_Dom_Query();
           $dom->setDocumentXml($output);
            	
            	/**
            	 *	Build access path to element
            	 */ 
            	 
           $filename = "tempo.txt";
           $fp = fopen($filename, 'w');
           fwrite($fp, $output);
           fclose($fp);
           //$xmlfile =  simplexml_load_file($filename); 
           $xmlfile = new SimpleXMLElement($output);                       	
           $this->xmlRecurse($xmlfile,$depth,$xpath);
  	  
	  /**
	  	Zone gravity center geographic localisation
	  */
	  foreach($this->arraylonlat as $val){
	  	 $this->arraylonlatmoy['latmoy']=bcadd($this->arraylonlatmoy['latmoy'],(string)$val['lat'], 10);
	  	 $this->arraylonlatmoy['lonmoy']=bcadd($this->arraylonlatmoy['lonmoy'],(string)$val['lon'], 10);	  	  
	  }
	  $n=count($this->arraylonlat); 
	  $this->arraylonlatmoy['lonmoy']=bcdiv($this->arraylonlatmoy['lonmoy'],(string)$n,10);
	  $this->arraylonlatmoy['latmoy']=bcdiv($this->arraylonlatmoy['latmoy'],(string)$n,10);	  
   }

   
	/**
	 * 	How to extract a pure XML string from a <![[CDATA ...]] store]
	 *	header("Content-Type: text/xml");
	 *	$torepalace =array('&lt;![[CDATA', ']]&gt;', '<br />','&lt;', '&gt;');
	 *	$replacewith=array('', '', '' ,'<',  '>');
	 *	$output  = str_replace($torepalace , $replacewith, $row['structQuote']);
	 *	echo $output ;die();
	*/

    public function purexmlAction(){
    	    
    	/**
         * 	Call to GenForm extension
         */
         $this->view->validPerm($this, $this->params['t'], 'update');
	$className=$this->classForm ;
        $form = new $className();        
        $this->view->buildForm($form, $this->view->tableName);
        $this->view->form = $form;
        $className=$this->classTable ;
        $records = new $className($this->params['t']);
        $id = $this->_request->getParam('id', 0);
            if ($id > 0) {
            	$row=$records->getRecord($id);
            	$records = new $className('tNomenclature');
            	$row=$records->getRecord($row['FKid_nomeXml']); 
            	$tabpath=preg_split("/<br \/>/", $row['xmlpath']);
            	$torepalace =array('&lt;![[CDATA', ']]&gt;', '<br />','&lt;', '&gt;');
            	$replacewith=array('', '', '' ,'<',  '>');
            	$output  = str_replace($torepalace , $replacewith, $row['structQuote']);
            	$dom = new Zend_Dom_Query();
            	$dom->setDocumentXml($output);
            	
            	/**
            	 *	Build access path to element
            	 */ 
            	$xmlfile = new SimpleXMLElement($output);  
            	
            	$this->xmlRecurse($xmlfile,0, '/paragrele');
 		$kart=0;
		$messageError="";
		if (isset($this->params['testpath']) && $this->params['testpath']=='go'){
			$className=$this->classTable ;
			$approRecords = new $className('tAppros');
			while(isset($this->arrayPath[$kart])){
				$res= $dom->queryXpath($this->arrayPath[$kart]); 
				foreach ($res as $p)
				{
					if(preg_match("/[<> \/]/",$p->nodeValue )==0){
						$rowArt = $approRecords->fetchRow("codeArticle = '" . $p->nodeValue."'");
							if (!$rowArt) {
								$messageError .=  "Absence de => ".$p->nodeValue." en table appros<BR/>";
								die($messageError);
							}
					}
				}
				$kart++;
			}
		}
		
		if($messageError==""){
			header("Content-Type: text/xml");
			echo $output ;die();
            	}else{
            		echo $messageError ;die();
            	}
            }else{
            	$this->_redirect('/mngtb/index/t/'.$this->params['t']);    
            }
    	    
    }
    
    public function partslistAction()
    {
   	/**
    	 * 	Exemple for a suffix change
    	 * 	Or a layout change
    	 */
 	 
        $this->_helper->viewRenderer->setViewSuffix('php');
        $this->_helper->layout->setlayout('layout'); 
        $this->view->title .= $this->_infoTitle;
        $this->view->headTitle($this->view->title, 'PREPEND');
        $this->view->validPerm($this, $this->params['t'], 'update');
        $className=$this->classForm ;
        $form = new $className();         
        $this->view->display=$form->display;
        $className=$this->classTable ;
        $table = new $className($this->params['t']);
        
        $this->view->records =$table->fetchAll(
        			$table->select()
        			->order('codeArticle ASC'));
        
        $this->view->paginator= Zend_Paginator::factory($this->view->records);
    	$this->view->paginator->setItemCountPerPage(30);
    	$this->view->paginator->setPageRange(5);
        $this->view->paginator->setCurrentPageNumber($this->_getParam('page')); 
        
    }
 
    /**
     * 	Calcul intersection vecteurs, dans leur espace de définition
     *	Introdduction d'une tolérance d'erreur de calcul à 11 décimales
     */
    
   
function cutornot($a,$b, $c, $d){
	$res = array();
	/**
	 *	Introduction Imprécision de calcul
	 */
	$tol=1.0003;
	$Xa = $a["x"];//Long
	$Ya = $a["y"];//Lat
	$Xb = $b["x"];
	$Yb = $b["y"];
	$Xc = $c["x"];
	$Yc = $c["y"];
	$Xd = $d["x"];
	$Yd = $d["y"];
	
	$val= (($Yc*$Xd -$Xc*$Yd)*($Xb-$Xa)-($Ya*$Xb-$Xa*$Yb)*($Xd-$Xc))/(($Yb-$Ya)*($Xd-$Xc)-($Yd-$Yc)*($Xb-$Xa));
	$res["val"]=$val;
	
	if($a["x"] > $b["x"]){
		$a["x"] +=0.00000000001;
		$b["x"] -=0.00000000001;
		if($val >= $b["x"] && $val <= $a["x"] )	{
			if($c["x"] > $d["x"]){
				$c["x"] +=0.00000000001;
				$d["x"] -=0.00000000001;
				if($val >= $d["x"] && $val <= $c["x"] ){	
					$in = true;
				}else{
					$in = false;
				}
			}else{
				$c["x"] -=0.00000000001;
				$d["x"] +=0.00000000001;
				if($val >= $c["x"] && $val <= $d["x"] ){	
					$in = true;
				}else{
					$in = false;
				}				
			}				
		}else{
			$in = false;
		}
	}else{
		$a["x"] -=0.00000000001;
		$b["x"] +=0.00000000001;
		if($val >= $a["x"] && $val <= $b["x"] )
		{
			if($c["x"] > $d["x"]){
				$c["x"] +=0.00000000001;
				$d["x"] -=0.00000000001;
				if($val >= $d["x"] && $val <= $c["x"] ){	
					$in = true;
				}else{
					$in = false;
				}
			}else{
				$c["x"] -=0.00000000001;
				$d["x"] +=0.00000000001;
				if($val >= $c["x"] && $val <= $d["x"] ){	
					$in = true;
				}else{
					$in = false;
				}				
			}
		
		}else{
			$in=false;
		}
	}
	$res["in"]= $in;	
	return $res;	
}
 
    public function quotelistAction()
    {
    	
    	$distancePlace = 0.0;
    	$LgEfficaceT = 0.0;
    	$LgEfficace = 0.0;
    	$LgCbTr = 0.0;		
    	$NbRgLg  = 0.0;
    	$NbRgT = 0.0;
    	$NbPotL = 0.0;
    	$NbPotT = 0.0;
    	$NbPotDevL = 0.0;
    	$NbPotDevT  = 0.0;
    	$NbPotPerim  = 0.0;
    	$NbAncrage  = 0.0;
    	$NbPotPos = 0.0;
    	$NbTotPot = 0.0;
    	$qLongueurFilAcier = 0.0;
    	$qLongueurFilet = 0.0;
    	$qpotVertical = 0.0;
    	$qLongueurCableT = 0.0;
    	$sarea  = 0.0;
    	$serreCableLong  = 0.0;
    	$serreCableTrans  = 0.0;
    	$CltChp='/';
    	    
    	$tbID=array();
    	$tbId = unserialize($this->params['listid']);
    	//var_dump($tbId); die();
    	$i=0;
    	while(isset($tbId[$i])){
    		$this->params['id']=$tbId[$i];
    		if($i==0){
    		    $this->view->a = $this->view->extractPath($this->params['id'], $this->classTable, 'tChamp');
    		}
    		$this->quote();
    		$CltChp .= $this->view->CltChp.'/';
    		$distancePlace += $this->view->distancePlace;
    		$LgEfficaceT += $this->view->LgEfficaceT;
    		$LgEfficace += $this->view->LgEfficace;
    		$LgCbTr += $this->view->LgCbTr;		
    		$NbRgLg += $this->view->NbRgLg;
    		$NbRgT += $this->view->NbRgT;
    		$NbPotL += $this->view->NbPotL;
    		$NbPotT += $this->view->NbPotT;
    		$NbPotDevL += $this->view->NbPotDevL;
    		$NbPotDevT += $this->view->NbPotDevT;
    		$NbPotPerim += $this->view->NbPotPerim;
    		$NbAncrage += $this->view->NbAncrage;
    		$NbPotPos += $this->view->NbPotPos;
    		$NbTotPot += $this->view->NbTotPot;
    		$qLongueurFilAcier += $this->view->qLongueurFilAcier;
    		$qLongueurFilet += $this->view->qLongueurFilet;
    		$qpotVertical += $this->view->qpotVertical;
    		$qLongueurCableT += $this->view->qLongueurCableT;
    		$sarea += $this->view->sarea;
    		$serreCableLong += $this->view->serreCableLong;
    		$serreCableTrans += $this->view->serreCableTrans;
    		$i++;    		
    	}
    	
	$this->view->distancePlace = $distancePlace;
	$this->view->LgEfficaceT = $LgEfficaceT;
	$this->view->LgEfficace = $LgEfficace;
	$this->view->LgCbTr = $LgCbTr;		
	$this->view->NbRgLg = $NbRgLg;
	$this->view->NbRgT = $NbRgT;
	$this->view->NbPotL = $NbPotL;
	$this->view->NbPotT = $NbPotT;
	$this->view->NbPotDevL = $NbPotDevL;
	$this->view->NbPotDevT = $NbPotDevT;
	$this->view->NbPotPerim= $NbPotPerim;
	$this->view->NbAncrage = $NbAncrage;
	$this->view->NbPotPos= $NbPotPos;
	$this->view->NbTotPot= $NbTotPot;
	$this->view->qLongueurFilAcier= $qLongueurFilAcier;
	$this->view->qLongueurFilet = $qLongueurFilet;
	$this->view->qpotVertical = $qpotVertical ;
	$this->view->qLongueurCableT = $qLongueurCableT;
	$this->view->sarea = $sarea;
	$this->view->serreCableLong = $serreCableLong;
	$this->view->serreCableTrans = $serreCableTrans;
	$this->view->CltChp = $CltChp;
    }
    
    public function quoteAction() {
    	    
    	/**
	 *	Get all the fields for each article code
	 *	present in the nomenclature
	 */
	 
	 $this->view->a = $this->view->extractPath($this->params['id'], $this->classTable, 'tChamp');
    	 $this->quote();
    	    
    }
    
     public function quote()
     
    { 
	$tbPerimLat=array();
	$tbPerimLong=array();
	$tabItems = array();
	$LigneTab=array();
	$Deviations= Array();
	$this->view->params=$this->params;
	if(!isset($this->params['devis'])){
        		$this->view->sancout="on";
        }
	$dist = 0.0;
	$k=0;
	$interval=13;
	$this->view->qrang = 0;
	$this->view->qrangTrans = 0;
	$this->view->qpotVertical=0;
	$this->view->qpotVertical2=0;
	$this->view->qpotExtrem = 0;
	$this->view->qLongueurFilet=0;
	$this->view->qLongueurCable=0;
	$this->view->qlongueurTransverse=0;
	$this->_helper->layout->setlayout('devislayout');
	
	
	/**
	 *	Is it a standard rectangle for estimation ?
	 */
	 
	if (!isset($this->params['notRectangleStandard'])){
		$notRectangleStandard=false;
	}else{
		$notRectangleStandard=true;
	}
	
	if (!$notRectangleStandard){
		$db2= Zend_Registry::get('db2');		
		$query2 = "SELECT c_name, alignments, transverses, deviations, deviationst, latitude, longitude, sarea, perimeter, FKid_Client, distance FROM tChamp WHERE id=".$this->params['id']; 			
		$db2->setFetchMode(Zend_Db::FETCH_OBJ);
		$res = $db2->fetchAll($query2);
		
		$this->view->latitude = $res[0]->latitude;
		$this->view->longitude = $res[0]->longitude;
		$this->view->sarea = $res[0]->sarea;
		$this->view->perimeter = $res[0]->perimeter;
		$this->view->distance = $res[0]->distance;
		$poteauxVert = array();
		$LigneTab = unserialize($res[0]->alignments);
		$LigneTob = unserialize($res[0]->transverses);
		$this->view->LgCbFait=0.0;
		$this->view->LgCbTr=0.0;
		strtok($res[0]->FKid_Client, "-");
		$this->view->Id_Client =(int)trim(strtok("-"));
		$this->view->nomChamp = $res[0]->c_name;
		$this->view->CltChp = $this->view->Id_Client.'-'.$this->params['id'];
		
		$k=0;
		while( isset($LigneTab[$k])){
			$poteauxVert[$k]=0;
			$poteauxVert1[$k]=0;
			$poteauxVert2[$k]=0;
			$pV1[$k]=0;
			$pV2[$k]=0;
			if(isset($LigneTab[$k]['fin'])){	
				$this->view->qrang ++;	

				/**
				 *	Experimental =>  test the need of a pick with dot product
				 *	For insection checking
				 *
				 */
				 
				$tt=0;
				
				while( isset($LigneTob[$tt])){					
					$a = array("x" => (double)$LigneTob[$tt]['deb']['long'], "y" => (double)$LigneTob[$tt]['deb']['lat']);
					$b = array("x" => (double)$LigneTob[$tt]['fin']['long'], "y" => (double)$LigneTob[$tt]['fin']['lat']);
					$c = array("x" => (double)$LigneTab[$k]['deb']['long'], "y" => (double)$LigneTab[$k]['deb']['lat']);
					$d = array("x" => (double)$LigneTab[$k]['fin']['long'], "y" => (double)$LigneTab[$k]['fin']['lat']);
					$result=$this->cutornot($a,$b, $c, $d);
					$delta=bcsub($LigneTob[$tt]['deb']['long'],(double)$LigneTob[$tt]['fin']['long'], 10);
					$degTorad = (string)(M_PI/180.0);
					$delta= bcmul($degTorad, $delta, 10); 
					$metre=bcmul("6378137.0", (string)$delta, 10);
					if ($result["in"]){
						$poteauxVert[$k]++;
					}
					$tt++;

				}
				
				$tabItems[$this->view->qrang]["potVertical"] = $poteauxVert[$k] ;
				$this->view->qpotVertical +=  $poteauxVert[$k];
				
				
				$tabItems[$this->view->qrang]["potExtrem"] = 2;
				$this->view->qpotExtrem += 2;
				$this->view->qLongueurFilet += $LigneTab[$k]['fin']['dist'];
				$this->view->qLongueurFilAcier += $LigneTab[$k]['fin']['dist'] + 0.5;
				if($k==0){
					$this->view->qLongueurFilAcier += $LigneTab[$k]['fin']['dist'] + 0.5;
				}
								
			}

			$k++;
		}
		$this->view->NbPotPos= $this->view->qpotVertical;
		$this->view->LgEfficaceL = $this->view->qLongueurFilet;
		
		/**
		 * 	A chaque extrémité de rangs, filets et cables complémentaires sont nécessaires
		 *	Pour accrochage (Filet) et ancrage (Cable)
		 *	Longueur total Filet
		 *	Longueur total cable faitage
		 */
		
		$this->view->qLongueurFilet = $this->view->LgEfficaceL + (7.50 *2.0)*$this->view->qrang;
		$this->view->qLongueurCableF = $this->view->LgEfficaceL + (1.250 *2.0)*$this->view->qrang;
		
		$LigneTab= unserialize($res[0]->transverses);
		$k=0;
		
		while( isset($LigneTab[$k])){
			
			if(isset($LigneTab[$k]['fin'])){	
				$this->view->qrangTrans ++;
				//$this->view->qpotVertical +=  (int)($LigneTab[$k]['fin']['dist'] / $interval) -1;
				$tabItems[$this->view->qrangTrans]["potExtrem"] = 2;
				$this->view->qpotExtrem += 2;
				$this->view->qlongueurTransverse += $LigneTab[$k]['fin']['dist'];						
			}
			$k++;
		}
		
		
		/**
		 *	Ajout longueur fixation sur poteau extrême
		 *	1.25 par extrémité
		 */
		 
		$this->view->LgEfficaceT = $this->view->qlongueurTransverse;
		$this->view->qLongueurCableT = $this->view->LgEfficaceT + (2.5 * $this->view->qrangTrans);
		
		
		/**
		 *	Deviations longitudinales
		 *	
		 */
		 
		$deviations= unserialize($res[0]->deviations);
		$k=0;
		$this->view->NbPotDevL=0;
		while( isset($deviations[$k])){
			$this->view->NbPotDevL++;
			$this->view->qpotExtrem += 1;		
			$k++;
		}
		
		/**
		 *	Deviations transversales
		 */
		 
		$deviationst= unserialize($res[0]->deviationst);
		$k=0;
		$this->view->NbPotDevT=0;
		while( isset($deviationst[$k])){			
			$this->view->NbPotDevT++;
			$this->view->qpotExtrem += 1;		
			$k++;
		}
		
		/**
		 *	Addition des cables d'ancrages (Même type que transverses) :
		 *	- 1 par extrémités longitudinales diminuées des déviations
		 *	- 2 par extrémités transversaleses diminuées des déviations
		 *	- 3 par poteaux de déviation
		 */
		$this->view->NbAncrage = ($this->view->NbPotL - $this->view->NbPotDevL) + ($this->view->NbPotT - $this->view->NbPotDevT)*2 + ($this->view->NbPotDevL + $this->view->NbPotDevT)*3; 
		$this->view->qLongueurCableT +=7.50 *  $this->view->NbAncrage;
		
		/**
		 *	Pour cable type faîtage
		 *	3 Serre-câbles à chaque extrémité
		 */
		 
		$this->view->serreCableLong = 6*$this->view->NbRgLg;
		
		
		/**
		 *	Pour cable type transverse
		 *	3 Serre-câbles à chaque extrémité
		 *	3 Serre-cable sur chaque hauban d'ancrage
		 *	1 serre-câbles sur chaque extrémité de rang pour tenue chapelle
		 */
		 
		$this->view->serreCableTrans = 6 * $this->view->NbRgT + 3 * $this->view->NbAncrage + 2 * $this->view->NbRgT;
		
		
		$this->view->NbRgLg= $this->view->qrang;
		$this->view->NbRgT= $this->view->qrangTrans;
		$this->view->qpotVertical=$this->view->NbPotPos;
		$this->view->NbPotL = 2 * $this->view->NbRgLg;
		$this->view->NbPotT = 2 * $this->view->NbRgT;
		$this->view->NbPotDevL=$this->view->NbPotDevL;
		$this->view->NbPotDevT=$this->view->NbPotDevT;
		$this->view->NbPotPerim= $this->view->NbPotL + $this->view->NbPotT;
		$this->view->NbAncrage = ($this->view->NbPotL - $this->view->NbPotDevL) + ($this->view->NbPotT - $this->view->NbPotDevT)*2 + ($this->view->NbPotDevL + $this->view->NbPotDevT)*3;
		$this->view->LgEfficace = $this->view->LgCbFait;
		$this->view->LgCbTr = $this->view->LgCbTr;
		$this->view->NbPotPos=$this->view->NbPotPos;
		$this->view->NbTotPot=$this->view->NbPotL + $this->view->NbPotT + $this->view->NbPotPos;
		$this->view->qLongueurCableT = $this->view->qLongueurCableT ; // Câble transversal + haubans ancrage

		/**
		 *	Pour cable type faîtage
		 *	3 Serre-câbles à chaque extrémité
		 */
		 
		$this->view->serreCableLong = 6*$this->view->NbRgLg;
		
		
		/**
		 *	Pour cable type transverse
		 *	3 Serre-câbles à chaque extrémité
		 *	3 Serre-câbles sur chaque hauban d'ancrage
		 *	1 serre-câbles sur chaque extrémité de rang pour tenue chapelle
		 */
		 
		$this->view->serreCableTrans = (6 * $this->view->NbRgT) + 3 * ($this->view->NbAncrage) + 2 * ($this->view->NbRgT);
		

	}else{
		$this->view->Id_Client =1;
		$this->view->nomChamp = "Champ de test rectangulaire";
		$this->view->latitude = 42.654321;
		$this->view->longitude =  -2.718;
		$this->view->sarea = (float)$this->params['length']* (float)$this->params['width'];
		$this->view->perimeter = ((float)$this->params['length'] +(float)$this->params['width'])*2.0;
		$this->view->distance = 60.0;
		$this->view->NbRgLg = $this->view->qrang= (int)((float)$this->params['width'] /(float)$this->params['widthRank']) + 1;
		$this->view->NbRgT = $this->view->qrangTrans=(int)((float)$this->params['length'] /(float)$this->params['widthTrans']) - 1;
		$this->view->NbPotL = 2 * $this->view->NbRgLg;
		$this->view->NbPotT = 2 * $this->view->NbRgT + 4;
		$this->view->NbPotDevL=2;
		$this->view->NbPotDevT=2;
		$this->view->NbPotPerim = $this->view->qpotExtrem = $this->view->NbPotL + $this->view->NbPotT;
		$this->view->NbAncrage = ($this->view->NbPotL - $this->view->NbPotDevL) + ($this->view->NbPotT - $this->view->NbPotDevT)*2 + ($this->view->NbPotDevL + $this->view->NbPotDevT)*3;
		$this->view->NbPotPos = $this->view->qpotVertical= $this->view->qrangTrans*$this->view->qrang;
		$this->view->qLongueurCableF = ((float)$this->params['length'] + 6.0 + 2.5)* $this->view->qrang;
		$this->view->qLongueurFilet=((float)$this->params['length'] * $this->view->qrang) + (7.50 *2.0)*$this->view->qrang;		
		$this->view->qlongueurTransverse=$this->view->qrangTrans * ((float)$this->params['width'] + 8.0);
		$this->view->qLongueurCableT = $this->view->qlongueurTransverse + (7.50 *  $this->view->NbAncrage);
		$this->view->serreCableLong = 6*$this->view->NbRgLg;
		$this->view->serreCableTrans = (6 * $this->view->NbRgT) + (3 * $this->view->NbAncrage) + (2 * $this->view->NbRgT);
		$this->view->NbTotPot=$this->view->NbPotL + $this->view->NbPotT + $this->view->NbPotPos;
		$this->view->qLongueurFilAcier = (float)$this->params['length']* ($this->view->NbRgLg+1);

	}
	
    }
    
    	
	/**
	 *	Unauthorized message
	 */
	
	
	function nopermAction()
	{
		$this->_helper->viewRenderer->setViewSuffix('php');
		$this->_helper->layout->setlayout('layout');
		$this->view->title .= $this->_infoTitle;
		$this->view->headTitle($this->view->title, 'PREPEND');
		$auth = Zend_Auth::getInstance();		
		if ($auth->hasIdentity()) 
		{
		    $this->view->user = Zend_Auth::getInstance()->getIdentity();		    
		} 
		$tsess= new Zend_Session_Namespace('Messages');	
		$this->view->nopermMsg =  $tsess->nopermMsg; 
		
	}

    

}

