<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    	
        $tlang=Zend_Registry::get('tloc');
        if($tlang=='fr'){
        	$this->view->langue=" French";
        }
         if($tlang=='en'){
        	$this->view->langue=" English";
        }
        if($this->_request->getParam('t')==''){
        	$this->view->tableName = 'tUser';
        }else{
        	$this->view->tableName = $this->_request->getParam('t');
        }
       $this->view->addHelperPath($_SERVER["DOCUMENT_ROOT"].'/zcarto/application/resources/helpers', 'Form_View_Helper');
       $this->classTable ='Model_DbTable_GenTb';
       Zend_Registry::set('controller', $this);
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
    	    $this->view->havePerm('read', 'tUser',  $this->view->user);
    	}
    	
    	    $this->_helper->viewRenderer->setViewSuffix('php');
    	    $this->_helper->layout->setlayout('layout');
    	    $this->view->title .= ' Page d\'accueil';
    	    $this->view->headTitle($this->view->title, 'PREPEND');

        /*
            
            Not necessary to access table 
            /**
             * The following example is let as a reminder
             *
             /
            
    	    $this->view->validPerm($this, $this->params['t'], 'update', $private);

    	    $className='Form_tUser' ; 
    	    $form = new $className();         
    	    $this->view->display=$form->display;        
    	    $className=$this->classTable ;
    	    $table = new $className('tUser');
        
    	    $this->View->cloud = new Zend_Tag_Cloud(array('tags' => array(
    	    	    array('title' => 'Code', 'weight' => 50, 'params' => array('url' => '/tag/code')),
    	    	    array('title' => 'Zend Framework', 'weight' => 1,  'params' => array('url' => '/tag/zend-framework')),
    	    	    array('title' => 'PHP', 'weight' => 5, 'params' => array('url' => '/tag/php')),
    	    	    'tagDecorator' => array(
    	    	    	    'decorator' => 'HtmlTag',
    	    	    	    'options' => array(
    	    	    	    	    'htmlTags' => array(),
    	    	    	    	    'fontSizeUnit' => 'px',
    	    	    	    	    'minFontSize' => 20))
    	    	    )
    	   ));
    	   */
    	   
    	 /*   
    	    $this->view->records = $table->fetchAll();
    	    $this->view->paginator= Zend_Paginator::factory($this->view->records);
    	    $this->view->paginator->setItemCountPerPage(3);
    	    $this->view->paginator->setPageRange(5); 
    	    $this->view->paginator->setCurrentPageNumber($this->_getParam('page')); 
        */
    
    }


}

