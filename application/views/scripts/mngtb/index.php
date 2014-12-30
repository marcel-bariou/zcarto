<?php
    $tpl1 = Zend_Registry::get('tpl1');  
    $htmltpl1="views/mngtb/index.tpl";
    $tpl1->setFile("MyFileHandle", $htmltpl1);
    $tpl1->setBlock("MyFileHandle", "listTitle", "listTitles");
    $tpl1->setBlock("MyFileHandle", "list", "lists");
    $tpl1->setBlock("MyFileHandle", "fabpdf", "fabpdfs");
    $tpl1->setBlock("MyFileHandle","fablstzpdf", "fablstzpdfs");
    $tpl1->setBlock("list", "listValue", "listValues");
    $tpl1->setBlock("list", "testxml", "testxmls");
    $tpl1->setBlock("list", "copyrec", "copyrecs");
    $tpl1->setBlock("list","fabdevispdf", "fabdevispdfs");
    $tpl1->setBlock("list", "georef", "georefs");
    $tpl1->setBlock("list", "entzone", "entzones");
    //$tpl1->setBlock("MyFileHandle", "entzone", "entzones");
    $tpl1->setVar("LANGUE", $this->langue);
    if ($this->tableName != 'tEntreprisespo' &&  $this->tableName != 'tEntrepriseslr' &&  $this->tableName != 'tZones')
      {
	$tpl1->setBlock("MyFileHandle", "frmsearch", "frmsearchs");
	$tpl1->setVar("frmsearchs", "");
      }else{
      	$tpl1->setBlock("MyFileHandle", "optionzone", "optionzones");
      	/**
      	 *	Zones list building
      	 */
      	if ($this->tableName == 'tEntreprisespo'){
		$tzone = $this->forms->getLabelForm(24, 2);
		foreach($tzone as $key => $val){
			$tpl1->setVar("VALUEZONE", $key);
			$tpl1->setVar("NAMEZONE", $val);
			$tpl1->parse("optionzones", "optionzone", true);
		}
      	}
 	$tpl1->setBlock("MyFileHandle", "selfield", "selfields");     
	$tpl1->setVar("POSTSEARCH", $this->url(array('controller'=>'mngtb', 'action'=>'index','t' => $this->tableName)));
      	foreach($this->forms->listElems as $t){
	  $tpl1->setVar("NOMFIELD", $t['name']);
	  $tpl1->setVar("NOMLABEL", $t['label']);
	  $tpl1->parse("selfields", "selfield", true);
	}
	
      }
 	
    $countTot= count($this->records);  
    $countRec=count($this->paginator);
    $tpl1->setVar("COUNTREG", $countTot);
    $tpl1->setVar("NBREG", $this->paginator->getItemCountPerPage());
    
    if($this->tableName=='tZones'){ 
        $tpl1->setVar("FAITLSTZPDF", "/zcarto/public/mngtb/lstzpdf/t/tZones/page/1/tzone/0");        		
    	$tpl1->parse("fablstzpdfs", "fablstzpdf", true);
    }else{
    	$tpl1->setVar("fablstzpdfs",'');
    }
    
    
    $tpl1->setVar("FAITPDF", "/zcarto/public/mngtb/buildpdf/t/tEntreprisespo/page/1/tzone/0");
    if($countTot<150 && $this->flagzone===1){
    	$tpl1->parse("fabpdfs", "fabpdf", true);	    
    }else{
    	$tpl1->setVar("fabpdfs", '');    
    }
    
    if($countRec!=0){ 
    	foreach($this->display['title'] as $title){
    		$tpl1->setVar("TITLE", $title);
    		$tpl1->parse("listTitles", "listTitle", true);
    	}
    	foreach($this->paginator as $record)
    	{
	  	$style_edit = "#0398CA";
	  	$style_geo = "#0398CA";
    		$tpl1->setVar("COLORBG", $this->cycle(array("#BDD3EA","#FFFFFF"))->next());
    		
    		foreach($this->display['value'] as $value){
    			if ($value=='zones'){
    				$dbdirect= Zend_Registry::get('mysqldirect');
    				$sql= "SELECT `c_name` FROM `tZones` WHERE `id`= ".$record->$value;
    				$res=$dbdirect->query($sql);
    				$v=$res->fetch_array(MYSQLI_ASSOC);    				
    				$tpl1->setVar("MAZONE", "TITLE=\"".$this->escape($v['c_name'])."\"");
    				
    			}else{
    				$tpl1->setVar("MAZONE", "");
    			}
    			
    			if ($value=='latitude' ||  $value=='longitude'){
    				$tpl1->setVar("VALUE", sprintf("%.5f", $this->escape($record->$value)));    				
    			}else{
    			
    				$tpl1->setVar("VALUE", html_entity_decode($this->escape($record->$value)));
    			}
    			$tpl1->parse("listValues", "listValue", true); 
    		}
    		if($this->tableName=='tEntreprisespo'){
    			if($record->existverif==2){
    				$style_edit = "#FF0000"; 
			}elseif (preg_match("/2014/", $record->dateverif) && $record->verifok==2) {
				$style_edit = "#0398CA";  
			} else {
				$style_edit = "green;font-weight: bold;";
			}
			if ( $record->gpsok==2) {
				$style_geo = "#0398CA";
			} else {
				$style_geo = "green;font-weight: bold;";
			}
    		}
    		
		    if($this->tableName=='tDevisetc'){ 
		    	if($record->locked==1){
    				$style_pdf = "#FF0000"; 
			}else{
				$style_pdf = "#0398CA";  
			} 
			$tpl1->setVar("STYLEDEVIS", $style_pdf);
			$tpl1->setVar("FAITDEVISPDF", "/zcarto/public/mngtb/devetcpdf/t/tDevisetc/id/".$record->id);        		
			$tpl1->parse("fabdevispdfs", "fabdevispdf", true);
		    }else{
			$tpl1->setVar("fabdevispdfs","");
		    }

    		$tpl1->setVar("STYLEEDIT", $style_edit);
    		$tpl1->setVar("STYLEGEO", $style_geo);
        	$tpl1->setVar("EDIT",  $this->url(array('controller'=>'mngtb', 'action'=>'edit', 'id'=>$record->id, 'tzone'=>Zend_Registry::get('tzone'), 'page' =>  $this->paginator->getCurrentPageNumber())));
        	$tpl1->setVar("DELETE", $this->url(array('controller'=>'mngtb', 'action'=>'delete', 'id'=>$record->id,'tzone'=>Zend_Registry::get('tzone'), 'page' =>  $this->paginator->getCurrentPageNumber())));
        	$tpl1->setVar("ADD", $this->url(array('controller'=>'mngtb', 'action'=>'add','t' => $this->tableName, 'tzone'=>Zend_Registry::get('tzone'))));
        	if($this->tableName=='tChamp'){
        		$tpl1->setVar("CHECKXML", $this->url(array('controller'=>'mngtb', 'action'=>'purexml','t' => $this->tableName, 'id'=> $record->id)));
        		$tpl1->setVar("TESTPATH", $this->url(array('controller'=>'mngtb', 'action'=>'purexml','t' => $this->tableName, 'id'=> $record->id, 'testpath' => 'go')));
        		$tpl1->parse("testxmls", "testxml", true);
        	}else{
        		$tpl1->setVar("testxmls", "");
        	}
        	if($this->tableName=='tEntreprisespo'){ 
        		$tpl1->setVar("CARACLIST", $this->texttitlePage);
        		if($record->AddressLine2!="---"){
        			$mesinfos = $record->AddressLine1.", ". $record->AddressLine2.", ".$record->City.", ".$record->PostalCode.", ".$record->country;
        			$stringAddress =base64_encode($mesinfos);
			}else{
        			$mesinfos = $record->AddressLine1.", ".$record->City.", ".$record->PostalCode.", ".$record->country;
				$stringAddress =base64_encode($mesinfos);
        		}
        		$tpl1->setVar("MESINFOS", "TITLE=\"".$mesinfos."\"");
        		$tpl1->setVar("GEOREF", "javascript: void();\" onclick=\"window.open('".$this->url(array('controller'=>'mngtb', 'action'=>'georef','adresse' => $stringAddress, 'latit'=> $record->latitude, 'longit'=> $record->longitude, 'id'=> $record->id))."', 'GeoRefInverse', 'width=950, height=900, SCROLLBARS')");
        		$tpl1->parse("georefs", "georef", true);
        	}else{
        		$tpl1->setVar("georefs", "");
        		$tpl1->setVar("MESINFOS", "");
        		$tpl1->setVar("CARACLIST", "");
        	}
        	
        	if($this->tableName=='tZones'){ 
        		
        		 if ( $record->enverif=="1") {
        			$style_geo = "purple;font-weight: bold;";	
        		 } elseif($record->enverif=="0") {
        			$style_geo = "green;font-weight: bold;";
        		 }else{
        		 	 $style_geo = "#0398CA";
        		 }
        		 
  			  $tpl1->setVar("ENTRE", $this->url(array('controller'=>'mngtb', 'action'=>'index','t' => 'tEntreprisespo', 'fkidzone'=> $record->id, 'tzone'=>  Zend_Registry::get('tzone'))));
  			  $tpl1->setVar("STYLEENT", $style_geo);
  			  $tpl1->parse("entzones", "entzone", true);
        		
        	}else{
         		$tpl1->setVar("entzones", "");       		
         		$tpl1->setVar("fablstzpdfs", "");       		
        	}
        	
        	$tzone= Zend_Registry::get('tzone');
        	if($this->tableName !='txxxUser'){
        	
        		if($this->tableName=='tChamp'&& $tzone!=''){
			  $tpl1->setVar("COPY", $this->url(array('controller'=>'mngtb', 'action'=>'copy','t' => $this->tableName, 'id'=> $record->id, 'tzone'=>  Zend_Registry::get('tzone'))));
        		}
        	
        		$tpl1->setVar("COPY", $this->url(array('controller'=>'mngtb', 'action'=>'copy','t' => $this->tableName, 'id'=> $record->id)));
        		$tpl1->parse("copyrecs", "copyrec", true);
        	}else{
        		$tpl1->setVar("copyrecs", "");
        	}
        	 
        	$tpl1->parse('lists', 'list', true);
        	$tpl1->setVar("listValues", "");
        	$tpl1->setVar("testxmls", "");
        	$tpl1->setVar("copyrecs", "");
        	$tpl1->setVar("georefs", "");
        	$tpl1->setVar("entzones", "");
        	$tpl1->setVar("fabdevispdfs","");
    	}
   
    }else{
    	$tpl1->setVar("listTitles", "");
    	$tpl1->setVar("lists", "");
    }
   $tpl1->setVar("PAGINATOR", $this->paginationControl($this->paginator, 'Sliding', 'pagination.php'));
   $tpl1->parse( "MyOutput", "MyFileHandle");
   $tpl1->p("MyOutput");
?>