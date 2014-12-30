<?php
/*
 * Created on Dec 2, 2014
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
 class Form_View_Helper_BuildHeadPdf extends Zend_View_Helper_Abstract
{ 

	public $db2;
			//================= Start page creation	  
	public function buildHeadPdf($base, $tt, $font, $num) {

	  /**
	   *	Create page
	   */
	   
	   $this->db2=$base;
	   $texttitlePage = "Devis d'expertise"; 
	   $page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_A4);
	   $width  = $page->getWidth();
	   $height = $page->getHeight();
	  
	   $x1=0.00;
	   $y1=0.00;
	   $y2=$height;
	   $x2=$width;
	   
	   $grayLevel=1.0;
	   $color1 = new Zend_Pdf_Color_GrayScale($grayLevel);
	   $color1 = new Zend_Pdf_Color_Rgb(0, 0, 0);
	   $page->setLineColor($color1);
	   $page->setLineWidth(2.0);
	   $page->drawRectangle($x1, $y1, $x2, $y2,
	   $fillType = Zend_Pdf_Page::SHAPE_DRAW_STROKE);
	   //$page->drawLine(5.0, 5.0, 550.0, 5.0);
		
	   $page->setFont($font, 10)
		->setLineWidth(1.0)
		->drawText('ECO-TECH CERAM S.A.S au capital de 10 000 €', 210, 822, 'UTF-8//IGNORE')
		->drawText('Siège : Rambla de la Thermodynamica - 66 000 Perpinya', 210, 807, 'UTF-8//IGNORE')
		->drawText('Siren : 801.331.000 RCS de Perpinya', 210, 792, 'UTF-8//IGNORE')
		->drawText('NAF 7219Z : R&D ou autres Sciences Physiques ou Naturelles', 210, 777, 'UTF-8//IGNORE')
		->drawLine(10.0, 15.0, 585.0, 15.0)
		->drawText('ECO-TECH CERAM  Rambla de la Thermodynamica - 66 000 Perpinya  Tél.: +334 68 55 68 43 , +336 71 44 70 68', 30, 3, 'UTF-8//IGNORE')
		->drawLine(10.0, 758.0, 585.0, 758.0);
		 $query2 = "SELECT c_name, client FROM tProjectetc WHERE id=".$tt[0]['projectid'];			
		 $this->db2->setFetchMode(Zend_Db::FETCH_OBJ);
		 $res = $this->db2->fetchAll($query2);
		 $val = explode('-', $res[0]->client);
		 $query3= "SELECT c_name, AddressLine1, PostalCode, City, Phone  FROM tClientetc WHERE id=".trim($val[1]);
		 $this->db2->setFetchMode(Zend_Db::FETCH_OBJ);
		 $res2 = $this->db2->fetchAll($query3);
		 $client = $res2[0]->c_name.' '.$res2[0]->AddressLine1.' '.$res2[0]->PostalCode.' '.$res2[0]->City.' Tél.: '.$res2[0]->Phone;
		 $page->setFont($font, 14)
			->drawText('DEVIS PROJET : '.$res[0]->c_name, 10, 740, 'UTF-8//IGNORE')
			->setFont($font, 10)
			->drawText('Référence devis : '.$tt[0]['ref'], 400, 740, 'UTF-8//IGNORE')
			->drawLine(400.0, 737.0, 575.0, 737.0)
			->drawText(' Client => '.$client, 10, 725, 'UTF-8//IGNORE')
			->drawLine(10.0, 719.0, 400.0, 719.0)
			->drawText(' Page '.$num.' sur    ', 400, 725, 'UTF-8//IGNORE');
			
	   try{	
		   $imageFile = 'images/logoEtc160x80.png';
		   $stampImage = Zend_Pdf_Image::imageWithPath($imageFile); 
		   $page->drawImage($stampImage, 10, 760, 170, 840); 
	   }
	   catch (Zend_Pdf_Exception $e) {
			// Example of operating with image loading exceptions.
		if ($e->getMessage() != 'Image extension is not installed.' &&
			$e->getMessage() != 'JPG support is not configured properly.') {
			throw $e;
		}
				
	}
	return $page;
    }
}				   	
			//============= End page creation	   	
?>
