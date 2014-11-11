<?php
require ('../../class/dbconnect.php');	

$dbh=conx();

$dni=$_REQUEST["dni"];
$tipoSubida=$_REQUEST["action"];
$existeFoto=isset($_REQUEST["existeFoto"])?$_REQUEST["existeFoto"]:"";
$existeCV=isset($_REQUEST["existeCV"])?$_REQUEST["existeCV"]:"";

$tamano = $_FILES["archivo"]['size'];
$tipo = $_FILES["archivo"]['type'];
$archivo = $_FILES["archivo"]['name'];
$path_parts = pathinfo($archivo);
$parteExtension=isset($path_parts["extension"])?$path_parts["extension"]:"";
$ext=$parteExtension; 
$extension='.'.$ext;

switch($tipoSubida){
	case "fotos":
            //echo $tipoSubida;
/*********************************
 Permitir solo: 
 Para las fotos: Archivos jpg o jpeg
 Para los cv: pdf
*********************************/

		$ext1="jpg";

/***Para saber que tipo de imagen se subió
		$nomFoto=$dni.$extension;		
***/
		$nomFoto=$dni.".jpg";		
		$destino =  "../upload/fotos/".$nomFoto;

		$existeArchivo=$existeFoto;
		$update="update personal set foto='1' where dni='$dni'";
		
/***Subir la Imagen***/
if ($archivo != "") 
{
/***Para saber que tipo de imagen se subió***/
	$path_parts = pathinfo($archivo);
	$ext = $path_parts["extension"]; 
	/***Restingimos la Subida de Archivos***/
	//<1000000 = menos de un megaByte //echo $tamano; || $ext == $ext2	background-repeat: repeat-y;
	if(!($ext == $ext1  && ($tamano < 1000000))){
		echo "<center><body style='color:red; background-image: url(\"../template/images/fondo.jpg\");  background-repeat: repeat-x; ' >";
		echo "<br>";
		echo "<br>";
		echo "<br>";
		echo "<br>";
		echo "<br>";
		echo "<br>";
		echo "<font face='Monotype Corsiva' size=5>Solo se permiten Imagenes en formato JPG<br>";
		echo "<br><br>";
		echo "Redireccionando...";
		echo "<br><br><br><img style='border:0px solid #FF0000;' src='../imagenes/iconos/cargaAzul.gif' ><br><br>";
		echo "</font></body></center>";
		     	
		header("refresh:3; URL= ../admin.php"); 				           	                                        			           	                                        
    }
    else{    	
			if (copy($_FILES['archivo']['tmp_name'],$destino)) {
	            $status = "Archivo subido";
 	            
				//if($existeArchivo!=""){	            
					/***Se actualiza para luego mostrar la imagen en una consulta***/				
				//	$dbh->query($update);
//					sleep(6);
					echo "<center><body style='color:red; font-size:21;  background-image: url(\"../template/images/fondo.jpg\"); background-repeat: repeat-x;' >";
		            echo "<br>";
		            echo "<br>";
		            echo "<br>";
		            echo "<br>";
		            echo "<br>";
		            echo "<br>";
	                echo $status = "Subiendo Fotograf&iacute;a: <font face='Monotype Corsiva' color='blue' size=5 ><b>".$nomFoto."</b><br><img style='border:0px solid #FF0000;' src='../imagenes/iconos/cargaAzul.gif' ><br><br><img style='border:1px solid #FF0000;' src='../upload/fotos/".$nomFoto."' > ";	            
		            echo "<br>";
		            echo "</font></body></center>";	
					header("refresh:3; URL= ../admin.php"); 								                               
				//}	
	    	} 
		    else 
		        $status = "Error al subir el archivo";
    }
} 
else{
				echo "<center><body style='color:red; font-size:21;  background-image: url(\"../template/images/fondo.jpg\"); background-repeat: repeat-x; ' >";
	            echo "<br>";
	            echo "<br>";
	            echo "<br>";
	            echo "<br>";
	            echo "<br>";
	            echo "<br>";
	            echo "<br><img style='border:0px solid #FF0000;' src='../imagenes/iconos/cargaAzul.gif' ><br><br><font face='Monotype Corsiva' color='red' size=5>No se subió ningún archivo<br>";
	            echo "<br><br><br>";
	            echo "Redireccionando...";
	            echo "<br>";
	            echo "</font></body></center>";
            	
				header("refresh:2; url= ../admin.php");
}


/*********************/		
	break;
	case "cv":
		$ext1="pdf";

		$extension=".pdf";
		$destino =  "../upload/cv/".$dni.$extension;
		$existeArchivo=$existeCV;
		$update="update personal set cv='1' where dni='$dni'";

/***Subir el PDF***/
if ($archivo != "") 
{
	$path_parts = pathinfo($archivo);
	$ext = $path_parts["extension"]; 

	/***Restingimos la Subida de Archivos***/	
	if(!($ext == $ext1  && ($tamano < 1000000))){
		echo "<center><body style='color:red; font-size:21;  background-image: url(\"../template/images/fondo.jpg\"); background-repeat: repeat-x; ' >";
		echo "<br>";
		echo "<br>";
		echo "<br>";
		echo "<br>";
		echo "<br>";
		echo "<br>";
		echo "<br><img style='border:0px solid #FF0000;' src='../imagenes/iconos/cargaAzul.gif' ><br><br><font face='Monotype Corsiva' color='red' size=5>Solo se permiten Documentos en formato PDF<br>";
		echo "<br><br><br>";
		echo "Redireccionando...";
		echo "<br>";
		echo "</font></body></center>";

		header("refresh:2; URL= ../admin.php"); 				           	                                        
 				           	                                        
    }
    else{
			if (copy($_FILES['archivo']['tmp_name'],$destino)) {
	            $status = "Archivo subido";
 	            
				//if($existeArchivo!=""){	            
					/***Se actualiza para luego mostrar la imagen en una consulta***/				
				//	$dbh->query($update);
				//	sleep(6);    				
					header("Location: ../admin.php"); 								                               
				//}	
	    	} 
		    else 
		        $status = "Error al subir el archivo";
    }
} 
else{
				echo "<center><body style='color:red; font-size:21;  background-image: url(\"../template/images/fondo.jpg\"); background-repeat: repeat-x; ' >";
	            echo "<br>";
	            echo "<br>";
	            echo "<br>";
	            echo "<br>";
	            echo "<br>";
	            echo "<br>";
	            echo "<font face='Monotype Corsiva' color='red' size=5>No se subió ningún archivo<br>";
	            echo "<br><br><br>";
	            echo "Redireccionando...";
	            echo "<br>";
	            echo "</font></body></center>";
           	
				header("refresh:1; url= ../admin.php");
				
}
/*********************/		
		
	break;
	
			
}
  
?>