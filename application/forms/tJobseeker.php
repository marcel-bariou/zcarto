<?php
/**
 * Created on Jan 8, 2014
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
  
 class Form_tJobseeker extends Form_GenForm
{
	
	public $has_TinyMce= true;
	public $has_TinyAdvanced=false;
	public $is_ListJson=true;
	public $vectJson=array('c_fname', 'c_lastname');
	public $orderby ='c_fname ASC';
	
	/**
	 * 	Table fields not present in form
	 */
	protected $_cols_not_inform=Array('id');
	
	/**
	 * 	Complementary fields in form
	 */
	protected $_comp_inform=Array();	
	protected $_sourceInfo = array('Choisir une source', 'Midi Libre', 'Indépendant', 'Petit Journal', 'Semaine du Roussillon', 'FR3', 'France Bleu', 'Affiches', 'Amis', 'Internet', 'COMIDER', 'AFPA', 'IRFA', 'E2C', 'Pôle Emploi', 'MDEE', 'MLI-MLJ' );
	protected $_mobility = array('Aucune', 'Pyrénées Orientales', 'Languedoc-Roussillon', 'France', 'Europe', 'Monde');
	protected $_job = array('Choisir', 'Indépendant', 'Cadre', 'Maîtrise/Technicien', 'Apprenti/stagiaire', 'Employé(e)', 'Ouvrier(e)');
	protected $_sector = array('Choisir', 'BTP', 'Agro-alimentaire', 'Gde Distribution', 'Agriculture', 'Artisanat bois','Artisanat énerge', 'Artisanat mécanique','Artisanat Electricité','Artisanat métier de bouche', 'Transport', 'Restauration', 'Hôtellerie', 'Loisirs et sports');
	protected $_degree = array('Choisir', 'Scolarité Minimale', 'CAP', 'BEP', 'BAFA', 'BEPC', 'Bac. géné., pro., Techno', 'BTS', 'DUT', 'Licence', 'Maîtrise', 'Bac+4', 'Bac+5', 'Ingénieur', 'Doctorat');
	protected $_registration = array('Choisir', 'RSA', 'APEC', 'Pôle emploi', 'Autres');
	
	
	/**
	 * 	Label , type input, size 
	 *  for each element in form
	 */
	protected $_labelForm=Array(array('Civilité', 'select', Array('0' => 'Select a type', '1' =>'Mr', '2' =>'Mme', '3' =>'Melle'), NULL),
				array('First Name', 'text', array('size' => '27', 'control' => 'alphadiac'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
				array('Last Name', 'text', array('size' => '27', 'control' => 'alphadiac'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
				array('Date de naissance', 'datetextbox_Dojo', Array('size'=>'25'), NULL),
				array('Login', 'text', array('size' =>'27', 'control' => 'alphab'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
				array('Password', 'passwordtextbox_Dojo', array('size' => '27', 'control' => 'pswd'), NULL), 
                                array('Adress Ligne 1', 'text', array('size' =>'27', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Adresse ligne 2', 'text', array('size' =>'27', 'value'=>'--','control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => false)), 
                                array('Code Postal', 'text', array('size' =>'27', 'control'=> 'numspace'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Ville', 'text', array('size' => '27', 'value'=>'Perpignan', 'control' => 'alphadiac'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
                                array('Région', 'text', array('size' => '27', 'value'=>'Languedoc', 'control' => 'alphadiac'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => false)), 
                                array('Pays', 'comboBox_Dojo', Array('searchAttrib'=>'username', 'list' =>'countrylist' ), NULL), 
                                array('Télépnone', 'text', array('size' => '27', 'control' => 'phonefax'), NULL),  
                                array('Mail', 'text', array('size' => '27', 'control' => 'email'), NULL), 
				array('Situation Actuelle',  'multiselect', Array('0' => 'Précisez votre situation', '1' =>'Chômeur - 1 an', '2' =>'Chômeur + 1 an', '3' =>'En formation', '4' => 'Sortie études recherche 1er emploi'), NULL),
                                array('Innscriptions', 'multiselect', array('size' =>'27'), NULL), 
				array('Diplômes', 'select', array('size' => '27'), NULL),
				array('Dernier emploi ?', 'select', array('size' => '27'), NULL),
				array('Date sortie emploi', 'datetextbox_Dojo', Array('size'=>'25'), NULL),
				array('En Formation ?', 'select', Array('0' => 'Non', '1' =>'Oui'), NULL),
				array('Secteur ciblé ?', 'select', array('size' => '27'), NULL),
				array('Mobilité ?', 'select', array('size' => '27'), NULL),
				array('Temps partiel', 'select', Array('0' => 'Non', '1' =>'Oui'), NULL),
				array('Info from ?', 'select', array('size' => '27'), NULL),
				array('Voiture le jour J ?', 'select', Array('0' => 'Non', '1' =>'Oui'), NULL),
				array('Date de création', 'datetextbox_Dojo', Array('size'=>'25'), NULL),
				array('Comments', 'textarea', array('cols' =>'57', 'rows' => '10', 'value' => 'Donnez un avis si vous voulez'), array('validators' => array('NotEmpty'), 'filters' => array(), 'required' => false)));

	//public $displayGroup= array('group' => array('c_username', 'password') ,
	//						'name' => 'Login');
	/**
	 * 	Listing display parameters
	 */
	 
	public $display=Array('title' => array('0'=>'First Name', '1'=>'Last Name', '2' => 'User Name' ),
					  'value' => array('0'=>'c_fname', '1' => 'c_lastname', '2' =>'c_username'));
	
	
	public function  __construct($name='tJobseeker') {  
		
		$options=Array();
		$query = 'SELECT id, c_name FROM tGroup ';
		$db1= Zend_Registry::get('db1');
		$db1->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $db1->fetchAll($query);
		foreach($result as $key => $value){
			$options[$result[$key]->id]= $result[$key]->c_name;    	}
		
		$this->_labelForm[15][2]= $this->_registration;
		//$this->_labelForm[14][2]= $options;
		$this->_labelForm[16][2]= $this->_degree;
		$this->_labelForm[17][2]= $this->_job;
		$this->_labelForm[20][2]= $this->_sector;
		$this->_labelForm[21][2]= $this->_mobility;
		$this->_labelForm[23][2]= $this->_sourceInfo;
		parent::__construct($name);
	}
    
	public function getLabelForm($x,$y){
    	    return $this->_labelForm[$x][$y];
    	}


}
?>