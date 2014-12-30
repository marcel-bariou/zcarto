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
 * @package    Setting Up
 * @author	   Marcel Bariou	
 * @copyright  Copyright (c) 2005-2009 Brasnah sarl (http://www.brasnah.com)
 * @license    http://www.brasnah.com/lpgl.txt    
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
 /**
  *     This class helps you to build form linked to a specific table  
  * 	with elements of you choice, parametred with default attributs
  * 	After the generation of your class you have to edit it for adjustement 
  */
  
 class Form_BasicForm extends Zend_Form
{
	/**
	 * 	Database Schema Name & MetaData
	 *  Not used, provision for near future
	 */
	public $trancode_type=Array('INT' => 'text', 'VARCHAR' => 'text', 'TEXT' => 'textarea', 
								'DATE' => 'text', 'TINYINT' => 'text', 'SMALLINT' => 'text', 
                                'MEDIUMINT' => 'text', 'INT' => 'text', 'BIGINT' => 'text', 
                                'DECIMAL' => 'text', 'FLOAT' => 'text', 'DOUBLE' => 'text', 
								'REAL' => 'text', 'BIT' => 'text', 'BOOL' => 'text', 
                                'SERIAL' => 'text', 'DATE' => 'text', 'TIMESTAMP' => 'text', 
                                'TIME' => 'text', 'YEAR' => 'text', 'CHAR' => 'text', 
                                'TINYTEXT' => 'text', 'TEXT' => 'text', 'MEDIUMTEXT' => 'text', 
                                'LONGTEXT' => 'text', 'BINARY' => 'default', 'VARBINARY' => 'default', 
                                'TINYBLOB' => 'default', 'MEDIUMBLOB' => 'default', 'BLOB' => 'default', 
                                'LONGBLOB' => 'default', 'ENUM' => 'default', 'SET' => 'default', 'GEOMETRY' => 'default', 
                                'LINESTRING' => 'default', 'POLYGON' => 'default', 'MULTIPOINT' => 'default', 
                                'MULTILINESTRING' => 'default', 'MULTIPOLYGON' => 'default', 'GEOMETRYCOLLECTION' => 'default');
    /**
     * 	Liste and default attribut for a first setting
     *  Each new element mut be inserted in the element Json list
     */                            
	private $defaultAttributs = array('text' => "array('size' => '27'), array('validators' => array('StripTags', 'StringTrim'), 'filters' => array('NotEmpty'), 'required' => true)",
									  'textarea' => "array('cols' =>'27', 'rows' => '20'), array('validators' => array('StripTags', 'StringTrim'), 'filters' => array('NotEmpty'), 'required' => true)",
									  'select' => "array('0' => 'Select a type', '1' =>'Article standard', '2' => 'F.A.Q'), NULL",
									  'multiselect' => "array('0' => 'val0', '1' => 'val1', '2' => 'val2', '3' => 'val3'), NULL",
									  'comboBox_Dojo' => "array('searchAttrib'=>'username', 'list' =>'countrylist' ), NULL",
									  'textarea_Dojo' => "array('cols' =>'27', 'rows' => '20'), NULL",
									  'datetextbox_Dojo' => "Array('size'=>'20')",
									  'validtextbox_Dojo' => "array('valid' =>'phoneStrict', 'error' =>'phone', 'prompt' => 'phone'), NULL",
									  'passwordtextbox_Dojo' => "array('valid' =>'pswdStrict', 'error' =>'pswd', 'prompt' => 'pswd'), NULL",
									  'checkbox_Dojo' => "array('checked' => 'On', 'unchecked' => 'Off', 'default' => 'Off'), NULL",
									  'zendbox_Dojo' => "array('path' =>$_SERVER["DOCUMENT_ROOT"].'/ExpZF/public/audios', 'validators' => array('Count' => '5', 'Size' => '25000000', 'Extension' => 'jpg,gif,png,odt,doc,pdf,flv,mov')), NULL");
	public $listElems=Array();
	public $objElemens=Array();
	private $_labelForm;
	public $has_TinyMce= false;
	public $has_TinyAdvanced=false;
	


	public function __construct($name, $TbMetaInf=NULL, $elemform=NULL, $formData=NULL)
    { 
        parent::__construct($options=null);
 
        /**
         * 	$metainf initialisation with the common element of the form
         */    	
        $metaInf['cols'][]='hasTiny';
        $metaInf['cols'][]='advTiny';
        $metaInf['cols'][]='isJson';
        $metaInf['cols'][]='notInForm';
    	
        $this->_labelForm[]= array('Set Editor ?', 'checkbox_Dojo', array('checked' => 'On', 'unchecked' => 'Off', 'default' => 'Off'), NULL);
        $this->_labelForm[]= array('Advanced  Editor ?', 'checkbox_Dojo', array('checked' => 'On', 'unchecked' => 'Off', 'default' => 'Off'), NULL);
        $this->_labelForm[]= array('Generate Json ?', 'checkbox_Dojo', array('checked' => 'On', 'unchecked' => 'Off', 'default' => 'Off'), NULL);
        $this->_labelForm[]= array('Not in Form ?', 'multiselect', $TbMetaInf['cols'], NULL);
		
		if($formData==NULL){
		
			/**
			 * 	Step 2
			 *  Labels and attributs definition for the new element of the 
			 *  form linked to the analysed table
			 *  $metainf completion
			 */
			
			if($elemform !=NULL){			
				foreach($elemform as $key => $value){								
					$this->_labelForm[]=array(strtoupper($value),  'comboBox_Dojo', array('searchAttrib'=>'username', 'list' =>'elements'), NULL);
					$metaInf['cols'][]=$value;				
				}
    		}
	
			/**
			 * 	Step 1 & 2
			 * 	Complete data setup for the formgenerator
			 */
			 
			$i=0;
			while(isset($metaInf['cols'][$i])){				
				$this->listElems[]=array('name'=> $metaInf['cols'][$i], 'label'=> $this->_labelForm[$i][0], 'type' => $this->_labelForm[$i][1], 'attribs' => $this->_labelForm[$i][2], 'features' => $this->_labelForm[$i][3]);
				$i++;
			}
    	}else{
    		
    		/**
    		 * 	Step 3
    		 * 	Now data definition are ready to create the class
    		 */
    		 
    		 $this -> generateClass($elemform, $TbMetaInf, $formData);
    		
    	}
     }
     
     /**
      * 
      */
      
     private function generateClass($elemform, $TbmetaInf, $formData){
     	
 		$tpl3 = Zend_Registry::get('tpl3');
    	$htmltpl3="source/tBform.tpl";
    	$tpl3->setFile("MyFileHandle", $htmltpl3);    	
    	$tpl3->setBlock("MyFileHandle", "listelem", "listelems");
    	    	
    	$tpl3->setVar("CLASSNAME", "Form_".$formData['className']);
    	$tpl3->setVar("TABLE", $formData['className']);
    	
    	if($formData['hasTiny']=='On')
    	{
    		$tpl3->setVar("HASTIN", "true");
    	}else{
    		$tpl3->setVar("HASTIN", "false");
    	}
    	
    	if($formData['advTiny']=='On')
    	{
    		$tpl3->setVar("HASTINADV", "true");
    	}else{
    		$tpl3->setVar("HASTINADV", "false");
    	}
    	
    	if($formData['isJson']=='On')
    	{
    		$tpl3->setVar("ISJSON", "true");
    	}else{
    		$tpl3->setVar("ISJSON", "false");
    	}
    	
    	$cnt= count($formData['notInForm'])-1;
    	$notinform='';
    	$i=0;
 		while(isset($formData['notInForm'][$i])){ 			
 			if($i<$cnt){
  				$notinform .= "'".$TbmetaInf['cols'][$formData['notInForm'][$i]]."', ";				
 			}else {
 				$notinform .= "'".$TbmetaInf['cols'][$formData['notInForm'][$i]]."'";
 			}
 			$i++;
 		}
 		
     	$tpl3->setVar("NOINFORM", "$notinform");
    	$tpl3->setVar("COMPINFORM", "");
    	$tpl3->setVar("TITLEDISPLAY", "'title' => array('0'=>'First Name', '1'=>'Last Name', '2' => 'User Name' )");
    	$tpl3->setVar("VALUEDISPLAY", "'value' => array('0'=>'c_fname', '1' => 'c_lastname', '2' =>'c_username')");

    	foreach($elemform as $key => $value)
    	{
     		$tpl3->setVar("LISTELEMENT", "array('".strtoupper($value)."','".$formData[$value]."' , ".$this->defaultAttributs[$formData[$value]]." ), ");
    		$tpl3->parse("listelems", "listelem", true);
     	}
    	$tpl3->parse( "MyOutput", "MyFileHandle");
    	$classForm=$tpl3->get( "MyOutput");
    	$classForm='<\?php\n'.$classForm.'\n?>';
    	$handle = fopen("../application/forms/".$formData['className'].".php", "w+");
		$this->fwrite_stream($handle, $classForm);
	}
	
	private function fwrite_stream($fp, $string) {
        $fwrite = fwrite($fp, $string, strlen($string));        
    }
}
?>