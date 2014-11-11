<?php

	include("../class/ClassPlantilla.php");

 	$Contenido=new Plantilla("index");
	$Contenido->asigna_variables(array(
			"ajax" => $xajax->printJavascript()
	));

	$ContenidoString = $Contenido->muestra();
	echo $ContenidoString;

?>