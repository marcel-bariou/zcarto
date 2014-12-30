<?php
/**
 *Created on November 23, 2014
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
  
 class Form_tClientetc extends Form_GenForm
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
	public $vectJson=array('c_name', 'City');
	
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
                                array('Adresse Ligne 1', 'text', array('size' =>'27', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Adresse ligne 2', 'text', array('size' =>'27', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Code Postal', 'text',  array('size' =>'27', 'control'=> 'numspace'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Ville', 'text', array('size' => '27', 'control' => 'alphadiac'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Région', 'text', array('size' => '27', 'control' => 'alphadiac'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Pays', 'comboBox_Dojo', Array('searchAttrib'=>'username', 'list' =>'countrylist' ), NULL), 
                                array('Tél.', 'text', array('size' => '27', 'control' => 'phonefax'), NULL), 
                                array('Fax ou Portable', 'text', array('size' => '27', 'control' => 'phonefax'), NULL),  
                                array('@Mail', 'text', array('size' => '27', 'control' => 'email'), NULL), 
                                array('Site Web', 'text', array('value', 'size' => '27', 'control' => 'pathfile'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Activités',  'textarea',  array('cols' =>'52', 'rows' => '5'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Siret-Rcs', 'text',  array('value' => 'comments', 'size' => '27', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)),                                 
                                array('Nbre Emplois', 'text',  array('value' => 'comments', 'size' => '27', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)),                                 
                                array('Chiffres Affaires (k€)', 'text',  array('value' => 'comments', 'size' => '27', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)),                                 
                                array('Code activité (NAF)', 'text', array('value' => 'comments', 'size' => '27', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Suivi client', 'comboBox_Dojo', Array('searchAttrib'=>'username', 'list' =>'tUser' ), NULL),
                                array('Type Orga.', 'select',  array('size' =>'27'), NULL), 
                                array('Latitude', 'text', array('value' => '4.45632', 'size' => '27', 'control' => 'numfloat'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)),
                                array('Longitude', 'text',array('value' => '4.45632', 'size' => '27', 'control' => 'numfloat'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)),
                                array('Langue', 'comboBox_Dojo', Array('searchAttrib'=>'username', 'list' =>'countrylist' ), NULL),
                                array('Valeur GPS correcte',  'select',  array('size' =>'27'), NULL),
                                array('Vérifié cette année ?',  'select',  array('size' =>'27'), NULL),
                                array('Date de vérification', 'datetextbox_Dojo', Array('size'=>'25'), NULL));
 	/**
	 * 	Listing display parameters
	 */	 
    public $display=Array('title' => array('0'=>'Nom', '1' => 'Ville', '2' => 'Pays' ),
    					  'value' => array('0'=>'c_name',  '1' => 'City', '2' =>'country'));

    public function  __construct($name='tClientetc') {  
    	
    	$options_1 = Array(0 => "Quel secteur ou type entreprise", 1 =>"Sidérurgie", 2 => "Carrier", 3 => "SIOM", 4 => "STEP", 5 => "Chaufferie, centrale thermique", 6 => "Chimie", 7 => "Fournisseur");
	$options_2 = Array(0 => "Validez votre vérification", 1 => "Non", 2 => "Oui");
	$options_3 = Array(0 => "Géoréférence OK ?", 1 => "Non", 2 => "Oui");
	

   	
    	/**
    	 *	Warning ! Here the first index
    	 *	reflect the position in $_labelForm array
    	 */
    	 
    	$this->_labelForm[17][2]= $options_1;
    	$this->_labelForm[21][2]= $options_3;
   	$this->_labelForm[22][2]= $options_2;
   	
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