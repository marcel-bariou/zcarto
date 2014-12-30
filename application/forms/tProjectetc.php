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
  
 class Form_tProjectetc extends Form_GenForm
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
	 
	 
	 public $_labelForm=Array(array('Nom', 'text', array('size' => '35', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Description',  'textarea',  array('cols' =>'52', 'rows' => '5'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('client', 'comboBox_Dojo', Array('searchAttrib'=>'username', 'list' =>'tClientetc' ), NULL),
                                array('Date de démarrage', 'datetextbox_Dojo', Array('size'=>'25'), NULL),
                                array('Date de fin ciblée', 'datetextbox_Dojo', Array('size'=>'25'), NULL),
                                array('Budget', 'text',  array('value' => 'comments', 'size' => '27', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)),                                 
                                array('Statut',  'select',  array('size' =>'27'), NULL),
                                array('Responsable', 'comboBox_Dojo', Array('searchAttrib'=>'username', 'list' =>'tUser' ), NULL));
 	/**
	 * 	Listing display parameters
	 */	 
    public $display=Array('title' => array('0'=>'Nom', '1' => 'Responsable', '2' => 'Client' ),
    					  'value' => array('0'=>'c_name',  '1' => 'manager', '2' =>'client'));

    public function  __construct($name='tProjectetc') {  
    	
    	$options_1 = Array(0 => "Devis en cours", 1 =>"Décision client", 2 => "En cours", 3 => "Achevé", );	

   	
    	/**
    	 *	Warning ! Here the first index
    	 *	reflect the position in $_labelForm array
    	 */
    	 
    	$this->_labelForm[6][2]= $options_1;
   	
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