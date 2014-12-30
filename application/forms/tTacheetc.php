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
  
 class Form_tTacheetc extends Form_GenForm
{
	
	/**
	 *	TinyMce as editor
	 *	an alternative to Dojo editor
	 */	 
	public $has_TinyMce= true;
	public $has_TinyAdvanced=false;
	public $is_ListJson=true;
	public $orderby ='c_name ASC';
	
	/**
	 *	Give fields included in ListJson
	 *	file, limited to two fields
	 */	 
	public $vectJson=array('c_name', 'manager');
	
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
	 
	 
	 public $_labelForm=Array(array('Nom', 'text', array('size' => '55', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Type de tâche.', 'select',  array('size' =>'27'), NULL), 
                                array('Lot et chapitre.', 'select',  array('size' =>'27'), NULL), 
                                array('Coût forfaitaire (€) ', 'text',  array('value' => 'comments', 'size' => '27', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)),                                 
                                array('Coût horaire (€)', 'text',  array('value' => 'comments', 'size' => '27', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)),                                 
                                array('Durée tâche (j)', 'text',  array('value' => 'comments', 'size' => '27', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)),                                 
                                array('Volume (h)', 'text',  array('value' => 'comments', 'size' => '27', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)),                                 
                                array('Taux de suivi (%)', 'text',  array('value' => 'comments', 'size' => '27', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)),                                 
				array('Antécédent', 'multiselect', array('size' => '35', 'control' => 'alphadiac'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)),
                                array('Description',  'textarea',  array('cols' =>'52', 'rows' => '5'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Responsable', 'comboBox_Dojo', Array('searchAttrib'=>'username', 'list' =>'tUser' ), NULL));
 	/**
	 * 	Listing display parameters
	 */	 
    public $display=Array('title' => array('0'=>'Nom', '1' => 'Responsable', '2' => 'Durée' ),
    					  'value' => array('0'=>'c_name',  '1' => 'manager', '2' =>'duration'));

    public function  __construct($name='tTacheetc') {  
    	
    	$options_1 = Array(0 => "Quel type de tâche", 1 =>"Forfaitaire", 2 => "Base horaire");	

   	$query = 'SELECT id, c_name FROM tLotchapetc WHERE type =2';
	$db1= Zend_Registry::get('db1');
	$db1->setFetchMode(Zend_Db::FETCH_OBJ);
	$result = $db1->fetchAll($query);
	$result = $db1->fetchAll($query);
	$options_2[0]= "Veuillez choisir un chapitre";
	foreach($result as $key => $value){
		$options_2[$result[$key]->id]= $result[$key]->c_name;    	
	}
	
	$query = 'SELECT id, c_name FROM tTacheetc';
	$db1= Zend_Registry::get('db1');
	$db1->setFetchMode(Zend_Db::FETCH_OBJ);
	$result = $db1->fetchAll($query);	
	$options_3[0]= "Veuillez choisir un Antécédent";
	foreach($result as $key => $value){
		$options_3[$result[$key]->id]= $result[$key]->c_name;    	
	}
    	/**
    	 *	Warning ! Here the first index
    	 *	reflect the position in $_labelForm array
    	 */
    	 
    	$this->_labelForm[1][2]= $options_1;
    	$this->_labelForm[2][2]= $options_2;
    	$this->_labelForm[8][2]= $options_3;
   	
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