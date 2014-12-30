<?php
/*
 * Created on Apr 27, 2009
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
 * 	This class provide the capability of basic operation, in a generic way,
 *  on database table. This class is intancied in a controller which transmits the name 
 *  table to the constructor
 */
 
class Model_DbTable_GenTb extends Zend_Db_Table
{
	/**
	 * 	Table name used by Zend_Db_Abstract
	 */
	 
  protected $_name;
  protected $_metaInf;
    
  public function __construct($table, $orderby='')
    {
    	parent::__construct();
    	$this->_name=$table;
    	/**
    	 * 	Use cache for MetaData look § 15-5
    	 */
    	$this->_metaInf = $this->info();   	
    }
    
    public function getmetaInf()
    {
    	return $this->_metaInf;
    }
    public function getRecord($id) 
    {
        $id = (int)$id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row) {
            throw new Exception("Count not find row $id");
        }
        return $row->toArray();    
    }
    
    public function addRecord($data)
    {        
        $this->insert($data);
    }
    
    function updateRecord($id, $data)
    {
        $this->update($data, 'id = '. (int)$id);
        
    }
    
    function deleteRecord($id)
    {
        $this->delete('id =' . (int)$id);
    }
}
