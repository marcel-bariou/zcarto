<?php
    $tpl2 = Zend_Registry::get('tpl2');
    $this->translator = Zend_Registry::get('translator');
	
$buffer='
		<div class="menudrop">
		<ul>
			<li class="home"><a href="#nogo">Home</a></li>
			<li class="products"><a class="drop" href="#nogo">Products<!--[if IE 7]><!--></a><!--<![endif]-->
			<!--[if lte IE 6]><table><tr><td><![endif]-->
			<ul>
				<li class="subprod"><a href="#nogo">Mobiles</a></li>
		
				<li class="subprod2"><a href="#nogo">Photoframes</a></li>
				<li class="subprod2"><a href="#nogo">Tripods</a></li>
				<li class="subprod"><a href="#nogo">Memory cards</a></li>
				<li class="subprod3"><a class="drop" href="#nogo">Cameras<!--[if IE 7]><!--></a><!--<![endif]-->
				<!--[if lte IE 6]><table><tr><td><![endif]-->
				<ul>
					<li class="subsubl"><a href="#nogo">SLRs</a></li>
		
					<li class="subsubl"><a href="#nogo">Compact</a></li>
					<li class="subsubl"><a href="#nogo">Digital</a></li>
					<li class="subsubl"><a href="#nogo">Video</a></li>
				</ul>
				<!--[if lte IE 6]></td></tr></table></a><![endif]-->
				</li>
				<li class="subprod2"><a href="#nogo">Camcorders</a></li>
		
				<li class="subprod"><a href="#nogo">Accessories</a></li>
			</ul>
			<!--[if lte IE 6]></td></tr></table></a><![endif]-->
			</li>
			<li class="services"><a class="drop" href="#nogo">Services<!--[if IE 7]><!--></a><!--<![endif]-->
			<!--[if lte IE 6]><table><tr><td><![endif]-->
			<ul>
				<li class="subserv4"><a href="#nogo">Photography</a></li>
		
				<li class="subserv3"><a href="#nogo">Video editing</a></li>
				<li class="subserv"><a href="#nogo">Web site Design and Hosting</a></li>
				<li class="subserv5"><a href="#nogo">Reference guides</a></li>
				<li class="subserv2"><a href="#nogo">Tutorials</a></li>
			</ul>
			<!--[if lte IE 6]></td></tr></table></a><![endif]-->
			</li>
		
			<li class="contact"><a href="#nogo">Contact us</a></li>
			<li class="site"><a href="#nogo">Site Map</a></li>
			<li class="news"><a class="drop" href="#nogo">Admin<!--[if IE 7]><!--></a><!--<![endif]-->
			<!--[if lte IE 6]><table><tr><td><![endif]-->
			<ul>
				<li class="subnews3"><a href="#nogo">Build</a></li>		
				<li class="subnews2"><a class="drop" href="#nogo">Manage<!--[if IE 7]><!--></a><!--<![endif]-->
				<!--[if lte IE 6]><table><tr><td><![endif]-->
				<ul class="left">
					<li class="subsubr"><a href="#nogo">Articles</a></li>
					<li class="subsubr"><a href="#nogo">Users</a></li>
					<li class="subsubr"><a href="#nogo">Groups</a></li>
					<li class="subsubr"><a href="#nogo">Roles</a></li>
					<li class="subsubr"><a href="#nogo">Organization</a></li>
					<li class="subsubr"><a href="#nogo">Respite Center</a></li>		
					<li class="subsubr"><a href="#nogo">January 2006</a></li>
				</ul>
				<!--[if lte IE 6]></td></tr></table></a><![endif]-->
				</li>
			</ul>
			<!--[if lte IE 6]></td></tr></table></a><![endif]-->
			</li>
		</ul>
		</div>';


    $htmltpl2="layouts/map-services.tpl";
    $base_url=$this->baseUrl;
    $tpl2->setFile("MyFileHandle", $htmltpl2);
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
    $tpl2->setVar("TITLE", "PapWeb - ZF - Doctrine");
    $tpl2->setVar("HEADERMENU", $buffer);
    $auth = Zend_Auth::getInstance();		
	if ($auth->hasIdentity()) 
	{
	    $tpl2->setVar("INVITE", "Vous trouverez ci-dessous les règles de mise n place de votre application");    
	    $this->user = Zend_Auth::getInstance()->getIdentity();
        $tpl2->setVar("CURUSER", $this->translator->_('Current user'));
	    $tpl2->setVar("USR", $this->escape($this->user->c_username));
	    $tpl2->setVar("LGO", $base_url.'/ExpZF/public/auth/logout');
	    $logout_profile= $this->translator->_('Logout').' => <a href="/ExpZF/mngtb/perso/base/tUser/tUserID/'.$this->user->id.'"><span>'.$this->translator->_('Your profile').'</span></a>';
	    $tpl2->setVar("LGX", $logout_profile);
	}else{
	   $tpl2->setVar("INVITE", "Authentifiez vous ici pour découvrir les règles de mise en oeuvre du cadriciel Zend et assurer la mise en place d'application");    
	   $tpl2->setVar("CURUSER","Not connected");
	   $tpl2->setVar("USR", 'Anonymous');
	   $tpl2->setVar("LGO", $base_url.'/ExpZF/public/auth/login');
	   $tpl2->setVar("LGX", 'Login !');
	}

    $this->dojo()->enable();
    $tpl2->setVar("HEADDOJO", $this->dojo()->__toString());
    $tpl2->setVar("CLASSBODY", "tundra");
    $tpl2->setVar("TITLEBODY", $this->escape($this->title));
    $tpl2->setVar("CONTENT", $this->layout()->content);
    $tpl2->parse( "MyOutput", "MyFileHandle");
    //$this->cache->save($tpl2->get('MyOutput'), $this->form->getName());
    $tpl2->p("MyOutput");
?>