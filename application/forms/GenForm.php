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
 
  
 /**
  * 	Class preparing element for forms linked to table
  */
 
 class Form_GenForm extends Zend_Form
{
		
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
	public $val_field;
	public $listElems=Array();
	public $objElemens=Array();
	public $has_TinyMce= false;
	public $has_TinyAdvanced=false;
	public $default_features = array('validators' => array('StripTags', 'StringTrim'), 'filters' => array('NotEmpty'), 'required' => true);
	
	public function __construct($name, $private='')
	{
		parent::__construct($options=null);
		$this->setName($name);
		$u= new Model_DbTable_GenTb($name);
		$metaInf = $u->getmetaInf();
		
		/*
		if($name =='tNomenclature')     { 
			var_dump($metaInf);
			print "<BR/>";
			var_dump($this->_labelForm);die();
		   }
		 */
		 
		if(count($this->_cols_not_inform)==0){		
			$i=0;
			while(isset($metaInf['cols'][$i])){				
				$this->listElems[]=array('name'=> $metaInf['cols'][$i], 'label'=> $this->_labelForm[$i][0], 'type' => $this->_labelForm[$i][1], 'attribs' => $this->_labelForm[$i][2], 'features' => $this->_labelForm[$i][3]);
				$i++;
			}
		}else{			
			$i=0;
			$j=0;
			if ($private ==''){
				$this->val_field = $this->_cols_not_inform;
			}else{
				$this->val_field = $this->_cols_not_inform_private;
			}
			while(isset($metaInf['cols'][$i])){
				
				if(array_search($metaInf['cols'][$i], $this->val_field, true)===false){	
					//print "I cols => ".$i.", J label => ". $j.", ".$metaInf['cols'][$i]. " => ". $this->_labelForm[$j][0]."<BR/>";if($j==12) {die();}
					$this->listElems[]=array('name'=> $metaInf['cols'][$i], 'label'=> $this->_labelForm[$j][0], 'type' => $this->_labelForm[$j][1], 'attribs' => $this->_labelForm[$j][2],  'features' => $this->_labelForm[$j][3]);
					$j++;				
				}else{					
				} 
				$i++; 
			}
		}	
	}
}
?>