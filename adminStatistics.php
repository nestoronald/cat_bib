<?php

	/************************************************************
	Función que muestra las Capas que Contendrán los Graficos 
	************************************************************/
	function muestraFormGrafico(){
		$respuesta = new xajaxResponse();
		$formGrafico="<div><h1>Estad&iacute;sticas del &Aacute;rea de ".$_SESSION["area_description"].":</h1></div>
		<table class='tablecontent'><tr><td aling=top>
				<div id='maestro_chart'></div>
				   </td>
				   <td>
				<div id='detalle_chart' class='grafico'></div>
				   </td></tr>
					<tr><td><p>
		                Clic en las barras para ver el detalle.</p>
						</td>
					</tr>
		</table>";
	
		$cadena="xajax_graficosEstadisticos()";
		$respuesta->script($cadena);
		$respuesta->assign("estadisticas","style.display","block");			
		$respuesta->assign("estadisticas","innerHTML",$formGrafico);
		$respuesta->assign("detalle_chart","style.display","none");
		$respuesta->assign("detalle_chart","style.display","none");
		return $respuesta;
	}


	/*************************************************************
	
	**************************************************************/
	function graficosEstadisticos(){
		$respuesta = new xajaxResponse();
		$link = connectToDB();
		$strXML = "";
		$strXML = "<chart caption='Contribuciones Científicas' subCaption=''  xAxisName='' yAxisName='Cantidad'  showBorder='1' formatNumberScale='0' numberSuffix=' ' bgColor='#D5E1F0,FFFFFF' bgAlpha='100,100' bgRatio='0,100' bgAngle='0' >";
		$strQuery = "select * from category";
		$result = mysql_query($strQuery) or die(mysql_error());
	
		$idArea=$_SESSION["idarea"];
	
		if ($result) {
			while($ors = mysql_fetch_array($result)) {
				$strQuery = "SELECT c.idcategory,Count(c.idcategory) as cantidad, c.category_description, s.idsubcategory, s.subcategory_description FROM data d, category c, subcategory s WHERE c.idcategory=s.idcategory and d.idsubcategory=s.idsubcategory and s.idcategory=".$ors['idcategory']." and ExtractValue(data_content,'publicaciones/areaPRI')=$idArea ORDER BY ExtractValue(data_content,'publicaciones/date_pub')";
				$result2 = mysql_query($strQuery) or die(mysql_error()); 
				$ors2 = mysql_fetch_array($result2);
				$idcategory=$ors['idcategory'];
				$linkDetalle = urlencode("\"javascript:detalleGrafico('$idcategory','$idArea');\"");
	
				if($ors['idcategory']==2){
					if($ors2['cantidad']!=0){
						$strXML .= "<set label='".ucfirst($ors['category_description'])."' value='" . $ors2['cantidad'] . "' />";
					}
				}
				else{
					if($ors2['cantidad']!=0){
						$strXML .= "<set label='".ucfirst($ors['category_description'])."' value='" . $ors2['cantidad'] . "' link = ".$linkDetalle." />";
					}
				}
				//free the resultset
				mysql_free_result($result2);
			}
		}
		mysql_close($link);
	
		// Cerramos la etiqueta "chart".
		$strXML .= "</chart>";
		$grafico=renderChartHTML("graficos/swf_charts/Column3D.swf", "",$strXML, "maestro", 300, 300, false);
		$respuesta->Assign("maestro_chart","innerHTML",$grafico);
		return $respuesta;	
	    
	}



	/*************************************************************
	Función que Grafica la cantidad del Personal Activo y retirado
	**************************************************************/
	function detalleGraficosEstadisticos($numestado){
		$respuesta = new xajaxResponse();
		$strXML = "<chart palette='2' caption='Detalle de Estado de Personal  ' subcaption='(En Unidades)' xAxisName='Date' showValues='1' showBorder='0' >";    
		$link = connectToDB();                
	
	    $strQuery = "select codArea, Count(codArea) as cantidad from personal where estado=$numestado GROUP BY codArea";
	    $result = mysql_query($strQuery) or die(mysql_error());
	
	    
	    if ($result) {
	
	        while($ors = mysql_fetch_array($result)) {
			$cantidad = mysql_num_rows($result);
			$detalle=$ors["codArea"];
			switch($detalle){
				case 0:
				$detalle="Sin &Aacute;rea";
				break;			
				case 1:
				$detalle="Aeronom&iacute;a";
				break;
				case 2:
				$detalle="Astronom&iacute;a y Astrof&iacute;sica";
				break;        	
				case 3:
				$detalle="Geodesia y Peligro Geol&oacute;gico";
				break;    
				case 4:
				$detalle="Geomagnet&iacute;smo";
				break;        	
				case 5:
				$detalle="Variabilidad y Cambio Clim&aacute;tico";
				break;
				case 6:
				$detalle="Sismolog&iacute;a";
				break;    
				case 7:
				$detalle="Vulcanolog&iacute;a";
				break;    
				case 13:
				$detalle="Alta Direcci&oacute;n";
				break;    
				case 11:
				$detalle="Redes Geof&iacute;sicas";
				break;    
				case 8:
				$detalle="Asuntos Acad&eacute;micos";
				break;    
				case 14:
				$detalle="Log&iacute;stica";
				break;    
				case 15:
				$detalle="Contabilidad";
				break;    
				case 16:
				$detalle="Recursos Humanos";
				break;    
				case 17:
				$detalle="Tesorer&iacute;a";
				break;    
				case 18:
				$detalle="Oficina de Dirección de Administraci&oacute;n";
				break;    
				case 19:
				$detalle="Oficina de Dirección Institucional";
				break;    
				case 20:
				$detalle="Departamento Legal";
				break;    
				case 22:
				$detalle="Oficina de Control Institucional";
				break;    
				        	
			}
	            $strXML .= "<set label='" . $detalle . "' value='" . $ors['cantidad'] . "'/>";
	        }
	    }
	    mysql_close($link);
		// Cerramos la etiqueta "chart".
		$strXML .= "</chart>";
		$grafico=renderChartHTML("swf_charts/Pie3D.swf", "",$strXML, "detalle", 400, 300, false);
		$respuesta->Assign("detalle_chart","innerHTML",$grafico);
		return $respuesta;	
	}

	function detalleGraficosEstadisticos1($idCategory){
		$respuesta = new xajaxResponse();
	    switch($idCategory){
	    	case 1:
				$tabla="Publicaciones";
			break;
	    	case 2:
				$tabla="Ponencias";
			break;
	    	case 3:
				$tabla="Información Interna";
			break;
	    	case 4:
				$tabla="Asuntos Académicos";
			break; 
	    }   	
		
		$strXML = "";
		$strXML = "<chart caption='Detalle de " . $tabla ."  ' subcaption='(en Unidades)' xAxisName='Date' showValues='1' labelStep='2' >";
		$link = connectToDB();
		$strQuery ="SELECT s.idsubcategory, s.subcategory_description, Count(s.idsubcategory) as count FROM data d, subcategory s WHERE d.idsubcategory=s.idsubcategory and s.idcategory=$idCategory GROUP BY s.idsubcategory";
		$result = mysql_query($strQuery) or die(mysql_error());
		    
	    if ($result) {
	        while($ors = mysql_fetch_array($result)) {
	            $strXML .= "<set label='" . $ors['subcategory_description'] . "' value='" . $ors['count'] . "' />";
	        }
	    }
		mysql_close($link);
		$strXML .= "</chart>";
		$grafico=renderChartHTML("swf_charts/Pie3D.swf", "",$strXML, "detalle", 300, 300, false);
		$respuesta->Assign("detalle_chart","innerHTML",$grafico);
		return $respuesta;	
	}



?>