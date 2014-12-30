<?php
/*
 * Created on Jan 24, 2014
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
 * @copyright  Copyright (c) 2005-2014 Brasnah sarl (http://www.brasnah.com)
 * @license    http://www.brasnah.com/lpgl.txt    
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class Form_View_Helper_ValidPerm extends Zend_View_Helper_Abstract
{
	private $_auth;
	 
	/**
	 *	Check for permission to act for $this->view->user
	 *	
	 *	@param mixed Set of permissions needed to execute an action
	 *  @param object Current User
	 */
	
	
	public function validPerm($t, $objet, $codop, $private=''){
		$auth = Zend_Auth::getInstance();
		$tsess= new Zend_Session_Namespace('Messages');		
		if ($auth->hasIdentity()) 
		{ 
		    $t->view->user = Zend_Auth::getInstance()->getIdentity();
		    if ($private !='' && (int)$private != $t->view->user->id){
			    $tsess->nopermMsg = $private." => ".$t->view->user->id."Vous tentez manifestement de modifier des données personnelles qui ne sont pas les votres. Ceci est peu recommandé !.";
			    $t->_redirector->gotoSimple('noperm', 'mngtb',  null, array('t' => 'error'));
			    
		    }elseif($private !='' && (int)$private == $t->view->user->id){
				$usess= new Zend_Session_Namespace('experimental');
				$usess->privateid=$private;
		    }elseif ($private =='' && !$t->view->havePerm('update', $objet,  $t->view->user)){
		    	    $msg = Zend_Registry::get('MsgErrorPerm');
		    	    $sentence = "Vous n'avez pas le droit ".$msg[0][$codop].$msg[1][$objet];
		    	    $tsess->nopermMsg = $sentence;
			    $t->_redirector->gotoSimple('noperm', 'mngtb',  null, array('t' => 'error'));
		    }
		}else{
		    $tsess->nopermMsg = "Vous devez vous authentifier, voir login en haut d'écran.";
		    $t->_redirector->gotoSimple('noperm', 'mngtb',  null, array('t' => 'error'));
		}
	}
}
?>
