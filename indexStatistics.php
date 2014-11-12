<?php


	//include ("graficos/FusionCharts.php");
	//include("graficos/DBConn1.php");


	/************************************************************
	Función que muestra las Capas que Contendrán los Graficos
	************************************************************/
	function muestraFormGraficoAreas(){
		$respuesta = new xajaxResponse();

		$formGrafico="<div><h1>Estad&iacute;stica de las &Aacute;reas:</h1></div>
						<table class='tablecontent'><tr><td aling=top>
								<div id='maestro_chart'></div>
								   </td>
								   <td>
								<div id='detalle_chart' class='grafico'></div>
								   </td></tr>
									<tr><td><p>Click en las barras para ver el detalle.</p></td>
									</tr>
						</table>";

		$cadena="xajax_graficosEstadisticosAreas()";
		$respuesta->script($cadena);
		$respuesta->Assign("estadisticas","style.display","block");
		$respuesta->Assign("estadisticas","innerHTML",$formGrafico);
		$respuesta->Assign("detalle_chart","style.display","none");

		return $respuesta;
	}

	/*************************************************************
	Función que Grafica la cantidad del Personal Activo y retirado
	**************************************************************/
	function graficosEstadisticosAreas(){
		$respuesta = new xajaxResponse();
		$link = connectToDB();
		$strXML = "";
		$strXML = "<chart palette='3' caption='' subCaption=''  xAxisName='' yAxisName='Cantidad de Documentos'  showBorder='0' formatNumberScale='0' numberSuffix=' ' bgColor='#D5E1F0,FFFFFF' bgAlpha='100,100' bgRatio='0,100' bgAngle='0' labelDisplay='Rotate' slantLabels='1'>";

		// Fetch all factory records
		//$strQuery = "select estado, Count(estado) as cantidad from personal GROUP BY estado";
		$strQuery = "select * from area";

		$result = mysql_query($strQuery) or die(mysql_error());

		if ($result) {
			while($ors = mysql_fetch_array($result)) {
		        $idArea=$ors["idarea"];
		        $area_description=$ors["area_description"];

				$strQuery = "SELECT count(ExtractValue(data_content,'publicaciones/areaPRI')) as cantidad FROM data d where EXTRACTVALUE( data_content,  'publicaciones/areaPRI' ) =".$ors["idarea"];
				$result2 = mysql_query($strQuery) or die(mysql_error());
				$ors2 = mysql_fetch_array($result2);
				// Una vez generado el gráfico detalle, se desplegará en el DIV "detalle_chart". Haciéndose ahora visible.
				//$linkDetalle = urlencode("\"javascript:xajax_graficoFromLoad('2','1');\"");
				/*llamo un javascript definido en el tpl y asu vez este llama a la funcion xajax*/
				$idfrom=$_SESSION["idfrom"];
				$linkDetalle = urlencode("\"javascript:ejecutarXajax($idfrom,$idArea);\"");

				if($ors2['cantidad']!=0){
					$strXML .= "<set label='$area_description' value='" . $ors2['cantidad'] . "' link = ".$linkDetalle." />";
				}
				//free the resultset
				mysql_free_result($result2);
			}
		}
		mysql_close($link);

		$strXML .= "</chart>";
		$grafico=renderChartHTML("graficos/swf_charts/Bar2D.swf", "",$strXML, "maestro", 600, 300, false);
		$respuesta->Assign("maestro_chart","innerHTML",$grafico);
		return $respuesta;

	}


	function verGrafico(){
	$respuesta = new xajaxResponse();

        if(isset($_SESSION["loginDownload"])){
            $html="Esta logeado como ".$_SESSION["loginDownload"];
            $html.=" <a href='#' >Cerrar Sesión</a>";
            $respuesta->Assign("loginform","innerHTML",$html);
        }
        else{
            $html="<p>&nbsp;</p><p>Para descargar es necesario identificarse, ingrese usuario y contraseña</p>";
            $respuesta->Assign("mensaje","innerHTML",$html);

        }

        if(isset($_SESSION["idfrom"])){
            if($_SESSION["idfrom"]==1){

                $respuesta->script("xajax_formConsultaIndexShow(1)");
                //$respuesta->script("xajax_muestraFormGraficoAreas()");

            }

            if($_SESSION["idfrom"]==2 && $_SESSION["idarea"]){
            	//$respuesta->script("xajax_formConsultaIndexShow(2)");
                if($_SESSION["pag"]==1){
                    //$respuesta->Assign("resultSearch","style.display","none");
                    //$respuesta->Assign("divformSearch","style.display","block");
                    $respuesta->script("xajax_formConsultaIndexShow(2)");

                }
                elseif($_SESSION["pag"]==2){
                    //$respuesta->Assign("divformSearch","style.display","none");
                    //$respuesta->script("xajax_searchPublicationShow(xajax.getFormValues('formSearch'),'3');");
                    //$respuesta->script('xajax_auxSearchShow("5","1","","3")');
                    $respuesta->script("xajax_formConsultaIndexShow(2,2)");

                }


                //$cadena="xajax_muestraFormGrafico()";
                //$respuesta->script($cadena);
            }

            if($_SESSION["idfrom"]==3 && $_SESSION["idautor"]){
                //$respuesta->script("xajax_formConsultaIndexShow(3,2)");
                //$respuesta->script('xajax_auxSearchShow("5","1",xajax.getFormValues(\'formSearch\'),"3")');
                //$respuesta->script("xajax_searchPublicationShow(xajax.getFormValues('formSearch'),'3');");


                if($_SESSION["pag"]==1){
                    //$respuesta->Assign("resultSearch","style.display","none");
                    //$respuesta->Assign("divformSearch","style.display","block");
                    $respuesta->script("xajax_formConsultaIndexShow(3)");

                }
                elseif($_SESSION["pag"]==2){
                    //$respuesta->Assign("divformSearch","style.display","none");
                    //$respuesta->script("xajax_searchPublicationShow(xajax.getFormValues('formSearch'),'3');");
                    //$respuesta->script('xajax_auxSearchShow("5","1","","3")');
                    $respuesta->script("xajax_formConsultaIndexShow(3,2)");

                }









            }
        }



        return $respuesta;
	}

	/************************************************************
	Función que muestra las Capas que Contendrán los Graficos
	************************************************************/
	function muestraFormGrafico(){
		$respuesta = new xajaxResponse();

	    $idArea=$_SESSION["idarea"];

	    $area_description="";
		switch($idArea){
		    case 1:
		        $area_description="Aeronom&iacute;a";
		    break;
		    case 2:
		        $area_description="Astronom&iacute;a";
		    break;
		    case 3:
		        $area_description="Geod&eacute;sia";
		    break;
		    case 4:
		        $area_description="Geomagnetismo";
		    break;
		    case 5:
		        $area_description="Sismolog&iacute;a";
		    break;
		    case 6:
		        $area_description="Variabilidad y Cambia Clim&aacute;tico";
		    break;
		    case 7:
		        $area_description="Vulcanolog&iacute;a";
		    break;

		}

		$formGrafico="<div><h1>Estad&iacute;sticas del &Aacute;rea de ".$area_description.":</h1></div>
						<table class='tablecontent'>
							<tr><td aling=top><div id='maestro_chart'></div></td><td><div id='detalle_chart' class='grafico'></div></td></tr>
				   			<tr><td><p>Click en las barras para ver el detalle.</p></td></tr>
						</table>";
		$cadena="xajax_graficosEstadisticos()";
		$respuesta->script($cadena);
		$respuesta->Assign("estadisticas","style.display","block");
		$respuesta->Assign("estadisticas","innerHTML",$formGrafico);
		$respuesta->Assign("detalle_chart","style.display","none");
		$respuesta->Assign("detalle_chart","style.display","none");
		return $respuesta;
	}

	/*************************************************************
	Función que Grafica la cantidad del Personal Activo y retirado
	**************************************************************/
	function graficosEstadisticos(){
		$respuesta = new xajaxResponse();

		// Connect to the DB
		$link = connectToDB();
		$strXML = "";
		$strXML = "<chart caption='Contribuciones Científicas' subCaption='por Cantidad'  xAxisName='Tipo de Documento' yAxisName='Cantidad'  showBorder='0' formatNumberScale='0' numberSuffix=' ' bgColor='#D5E1F0,FFFFFF' bgAlpha='100,100' bgRatio='0,100' bgAngle='0' >";
		$strQuery = "select * from category";
		$result = mysql_query($strQuery) or die(mysql_error());
		$idArea=$_SESSION["idarea"];
		$idfrom=$_SESSION["idfrom"];
		//Iterate through each factory
		if ($result) {
			while($ors = mysql_fetch_array($result)) {

				$strQuery = "SELECT c.idcategory,Count(c.idcategory) as cantidad, c.category_description, s.idsubcategory, s.subcategory_description FROM data d, category c, subcategory s WHERE c.idcategory=s.idcategory and d.idsubcategory=s.idsubcategory and s.idcategory=".$ors['idcategory']." and ExtractValue(data_content,'publicaciones/areaPRI')=$idArea ORDER BY ExtractValue(data_content,'publicaciones/date_pub')";
				$result2 = mysql_query($strQuery) or die(mysql_error());
				$ors2 = mysql_fetch_array($result2);
				$idcategory=$ors['idcategory'];
				$linkDetalle = urlencode("\"javascript:ejecutarXajax($idfrom,$idArea);\"");

			    switch($ors['idcategory']){
			    	case 1:
						$tabla="Pub";
					break;
			    	case 2:
						$tabla="Pon";
					break;
			    	case 3:
						$tabla="Inf. In";
					break;
			    	case 4:
						$tabla="Asuntos Académicos";
					break;
			    }

				if($ors['idcategory']==2){
					if($ors2['cantidad']!=0){
						$strXML .= "<set label='$tabla' value='" . $ors2['cantidad'] . "' />";
					}
				}
				else{
					if($ors2['cantidad']!=0){
						$strXML .= "<set label='$tabla' value='" . $ors2['cantidad'] . "' link = ".$linkDetalle." />";
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




	/************************************************************
	Función que muestra las Capas que Contendrán los Graficos
	************************************************************/
	function muestraFormGraficoAutor($idautor=""){
		$respuesta = new xajaxResponse();


		$htmlAutor="";
		if($idautor==523){
			$htmlAutor=" ( Woodman, R.)";
		}

		if($idautor==571){
			$htmlAutor=" ( Chau, J.)";
		}


		if($idautor==590){
			$htmlAutor=" ( Tavera, H.)";
		}



		$formGrafico="<div><h1>Estad&iacute;sticas de Autores $htmlAutor</h1></div>
					<table class='tablecontent'><tr><td aling=top>
							<div id='maestro_chart'></div>
							   </td>
							   <td>
							<div id='detalle_chart' class='grafico'></div>
							   </td></tr>
								<tr><td><p>Click en las barras para ver el detalle.</p></td>
								</tr>
					</table>
					";

		$cadena="xajax_graficosEstadisticosAutor()";
		$respuesta->script($cadena);
		$respuesta->Assign("estadisticas","style.display","block");
		$respuesta->Assign("estadisticas","innerHTML",$formGrafico);
		$respuesta->Assign("detalle_chart","style.display","none");
		$respuesta->Assign("detalle_chart","style.display","none");
		return $respuesta;
	}

	/*************************************************************
	Función que Grafica la cantidad del Personal Activo y retirado
	**************************************************************/
	function graficosEstadisticosAutor(){
		$respuesta = new xajaxResponse();
		$link = connectToDB();
		$strXML = "";
		$strXML = "<chart caption='Contribuciones Científicas' subCaption='por Cantidad'  xAxisName='Tipo de Documento' yAxisName='Cantidad'  showBorder='0' formatNumberScale='0' numberSuffix=' ' bgColor='#D5E1F0,FFFFFF' bgAlpha='100,100' bgRatio='0,100' bgAngle='0' >";
		$strQuery = "select * from category";
		$result = mysql_query($strQuery) or die(mysql_error());
		$idAutor="'".$_SESSION["idautor"]."'";
		$idfrom=$_SESSION["idfrom"];
		//Iterate through each factory
		if ($result) {
			while($ors = mysql_fetch_array($result)) {
				$idcategory=$ors['idcategory'];
				$strQuery2 = "
				SELECT c.idcategory,Count(c.idcategory) as cantidad, c.category_description FROM data d, category c, subcategory s WHERE c.idcategory=s.idcategory and d.idsubcategory=s.idsubcategory and s.idcategory=$idcategory and (
				ExtractValue(data_content,'publicaciones/authorPRI/idauthor0')= $idAutor OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor0') =$idAutor OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor1') =$idAutor OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor2') =$idAutor OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor3') =$idAutor OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor4') =$idAutor OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor5') =$idAutor OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor6') =$idAutor OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor7') =$idAutor OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor8') =$idAutor OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor9') =$idAutor OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor10')=$idAutor OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor11')=$idAutor OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor12')=$idAutor OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor13')=$idAutor OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor14')=$idAutor OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor15')=$idAutor OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor16')=$idAutor OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor17')=$idAutor OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor18')=$idAutor OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor19')=$idAutor OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor20')=$idAutor OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor21')=$idAutor OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor22')=$idAutor OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor23')=$idAutor OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor24')=$idAutor OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor25')=$idAutor
				)  ORDER BY ExtractValue(data_content,'publicaciones/date_pub')
				";
				$result2 = mysql_query($strQuery2) or die(mysql_error());
				$ors2 = mysql_fetch_array($result2);
				$linkDetalle = urlencode("\"javascript:ejecutarXajax($idfrom,0,$idAutor);\"");

			    switch($ors['idcategory']){
			    	case 1:
						$tabla="Pub";
					break;
			    	case 2:
						$tabla="Pon";
					break;
			    	case 3:
						$tabla="Inf. In";
					break;
			    	case 4:
						$tabla="Asuntos Académicos";
					break;
			    }

				if($ors['idcategory']==2){
					if($ors2['cantidad']!=0){
						$strXML .= "<set label='" . $tabla . "' value='" . $ors2['cantidad'] . "' />";
					}
				}
				else{
					if($ors2['cantidad']!=0){
						$strXML .= "<set label='" . $tabla . "' value='" . $ors2['cantidad'] . "' link=$linkDetalle />";
					}
				}
				//free the resultset
				mysql_free_result($result2);
			}
		}
		mysql_close($link);

		//Cerramos la etiqueta "chart".
		$strXML .= "</chart>";
		$grafico=renderChartHTML("graficos/swf_charts/Column3D.swf", "",$strXML, "maestro", 300, 300, false);
		$respuesta->Assign("maestro_chart","innerHTML",$grafico);
		return $respuesta;

	}



?>