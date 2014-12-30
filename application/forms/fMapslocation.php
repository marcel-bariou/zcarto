<?php

class Form_fMapslocation extends Form_GenForm
{

	/**
	 *  has_TinyMce processed in layout
	 *  $has_TinyAdvanced processed here
	 *  $is_ListJson processed in  controller
	 */	

	public $has_TinyMce= false;
	public $has_TinyAdvanced=false;
	public $is_ListJson=false;
	
	/**
	 * 	Table fields not present in form
	 */
	protected $_cols_not_inform=Array('');
	
	/**
	 * 	Complementary fields in form
	 */
	protected $_comp_inform=Array();
	
	/**
	 * 	Label , type input, size 
	 *  for each element in form
	 */
	public $_labelForm=Array(array('Name', 'text', array('size' => '35', 'control' => 'alphadiac'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
					array('Description', 'textarea',  array('cols' =>'52', 'rows' => '5'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
					array('Role list', 'multiselect', array('size' => '40'), NULL));

	/**
	 * 	Listing display parameters
	 */
    public $display=Array('title' => array('0'=>'Role Name', '1'=>'Description', '2' => 'Description' ),
    					  'value' => array('0'=>'c_name', '1' => 'c_descript', '2' => 'c_role_smul'));
		
 
    public function  __construct($name='tGroup') { 
    	    	
    	$query = 'SELECT id, c_name FROM tRole ';
    	$db1= Zend_Registry::get('db1');
    	$db1->setFetchMode(Zend_Db::FETCH_OBJ);
    	$result = $db1->fetchAll($query);
    	foreach($result as $key => $value){
    		$options[$result[$key]->id]= $result[$key]->c_name;    	}
    	
    	$this->_labelForm[2][2]= $options; 
    	
    	parent::__construct($name);
    }
}