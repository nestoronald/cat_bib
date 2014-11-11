<?php

/**************************************************
Contiene funciones que modelan el ingreso de datos
a la biblioteca 
***************************************************/
	require ('xajax_core/xajax.inc.php');
	$xajax = new xajax();
	$xajax->configure('javascript URI', './');

	require ("../class/ClassCalendar.php");
	require ("../class/ClassCombo.php");
	require ("../class/ClassForm.php");
	require ('dbconnect.php');

	/**************************************************
	Funcion que muestra una lista de categorias
	***************************************************/

	function categoryPub(){
		$objResponse = new xajaxResponse();
		$i=0;
		$dbh=conx();
		foreach($dbh->query("SELECT * FROM category") as $row) {
			$category[$i]= $row["namecategory"];
			$id[$i]= $row["idcategory"];
			$i++;
		}
		$dbh = null;

		if (is_array($category)){
			$cbocategory=new combo();
			$combo=$cbocategory->comboList($category,$id,"OnChange","xajax_publicationType(this.value)","Selecciona la categoria ","*","cbostation",0);
			$objResponse->assign('divcategory', 'innerHTML', $combo);
		}
		else{
			$objResponse->assign('divcategory', 'innerHTML', 'No data available');
		}
		
		return $objResponse;			
	}


	/**************************************************
	Funcion que muestra los tipos de publicaciones para una categoria
	***************************************************/

	function publicationType($idcategory){
		$objResponse = new xajaxResponse();
		
		$i=0;
		$dbh=conx();
		foreach($dbh->query("SELECT * FROM publication where idcategory='$idcategory'") as $row) {
			$publication[$i]= $row["namepublication"];
			$id[$i]= $row["idpublication"];
			$i++;
		}
		$dbh = null;

		if (is_array($publication)){
			$cboPublication=new combo();
			$combo=$cboPublication->comboList($publication,$id,"OnChange","xajax_showDataList($idcategory,this.value)","Selecciona el tipo ","*","cbostation",0);
			$objResponse->assign('divpublication', 'innerHTML', $combo);
		}
		else{
			$objResponse->assign('divpublication', 'innerHTML', 'No data available');
		}
		
		
		return $objResponse;			
	}


	/*******************************************************************
	Funcion que muestra una lista de categorias de publicaciones
	*******************************************************************/

	function showDataList($idcategory,$idpublication){
		$objResponse = new xajaxResponse();
		$n=0;
		$dbh=conx();
		$dbh->query("SET NAMES 'utf8'");
		foreach($dbh->query("SELECT item,data FROM datapublication where idcategory='$idcategory' and idpublication='$idpublication'") as $row) {
			$xmltemplate[$n] = $row["data"];
			$item[$n] = $row["item"];
			$n++;
		}
		$dbh = null;
		
		/*
		$xmltemplate="<?xml version='1.0'?><fields><field><campo>Autor</campo><valor>Hola, que tal?</valor></field><field><campo>Tema</campo><valor>Bien, gracias.</valor></field></fields>";
		*/
		$htmlForm="";
		$th="";
		$countTh=0;
		
		if (isset($xmltemplate)){

			for ($i=0; $i<count($xmltemplate); $i++){			
				$xml = simplexml_load_string($xmltemplate[$i]);
				$html="";		
				foreach ($xml->field as $field){
					
					if($countTh==0){
		  				$th.="<th>$field->campo</th>";
					}
		  			
		  			$html.="<td>$field->valor</td>";
				}
				$countTh++;
				$htmlForm.="<tr><td><a href=# onclick=xajax_showDataEdit($idcategory,$idpublication,$item[$i]);><img src=./images/edit.png></a></td>$html</tr>";
				//$htmlForm="<tr>".$htmlForm."</tr>";
			}
			$th="<th>Edit</th>$th";
			$htmlForm="<table>".$th.$htmlForm."</table>";				
				//$objResponse->alert((string)$x);
			$objResponse->assign('divlist', 'innerHTML', $htmlForm);
			$objResponse->assign('divfigure', 'innerHTML', '');			
			$objResponse->assign('divupload', 'innerHTML', '');
			$objResponse->assign('divmessage', 'innerHTML', '');
			
		}
		else{
			$htmlForm="No existen datos";
			$objResponse->assign('divlist', 'innerHTML', $htmlForm);
			$objResponse->assign('divfigure', 'innerHTML', '');
			$objResponse->assign('divmessage', 'innerHTML', '');
			$objResponse->assign('divupload', 'innerHTML', '');
		}
	
		
		return $objResponse;			
	}

	/*******************************************************************
	Funcion que verifica que una cadena contenga solo ciertos caracteres validos
	*******************************************************************/
	function strinChr($str,$valChr){
		$arrayStr=str_split(trim($str));
		$error=false;
		for ($i=0; $i<count($arrayStr); $i++){
			$pos = strrpos($valChr,$arrayStr[$i]);
			if ($pos===false){
				$error=true;
			}
		}
		
		return !$error;
	}
	

	/*******************************************************************
	Funcion que muestra los campos a editar para las publicaciones
	*******************************************************************/

	function showDataEdit($idcategory,$idpublication,$item){
		$objResponse = new xajaxResponse();

		$dbh=conx();
		$dbh->query("SET NAMES 'utf8'");
		foreach($dbh->query("SELECT data FROM datapublication where item=$item") as $row) {
			$xmldata= $row["data"];
		}
		
		
		$dbh->query("SET NAMES 'utf8'");
		foreach($dbh->query("SELECT xmltemplate FROM publication where idcategory='$idcategory' and idpublication='$idpublication'") as $row) {
			$xmltemplate= $row["xmltemplate"];
		}
		
		$dbh = null;
		
		// Cargamos tanto el dato en formato xml como la plantilla actual en xml
		// y mostramos aquellos campos del dato que corresponden a la plantilla
		if (isset($xmldata) and isset($xmltemplate)){
			
			$xmld = simplexml_load_string($xmldata);
			$xmlt = simplexml_load_string($xmltemplate);
			$i=0;
			
			foreach ($xmlt->field as $fieldT){
	  			$fieldnameTemplate[$i]= $fieldT->campo;
	  			$j=0;
	  			foreach ($xmld->field as $fieldD){
	  				$fieldnameData[$j]= $fieldD->campo;
		  			
		  			if(((string)$fieldnameTemplate[$i])==((string)$fieldnameData[$j])){
		  				$value[$i]= $fieldD->valor;
		  				break;
		  			}
		  			else{
		  				$value[$i]= "";
		  			}
		  			
		  			$j++;
	  			}
	  			//$value[$i]= "x";
	  			$i++;
			}
			
			$form = new form();
			$htmlForm=$form->showInputs($fieldnameTemplate,$fieldnameTemplate,$value,"text","formInput");
			//$objResponse->alert((string)$x);
			//$objResponse->alert($xml->asXML());
		    $form="
			<label for='file'>Archivo</label>
			<input class='file' name='file' id='file' size='30' type='file' text='Buscar' onchange='init();''>
			<input type=hidden name='idfile' id='idfile' value='$idcategory-$idpublication-$item'>"; 
			
			$dbh=conx();
			
			foreach($dbh->query("SELECT filename FROM datafiles WHERE item=$item") as $row) {
				$filename = $row["filename"];
			}
			
			if (isset($filename)){
				$frontJpg="<img width=100px src=datafiles/front/$filename.jpg>";
				$objResponse->assign('divfigure', 'innerHTML', $frontJpg);
			}


			$button="<label for=Actualizar>&nbsp;</label><input name=Actualizar style='width:100px' type=button value=Actualizar onclick=xajax_updateForm($idcategory,$idpublication,$item,xajax.getFormValues('formHemeroteca'))>";
			$button.="&nbsp;&nbsp;<input name=Regresar style='width:100px' type=button value=Cancelar onclick=xajax_showDataList($idcategory,$idpublication)>";
			
			$objResponse->assign('divupload', 'innerHTML', $form);
			$objResponse->assign('divlist', 'innerHTML', $htmlForm);
			$objResponse->assign('buttonUpdate', 'innerHTML', $button);

		}

		else{
			$htmlForm="No existen datos";
			$objResponse->assign('divlist', 'innerHTML', $htmlForm);
		}
		
		//$xml = simplexml_load_string($xmltemplate[0]);
		//$objResponse->alert($xml->asXML());

		return $objResponse;			
	}

	
	/*******************************************************************	
	Funcion que inserta valores en la base de datos, verificando su sintaxis
	*******************************************************************/
	function updateForm($idcategory,$idpublication,$item,$formInput){

		$errores=0;
		$objResponse = new xajaxResponse();
		
		
		$chr = ",.-ABCDEFGHIJKLMNÑOPQRSTUVWXYZabcdefghijklmnñopqrstuvwxyzáéíóú1234567890 ";
			
		// Verificamos los caracteres validos 
		$i=0;
		foreach($formInput as $row => $value) {
			$datos[$i]= $value;
			$i++;
		}


		for ($i=0; $i<count($datos); $i++){
			if (strlen($datos[$i])>0){			
				if (strinChr($datos[$i],$chr)===false){
					$errores=1;
					$msg="Datos incorrectos";
				}
			}
			else{
				$errores=1;
				$msg="Datos incompletos";
			}
		}
		

		if ($errores==1){
			$objResponse->alert($msg);			
		}
		

		// Si no hay errores entonces se inserta en la base de datos
		if ($errores==0){
			
			$dbh=conx();
			$dbh->query("SET NAMES 'utf8'");
			foreach($dbh->query("SELECT xmltemplate FROM publication where idcategory='$idcategory' and idpublication='$idpublication'") as $row) {
				$xmltemplate = $row["xmltemplate"];
			}
			
			// xmltemplate es la plantilla xml
			
			if (isset($xmltemplate)){
			
				$xml = simplexml_load_string($xmltemplate);
				$i=0;
				
				// para cada campo debemos asignar un valor
				// es decir adicionar un atributo llamado valor
				foreach ($xml->field as $field){
		  			$field->addChild('valor',$datos[$i]);
		  			$fieldname[$i]= $field->valor;
		  			$i++;
				}

			}

			//$htmlForm=(string)$fieldname[1];
			$strXml=$xml->asXML();

			$sql="UPDATE datapublication SET data ='$strXml' WHERE item=$item";
			$dbh->query("SET NAMES 'utf8'");
			$dbh->query($sql);
			$dbh = null;
			

			$objResponse->alert("Registro actualizado correctamente");
			$objResponse->script("xajax_showDataList($idcategory,$idpublication)");
			//$objResponse->redirect("formInput.php", $iDelay=0);
			
			//Y se envia un e-mail de alerta
			//mail("jro-imagen@jro.igp.gob.pe","Alerta-Visitas guiadas al ROJ dia : ".$dia."/".$mes."/".$anio,"Nuevo inscrito : ".$form_entrada['nombre'],"From: webmaster@jro1.igp.gob.pe;");

		}
		
		return $objResponse;			
	}
	
	
	/*******************************************************************
 	Funcion que verifica el archivo que se va a subir
	*******************************************************************/
	
	function upload($file_id, $folder="", $types="", $idfile="") {
	
		if(!$_FILES[$file_id]['name']) return array('','No ha seleccionado un archivo');

		$file_name = $_FILES[$file_id]['name'];
		//Get file extension
		$ext_arr = split("\.",basename($file_name));
		$ext = strtolower($ext_arr[count($ext_arr)-1]); //Get the last extension

		$all_types = explode(",",strtolower($types));
		if($types) {
			
			//Check name and extension of file
		    if(in_array($ext,$all_types));
		    else {
		        $result = "' ".$_FILES[$file_id]['name']." '  no es un archivo v&aacute;lido."; //Show error if any.
		        return array('',$result);
		    }
		}

		//Where the file must be uploaded to
		if($folder) $folder .= '/';//Add a '/' at the end of the folder
		$uploadfile = $folder . $file_name;

		$result = '';
		//Move the file from the stored location to the new location
		if (!move_uploaded_file($_FILES[$file_id]['tmp_name'], $uploadfile)) {
		    $result = "No se puede subir el archivo '".$_FILES[$file_id]['name']."'"; //Show error if any.
		    if(!file_exists($folder)) {
		        $result .= " : Folder don't exist.";
		    } elseif(!is_writable($folder)) {
		        $result .= " : Folder not writable.";
		    } elseif(!is_writable($uploadfile)) {
		        $result .= " : File not writable.";
		    }
		    $file_name = '';
		    
		} else {
		    if(!$_FILES[$file_id]['size']) { //Check if the file is made
		        @unlink($uploadfile);//Delete the Empty file
		        $file_name = '';
		        $result = "El archivo esta vac&iacute;o - use otro archivo."; //Show the error message
		    } else {
		        chmod($uploadfile,0777);//Make it universally writable.
		        $result ="El archivo se guard&oacute; correctamente.";
		        createFront($file_name,$idfile);
		    }
		}

		return array($file_name,$result);
	}
	
	
	function createFront($fileName,$idfile){
		$pathFile="/web/hemeroteca/datafiles/pdf/$fileName";
		$tempPdf="/web/hemeroteca/datafiles/temp.pdf";
		$frontJpg="/web/hemeroteca/datafiles/front/$fileName.jpg";
		
		
		exec("pdftk A=$pathFile cat A1  output $tempPdf");
		exec("convert $tempPdf $frontJpg");
		exec("mogrify -resize 50% $frontJpg");
		
		list($idcategory,$idpublication,$item)=explode("-",$idfile);
		$dbh=conx();
		
		foreach($dbh->query("SELECT item FROM datafiles WHERE item=$item") as $row) {
			$rowItem = $row["item"];
		}
		
		if (isset($rowItem)){
			$sql="UPDATE datafiles SET filename='$fileName' WHERE item=$item";
		}
		else{
			$sql="INSERT INTO datafiles(item,filename) VALUES ($item,'$fileName')";
		}
		$dbh->query("SET NAMES 'utf8'");
		$dbh->query($sql);
		$dbh = null;
		
	}
	
	$reqcatPub =& $xajax->registerFunction('categoryPub');
	$reqpubType =& $xajax->registerFunction('publicationType');
	$reqshowDataList =& $xajax->registerFunction('showDataList');
	$reqshowDataEdit =& $xajax->registerFunction('showDataEdit');
	$requpdForm =& $xajax->registerFunction('updateForm');
	$xajax->processRequest();

?>
