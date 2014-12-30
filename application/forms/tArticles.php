<?php
/**
 * Created on May 8, 2009
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
 class Form_tArticles extends Form_GenForm
{
	/**
	 *  has_TinyMce processed in layout
	 *  $has_TinyAdvanced processed here
	 *  $is_ListJson processed in  controller
	 */	
	 
	public $has_TinyMce= true;
	public $has_TinyAdvanced=true;
	public $is_ListJson=false;
		
	public $orderby ='c_name ASC';
	
	/**
	 * 	Table fields not present in form
	 */
	protected $_cols_not_inform=Array('id');
	
	/**
	 * 	Complementary fields in form
	 */
	protected $_comp_inform=Array();
	protected $_txtFeatures =  array('validators' => array('StripTags', 'StringTrim'), 'filters' => array('NotEmpty'), 'required' => true);
	
	/**
	 * 	Label , type input, size 
	 *  for each element in form
	 */

	protected $_labelForm=Array(array('Type', 'select', Array('0' => 'Select a type', '1' =>'Article standard', '2' => 'F.A.Q'), NULL), 
				array('Titre', 'text', array('size' => '27', 'control' => 'alphadiac'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
				array('Résumé', 'textarea', Array('cols'=>'25', 'rows' => '20'), array('validators' => array('NotEmpty'), 'filters' => array(), 'required' => true)), 
				array('Contenu', 'textarea',  array('cols' =>'25', 'rows' => '20'), array('validators' => array('NotEmpty'), 'filters' => array(), 'required' => true)),  
	                        array('Auteur',  'comboBox_Dojo', Array('searchAttrib'=>'username', 'list' =>'tUser'), NULL), 
	                        array('Date Publication', 'datetextbox_Dojo', Array('size'=>'20'), NULL), 
	                        array('Date de retrait', 'datetextbox_Dojo', Array('size'=>'20'), NULL), 
	                        array('Thème', 'datetextbox_Dojo', Array('size'=>'20'), NULL),
	                        array('Statut', 'select',  Array('0' => 'Select a status', '1' =>'Publication', '2' => 'Attente', '3' => 'Ecriture'), NULL), 
                                array('Mots clés', 'textbox_Dojo', Array('size'=>'20'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Langue / Pays', 'comboBox_Dojo', Array('searchAttrib'=>'username', 'list' =>'countrylist' ), NULL), 
                                array('Date de création', 'datetextbox_Dojo', Array('size'=>'25'), NULL),
                                array('Traduction', 'comboBox_Dojo', Array('searchAttrib'=>'username', 'list' =>'countrylist' ), NULL));
                                
	/**
	 * 	Listing display parameters
	 */
    public $display=Array('title' => array('0'=>'Titre', '1'=>'Auteur', '2' => 'Date de création' ),
    					  'value' => array('0'=>'c_name', '1' => 'Author', '2' =>'tCreatedOn'));
     public function  __construct($name='tArticles') { 
    	parent::__construct($name);
    }
}
?>