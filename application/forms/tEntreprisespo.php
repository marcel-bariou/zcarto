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
  
 class Form_tEntreprisespo extends Form_GenForm
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
                                array('Chiffres Affaires', 'text',  array('value' => 'comments', 'size' => '27', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)),                                 
                                array('Certifié ISO ?',  'select',  array('size' =>'27'), NULL),
                                array('BE/Méthodes ?',  'select',  array('size' =>'27'), NULL),
                                array('Atelier production ?',  'select',  array('size' =>'27'), NULL),
                                array('Code activité (NAF)', 'text', array('value' => 'comments', 'size' => '27', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Ref. & Contrôle', 'comboBox_Dojo', Array('searchAttrib'=>'username', 'list' =>'tUser' ), NULL),
                                array('Type Orga.', 'select',  array('size' =>'27'), NULL), 
                                array('Latitude', 'text', array('value' => '4.45632', 'size' => '27', 'control' => 'numfloat'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)),
                                array('Longitude', 'text',array('value' => '4.45632', 'size' => '27', 'control' => 'numfloat'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)),
                                array('Langue', 'comboBox_Dojo', Array('searchAttrib'=>'username', 'list' =>'countrylist' ), NULL),
                                array('Zones',  'select',  array('size' =>'27'), NULL),
                                array('Valeur GPS correcte',  'select',  array('size' =>'27'), NULL),
                                array('Vérifié cette année ?',  'select',  array('size' =>'27'), NULL),
                                array('Date de vérification', 'datetextbox_Dojo', Array('size'=>'25'), NULL),
                                array('Contrôle existence ?',  'select',  array('size' =>'27'), NULL),
                                array('Potentiel emploi ?',  'select',  array('size' =>'27'), NULL));
 	/**
	 * 	Listing display parameters
	 */	 
    public $display=Array('title' => array('0'=>'Nom', '1'=> 'Zones', '2' => 'Ville', '3' => 'Pays' ),
    					  'value' => array('0'=>'c_name', '1' => 'zones', '2' => 'City', '3' =>'country'));

    public function  __construct($name='tEntreprisespo') {  
    	$options=Array();
    	$options = Array(0 => "Industrie", 1 => "Installateur Constructeur", 2 => "Artisanat->Particuliers", 3 => "Location véhicules, outillages, machines", 4 => "Pompes funebres", 5 => "Informatique, services, ventes, maintenance",  6 => "Coiffure, esthetique, manucure",7 => "Immobilier", 8 => "Artisanat->Entreprises",9 => "Bureau études, Architecte", 10 => "BTP", 11 => "Negoce Fruits/Légume", 12 => "Transport Logistique", 13 => "Gde Distribution", 14 => "Commerce Alimentaire, metiers de bouche", 15 => "Commerce Textile", 16 => "Autres Commerces", 17 => "Café Bar Hotel Restauration", 18 => "Hôtellerie", 19 => "Prof. de santé", 20 => "Banques Assurances", 21 => "Education/Formation", 22 => "Secteur Public", 23 => "Activités Financières", 24 => "Communication, Presse", 25 => "Comptabilité, gestion", 26 => "Activités sportives", 27 => "Electroménager ventes services", 28 => "Artisan bois", 29 => "Artisan electricité", 30 => "Artisan plomberie", 31 => "Artisan mécanique", 32 => "Artisan sanitaire, chauffage", 33 => "Artisan peintre, maçon, plâtrier, carreleur", 34 => "Artisan couverture, bardage", 35 => "Juriste, avocat, conseil");
   	
    	$options_2=Array();
    	$options_3 = Array(0 => "Certification qualité ?", 1 => "Non", 2 => "Oui");
	$options_4 = Array(0 => "Etudes/méthodes/Labo", 1 => "Non", 2 => "Oui");
	$options_5 = Array(0 => "Atelier de production", 1 => "Non", 2 => "Oui");
	$options_1 = Array(0 => "Validez votre vérification", 1 => "Non", 2 => "Oui");
	$options_6 = Array(0 => "Géoréférence OK ?", 1 => "Non", 2 => "Oui");
	$options_7 = Array(0 => "Nécessaire ?", 1 => "Non", 2 => "Oui");
	$options_8 = Array(0 => "A évaluer", 2 => "Normal", 3 => "Faible");
	
	/**
	 * // 	$options = Array(0 => "Cliente", 1 => "Fournisseur", 2 => "Gouvernementale", 3 => "Partenaire", 4 => "Employeur");
	 * 	Construction des listes de sélection
	 */
		
	$query = 'SELECT id, c_name FROM tZones ';
	$db1= Zend_Registry::get('db1');
	$db1->setFetchMode(Zend_Db::FETCH_OBJ);
	$result = $db1->fetchAll($query);
	$result = $db1->fetchAll($query);
	$options_2[0]= "Veuillez choisir une zone";
	foreach($result as $key => $value){
		$options_2[$result[$key]->id]= $result[$key]->c_name." - ".$result[$key]->id;    	
	}
   	
    	/**
    	 *	Warning ! Here the first index
    	 *	reflect the position in $_labelForm array
    	 */
    	 
    	$this->_labelForm[15][2]= $options_3;
    	$this->_labelForm[16][2]= $options_4;
   	$this->_labelForm[17][2]= $options_5;
   	$this->_labelForm[20][2]= $options;
   	$this->_labelForm[24][2]= $options_2;    	
    	$this->_labelForm[25][2]= $options_6;
    	$this->_labelForm[26][2]= $options_1;
    	$this->_labelForm[28][2]= $options_7;
    	$this->_labelForm[29][2]= $options_8;
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