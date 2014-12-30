<?php
/**
 * Created on November 23, 2014
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
  
 class Form_tLotchapetc extends Form_GenForm
{
	
	/**
	 *	TinyMce as editor
	 *	an alternative to Dojo editor
	 */	 
	public $has_TinyMce= true;
	public $has_TinyAdvanced=false;
	public $is_ListJson=false;
	public $orderby ='c_name ASC';
	
	/**
	 *	Give fields included in ListJson
	 *	file, limited to two fields
	 */	 
	public $vectJson=array('c_name', 'type');
	
	/**
	 * 	Table fields not present in form
	 */
	protected $_cols_not_inform=Array('id');
	
	/**
	 * 	Complementary fields in form
	 *	without peer in database
	 */
	protected $_comp_inform=Array();
	
	/**
	 * 	Label , type input, size 
	 *  for each element in form
	 */
	 
	 
	 public $_labelForm=Array(array('Nom générique', 'text', array('size' => '35', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                 			array('Nom etendu', 'text', array('size' => '35', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)),               
                 			array('Infos complémentaires',  'textarea',  array('cols' =>'55', 'rows' => '5', 'value' => 'Informations complémentaires'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                 			array('Type',  'select',  array('size' =>'27'), NULL),
                 			array('Antécédent',  'select',  array('size' =>'27'), NULL));
 	/**
	 * 	Listing display parameters
	 */	 
    public $display=Array('title' => array('0'=>'Nom générique', '1' => 'Nom étendu' ),
    					  'value' => array('0'=>'c_name',  '1' => 'c_title'));

    public function  __construct($name='tLotchapetc') {  
    	
    	$options_1 = Array(0 => "Lot ou chapitre ?",1 => "Lot", 2 =>"Chapitre");	
    	/**
	 * // 	$options = Array(0 => "Cliente", 1 => "Fournisseur", 2 => "Gouvernementale", 3 => "Partenaire", 4 => "Employeur");
	 * 	Construction des listes de sélection
	 */
		
	$query = 'SELECT id, c_name FROM tLotchapetc WHERE c_name LIKE "%lot%"';
	$db1= Zend_Registry::get('db1');
	$db1->setFetchMode(Zend_Db::FETCH_OBJ);
	$result = $db1->fetchAll($query);
	$result = $db1->fetchAll($query);
	$options_2[0]= "Veuillez choisir une Racine";
	foreach($result as $key => $value){
		$options_2[$result[$key]->id]= $result[$key]->c_name." - ".$result[$key]->id;    	
	}
   	
    	/**
    	 *	Warning ! Here the first index
    	 *	reflect the position in $_labelForm array
    	 */
    	 
    	$this->_labelForm[3][2]= $options_1;
    	$this->_labelForm[4][2]= $options_2;
   	
    	parent::__construct($name);
    	
    	/**
    	 *	Help for button (Submit;, cancel, reset) position
    	 */
    	$this->setDisableLoadDefaultDecorators(false); 
    	$this->setDecorators(array('FormElements', array('HtmlTag', array('tag' => 'dl', 'class' => 'MaClass')), 'Form'));
    }
    
    public function getLabelForm($x,$y){
    	    return $this->_labelForm[$x][$y];
    }

}
?>