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


class MapsController extends Zend_Controller_Action
{
	private $classTable;
	private $classForm;
	private $params;
	

    public function init()
    {
        /* Initialize action controller here */
    	$this->_config = Zend_Registry::get('config'); 
        $tlang=Zend_Registry::get('tloc');
        if($tlang=='fr'){
        	$this->view->langue=" French";
        }
         if($tlang=='en'){
        	$this->view->langue=" English";
        }
        
        $this->params=$this->_request->getParams();
        if(null ==$this->params['type'])
        { 
        	$this->_redirect('/');
    	}
        if(isset($this->params['champ']))
        { 
        	$this->view->champ=$this->params['champ'];
    	}
    	
	if(isset($this->params['tzone'])){
	      Zend_Registry::set('tzone', $this->params['tzone']);
	}
        
    	
        $this->view->cache=Zend_Registry::get('cache');
        
        /**
         * 	Setup the path to specific helpers, activation of generic table
         *  	Class form definition
         *  	Indexer/Search engine activation 
         *	Perms management  
         */ 
         
        $this->view->addHelperPath($_SERVER["DOCUMENT_ROOT"].'/'.$this->_config->name->root.'/application/resources/helpers', 'Form_View_Helper');

    }

    public function indexAction()
    {
    	/**
    	 * 	Exemple for a suffix change
    	 * 	Or a layout change
    	 */
    	 
    	$auth = Zend_Auth::getInstance();
   	if ($auth->hasIdentity()) 
	{
	    $this->view->user = Zend_Auth::getInstance()->getIdentity();    	    
    	}
 	 
         if(isset($_POST['items']) && ($_POST['items']== "12" || $_POST['items']== "17")){
        	if(isset($_POST['project']) && $_POST['items']== "12"){
        		$val_id =$_POST['project'];
        		$pathRedirect= "/mngtb/quote/t/tChamp/id/".$val_id; 
        	}
        	
        	if(isset($_POST['listfields']) && $_POST['items']== "17"){
        		$val_id =serialize($_POST['listfields']);
        		$pathRedirect= "/mngtb/quotelist/t/tChamp/listid/".$val_id;
        	}
        	
		if(isset($_POST['devounom'])){	
				$pathRedirect .="/devis/1";
		}
		
		if(isset($_POST['support'])){
				$pathRedirect .="/support/1";
		}
		
		if(isset($_POST['filet'])){
				$pathRedirect .="/filet/1";
		}
		
		if(isset($_POST['cables'])){
				$pathRedirect .="/cables/1";
		}
		
		if(isset($_POST['fildefer'])){
				$pathRedirect .="/fildefer/1";
		}
		
		if(isset($_POST['accessoires'])){
				$pathRedirect .="/accessoires/1";
		}
			
		if(isset($_POST['mo'])){
				$pathRedirect .="/mo/1";
		}
		if(isset($_POST['transport'])){
				$pathRedirect .="/transport/1";
		}        		
		if(isset($_POST['beton'])){
				$pathRedirect .="/beton/1";
		}        		
		$this->_redirect($pathRedirect);	        	

        } elseif(isset($_POST['SUBMIT']) && (isset($_POST['items']) && ($_POST['items']== "13")) ){
        		
		$out[0]= explode(",", $_POST['commentaires']);
		
		/**
		 * 	As it is only an estimation on a 1 ha area rectangular field
		 *	we use th field 25 to access a nomenclature type
		 */
		 
		$pathRedirect= "/mngtb/quote/notRectangleStandard/1/t/tChamp/id/25/length/".$out[0][0]."/width/".$out[0][1]."/widthRank/".$out[0][2]."/widthTrans/". $out[0][3];
		if(isset($_POST['devounom'])){	
				$pathRedirect .="/devis/1";
		}
		
		if(isset($_POST['support'])){
				$pathRedirect .="/support/1";
		}
		
		if(isset($_POST['filet'])){
				$pathRedirect .="/filet/1";
		}
		
		if(isset($_POST['cables'])){
				$pathRedirect .="/cables/1";
		}
		
		if(isset($_POST['fildefer'])){
				$pathRedirect .="/fildefer/1";
		}
		
		if(isset($_POST['accessoires'])){
				$pathRedirect .="/accessoires/1";
		}
			
		if(isset($_POST['mo'])){
				$pathRedirect .="/mo/1";
		}
		if(isset($_POST['transport'])){
				$pathRedirect .="/transport/1";
		}        		
		if(isset($_POST['beton'])){
				$pathRedirect .="/beton/1";
		}        		
		$this->_redirect($pathRedirect);
	}
	
	
        
        $this->_helper->viewRenderer->setViewSuffix('php');
        switch($this->params['type']){
        	case 'localisation':
        		$this->_helper->layout->setlayout('mapslocalisation');
        		$this->view->title .= ' Localisation de surfaces à équiper';
        		if(!isset($_POST['project']) && $this->params['champ'] != ''){
        			$_POST['project']=$this->params['champ'];        			
        		}
               	break;
        	case 'old':
        		$this->_helper->layout->setlayout('mapslayout');
        		$this->view->title .= ' Traitement cartographique';
        		$this->view->title .= " de surfaces agricoles en test";
                break;
        	case 'profilsurface':        		
        		$this->_helper->layout->setlayout('profilrelief');
        		//$profil= $this->view->getHelper('profilrelief');
        		$this->view->latlongfield=Array($this->params['latit'], $this->params['longit']);
        		$this->view->title .= ' Etablissement de profil du relief';
        		$this->view->title .= " entre points";
                break;
               	
               	default :
               		$this->_redirect('/');
               	break;
        
        }
        
        
        $this->view->headTitle($this->view->title, 'PREPEND');
         
    }

 
}

