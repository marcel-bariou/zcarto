<?php
    $tpl2 = Zend_Registry::get('tpl2');
    $this->translator = Zend_Registry::get('translator');
    $config=Zend_Registry::get('config');
    /**
     *		Dropdown menu construction
     *		To automaticated
     */
    
    include('menugandi.php');
    		
    /**
     *	Main layout Management 
     */
     
    $htmltpl2="layouts/map-services.tpl";
    $base_url=$this->baseUrl;
    $tpl2->setFile("MyFileLayoutHandle", $htmltpl2);
    $tpl2->setBlock("MyFileLayoutHandle", "regtheme", "regthemes");
    
    if(is_object($this->form) && $this->form->has_TinyMce){
    	$tpl2->setVar("TINYMCE", $this->getHelper('BuildForm')->get_tinyMce());
    }else{
    	$tpl2->setVar("TINYMCE", "");
    }
    
    $tpl2->setVar("DOCTYPE", $this->doctype());
    $tpl2->setVar("HEADTITLE", $this->headTitle());
    $tpl2->setVar("HEADLINK", $this->headLink());
    $tpl2->setVar("HEADSTYLE", $this->headStyle());
    $tpl2->setVar("HEADSCRIPT", $this->headScript());
    if($_SERVER["HTTP_HOST"]=="www.markethon-po.cat"){
			 $tpl2->setVar("TITLE", "Markethon 2014 - départ Perpignan");
		}else{
			 $tpl2->setVar("TITLE", "PapWeb - ZF - Doctrine");
		}
   
    $tpl2->setVar("HEADERMENU", $buffer);
    
    /**
     *		Site authentification aund autorization getting
     *
     */
    
    $auth = Zend_Auth::getInstance();		
	if ($auth->hasIdentity()) 
	{
	    $tpl2->setVar("INVITE", "Vous trouverez sans doute dans les écran affichés les règles de mise en place de vos applications");    
	    $this->user = Zend_Auth::getInstance()->getIdentity();
        $tpl2->setVar("CURUSER", $this->translator->_('Membre :'));
	    $tpl2->setVar("USR", $this->escape($this->user->c_username));
	    $tpl2->setVar("LGO", $base_url.'/'.$config->name->root.'/public/auth/logout');
	    $fromCache= new Zend_Session_Namespace('experimental');
	    $tb_auth = $fromCache->userTable;
	    $logout_profile= $this->translator->_('| Quitter ').'<a href="/'.$config->name->root.'/public/mngtb/edit/t/'.$tb_auth.'/id/'.$this->user->id.'/private/'.$this->user->id.'"><span>'.$this->translator->_('| Votre profil ').'</span></a>';
	    $tpl2->setVar("LGX", $logout_profile);
	    
	    if  ($tb_auth == 'tJobseeker'){
	    	    $delete_profile = ' <a href="/'.$config->name->root.'/public/mngtb/delete/t/'.$tb_auth.'/id/'.$this->user->id.'/private/'.$this->user->id.'">'.$this->translator->_('| Supprimer votre profil |').'</a>';
	    	    $tpl2->setVar("DELETE",  $delete_profile);
	    }else{
	    	    $tpl2->setVar("DELETE", '');
	    }

	}else{
	   $tpl2->setVar("INVITE", "En vous identifiant vous accédez à vos données ou aux règles de mise en oeuvre du gestionnaire d'applications");    
	   $tpl2->setVar("CURUSER","Visiteur");
	   $tpl2->setVar("USR", '');
	   $tpl2->setVar("LGO", $base_url.'/'.$config->name->root.'/public/auth/login');
	   $tpl2->setVar("LGX", 'Si inscrit, identifiez vous ICI selon votre thème d\'intérêt !');	   		
	   $tpl2->parse("regthemes", "regtheme", true);
	   $tpl2->setVar("DELETE", '');
	   
	}

    /**
     *	Dojo settings
     */
     
    $this->dojo()->enable();
    $tpl2->setVar("HEADDOJO", $this->dojo()->__toString());
    $tpl2->setVar("CLASSBODY", "tundra");
    $tpl2->setVar("TITLEBODY", $this->escape($this->title));
   
    /**
     *	Mise en place de la partie calculée selon le contôleur utilisé 
     */
   Zend_Registry::set('page', 1);
    $tform=  str_replace('{PAGE}', Zend_Registry::get('page'), $this->layout()->content);
     $tpl2->setVar("CONTENT", $tform);
      
	$tpl2->setVar("BSHBANNER", $this->translator->_('A PAPWEB application LAMPJ platform PAPWEB &copy; 1995-').date("Y",time()).$this->translator->_('. Contact for support'));
	$tpl2->setVar("SUPHREF", "http://www.icijepeux.es");
    
    $tpl2->parse( "MyOutput", "MyFileLayoutHandle");
    $tpl2->p("MyOutput");
?>