<?php
require_once 'Zend/Mail.php';
require_once 'Zend/Mail/Transport/Smtp.php';

class AuthController extends Zend_Controller_Action
{

	
	/**
	 *	Path according to table
	 */
	 
	 protected $path;
	
	/**
	 *	Data to omit in session data
	 */

	 protected $Omit_Columns=array('password', 'AddressLine1', 'AddressLine2', 'PostalCode', 'City', 'state', 'country', 'phone','fax', 'Keyword', 'comments');

	 
	/**
	 *	Path according to table or redirection to bootstrap
	 */

	 
	function init()
	{
		
		$this->_helper->viewRenderer->setViewSuffix('php');
		$this->_helper->layout->setlayout('auth_layout');
		$this->initView();
		$this->view->baseUrl = $this->_request->getBaseUrl();
		$ctrl=$this->_request->getParam('ctrl',0);
		$base=$this->_request->getParam('base',0);
		$this->view->translator = Zend_Registry::get('translator');
		$this->group = new Model_ZDBtGroup();
		$this->role = new Model_ZDBtRole();
		if($base!=''){
			$this->path='/'.$ctrl.'/index/base/'.$base.'/';
		}else{
			$this->path='/';
		}
		$this->path='/';
	}
	
	/**
	 *	Redirection according to path
	 */
	
	function indexAction()
	{
		$this->_redirect($this->path);
	}
	
	
	 
	/**
	 *	Login evaluation
	 */
	
	 function loginAction()
	 {
		 $this->view->message = '';
		 if ($this->_request->isPost()) {
		 	 Zend_Loader::loadClass('Zend_Filter_StripTags');
		 	 $f = new Zend_Filter_StripTags();
		 	 $valtheme = $f->filter($this->_request->getPost('table'));
		 	 if ($valtheme== '0' || $valtheme== 'tUser'){
		 	 	 $tb_auth='tUser';
		 	 }else{
		 	 	 $tb_auth=$valtheme;
		 	 }
			 if(isset($_REQUEST['mailaddress'])  && $_REQUEST['mailaddress']!=''){
				 $this->connector=Zend_Registry::get('db1');
				 $where = 'WHERE email=\''.$_REQUEST['mailaddress'].'\' ';
				 $sql='SELECT  id, c_username FROM '.$tb_auth.' '.$where ;
				 $res2 = $this->connector->fetchRow($sql);				
				 if(is_array($res2)){
					 $config = Zend_Registry::get('config');
					 $key=$config->path->website->key;
					 $better_token = md5(uniqid(rand(), true));
					 $input=substr($better_token, 24, 7);
					 $new_pass = MD5($key.strrev($input)).'-'.MD5($input{0}.substr($input,(floor(strlen($input))/2),strlen($input)));
					 $where = 'WHERE id='.$res2['id'];
					 $sql2='UPDATE '.$tb_auth.' SET password=\''.$new_pass.'\' '.$where ;
					 $res3 = $this->connector->query($sql2);						 
					 $data_config = array('auth' => $config->zmail->type->login,
						 'username' => $config->zmail->transport->user,
						 'password' =>  $config->zmail->transport->pswd,
						 'port' => $config->zmail->transport->port);
					 $transport = new Zend_Mail_Transport_Smtp($config->zmail->transport->host, $data_config);
					 $mail = new Zend_Mail('utf-8');
					 $strBody1=$this->view->translator->_('Hello ');
					 $strBody2=$this->view->translator->_('Somebody, maybe yourself asks a modification of your data on the behalf of your email adress : your username');
					 $strBody3=$this->view->translator->_('and your password');
					 $strBody4=$this->view->translator->_('There has been a change since your last login. ');
					 $strBody5=$this->view->translator->_('La plateforme PAPWEB =>  www.icijepeux.es');
					 $mail->setBodyHtml('<p>'. $strBody1.' '.$res2['c_username'].'</p><p>'. $strBody2.' :<b>'.$res2['c_username'].'</b> '.$strBody3.' : <b>'.$input.'</b>.'. $strBody4.' . <p><br/><p><b>'.$strBody5.'</b></p>');
					 $mail->setFrom('marcel.bariou@gmail.com', $this->view->translator->_('Notification depuis PAPWEB www.icijepeux.es'));
					 $mail->addTo($_REQUEST['mailaddress'], $res2['c_username']);
					 $mail->setSubject($this->view->translator->_('Vos données dans PAPWEB GEO-ACTIVITES'));
					 $mail->send($transport);
					 $this->view->message=$this->view->translator->_('Check your mailbox, please.');
				 }else{
					 $this->view->message = $this->view->translator->_('No such address please recheck.');
				 }
			 }elseif(isset($_REQUEST['mailaddress'])  && $_REQUEST['mailaddress']==''){
				  $this->view->message = $this->view->translator->_('Supply a mail address please.');
			 }else{
				 
				 $username = $f->filter($this->_request->getPost('username'));
				 $password = $f->filter($this->_request->getPost('password'));
				 if (empty($username) || empty($password)) {
					 $this->view->message .= $this->view->translator->_(' Please complete your login details.');
				 } else {
					 Zend_Loader::loadClass('Zend_Auth_Adapter_DbTable');
					 $dbAdapter = Zend_Registry::get('db2');
					 $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
					 $authAdapter->setTableName($tb_auth);
					 $authAdapter->setIdentityColumn('c_username');
					 $authAdapter->setCredentialColumn('password');
					 $authAdapter->setIdentity($username);
					 $key='abracadabra';
					 $input=$password;
					 $password = MD5($key.strrev($input)).'-'.MD5($input{0}.substr($input,(floor(strlen($input))/2),strlen($input)));
					 $authAdapter->setCredential($password);//print $password; print '<BR/>';
					 $auth = Zend_Auth::getInstance();				
					 $result = $auth->authenticate($authAdapter);//var_dump($result);die('DANS AUTH');
					 if ($result->isValid() && $tb_auth =='tUser'){
					 	 
					 	 /**
					 	  *	User's privilieges for global administration
					 	  */
					 	  
					 	 $array_role=array();
					 	 $current_role=array();
						 $data = $authAdapter->getResultRowObject(null,$this->Omit_Columns);
						 $selGrp=explode(",", $data->groupe_smul);					
						 $i=0;
						 while(isset($selGrp[$i])){
							 $array_grp[]=$selGrp[$i];						
							 $i++;
						 }
						 $rows_grp = $this->group->find($array_grp);
						 foreach ($rows_grp as $row){						
							 $selRole=explode(",", $row->c_role);
							 $i=0;
							 while(isset($selRole[$i])){
								 $array_role[$selRole[$i]]=1;						
								 $i++;
							 }
						 }//var_dump($rows_grp);die();
						 $array_role=array_keys($array_role);
						 $rows_role = $this->role->find($array_role);
						 foreach ($rows_role as $row){
							 $current_role[]=$row->entitle_smul;
						 }
						 $data->perms=$current_role;
						 $data->real_name=$data->c_fname.' '.$data->c_lastname;
						 $storage=$auth->getStorage();
						 $storage->write($data);
						 
						 /**
						  *	Cache initialisation
						  */
						  
						 $prevclause= new Zend_Session_Namespace('experimental');
						 $prevclause->userTable= $tb_auth;
						 if(null!= $prevclause->clauselst)
						 	 {
						 	 	 $prevclause->clauselst='';
						 	 	 $prevclause->prevtable='';
						 	 }
						 
						 $this->_redirect($this->path);
						 
					 } elseif($result->isValid() && $tb_auth !='tUser'){					 	 
					 	 					 	 
					 	 /**
					 	  *	User's privilieges for thematics only
					 	  */
					 	  
					 	$data = $authAdapter->getResultRowObject(null,$this->Omit_Columns);
					 	$data->perms ="create,read,update,delete,list"; 
					 	$data->real_name=$data->c_fname.' '.$data->c_lastname;
					 	$data->groupe_smul ="27";
					 	$data->menu_smul = "";
					 	$data->tUserTypeID="6";
					 	$data->tOrganizationID="4";
					 	$storage=$auth->getStorage();
						$storage->write($data);	
						
						/**
						  *	Cache initialisation
						  */
						  
						 $prevclause= new Zend_Session_Namespace('experimental');
						 $prevclause->userTable= $tb_auth;

						 if(null!= $prevclause->clauselst)
						 	 {
						 	 	 $prevclause->clauselst='';
						 	 	 $prevclause->prevtable='';
						 	 }
						 
						 $this->_redirect($this->path);
					 }
					 else{
						 $this->view->message = $this->view->translator->_('Login failed.');
					 }
				 }
			 }
		 }
		 //$this->view->title = "Log in";
		 //$this->render();
	 }
	
	
	 
