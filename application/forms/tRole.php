<?php

class Form_tRole extends Form_GenForm
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
	 *  	for each element in form
	 */
	protected $_labelForm=Array(array('Name', 'text', array('size' => '35', 'control' => 'alphadiac'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
				    array('Description', 'textarea', array('cols' =>'52', 'rows' => '5'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
				    array('Droit', 'multiselect', array('size' => '35', 'control' => 'alphadiac'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)));
	
	/**
	 * 	Listing display parameters
	 */
	 public $display=Array('title' => array('0'=>'Role Name', '1'=>'Description', '2' => 'Internal Name' ),
    					  'value' => array('0'=>'c_name', '1' => 'c_descript'), '2' => 'entitle');

    	 

    public function  __construct($name='tRole') { 
    	 $options=array();
    	 $options['rien']= 'rien';
    	 $options['create']= 'create';
    	 $options['read']= 'read';
    	 $options['update']= 'update';
    	 $options['delete']= 'delete';
    	 $options['publish']= 'publish';
    	 $options['list']= 'list';
    	 $this->_labelForm[2][2]=$options;
    	parent::__construct($name);
    }

}