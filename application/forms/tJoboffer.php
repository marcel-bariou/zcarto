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
  
 class Form_tJoboffer extends Form_GenForm
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
	 
	 
	 public $_labelForm=Array(array('Nom entreprise', 'text', array('size' => '35', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Adresse Ligne 1', 'text', array('size' =>'27', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Adresse ligne 2', 'text', array('size' =>'27', 'value'=>'---', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Code Postal', 'text',  array('size' =>'27', 'control'=> 'numspace'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Ville', 'text', array('size' => '27', 'control' => 'alphadiac'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Tél.', 'text', array('size' => '27', 'control' => 'phonefax'), NULL), 
                                array('Activités', 'text', array('size' => '27', 'value'=> 'qualificatifs et espaces', 'control' => 'alphanumvirgapos'), NULL), 
                                array('Nom Contact', 'text', array('size' => '27', 'control' => 'alphanumvirgapos'), NULL),  
                                array('Tél. Contact', 'text', array('size' => '27', 'control' => 'phonefax'), NULL), 
                                array('@Mail Contact', 'text', array('size' => '27', 'value' => 'monmail@mail.com', 'control' => 'email'), NULL),
                                array('Poste proposé', 'text', array('value'=> 'Libellé du poste', 'size' => '27', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Date embauche', 'datetextbox_Dojo', Array('size'=>'25'), NULL),
                                array('Nbre postes', 'text', array('size' => '27', 'value' => '1', 'control' => 'phonefax'), NULL),                                 
                                array('Type contrat', 'select',  array('size' =>'27'), NULL), 
                                array('Qualification', 'select',  array('size' =>'27'), NULL), 
                                array('Type Orga', 'select',  array('size' =>'27'), NULL),
				array('Comments', 'textarea', array('cols' =>'57', 'rows' => '10', 'value'=>'Commentaires éventuels'), array('validators' => array('NotEmpty'), 'filters' => array(), 'required' => true)));
 	/**
	 * 	Listing display parameters
	 */	 
    public $display=Array('title' => array('0'=>'Nom', '1'=> 'Poste', '2' => 'Ville', '3' => 'Qualification' ),
    					  'value' => array('0'=>'c_name', '1' => 'jobname', '2' => 'City', '3' =>'skill'));

    public function  __construct($name='tJoboffer') {  
    	$options_1 = Array(0 => "Type de l'offre", 1 => "CDI à temps plein", 2 => "CDD à temps plein", 3 => "CDI à temps plein", 4 => "CDD à temps partiel" , 5 => "CDI à temps partiel", 6 => "Apprentissage", 7 => "Autres");
   	$options_2=  Array(0 => "Niveau de Qualification" , 1 => "Ouvrier non spécialisé", 2 => "Ouvrier spécialisé", 3 => "Employé", 4 => "Technicien", 5 => "Agent de maîtrise", 6 => "Cadre");
     	$options_3 = Array(0 => "Industrie", 1 => "Installateur Constructeur", 2 => "Artisanat->Particuliers", 8 => "Artisanat->Entreprises", 10 => "BTP", 11 => "Negoce Fruits/Légume", 12 => "Transport Logistique", 13 => "Gde Distribution", 14 => "Commerce Alimentaire", 15 => "Commerce Textile", 16 => "Autres Commerces", 17 => "Bar restauration", 18 => "Hôtellerie", 19 => "Prof. de santé", 20 => "Banques Assurances", 21 => "Education/Formation", 22 => "Secteur Public");
	
	/**
	 * // 	$options = Array(0 => "Cliente", 1 => "Fournisseur", 2 => "Gouvernementale", 3 => "Partenaire", 4 => "Employeur");
	 * 	Construction des listes de sélection
    	 *	Warning ! Here the first index
    	 *	reflect the position in $_labelForm array
    	 */
    	 
    	$this->_labelForm[13][2]= $options_1;
    	$this->_labelForm[14][2]= $options_2;
   	$this->_labelForm[15][2]= $options_3;
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