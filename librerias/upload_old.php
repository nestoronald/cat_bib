<?php

date_default_timezone_set('America/Lima');
	session_start();
$tipo = substr($_FILES['fileUpload']['type'], -3);

//  Definimos Directorio donde se guarda el archivo
$dir = '../tmpUpload/';


//$codPublicacionesEdit=$_POST['codPublicaciones'];
//$date_in=$_POST['date_in'];

$tipoResolucion=$_POST['tipoResolucion'];

$tipoResolucion_description="";
switch ($tipoResolucion){
    case 1:
        $tipoResolucion_description="respre";
    break;        
    case 2:
        $tipoResolucion_description="resdir";
    break;
    case 3:
        $tipoResolucion_description="resjef";
    break;        
    case 4:
        $tipoResolucion_description="resdir";
    break;        
    case 5:
        $tipoResolucion_description="contraLOG";
    break;        
    case 6:
        $tipoResolucion_description="contraRRHH";
    break;        

}

$year=$_POST['year'];


/*Codigo*/
$correlativo=$_POST['correlativoUp'];

    $Icero="0";
    $IIceros="00";
//    $IIIceros="000";
//    $IVceros="0000";
//    $Vceros="00000";

if($correlativo<=9)
        $correlativo="$IIceros$correlativo";

elseif($correlativo<=99)
        $correlativo="$Icero$correlativo";

/*
elseif($correlativo<=999)
        $correlativo="$Iceros$correlativo";
*/

/*
       $archivoUpload="IGP_".$tipoResolucion_description."_".$correlativo."-".$year.".pdf";
       $destino="/datos/resoluciones/".$tipoResolucion_description."/".$year."/".$archivoUpload;
*/
       $archivoUpload=$tipoResolucion_description."".$correlativo."_".$year.".pdf";
       $destino=$dir.$archivoUpload;


//  Intentamos Subir Archivo
//  (1) Comprovamos que existe el nombre temporal del archivo
if (isset($_FILES['fileUpload']['tmp_name'])) {

	    //  (2) - Comprovamos que se trata de un archivo de imágen
	 if ($tipo == 'pdf') {
		//  (3) Por ultimo se intenta copiar el archivo al servidor.
		if (!copy($_FILES['fileUpload']['tmp_name'],$destino)){
			    echo '<script> alert("Error al Subir el Archivo");</script>';
		    }
		else{
			//El archivo pdf se ha copiado con Exito.
                        echo '<script>parent.resultadoUpload(0, "'.$archivoUpload.'");</script>';
                    
		    
		    //$queryBDa= "update ofertas set pdf='$destino' where tipo='CAS'";
		    //@mysql_query($queryBDa)or die("Invalid Query: $queryBDa <br>" . mysql_error());
		}
	}
	 else{
		//El Archivo que se intenta subir NO ES del tipo PDF.
		echo '<script>parent.resultadoUpload(2, "'.$archivoUpload.'");</script>';
         }
}

else echo '<script> alert("El Archivo no ha llegado al Servidor.");</script>';


echo '<p>Nombre Temporal: '.$_FILES['fileUpload']['tmp_name'].'</p>';
echo '<p>Nombre en el Server: '.$_FILES['fileUpload']['name'].'</p>';
echo '<p>Tipo de Archivo: '.$_FILES['fileUpload']['type'];

//echo '<p>Codigo: '.$_POST['codPublicaciones'];
//echo '<p>Date: '.$date_in;
echo '<p>Tipo Documento: '.$_POST['tipoResolucion'];
//echo '<p>Tipo Publicacion: '.$_POST['tipoPublicacion'];
//echo '<p>'.$queryBDa;
echo '<p>'.$destino;

$_SESSION["ruta_pdf_temporal"]="tmpUpload/".$archivoUpload;
$_SESSION["pdf"]=$archivoUpload;

echo '<br>';
echo '<p>';
print_r($_SESSION);

echo '<p>';

?>
