<?php

class Form_tDevisetc extends Form_GenForm
{

	/**
	 *  has_TinyMce processed in layout
	 *  $has_TinyAdvanced processed here
	 *  $is_ListJson processed in  controller
	 * ALTER TABLE`tDevisetc` ADD `datehour` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ;
	 */	

	public $has_TinyMce= true;
	public $has_TinyAdvanced=false;
	public $is_ListJson=false;
	public $orderby ='c_name ASC';
	/**
	 * 	Table fields not present in form
	 */
	protected $_cols_not_inform=Array('id', 'datehour');
	
	/**
	 * 	Complementary fields in form
	 */
	protected $_comp_inform=Array();
	
	/**
	 * 	Label , type input, size 
	 *  for each element in form
	 */
	public $_labelForm=Array(array('Nom', 'text', array('size' => '35', 'control' => 'alphanumvirgapos'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)),
					array('Projet concerné', 'select', array('size' => '40'), NULL),
					array('Date Initiale', 'datetextbox_Dojo', Array('size'=>'25'), NULL),
					array('Remarques', 'textarea',  array('cols' =>'52', 'rows' => '5'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
					array('Liste des tâches', 'multiselect', array('size' => '35', 'control' => 'alphadiac'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)),
					array('Resp. Devis', 'comboBox_Dojo', Array('searchAttrib'=>'username', 'list' =>'tUser' ), NULL),
					array('Verrouillé ?',  'select',  array('size' =>'27'), NULL));

	/**
	 * 	Listing display parameters
	 */
    public $display=Array('title' => array('0'=>'Projet', '1'=>'Démarrage prévu', '2' => 'Reference devis', '3' => 'Producteur devis' ),
    					  'value' => array('0'=>'c_name', '1' => 'dateinit', '2' => 'datehour', '3' => 'manager'));
		
 
    public function  __construct($name='tDevisetc') { 
    	    	
    	$query = 'SELECT id, c_name FROM tProjectetc ';
    	$db1= Zend_Registry::get('db1');
    	$db1->setFetchMode(Zend_Db::FETCH_OBJ);
    	$result = $db1->fetchAll($query);
    	$options[0] = "Projet concerné ?";
    	foreach($result as $key => $value){
    		$options[$result[$key]->id]= $result[$key]->c_name;    	
    	}
    	
    	$query = 'SELECT  tTacheetc.c_name as c_name1, tLotchapetc.c_name as c_name2, tTacheetc.id as id  FROM tLotchapetc, tTacheetc where tTacheetc.pkgchapter=tLotchapetc.id ORDER BY tLotchapetc.c_name';
    	$db1= Zend_Registry::get('db1');
   	$db1->setFetchMode(Zend_Db::FETCH_OBJ);
   	$result = $db1->fetchAll($query);//var_dump($result);die();
    	foreach($result as $key => $value){
    		$options2[$result[$key]->id]= $result[$key]->c_name2.'-'. $result[$key]->c_name1;  
    	}
    	$options_3 = Array(2 => "Devis définitif ?", 0 => "Non", 1 => "Oui");
    	$this->_labelForm[1][2]= $options; 
    	$this->_labelForm[4][2]= $options2; 
    	$this->_labelForm[6][2]= $options_3;
    	
    	parent::__construct($name);
    }
}