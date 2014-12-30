<?php
/**
 * Created on June 8, 2009
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
  
 class Form_tUser extends Form_GenForm
{
	
	public $has_TinyMce= true;
	public $has_TinyAdvanced=false;
	public $is_ListJson=true;
	public $vectJson=array('c_fname', 'c_lastname');
	public $orderby ='c_fname ASC';
	
	/**
	 * 	Table fields not present in form
	 */
	protected $_cols_not_inform=Array('id', 'comments', 'tUserTypeID', 'tOrganizationID', 'respzone_smul');
	protected $_cols_not_inform_private=Array('id', 'anonymous', 'c_username', 'groupe_smul', 'menu_smul',  'comments', 'tUserTypeID', 'tOrganizationID', 'respzone_smul');
	
	/**
	 * 	Complementary fields in form
	 */
	protected $_comp_inform=Array();
	
	/**
	 *  Label , type input, size and control
	 *  for each element in form
	 *  Expressions régulières de contrôle :
	 *		alphadiac => Alphabétique, diacritique avec tiret et espace (Noms propres)
	 *		alphab => Alphabétique pur
	 *		alphanumvirgapos => Alphanumérique, virgule, apostrophe
	 *     		numspace => Numérique, espace
	 *		phonefax => 
	 *		email => adresse email
	 *		keyword => alphabétique, espace
	 * Exemple => array('First Name', 'text', array('size' => '27', 'control' => 'alphadiac'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true))
	 */
	 protected $_labelForm =array();
	 protected $_labelFormAdmin=Array(array('First Name', 'text', array('size' => '35', 'control' => 'alphadiac'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
    				array('Last Name', 'text', array('size' => '27', 'control' => 'alphadiac'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
    				array('Anonymous', 'checkbox_Dojo', array('checked' => 'On', 'unchecked' => 'Off', 'default' => 'Off'), NULL),  
                                array('Login', 'text', array('size' =>'27', 'control' => 'alphab'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Password', 'passwordtextbox_Dojo', array('size' => '27', 'control' => 'pswd'), NULL),
                                array('Address Line 1', 'text', array('size' =>'27', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Address line 2', 'text', array('size' =>'27', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Postal code', 'text', array('size' =>'27', 'control'=> 'numspace'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('City', 'text', array('size' => '27', 'control' => 'alphadiac'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('State', 'text', array('size' => '27', 'control' => 'alphadiac'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Country', 'comboBox_Dojo', Array('searchAttrib'=>'username', 'list' =>'countrylist' ), NULL), 
                                array('Group', 'multiselect', array('size' =>'27'), NULL),
                                array('Accès Applications', 'multiselect', array('size' =>'27'), NULL),
                                array('Téléph. fixe', 'text', array('size' => '27', 'control' => 'phonefax'), NULL), 
                                array('Portable', 'text', array('size' => '27', 'control' => 'phonefax'), NULL),  
                                array('Mail', 'text', array('size' => '27', 'control' => 'email'), NULL), 
                                array('Keyword', 'text', array('size' => '27', 'control' => 'keyword'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)),
                                array('Zones affectées', 'textarea', array('cols' =>'57', 'rows' => '3'), array('validators' => array('NotEmpty'), 'filters' => array(), 'required' => true)));
                                //array('Zones affectées', 'text', array('size' => '27', 'control' => 'keyword'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)));
 
	 protected $_labelFormPrivate=Array(array('First Name', 'text', array('size' => '35', 'control' => 'alphadiac'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
    				array('Last Name', 'text', array('size' => '27', 'control' => 'alphadiac'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Password', 'passwordtextbox_Dojo', array('size' => '27', 'control' => 'pswd'), NULL),
                                array('Address Line 1', 'text', array('size' =>'27', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Address line 2', 'text', array('size' =>'27', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Postal code', 'text', array('size' =>'27', 'control'=> 'numspace'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('City', 'text', array('size' => '27', 'control' => 'alphadiac'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('State', 'text', array('size' => '27', 'control' => 'alphadiac'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Country', 'comboBox_Dojo', Array('searchAttrib'=>'username', 'list' =>'countrylist' ), NULL), 
                                array('Téléph. fixe', 'text', array('size' => '27', 'control' => 'phonefax'), NULL), 
                                array('Portable', 'text', array('size' => '27', 'control' => 'phonefax'), NULL),  
                                array('Mail', 'text', array('size' => '27', 'control' => 'email'), NULL), 
                                array('Keyword', 'text', array('size' => '27', 'control' => 'keyword'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)));
 
    				/*
                                array('Comments', 'textarea', array('cols' =>'57', 'rows' => '10'), array('validators' => array('NotEmpty'), 'filters' => array(), 'required' => true)),
                     	        array('Organisation Type', 'select', Array('0' => 'Select a type', '1' =>'Call Center', '2' =>'Respite Center', '3' =>'Leisure Center'), NULL),
	                        array('User Type', 'select', Array('0' => 'Select a type', '1' =>'Call Center', '2' =>'Respite Center', '3' =>'Leisure Center'), NULL)array('Name', 'text', array('size' => '27'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)),
	                        */
    public $displayGroup= array('group' => array('c_username', 'password') ,
    							'name' => 'Login');
	/**
	 * 	Listing display parameters
	 */
	 
    public $display=Array('title' => array('0'=>'First Name', '1'=>'Last Name', '2' => 'User Name' ),
    					  'value' => array('0'=>'c_fname', '1' => 'c_lastname', '2' =>'c_username'));


    public function  __construct($name='tUser', $private='') {  
    	if($private==''){
		$this->_labelForm = $this->_labelFormAdmin;
		$options=Array();
		$query = 'SELECT id, c_name FROM tGroup ';
		$db1= Zend_Registry::get('db1');
		$db1->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db1->fetchAll($query);
		foreach($result as $key => $value){
			$options[$result[$key]->id]= $result[$key]->c_name;    	
		}		
		$this->_labelForm[11][2]= $options;
		$options_2=array('admin'=>'Administration', 'field' => 'Vergers' ,'work' => 'Activités' ,'water' => 'Eau' ,'power' => 'Energie', 'space' => 'Espaces' );
		$this->_labelForm[12][2]= $options_2;
    	}else{
 		$this->_labelForm = $this->_labelFormPrivate;   		
    	}
    	
    	parent::__construct($name, $private);    	
    	$this->setDisableLoadDefaultDecorators(false); 
    	$this->setDecorators(array('FormElements', array('HtmlTag', array('tag' => 'dl', 'class' => 'MaClass')), 'Form'));
    }
 
    public function getLabelForm($x,$y){
    	    return $this->_labelForm[$x][$y];
    	}


}
?>