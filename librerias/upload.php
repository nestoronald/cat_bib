<?php
function nombremes($mes){
setlocale(LC_TIME, 'spanish');
$nombre=strftime("%B",mktime(0, 0, 0, $mes, 1, 2000));
return $nombre;
} 

function obtenerExtensionFichero($str)
	{
	        return end(explode(".", $str));
	}

date_default_timezone_set('America/Lima');
	session_start();
//$tipo = substr($_FILES['fileUpload']['type'], -3);
//$tipo = substr($_FILES['fileUpload']['name'], -4);
$tipo_doc=obtenerExtensionFichero($_FILES['fileUpload']['name']);

//  Definimos Directorio donde se guarda el archivo
$dir = '../data/';


//$codPublicacionesEdit=$_POST['codPublicaciones'];
//$date_in=$_POST['date_in'];


$tipo_documento=$_POST['tipo_documento'];
$txtSolicitud=$_POST['txtSolicitud'];



$month=$_POST['monthUp'];
$mes=nombremes($month);
$year=$_POST['yearUp'];


       $rand=rand(1, 1000);
       //$nombrepdf=$carpeta_sede."_".$tipoDocumento_description."_".$mes."_".$year.$rand;
       $archivoUpload="solicitud_".$txtSolicitud."_".$tipo_documento.".".$tipo_doc;
       $destino=$dir.$archivoUpload;


//  Intentamos Subir Archivo
//  (1) Comprovamos que existe el nombre temporal del archivo
if (isset($_FILES['fileUpload']['tmp_name'])) {

	    //  (2) - Comprobamos de que se archivo se trata
	 if ($tipo_doc == 'pdf' || $tipo_doc == 'ppt' || $tipo_doc == 'pptx' || $tipo_doc == 'doc' || $tipo_doc == 'docx') {
		//  (3) Por ultimo se intenta copiar el archivo al servidor.
		if (!copy($_FILES['fileUpload']['tmp_name'],$destino)){
			    echo '<script> alert("Error al Subir el Archivo");</script>';
		    }
		else{
			//El archivo pdf se ha copiado con Exito.
                        echo '<script>parent.resultadoUpload(0, "'.$archivoUpload.'","'.$tipo_documento.'");</script>';
                    
		    
		    //$queryBDa= "update ofertas set pdf='$destino' where tipo='CAS'";
		    //@mysql_query($queryBDa)or die("Invalid Query: $queryBDa <br>" . mysql_error());
		}
	}
	 else{
		//El Archivo que se intenta subir NO ES del tipo permitido lineas arriba.
		echo '<script>parent.resultadoUpload(2, "'.$archivoUpload.'","'.$tipo_documento.'");</script>';
         }
}

else echo '<script> alert("El Archivo no ha llegado al Servidor.");</script>';


echo '<p>Nombre Temporal: '.$_FILES['fileUpload']['tmp_name'].'</p>';
echo '<p>Nombre en el Server: '.$_FILES['fileUpload']['name'].'</p>';
echo '<p>Tipo de Archivo: '.$_FILES['fileUpload']['type'];

//echo '<p>Codigo: '.$_POST['codPublicaciones'];
//echo '<p>Date: '.$date_in;
echo '<p>Tipo Documento: '.$_POST['tipo_documento'];
//echo '<p>Tipo Publicacion: '.$_POST['tipoPublicacion'];
//echo '<p>'.$queryBDa;
echo '<p>'.$destino;


$_SESSION["ruta_pdf_temporal_".$tipo_documento.""]="tmpUpload/".$archivoUpload;
$_SESSION["pdf_".$tipo_documento.""]=$archivoUpload;

$_SESSION["ruta_destino_".$tipo_documento.""]="data/".$archivoUpload;

echo '<br>';
echo '<p>';
print_r($_SESSION);
echo '<br>';


//echo $tipo_doc;

echo '<p>';

?>
