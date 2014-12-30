<?php
/*
 * Created on Jun 14, 2009
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
 
class Form_View_Helper_IndexSearch extends Zend_View_Helper_Abstract
{
	/**
	 * 		Database record Engine indexer which is called each time an indexation must be processed
	 * 		during a crud action, on a database record
	 * 		
	 * 		@param array $data an array with the data to process, the first index must be 'id' with the ID number of the record in the database
	 * 		@param object $index Indexer engine, instantiated in your controller
	 * 		@param array $toDo Indexation rules defined from the attribute $IsIndexed in you record/table form mapping
	 */
	 
	public function indexSearch($data, $index, $toDo)
	{ 
		
		/**
		 *	Index a document for his first adding
		 */
		$doc = new Zend_Search_Lucene_Document();
		
		/**
		 *	Route to restore the document for viewing after searching
		 */
		$doc->addField(Zend_Search_Lucene_Field::unIndexed('url', 'valUrl', 'utf-8'));
		foreach($toDo['Keyword'] as $key => $value){			
			if($value!='') 
			$doc->addField(Zend_Search_Lucene_Field::keyword($value, $data[$value], 'utf-8'));
		}
		
		foreach($toDo['unStored'] as $key => $value){
			if($value!='') 
			$doc->addField(Zend_Search_Lucene_Field::unStored($value, $data[$value], 'utf-8'));			
		}
		
		foreach($toDo['Text'] as $key => $value){
			if($value!='') 
			$doc->addField(Zend_Search_Lucene_Field::text($value, $data[$value], 'utf-8'));			
		}
		
		foreach($toDo['unIndexed'] as $key => $value){
			if($value!='') 
			$doc->addField(Zend_Search_Lucene_Field::unIndexed($value, $data[$value], 'utf-8'));			
		}
		foreach($toDo['Binary'] as $key => $value){
			if($value!='') 
			$doc->addField(Zend_Search_Lucene_Field::Binary($value, $data[$value]));			
		}
		
		$index->addDocument($doc);
		$index->commit();
	}
	
	/**
	 * 		Database record Engine indexer updater : as Lucene does not really updated directly the index database, previous index data linked to the
	 * 		current record must be deleted and the indexetion process must be re-started for the current record
	 * 		
	 * 		@param array $data an array with the data to process, the first index must be 'id' with the ID number of the record in the database
	 * 		@param object $index Indexer engine, instantiated in your controller
	 * 		@param array $toDo Indexation rules defined from the attribute $IsIndexed in you record/table form mapping
	 */

	public function upDateIndex($data, $index, $toDo)
	{
		$this->resetIndex($index, $data['id']);
		$this->indexSearch($data, $index, $toDo);

	}
	
	/**
	 * 		Record data indexation erasing. For a given ID all index data are removed
	 * 		
	 * 		@param array $id unique ID ot he record to erase
	 * 		@param object $index Indexer engine, instantiated in your controller
	 */
	
	public function resetIndex($index, $id)
	{
		
		$hits = $index->find('id:'.$id);
		foreach ($hits AS $hit) {  
		             $index->delete($hit->id);  
		         }
	 }	
 }
?>
