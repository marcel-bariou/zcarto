<?php

class Form_tNomenclature extends Form_GenForm
{	
	public $has_TinyMce= true;
	public $has_TinyAdvanced=false;
	public $is_ListJson=true;
	public $orderby ='c_name ASC';
	
	/**
	 *	Associations with the tAppros array option for classes of options
	 *      Array(0 => 'Label of tNomenclature form.', 'Variable of template' => Index of class option)
	 */
	 
	public $arrayOptionalArticle = Array(Array(0 => 'Pot. périm.', 'oPoteau' => 1), Array(0 => 'Tige ancrage', 'oTigeAnc' => 2), Array(0 => 'Câble Transv.', 'oCabT' => 3 ), Array(0 => 'Serre-cable Transv.', 'oSerCabT' => 4 ), Array(0 => 'Cosse coeur', 'oCosseCoeur' => 5 ), Array(0 => 'Chapeaux posés', 'oChapeauT' => 6 ), Array(0 => 'Etrier', 'oEtrier' => 7 ), Array(0 => 'Socles posés', 'oSocleBase' => 8 ), Array(0 => 'Filet', 'oFilet' => 9 ), Array(0 => 'Fixation', 'oFix' => 10 ), Array(0 => 'Plaq. faîtage', 'oPlqFait' => 11 ), Array(0 => 'Fil Chapelle', 'oFilChap' => 12 ), Array(0 => 'Plaquette chap', 'oPlqChp' => 13 ), Array(0 => 'Sandow', 'oSandow' => 14), Array(0 => 'Chapeaux périm.', 'oChapeauP' => 15), Array(0 => 'Câble faîtage', 'oCabFait' => 16 ), Array(0 => 'Poteaux posés', 'oPotAlign' => 17 ), Array(0 => 'Serre câble Fait.', 'oSerCabF' => 18 ));

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
	 */
	protected $_comp_inform=Array();
	
	/**
	 * 	Label , type input, size 
	 *  	for each element in form
	 */
	protected $_labelForm=Array(array('Nom', 'text', array('size' => '35', 'control' => 'alphadiac'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
				    array('Description', 'textarea', array('cols' =>'52', 'rows' => '5'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => true)), 
				    array('Modèle', 'dojoEditor',  array('cols' =>'52', 'rows' => '5'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => false)),
				    array('Chemin Xml', 'dojoEditor',  array('cols' =>'52', 'rows' => '5'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => false)),
				    array('Nomenclature', 'dojoEditor',  array('cols' =>'52', 'rows' => '5'), array('validators' => array('NotEmpty'), 'filters' => array('StripTags', 'StringTrim'), 'required' => false)));
	
	/**
	 * 	Listing display parameters
	 */
	 public $display=Array('title' => array('0'=>'Nom', '1'=>'Description' ),
    					  'value' => array('0'=>'c_name', '1' => 'c_descript'));



    public function  __construct($name='tNomenclature') {
    	
    	$db1= Zend_Registry::get('db1');
    	$db1->setFetchMode(Zend_Db::FETCH_OBJ);
    	$i=5;
    	$k = 0;
    	
    	/**
    	 *	List options built from tAppros
    	 *	For each options (Variable to replace) created in the xml template
    	 *	You must find its value in  $this->arrayOptionalArticle. Make a check!
    	 */
    	
    	
    	while (isset ($this->arrayOptionalArticle[$k])){    		
    		array_push( $this->_labelForm,  array($this->arrayOptionalArticle[$k][0], 'select',  array('size' =>'27'), NULL));
    		foreach($this->arrayOptionalArticle[$k] as $key => $value){
    			
    			if($key !== 0){
    				if(isset($newoptions)) unset($newoptions);
    				$newoptions= array();
    				$query = 'SELECT codeArticle, name, price FROM tAppros Where classOption='.$value;    	
    				$result = $db1->fetchAll($query);
    				foreach($result as $key1 => $value1){
    					$newoptions[$result[$key1]->codeArticle]= $result[$key1]->name.' - '.$result[$key1]->price;
    				}
    				$this->_labelForm[$i][2]= $newoptions;
    				$j=$i;
    				$i++;
    			}
    			
    		}
     		$k++;
    	}
    	// If process exhausted, now you save the new
    	parent::__construct($name);
    
   	/**
    	 *	Help for button (Submit;, cancel, reset) position
    	 */
    	 
    	$this->setDisableLoadDefaultDecorators(false); 
    	$this->setDecorators(array('FormElements', array('HtmlTag', array('tag' => 'dl', 'class' => 'MaClass')), 'Form'));
    
    }

}