	/**
	 *	Ask login
	 */
	
	function askloginAction()
	{
		$this->view->message = '';
		if ($this->_request->isPost()) {
			Zend_Loader::loadClass('Zend_Filter_StripTags');
			$f = new Zend_Filter_StripTags();
			$username = $f->filter($this->_request->getPost('username'));
			$password = $f->filter($this->_request->getPost('password'));
			if (empty($username)) {
				$this->view->message = $this->view->translator->_(' Please complete your login details.');
			} else {
				Zend_Loader::loadClass('Zend_Auth_Adapter_DbTable');
				$dbAdapter = Zend_Registry::get('db1');
				$authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
				$authAdapter->setTableName('tUser');
				$authAdapter->setIdentityColumn('c_username');
				$authAdapter->setCredentialColumn('password');
				$authAdapter->setIdentity($username);
				$authAdapter->setCredential($password);
				$auth = Zend_Auth::getInstance();				
				$result = $auth->authenticate($authAdapter);
				if ($result->isValid()) {
					$data = $authAdapter->getResultRowObject(null,$this->Omit_Columns);
					$selGrp=explode(",", $data->role_smul);					
					$i=0;
					while(isset($selGrp[$i])){
						$array_grp[]=$selGrp[$i];						
						$i++;
					}
					$rows_grp = $this->group->find($array_grp);
					foreach ($rows_grp as $row){						
						$selRole=explode(",", $row->c_role_smul);
						$i=0;
						while(isset($selRole[$i])){
							$array_role[$selRole[$i]]=1;						
							$i++;
						}
					}
					$array_role=array_keys($array_role);
					$rows_role = $this->role->find($array_role);
					foreach ($rows_role as $row){
						$current_role[]=$row->entitle;
					}
					$data->perms=$current_role;
					$data->real_name=$data->c_fname.' '.$data->c_lastname;
					$storage=$auth->getStorage('Crud', 'storage');
					$storage->write($data);
					
					$this->_redirect($this->path);
				} else {
					echo $this->view->message = $this->view->translator->_('Login failed.');
				}
			}
		}
	}
	
	
	/**
	 *	Logout and unset of session data
	 */
	
	
	function logoutAction()
	{
		$auth = Zend_Auth::getInstance();
		$auth->clearIdentity();
		$storage=$auth->getStorage();
		$storage->clear();
		$this->_redirect($this->path);
	}
	

}
