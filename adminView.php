<?php

	include("../class/ClassPlantilla.php");


 	$Contenido=new Plantilla("admin");
	$Contenido->asigna_variables(array(
			"ajax" => $xajax->printJavascript(),
			"year" => date('Y'),
	));

	$ContenidoString = $Contenido->muestra();
	echo $ContenidoString;

?>