<?php
/**
 * Created on June 8, 2012
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
 
 /**
  *	Supply :
  * 		- label, type of element and some HTML attribut,
  *		- validators, 
  *		-required or not
  */
  
 class Form_fFormtest extends Zend_Form
{
	
	/**
	 *	TinyMce as editor
	 *	an alternative to Dojo editor
	 */	 
	public $has_TinyMce= true;
	public $has_TinyAdvanced=false;
	public $is_ListJson=true;
	public $listElems=Array();
	public $objElemens=Array();
	public $default_features = array('validators' => array('StripTags', 'StringTrim'), 'filters' => array('NotEmpty'), 'required' => true);
	
	/**
	 * 	Label , type input, size 
	 *  for each element in form
	 *  Trouble => 'path'=>  $_SERVER['DOCUMENT_ROOT'].'/zcarto/public/storefile/'
	 */
	 protected $_fieldname = array('target', 'delimeter', 'startline',  'controleur', 'zone', 'file');
	 protected $_labelForm=Array(array('Table cible', 'text', array('size' =>'27', 'value'=>'tEntreprisespo', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)),
	 	 		array('Séparateur', 'text', array('size' =>'27', 'value' =>';', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('N° ligne départ', 'text', array('size' =>'27', 'value' => '3', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Contrôle', 'comboBox_Dojo', Array('searchAttrib'=>'username', 'list' =>'tOrganization' ), NULL), 
                                array('Zone.', 'select',  array('size' =>'27'), NULL),
                                array('Fichier.', 'zendfile',  array('Size' => 2000000, 'Count'=> 1, 'Extension'=>'csv', 'path' => '/var/www/html/zcarto/public/storefile/' ), NULL));
 	/**
	 * 	Listing display parameters
	 */	 
    public $display=Array('title' => array('0'=>'Nom', '1'=> 'Zones', '2' => 'Ville', '3' => 'Country' ),
    					  'value' => array('0'=>'c_name', '1' => 'zones', '2' => 'City', '3' =>'country'));

    public function  __construct() { 
    	parent::__construct($options=null);
    	$options=Array();
    	
    	/**
    	 *     	//$options = Array(0 => "Cliente", 1 => "Fournisseur", 2 => "Gouvernementale", 3 => "Partenaire", 4 => "Employeur");
    	 * Construction des listes de sélection
    	 */
    	        
    	$query = 'SELECT id, c_name FROM tZones ';
    	$db1= Zend_Registry::get('db1');
    	$db1->setFetchMode(Zend_Db::FETCH_OBJ);
    	$result = $db1->fetchAll($query);
    	foreach($result as $key => $value){
    		$options[$result[$key]->id]= $result[$key]->c_name;    	
    	}
    	
    	/**
    	 *	Warning ! Here the first index
    	 *	reflect the position in $_labelForm array
    	 */
    	 
    	$this->_labelForm[4][2]= $options;
    	
    	$i=0;
		while(isset($this->_fieldname[$i])){				
			$this->listElems[]=array('name'=> $this->_fieldname[$i], 'label'=> $this->_labelForm[$i][0], 'type' => $this->_labelForm[$i][1], 'attribs' => $this->_labelForm[$i][2], 'features' => $this->_labelForm[$i][3]);
			$i++;
		}

    	
    	/**
    	 *	Help for button (Submit;, cancel, reset) position
    	 */
    	$this->setDisableLoadDefaultDecorators(false); 
    	$this->setDecorators(array('FormElements', array('HtmlTag', array('tag' => 'dl', 'class' => 'MaClass')), 'Form'));
    }

}
?>