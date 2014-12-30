<?php
	$translator=Zend_Registry::get('translator');
	$tpl1 = Zend_Registry::get('tpl1');
	$config = Zend_Registry::get('config');
	$htmltpl1="views/form.tpl";
	$tpl1->setFile("MyFileHandle", $htmltpl1);
	$base_url=$this->baseUrl.'/auth/login/';
	$tpl1->setVar("URLAPPLI", $config->url->appli);
	$tpl1->setVar("URLBASE",$config->url->localhost);
	$tpl1->setvar("CHARSET", "UTF-8");
	$tpl1->setVar("TITLE", "Login");
	$tpl1->setVar("TITLEPAGE", "Accès spécifique PAPWEB");

	/**
	 * 	Form for login
	 */
	$form = new Zend_Form();
	$form->setDecorators(array(
            Array('Description',array('class' => 'leftalign')),
            'FormElements',
            'Form'
        ));
	
	$form->setAction('login')
     	 ->setMethod('post')
     	 ->setName('MyForm')
     	 ->setDescription($descript=$translator->_('Pour accéder, fournissez vos données d\'authentification.'));
     	 
	    $form->addElement('text', 'username', array('label'=> $translator->_('Votre identifiant'), 'required'=>true, 'class'=>'textName'));
		$username = $form->getElement('username')	
						 ->addValidator('alnum')	
						 ->addValidator('alnum')
						 ->setRequired(true)
						 ->addFilter('StringTrim');
		$username->getValidator('alnum')->setMessage('Your username should include letters and numbers only');
	    
	    $form->addElement('password', 'password', array('label'=> $translator->_('Mot de passe'), 'required'=>true));
	    $table = new Zend_Form_Element_Select('table');	
	    $table->setLabel('Quel thème ?')
		  ->setMultiOptions(array('0'=>'Choisir un thème', 
		  	  		'tUser'=>'Administration globale', 
		  	  		'tJobseeker'=>'Markethon : Activités et emplois', 
		  	  		'1'=>'Energie et territoires',
		  	  		'2'=>'Agriculture et terrritoires',
		  	  		'3'=>'Hydrogéologie',
		  	  		'4'=>'Alimentation et nutrition',
		  	  		'5'=>'Electronique et informatique'))
		  ->setRequired(true)->addValidator('NotEmpty', true);
	    $form->addElement($table);	
	    $form->addElement('submit', 'Soumettre');
	    //Zend_Debug::dump($descript);
	    $tpl1->setVar("THEFORM", $form);
	
		/**
		 * 	Ask for password
		 */	
		$form2 = new Zend_Form;
		$form2->setAction('login')
	     ->setMethod('post')
	     ->setName('MyForm2')
	     ->setDescription('Demande par courriel');
	    $form2->setAttrib('id', 'mailaddress');
	    $form2->addElement('text', 'mailaddress', array('label'=> $translator->_('Votre adresse de courriel'), 'required'=>true));
	    $form2->addElement('submit', 'Demander');
	    $tpl1->setVar("STARTFORM2", $form2);

	$tpl1->setVar("NOTICE", $this->translator->_('Use a valid username and password to gain access to your specific tools.'));
	$tpl1->setVar("NOTICE2", $this->translator->_('Warning! JavaScript must be enabled for proper operation of the Administrator Back-end	'));
	$tpl1->setVar("ERRMSG", $this->message);
	$tpl1->setVar("HOME", $this->translator->_('Return to site Home Page'));
	$tpl1->setVar("LOSTPSWD", $this->translator->_('Forgot your login, supply your mail address to get it !'));
	$tpl1->setVar("HOMELNK", $this->baseUrl);
	$tpl1->setVar("NAMELOG", $this->translator->_('Login'));
	$tpl1->setVar("NAMELOG2",  $this->translator->_('Get It !'));
	$tpl1->setVar("BSHBANNER", $this->translator->_('A PAPWEB application LAMPJ platform PAPWEB &copy; 1995-').date("Y",time()).$this->translator->_('. Contact for support'));
	$tpl1->setVar("SUPHREF", "http://www.icijepeux.es");
	$tpl1->parse( "MyOutput", "MyFileHandle");
	$tpl1->p( "MyOutput");
?>
