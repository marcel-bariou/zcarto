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
  
 class Form_tAppros extends Form_GenForm
{
	
	public $has_TinyMce= false;
	public $has_TinyAdvanced=false;
	public $is_ListJson=true;
	public $vectJson=array('codeArticle', 'name');
	
	/**
	 * 	Table fields not present in form
	 */
	protected $_cols_not_inform=Array('id');
	
	/**
	 * 	Complementary fields in form
	 */
	protected $_comp_inform=Array();
	
	public $orderby ='codeArticle ASC';
	/**
	 * 	Label , type input, size 
	 *  for each element in form
	 */

	 protected $_labelForm=Array(array('Ref. Item', 'text', array('size' =>'27', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
    				array('Name', 'text', array('size' => '27', 'control' => 'alphadiac'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Supplier', 'comboBox_Dojo', Array('searchAttrib'=>'username', 'list' =>'tOrganization' ), NULL),
     				array('Prix', 'text', array('size' => '27', 'control' => 'numfloat'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)),
     				array('Base de calcul.', 'select',  array('size' =>'27'), NULL),
     				array('Pond. prix', 'text', array('size' => '27', 'control' => 'numfloat'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)),
     				array('Groupe devis.', 'select',  array('size' =>'27'), NULL),
     				array('Pond. quté.', 'text', array('size' => '27', 'control' => 'numfloat'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)),
      				array('Classe options.', 'select',  array('size' =>'27'), NULL),
     				array('Explications', 'textarea', array('cols' =>'52', 'rows' => '5'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)));
    
 	/**
	 * 	Listing display parameters
	 */
	 
    public $display=Array('title' => array('0'=>'Item ref.', '1'=>'Name', '2' => 'Price' ),
    					  'value' => array('0'=>'codeArticle', '1' => 'name', '2' =>'price'));


    public function  __construct($name='tAppros') {  
     	$options = Array(0 => "base de calcul", 1 => "Rangs d'arbres", 2 => "Rangs tranverses", 3 => "Poteaux posés", 4 => 'Poteaux périmétriques', 5 => 'Nbre ancrages', 6 => 'Longueur filet', 7 => 'Longueur câble faîtage', 8 => 'Longueur câble transv.',  9 => 'Totalité des poteaux', 10 => 'Distance au chantier', 11 => 'Nbre de kg transporté', 12 => 'Longueur Fil acier', 13 => 'Unité article', 14 => 'Surface de terrain', 15 => 'Serre-câble transv.', 16 => 'Serre-câble long');
     	$options2 = Array(0 => "Quel groupe devis", 1 => "Support ancrage", 2 => "Filet", 3 => "câble", 4 =>"Fil de fer", 5 =>"accessoires", 6 => "Main d'oeuvre", 7 => "Transport", 8 => "Préparation béton");
    	
     	/**
    	 *	When you add an option here :
    	 *		1) you must follow existing order, and increases indexes
    	 *		2) You have to create a new field in tNomenclature table
    	 *		3) In the tNomenclature table you must respect order and indexes 
    	 */
    	 
     	$options3 =  Array(0 => "Classe d'options nomenclature", 1 => "Poteaux périm.", 2 => "Tiges ancrage", 3 => "Câbles Trans.", 4 =>"Serre câbles Trans.", 5 =>"Cosses coeur", 6 => "Chapeaux Posés", 7 => "Etriers", 8 => "Socles posés", 9 => 'Filets', 10 => 'Fixations pliage', 11 => 'Plaquettes faitage', 12 => 'Fil de fer', 13 => 'Plaquettes chapelle', 14 => 'Sandow', 15 => 'Chapeaux périm.', 16 => 'Câble faîtage', 17 => 'Poteaux posés', 18 => 'Serres câble faîtage');

    	/**
    	 *	Warning ! Here the first index
    	 *	reflect the position in $_labelForm array
    	 */
    	 
    	$this->_labelForm[4][2]= $options;
    	$this->_labelForm[6][2]= $options2;
    	$this->_labelForm[8][2]= $options3;

   	parent::__construct($name);
    	$this->setDisableLoadDefaultDecorators(false); 
    	$this->setDecorators(array('FormElements', array('HtmlTag', array('tag' => 'dl', 'class' => 'MaClass')), 'Form'));
    }
    
    public function  getClassOption() {
    	    return $this->_labelForm[8][2];
    }
    	    

}
?>