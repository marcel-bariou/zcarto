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
  
 class Form_tZones extends Form_GenForm
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
	public $vectJson=array('c_name');
	
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
	 *      for each element in form
	 *      If You add a line the validity of options index.
	 */

	 protected $_labelForm=Array(array('Name', 'text', array('size' => '35', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Ref. & Contrôle', 'comboBox_Dojo', Array('searchAttrib'=>'username', 'list' =>'tOrganization' ), NULL), 
                                array('Ville', 'text', array('size' => '27', 'control' => 'alphadiac'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
				array('Description', 'textarea', array('cols' =>'52', 'rows' => '5'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => false)), 
                                array('Latitude', 'text', array('size' => '35', 'control' => 'numfloat'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Longitude', 'text', array('size' => '35', 'control' => 'numfloat'), array('filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Surface', 'text', array('size' => '35', 'control' => 'numfloat'), array('filters' => array('StripTags', 'StringTrim'), 'required' => false)), 
                                array('Perimètre', 'text', array('size' => '35', 'control' => 'numfloat'), array('filters' => array('StripTags', 'StringTrim'), 'required' => false)), 
                                array('Potentiel solaire', 'text', array('size' => '35', 'control' => 'numfloat'), array('filters' => array('StripTags', 'StringTrim'), 'required' => false)), 
                                array('Potentiel Biomasse', 'text', array('size' => '35', 'control' => 'numfloat'), array('filters' => array('StripTags', 'StringTrim'), 'required' => false)), 
                                array('Potentiel PV', 'text', array('size' => '35', 'control' => 'numfloat'), array('filters' => array('StripTags', 'StringTrim'), 'required' => false)), 
 				array('Géométrie', 'textarea',  array('cols' =>'52', 'rows' => '5'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => false)), 
				array('Type Culture.', 'select',  array('size' =>'27'), NULL),
                                array('Distance au site', 'text', array('size' => '35', 'control' => 'numfloat'), array('filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
 				array('Couche Image', 'text', array('size' => '35', 'control' => 'pathfile'), array('filters' => array('StripTags', 'StringTrim'), 'required' => false)),
 				array('LatLong N/E', 'text',array('size' => '35', 'control' => 'numfloatvirg'), array('filters' => array('StripTags', 'StringTrim'), 'required' => false)),
 				array('LatLong S/W', 'text', array('size' => '35', 'control' => 'numfloatvirg'), array('filters' => array('StripTags', 'StringTrim'), 'required' => false)),
 				array('Opacité', 'text', array('size' => '35', 'control' => 'numfloat'), array('filters' => array('StripTags', 'StringTrim'), 'required' => false)),
 				array('En vérification.', 'select',  array('size' =>'27'), NULL));
	/**
	 * 	Listing display parameters
	 */	 
    public $display=Array('title' => array('0'=>'Nom Zone', '1' => 'Nom Responsable', '2'=>'latitude', '3' => 'Longitude', '4' => 'Ville'  ),
    					  'value' => array('0'=>'c_name', '1' => 'FKid_Client','2' => 'latitude', '3' =>'longitude', '4' => 'city' ));


    public function  __construct($name='tZones') {  
    	$options2=Array();
    	$options = Array(0 => "Fruitiers", 1 => "Olives", 2 => "Céreales", 3 => "Autres", 4 => 'Biomasse', 5 => 'Photovoltaïque', 6 => 'Zones Economiques');
    	$options1 = Array(0 => "En attente", 1 => "En contrôle", '2' => 'Validée');
    	
    	/**
    	 *	Warning ! Here the first index
    	 *	reflect the position in $_labelForm array
    	 */
    	 
    	$this->_labelForm[12][2]= $options;
   	$this->_labelForm[18][2]= $options1;

    	parent::__construct($name);
    	
    	/**
    	 *	Help for button (Submit;, cancel, reset) position
    	 */
    	$this->setDisableLoadDefaultDecorators(false); 
    	$this->setDecorators(array('FormElements', array('HtmlTag', array('tag' => 'dl', 'class' => 'MaClass')), 'Form'));
    }

}
?>