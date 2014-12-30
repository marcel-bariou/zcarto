<?php

class Form_tGroup extends Form_GenForm
{

	/**
	 *  has_TinyMce processed in layout
	 *  $has_TinyAdvanced processed here
	 *  $is_ListJson processed in  controller
	 */	

	public $has_TinyMce= true;
	public $has_TinyAdvanced=false;
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
	
	/**
	 * 	Label , type input, size 
	 *  for each element in form
	 */
	public $_labelForm=Array(array('Nom du groupe', 'text', array('size' => '35', 'control' => 'alphadiac'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)),
					array('Objet', 'multiselect', array('size' => '35', 'control' => 'alphadiac'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)),
					array('Description', 'textarea',  array('cols' =>'52', 'rows' => '5'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
					array('Droit sur objet', 'select', array('size' => '40'), NULL));

	/**
	 * 	Listing display parameters
	 */
    public $display=Array('title' => array('0'=>'Role Name', '1'=>'Description', '2' => 'Description' ),
    					  'value' => array('0'=>'c_name', '1' => 'c_descript', '2' => 'c_role'));
		
 
    public function  __construct($name='tGroup') { 
    	    	
    	$query = 'SELECT id, c_name FROM tRole ';
    	$db1= Zend_Registry::get('db1');
    	$db1->setFetchMode(Zend_Db::FETCH_OBJ);
    	$result = $db1->fetchAll($query);
    	foreach($result as $key => $value){
    		$options[$result[$key]->id]= $result[$key]->c_name;    	}
    	
    	$this->_labelForm[3][2]= $options; 
    	
    	$options_2=array();
    	$valo = scandir($_SERVER['DOCUMENT_ROOT'].'/zcarto/application/forms/'); 
    	 $i=0;
    	 foreach($valo as $namefile) {
    	 	 if ((substr($namefile, 0, 1)!=='t' && substr($namefile, 0,1)!=='f')||substr($namefile, strlen($namefile)-1, 1)=='~'){
    	 	 }else{
    	 	 	 $options_2[$i]= substr($namefile, 0, strlen($namefile)-4);
    	 	 	 $options_3[substr($namefile, 0, strlen($namefile)-4)]= substr($namefile, 0, strlen($namefile)-4);
    	 	 }
    	 	 $i++;
    	 }
    	 $this->_labelForm[1][2]=$options_3;
    	
    	parent::__construct($name);
    }
}