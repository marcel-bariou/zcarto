<?php
/*
 * Created on Jun 4, 2009
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
 class Form_View_Helper_BuildForm extends Zend_View_Helper_Abstract
{ 
	private $_objForm;
	private $_config;
	
	/**
	 *	Expressions régulières de contrôle :
	 *
	 *		alphadiac => Alphabétique, diacritique avec tiret et espace (Noms propres)
	 *		alphab => Alphabétique pur
	 *		alphanumvirgapos => Alphanumérique, virgule, apostrophe
	 *     		numspace => Numérique, espace
	 *		pswd => alphanumérique Min/Maj, &, #, % 6 mini)
	 *		phonefax => Numérique, espace, /
	 *		email => adresse email alphanumérique Min/Maj,
	 *		keyword => alphabétique, espace
	 * 	
	 */
	 
	private $_validRegExp = Array( 'alphadiac' =>array('valid' => '([a-zA-Zéèêàçùîôûüï \.-])*', 'error' => 'Anomalie, caractères alphabétiques et diacritiques attendus!', 'prompt' => 'Utilisez des caractères alphabétiques et/ou  diacritiques '), 
				       'alphab' => array('valid' => '[a-zA-Z]*', 'error' => 'Anomalie, alphabétique (Min./Maj)attendu !', 'prompt' => 'Utilisez des caractères alphabétiques'),
				       'alphanumvirgapos' => array('valid' => '([a-zA-Zéêèàçùîôûüï0-9 \',;\. &-])*', 'error' => 'Anomalie, alphanumérique, espace, virgule, apostrophe attendus !', 'prompt' => 'Utilisez alphanumérique, espace, virgule, apostrophe'),
				       'pswd' => array('valid' => '([a-zA-Z0-9&#%]{6,})', 'error' =>'Anomalie, alphabétique (Min./Maj)et/ou &#% attendus, 6 mini !', 'prompt' => 'alphabétique (Min./Maj)et/ou &#% attendus 6 mini !'),
				       'numspace' => array('valid' => '([0-9 ])*', 'error' => 'Anomalie, numérique et espace attendus', 'prompt' => 'Caractères numériques ou espace attendus'), 
				       'phonefax' =>array('valid' =>'([ 0-9\\/\-\\+\\)\\(])*', 'error' =>'Anomalie, caractères numériques , +, () autorisés, phone/fax', 'prompt' => 'Les caractères numériques , +, () autorisés, phone/fax'),
				       'email' => array('valid' =>'([A-Za-z0-9._%-]+@[A-Za-z0-9._%-]+\.[A-Za-z0-9._%-]{2,4})*', 'error' => 'Anomalie, caractères alphanumériques , _, %, - autorisés pour mail', 'prompt' => 'Les caractères alphanumériques , _, %, - autorisés pour mail'),
				       'numfloat' => array('valid' => '([0-9 \.+-])*', 'error' => 'Anomalie, caractères numériques, point décimal', 'prompt' => 'Utilisez des caractères numériques, point décimal'),
				       'numfloatvirg' => array('valid' => '([0-9 \.+,-])*', 'error' => 'Anomalie, caractères numériques, point décimal', 'prompt' => 'Utilisez des caractères numériques, point décimal'),
				       'pathfile' => array('valid' => '([\/_\.a-zA-Z0-9 :-])*', 'error' => 'Anomalie, caractères Alphanumériques, point extension, tiret', 'prompt' => 'Utilisez des caractères Alphanumériques, point extension, tiret'),
				       'keyword' => array('valid' => '([a-zA-Z ])*', 'error' => 'Anomalie, caractères alphabétiques sans diacritiques et espace attendus', 'prompt' => 'Utilisez des caractères alphabétiques sans diacritiques et espace'));
	
	private $_msgError= array('mail' => 'Must be a correct address mail',
				  'phone' => 'Must be a correct phone number',
				  'pswd' => 'Password must have 6 or more alphanumeric characters' );
	private $_msgPrompt= array('mail' => 'Enter here a correct address mail',
				   'phone' => 'Enter here a correct phone number',
				   'pswd' => 'Please enter a correct password : 6 or more alphanumeric characters');
	
	/**
	 *	Tiny MCe purpose 1
	 */
	 
	protected $info_tinymce;
	
	
	/**
	 *	Tiny MCe path to Javascript
	 */
	
	protected $addon_tinymce;
	
	
	/**
	 *	Tiny MCe parameters
	 */
	
	protected $param_tinymce='  <BR/> 
	tinyMCE.init({
			mode : "textareas",
			theme : "simple"
	});
	</script>';
	
	protected $close_tinymce ='<!-- /tinyMCE -->';

	 
    public function buildForm($obj, $table='', $xform='', $notLocked=True)    
    { 
    	$this->_config = Zend_Registry::get('config');
    	$this->addon_tinymce='
	<!-- tinyMCE -->
	<script language="javascript" type="text/javascript" src="/'.$this->_config->name->root.'/public/tiny_mce/tiny_mce.js"></script>
	<script language="javascript" type="text/javascript">
	// Notice: ';
    	if ($obj->has_TinyAdvanced)
    	{
    		$this->set_param_tinyMce('http://localhost/');
    	}    	
     	
    	$this->_objForm =$obj;
		for ($i=0; isset($this->_objForm->listElems[$i]);$i++)
		{
			switch($this->_objForm->listElems[$i]['type']){ 
				case "text":
					$this->validtextbox_Dojo($this->_objForm->listElems[$i]['name'], $this->_objForm->listElems[$i]['label'], $this->_objForm->listElems[$i]['attribs'], $this->_objForm->listElems[$i]['features']);
				break;
				case "textarea":
					$this->textarea($this->_objForm->listElems[$i]['name'], $this->_objForm->listElems[$i]['label'], $this->_objForm->listElems[$i]['attribs'], $this->_objForm->listElems[$i]['features']);
				break;
				case "select":
					$this->select($this->_objForm->listElems[$i]['name'], $this->_objForm->listElems[$i]['label'], $this->_objForm->listElems[$i]['attribs'],  $this->_objForm->listElems[$i]['features']);
				break;
				case "multiselect":
					$this->multiselect($this->_objForm->listElems[$i]['name'], $this->_objForm->listElems[$i]['label'], $this->_objForm->listElems[$i]['attribs'],  $this->_objForm->listElems[$i]['features']);
				break;
				case "comboBox_Dojo":
					$this->comboBox_Dojo($this->_objForm->listElems[$i]['name'], $this->_objForm->listElems[$i]['label'], $this->_objForm->listElems[$i]['attribs'],  $this->_objForm->listElems[$i]['features']);
				break;
				case "selectFiltering_Dojo":
					$this->selectFiltering_Dojo($this->_objForm->listElems[$i]['name'], $this->_objForm->listElems[$i]['label'], $this->_objForm->listElems[$i]['attribs'],  $this->_objForm->listElems[$i]['features']);
				break;
				case "textarea_Dojo":
					$this->textarea_Dojo($this->_objForm->listElems[$i]['name'], $this->_objForm->listElems[$i]['label'], $this->_objForm->listElems[$i]['attribs'],  $this->_objForm->listElems[$i]['features']);
				break;
				case "datetextbox_Dojo":
					$this->datetextbox_Dojo($this->_objForm->listElems[$i]['name'], $this->_objForm->listElems[$i]['label'], $this->_objForm->listElems[$i]['attribs'],  $this->_objForm->listElems[$i]['features']);
				break;
				case "validtextbox_Dojo":
					$this->validtextbox_Dojo($this->_objForm->listElems[$i]['name'], $this->_objForm->listElems[$i]['label'], $this->_objForm->listElems[$i]['attribs'],  $this->_objForm->listElems[$i]['features']);
				break;
				case "textbox_Dojo":
					$this->textbox_Dojo($this->_objForm->listElems[$i]['name'], $this->_objForm->listElems[$i]['label'], $this->_objForm->listElems[$i]['attribs'],  $this->_objForm->listElems[$i]['features']);
				break;
				case "passwordtextbox_Dojo":
					$this->passwordtextbox_Dojo($this->_objForm->listElems[$i]['name'], $this->_objForm->listElems[$i]['label'], $this->_objForm->listElems[$i]['attribs'],  $this->_objForm->listElems[$i]['features']);
				break;
				case "checkbox_Dojo":
					$this->checkbox_Dojo($this->_objForm->listElems[$i]['name'], $this->_objForm->listElems[$i]['label'], $this->_objForm->listElems[$i]['attribs'],  $this->_objForm->listElems[$i]['features']);
				break;
				case "zendfile":
					$this->zendfile($this->_objForm->listElems[$i]['name'], $this->_objForm->listElems[$i]['label'], $this->_objForm->listElems[$i]['attribs'],  $this->_objForm->listElems[$i]['features']);
				break;
				case "dojoEditor":
					$this->dojoEditor($this->_objForm->listElems[$i]['name'], $this->_objForm->listElems[$i]['label']);
				break;
			}
			
			
		}
		
		/**
		 * 	Additional elements
		 */
		 
		$id = new Zend_Form_Element_Hidden('id');
		$this->_objForm->objElemens[]=$id;
		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton');
		$reset= new Zend_Form_Element_Reset('Effacer');
		$reset->setAttrib('id', 'resetbutton');
		
		$abandon = new Zend_Form_Element_Button('Annuler');
		$abandon->setAttrib('id', 'cancelbutton');
		
		if(isset($table) && $table!=''){
			$abandon->setAttrib('onClick', 'window.location="/'.$this->_config->name->root.'/public/mngtb/index/t/'.$table.'/page/{PAGE}"');
		}elseif(isset($xform) && $xform!=''){
			if($xform=='fAffectation'){
				$abandon->setAttrib('onClick', 'window.location="/'.$this->_config->name->root.'/public/mngtb/affectation/t/'.$xform.'"');
			}else{
				$abandon->setAttrib('onClick', 'window.location="/'.$this->_config->name->root.'/public/index/"');
			}
		}else{
			$abandon->setAttrib('onClick', 'window.location="/'.$this->_config->name->root.'/public/index/"');
			
		}
		
		
		/**
		 *	Custom decorators
		 */
		 
		
		$decorators = array(
				array('ViewHelper'),
				array('Errors'),
				array('HtmlTag', array('tag' => 'span'))
			);
		
		$submit->setDecorators($decorators);
		$reset->setDecorators($decorators);
		$abandon->setDecorators($decorators);
		//$this->_objForm->clearDecorators();
		
		if($notLocked){
			$this->_objForm->objElemens[]=$submit;  
			$this->_objForm->objElemens[]=$reset; 
		}
		$this->_objForm->objElemens[]=$abandon;  		
		$this->_objForm->addElements($obj->objElemens);
		
    }

    
    /**
     * 		$name ='userID' element name
     *      	$label='Country'
     * 		$searchAttrib = 'username'
     * 		$list = "countrylist.json"
     */
    
    private function textbox_Dojo($name, $label, $attribs, $features=NULL){  
         $objElem = new Zend_Dojo_Form_Element_TextBox($name);
         $objElem-> setLabel($label)
         		 -> setName($name)
         		 -> setAttribs($attribs)
         		 //-> setRegExp($this->_validRegExp[$attribs['valid']])
         		 //-> setInvalidMessage($this->_msgError[$attribs['error']])
         		 //-> setPromptMessage($this->_msgPrompt[$attribs['prompt']])
         		 -> setRequired(true);
         $this->_objForm->objElemens[]=$objElem;
    }
    
    /**
     * 		$name ='userID' element name
     *      $label='Country'
     * 		$searchAttrib = 'username'
     * 		$list = "countrylist.json"
     */
    
    private function validtextbox_Dojo($name, $label, $attribs, $features=NULL){  
         $objElem = new Zend_Dojo_Form_Element_ValidationTextBox($name);
         if (isset($attribs['control'])){
         	$valid= $this->_validRegExp[$attribs['control']]['valid'];	 
         	$prompt= $this->_validRegExp[$attribs['control']]['prompt'];	 
         	$error= $this->_validRegExp[$attribs['control']]['error'];	 
         }else{
         	$valid= $this->_validRegExp['alphadiac']['valid'] ;
         	$prompt= $this->_validRegExp['alphadiac']['prompt'] ;
         	$error= $this->_validRegExp['alphadiac']['error'] ;
         }
         if(!isset($attribs['value'])){
         	$attribs['value']="";         	 
         }
         $objElem-> setLabel($label)
         	 -> setName($name)
         	 -> setAttrib('size', $attribs['size'])
         	 -> setAttrib('width', '35em')
         	 -> setDijitParam('width', '45em')
        	 -> setRegExp($this->_validRegExp[$attribs['control']]['valid'])
         	 -> setInvalidMessage($this->_validRegExp[$attribs['control']]['error'])
         	 -> setPromptMessage($this->_validRegExp[$attribs['control']]['prompt'])
         	 -> setValue($attribs['value'])
         	 -> setRequired(true);
         	 //var_dump($objElem->getDijitParams());die();      
         $this->_objForm->objElemens[]=$objElem;
   }
    
    /**
     * 		$name ='userID' element name
     *      $label='Country'
     * 		$searchAttrib = 'username'
     * 		$list = "countrylist.json"
     */
    
    private function checkbox_Dojo($name, $label, $attribs, $features=NULL){  
         $objElem = new Zend_Dojo_Form_Element_CheckBox($name);
         $objElem-> setLabel($label)
         		 -> setName($name)
         		 ->setRequired(true)
         		 -> setCheckedValue($attribs['checked'])
         		 -> setUnCheckedValue(null)
         		 -> setValue(null);
         $this->_objForm->objElemens[]=$objElem;
    }
		
		
    
    /**
     * 		$name ='userID' element name
     *      $label='Country'
     * 		$searchAttrib = 'username'
     * 		$list = "countrylist.json"
     */
    
    private function datetextbox_Dojo($name, $label, $attrib, $features=NULL){  
         $objElem = new Zend_Dojo_Form_Element_DateTextBox($name);
         
         $objElem->setLabel($label)
         		 ->setName($name)
         		 ->setDatePattern('dd.MM.yyyy - HH.mm.ss zzz')
         		 ->setLocale('FR')
         		 ->setFormatLength('full');
         $this->_objForm->objElemens[]=$objElem;
    }
		
    
    /**
     * 		$name ='userID' element name
     *      $label='Country'
     * 		$searchAttrib = 'username'
     * 		$list = "countrylist.json"
     */
    
    private function passwordtextbox_Dojo($name, $label, $attribs, $features=NULL){  
         $objElem = new Zend_Dojo_Form_Element_PasswordTextBox($name);         
         $objElem->setLabel($label)
         		 ->setName($name)
         		 -> setRegExp($this->_validRegExp[$attribs['control']]['valid'])
         		 -> setInvalidMessage($this->_validRegExp[$attribs['control']]['error'])
         		 -> setPromptMessage($this->_validRegExp[$attribs['control']]['prompt'])
         		 ->setTrim(true)
         		 ->setLowerCase(true)
         		 ->setRequired(false);
         $this->_objForm->objElemens[]=$objElem;
    }
		
    
    /**
     * 		$name ='userID' element name
     *      $label='Country'
     * 		$searchAttrib = 'username'
     * 		$list = "countrylist.json"
     */
    
    private function comboBox_Dojo($name, $label, $attribs, $features=NULL){  
         $objElem = new Zend_Dojo_Form_Element_ComboBox($name);
         $objElem->setLabel($label)
            	 ->setAutoComplete(true)
            	 ->setStoreId($attribs['list'].'Store')
            	 ->setStoreType('dojo.data.ItemFileReadStore')
            	 ->setStoreParams(array('url'=>'/'.$this->_config->name->root.'/public/listJson/'.$attribs['list'].'.json'))
            	 ->setAttrib("searchAttr", $attribs['searchAttrib'])
            	 ->setAttrib("maxHeight", '600')
            	 ->setAttrib("pageSize", 20)
            	 ->setRequired(false)
            	 ->setDijitParams  ( array('hasdownarrow' =>'false'));
         $objElem->addDecorator('DijitElement')
         		 ->addDecorator('Errors')
         		 ->addDecorator('HtmlTag', array('tag' =>'dd'))
         		 ->addDecorator('label', array('tag' =>'dt'));
         $objElem->getDijitParams(); // For test purpose
         $this->_objForm->objElemens[]=$objElem;
         
	} 
	
    /**
     * 		$name ='userID' element name
     *      $label='Country'
     * 		$searchAttrib = 'username'
     * 		$list = "countrylist.json"
     */
    
    private function selectFiltering_Dojo($name, $label, $attribs, $features=NULL){  
	$objElem = new Zend_Dojo_Form_Element_FilteringSelect($name);
	$objElem->setLabel($label)
            	 ->setAutoComplete(true)
            	 ->setStoreId($attribs['list'].'Store')
            	 ->setStoreType('dojo.data.ItemFileReadStore')
            	 ->setStoreParams(array('url'=>'/'.$this->_config->name->root.'/public/listJson/'.$attribs['list'].'.json'))
            	 ->setAttrib("searchAttr", $attribs['searchAttrib'])
            	 ->setAttrib("maxHeight", '600')
            	 ->setAttrib("pageSize", 20)
            	 ->setRequired(false)
            	 ->setDijitParams  ( array('hasdownarrow' =>'false'));
         $objElem->addDecorator('DijitElement')
         		 ->addDecorator('Errors')
         		 ->addDecorator('HtmlTag', array('tag' =>'dd'))
         		 ->addDecorator('label', array('tag' =>'dt'));
         $objElem->getDijitParams(); // For test purpose
         $this->_objForm->objElemens[]=$objElem;
         
	} 

   
    /**
     * 
     */
    
    private function text($name, $label, $attrib, $features=NULL){ 
        $objElem = new Zend_Form_Element_Text($name, $value='toto', $attrib);
        if($features==NULL){
        	$objElem->setLabel($label)
        			->setRequired(true)
        			->addFilter('StripTags')
        			->addFilter('StringTrim')
        			->addValidator('NotEmpty')
        			->setAttribs($attrib)
        			->setTranslator($t=Zend_Registry::get('translator'));
        }else{
        	$objElem->setLabel($label)
        			->setRequired ($features['required'])
        			->setTranslator($t=Zend_Registry::get('translator'));
        	foreach($features['validators'] as $key=>$value){
        		$objElem->addValidator($value);	
        	}
        	foreach($features['filters'] as $key=>$value){
        		$objElem->addFilter($value);	
        	}
        	$objElem->setAttribs($attrib);
        } 
        $this->_objForm->objElemens[]=$objElem;
    }
   
    /**
     * 
     */
    
    private function zendfile($name, $label, $attrib, $features=NULL){ 
        $objElem = new Zend_Form_Element_File($name);
        $objElem->setLabel($label)
        ->setRequired(false)
        ->setDestination($attrib['path'])
        ->setMultiFile($attrib['Count'])
        ->addValidator('Size', false, $attrib['Size'])
        ->addValidator('Extension', false, $attrib['Extension']);        
        $this->_objForm->objElemens[]=$objElem;
    }
 
   
    /**
     * 
     */
    
    private function select($name, $label, $option, $features=NULL){ 
        $objElem = new Zend_Form_Element_Select($name);
        $objElem->setLabel($label)
        ->setRequired(true);
        $objElem->setMultiOptions($option);   
        $this->_objForm->objElemens[]=$objElem;
    }
   
    /**
     * 
     */
    
    private function multiselect($name, $label, $option, $features=NULL){ 
        $objElem = new Zend_Form_Element_Multiselect($name);
        $objElem->setLabel($label)
        		->setRequired(false)
        		->setAttrib( "size", count($option) )
        		->addMultiOption("size", 20);
        $objElem->setMultiOptions($option);   
        $this->_objForm->objElemens[]=$objElem;
    }
 
    private function dojoEditor($name, $label){    
    	   Zend_Dojo::enableForm($this->_objForm);
           $objElem= new Zend_Dojo_Form_Element_Editor(
                $name,
                array(
                    'label'        => $label,
                    //ViewSource
                    'data-dojo-props'=> "extraPlugins:['prettyprint','normalizeindentoutdent','foreColor','hiliteColor']",
                    'inheritWidth' => false,
                    'closable' => true,
                    'focusOnLoad' => true,
                    'height' => '120px',
                    'cols' =>'32',
                    'showTitle' => false
               )
            );  
            
            $objElem->setPlugins(array('undo', 'redo', '|', 'copy', 'cut', 'paste',  'bold', 'italic', 'underline', 'strikethrough', 'insertOrderedList', 'insertUnorderedList', '|',
                'indent', 'outdent', 'justifyLeft', 'justifyRight', 'justifyCenter', 'justifyFull', 'foreColor', 'hiliteColor', 'createLink', 'dijit._editor.plugins.LinkDialog', 
                'dijit._editor.plugins.EnterKeyHandling',  'dijit._editor.plugins.ViewSource', 'dijit._editor.plugins.FullScreen', 'insertImage' )); 
            $this->_objForm->objElemens[]=$objElem;
    }
    
    /**
     * 
     */
    
    private function textarea($name, $label, $attribs, $features=NULL){    	 
        $objElem = new Zend_Form_Element_Textarea($name, $attribs);
        if($features==NULL){
        	$objElem->setLabel($label)
        			->setRequired(true)
        			->addValidator('NotEmpty')
        			->setTranslator($t=Zend_Registry::get('translator'));
        }else{
        	$objElem->setLabel($label)
        			->setRequired ($features['required'])
        			->setTranslator($t=Zend_Registry::get('translator'));
        	foreach($features['validators'] as $key=>$value){
        		$objElem->addValidator($value);	
        	}
        	foreach($features['filters'] as $key=>$value){
        		$objElem->addFilter($value);	
        	}
        } 
 /*       
        $objElem->setLabel($label)
        ->setRequired(true)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty');  
*/              
        $this->_objForm->objElemens[]=$objElem;
    }
    
	/**
	 * 	Parameters setup for TinyEditor
	 *	@param string $val_base_url
	 */
	 
	protected function set_param_tinyMce($val_base_url){
		$this->param_tinymce='  <BR/> 
		tinyMCE.init({
				mode : "textareas",
				theme : "advanced",
				plugins : "table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,flash,searchreplace,print,contextmenu",
				theme_advanced_buttons1_add_before : "save,separator",
				theme_advanced_buttons1_add : "fontselect,fontsizeselect",
				theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,zoom,separator,forecolor,backcolor",
				theme_advanced_buttons2_add_before: "cut,copy,paste,separator,search,replace,separator",
				theme_advanced_buttons3_add_before : "tablecontrols,separator",
				theme_advanced_buttons3_add : "emotions,iespell,flash,advhr,separator,print",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_path_location : "bottom",
				plugin_insertdate_dateFormat : "%Y-%m-%d",
				plugin_insertdate_timeFormat : "%H:%M:%S",
				extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
				height:"400px",
				width:"500px",
				relative_urls : false,
				remove_script_host : false,
				document_base_url : "http://'.$val_base_url.'/'.$this->_config->name->root.'/"
		});
		</script>';
	}
  
	/**
	 *	Supply TinyMce parameters
	 */
	
	
	public function get_tinyMce() {
		$tinyMce=$this->addon_tinymce.$this->info_tinymce.$this->param_tinymce.$this->close_tinymce;
		return $tinyMce;
	}
    	
}
?>
