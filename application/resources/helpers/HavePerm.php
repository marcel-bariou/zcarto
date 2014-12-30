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
 class Form_View_Helper_HavePerm extends Zend_View_Helper_Abstract
{ 
	private $_user;
	 
	/**
	 *	Check for permission to act for $this->view->user
	 *	
	 *	@param mixed Set of permissions needed to execute an action
	 *  @param object Current User
	 */
	
	
	public function havePerm($operation, $objet, $user)
	{		
		$tobjects= Zend_Registry::get('dataperm');
		$arrayGrp =explode( ',', $user->groupe_smul);		
		$key_obj= array_keys($tobjects, $objet); 
		$query = "SELECT Object_smul FROM tGroup WHERE id=".$arrayGrp[0] ;		
		$records = new Model_DbTable_GenTb('tGroup');
		$row=$records->getRecord($arrayGrp[0]);		
		if ((strpos($user->perms[0], $operation)!==false) && (strpos( $row['Object_smul'], $objet)!==false)){
			return true;
		}else{
			return false;
		}
	}
				
    	
}
?>
