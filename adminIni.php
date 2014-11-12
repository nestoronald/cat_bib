<?php


	function iniArchivoShow(){

                $html='<div id="carga_archivo">files</div>';

                if(isset($_SESSION["edit"])){
                    $sesion_iddata=$_SESSION["edit"]["idbook"];
                    $html.="<div class='blue' id='linkUpload' name='linkUpload'> &nbsp; &nbsp; &nbsp; ".downloadLink($sesion_iddata,"admin","form")."</div>";

                    $link=downloadLink($sesion_iddata,"admin","form");

                }
                else{
                    $link="";
                    //$html.= "<br /><br /><a id='linkSubir' name='linkSubir' href='#upload' onclick='xajax_verFile()'><b>Subir Archivo</b></a> ";
                }
		return array($html,$link);
	}


	function iniAuthorPriShow($typeAuthor){
	    $titulo="AUTOR PRINCIPAL";

		$typeAuthorHTML = $typeAuthor=="AuthorPer"? "<div id='rq_authorPRI'></div>":"<div id='rq_authorPRI_02'></div>";
		$typeAuthorPag = $typeAuthor=="AuthorPer"? "paginatorPRI":"paginatorPRI_02";
		if ($typeAuthor=="AuthorPer") {
			$typeAuthorHTML= "<div id='rq_authorPRI'></div>";
			$typeAuthorPag= "paginatorPRI";
			$typeAuthorPRI_session= "sesion_authorPRI";
			$typeAuthorSEC_session= "sesion_authorSEC";
		}
		else {
			$typeAuthorHTML = "<div id='rq_authorPRI_02'></div>";
			$typeAuthorPag= "paginatorPRI_02";
			$typeAuthorPRI_session= "sesion_authorPRI_02";
			$typeAuthorSEC_session= "sesion_authorSEC_02";
		}
		$html='
		<table width="620px;"><tr><td width="420px;" style="vertical-align: top;">
		<div>
			<p class="txt-azul">B&uacute;squeda de autor</p>

            <div id="div_autor">
	        <!--form name="autorPRI" id="autorPRI" onsubmit="xajax_auxAuthorPriShow(5,1,xajax.getFormValues(autorPRI)); return false;" -->
	            <div class="campo-buscador"><span style="font-size:12px;">Apellido:</span></div>
                  <div class="contenedor-caja-buscador-1">
                   <input type="text" value="" id="sAuthor" name="sAuthor" class="caja-buscador-2" />
                   <input type="button" value="Buscar" class="ui-state-default ui-corner-all" id="boton_buscar" onclick="xajax_auxAuthorPriShow(5,1,xajax.getFormValues(\'frmBiblio\'),\'\',\'\',\''.$typeAuthor.'\'); return false;" />
                </div>
	        <!--/form-->
		</div>
		'.$typeAuthorHTML.'
		<div class="paginacion">
			<div id="'.$typeAuthorPag.'" class="wp-pagenavi"></div>
		</div>
		</div>
		</td>
		<td style="vertical-align: top;">
		<div>
			<p class="txt-azul">Autor principal</p>
			<div id="'.$typeAuthorPRI_session.'"></div>
		</div>
		<div class="linea-separacion"></div>
		<div>
			<p class="txt-azul">Autor secundario</p>
			<div id="'.$typeAuthorSEC_session.'"></div>
		</div>
		</td></tr>
		</table>';

		return $html;
	}

function iniAuthorSecShow(){
    $titulo="AUTOR SECUNDARIO";
	$respuesta = new xajaxResponse();

$html='<br />
<fieldset>
<p><table class="tablecontent"><tr><td>
<!--<div id="sesion_authorSEC"></div>-->
<td>
<div id="rq_authorSEC"></div>
            <div class="paginacion">
                <div id="paginatorSEC" class="wp-pagenavi"></div>
            </div>
</td></tr></table></p>

            <br />
        <form name="autorSEC" id="autorSEC" onsubmit="xajax_auxAuthorSecShow(5,1,xajax.getFormValues(\'autorSEC\')); return false;">
            <p><label class="left">Añadir autor:</label><input type="text" name="sAuthor" id="sAuthor" class="field">
            <input type="submit" value="Buscar" class="ui-state-default ui-corner-all">

            <input type="radio" checked="checked" name="search_by" id="search_by" value="1"><b>Apellidos </b>&nbsp;
            <input type="radio" name="search_by" id="search_by" value="2"><b>Nombre</b><br>

        </p>

        </form>
</fieldset>
';

    //$cadena="xajax_searchAuthorSesionSecShow()";
    //$respuesta->script($cadena);

    $respuesta->Assign("search_authorSEC","innerHTML",$html);
    $respuesta->Assign("titautorSEC","innerHTML","<a href=#1 onclick=\"xajax_fadeappear('search_authorSEC','titautorSEC','$titulo');return false;\">$titulo</a>");

return $respuesta;
}




function iniTitulo($divTitulo){

    $subcategory=$_SESSION["subcategory"];
    if($subcategory=="ponencia"){
        $titulo="T&Iacute;TULO / TIPO";
    }
    else{
        $titulo="T&iacute;tulo";
    }

	$respuesta = new xajaxResponse();

        if(isset($_SESSION["edit"])){
            $recuperar=$_SESSION["edit"];
        }
        elseif(isset($_SESSION["tmp"])){
            $recuperar=$_SESSION["tmp"];
        }

        if(isset($recuperar["titulo"])){
            $tit=$recuperar["titulo"];
        }
        else{
            $tit="";
        }

        if(isset($recuperar["resumen"])){
                $abstract=$recuperar["resumen"];
        }
        else{
                $abstract="";
        }

		$html="<form name='tit_res' id='tit_res' >
				<div class='campo-buscador'>Título:&nbsp;</div>
				<div class='contenedor-caja-buscador-1'>
					<input type='text' class='caja-buscador-1' size='30' name='title' id='title' value='$tit' onchange='xajax_registerTitulo(this.value)'>
				</div>
				<div style='clear:both'></div>";

	    if(isset($_SESSION["subcategory"])){
	    	$subcategory=$_SESSION["subcategory"];
	    }
	    else{
	    	$subcategory="";
	    }

            switch($subcategory){
                case "articulos_indexados":
                case "tesis":
                case "otras_publicaciones":
                $html.="<div class='campo-buscador'>Resumen:&nbsp;</div>
				<div class='contenedor-caja-buscador-1'>
					<textarea class='caja-buscador-1' size='30' name='abstrac' id='abstrac' onchange='xajax_registerResumen(this.value)'>
					$abstract
					</textarea>
				</div>
				<div style='clear:both'></div>
		    </form>
		    <div id='titres_mensaje'></di>";

                    break;
            }



        $respuesta->Assign("titulo","innerHTML",$html);

	$respuesta->Assign($divTitulo,"innerHTML","<a href=#1 onclick=\"xajax_displaydiv('titulo','$divTitulo'); return false;\">$titulo</a>");
	return $respuesta;
}


	function iniTitulo_Tipo_Presentado($divTitulo){
		$titulo="T&iacute;tulo / Tipo";
		$respuesta = new xajaxResponse();

		if(isset($_SESSION["edit"])){
		    $recuperar=$_SESSION["edit"];
		}
		elseif(isset($_SESSION["tmp"])){
		    $recuperar=$_SESSION["tmp"];
		 }

        if(isset($recuperar["titulo"])){
            $tit=$recuperar["titulo"];
        }
        else{
            $tit="";
        }

        //Presentado por
        if(isset($recuperar["prePorNombre"])){
            $prePorNombre=$recuperar["prePorNombre"];
        }
        else{
            $prePorNombre="";
        }

        if(isset($recuperar["prePorApellido"])){
            $prePorApellido=$recuperar["prePorApellido"];
        }
        else{
            $prePorApellido="";
        }

        if(isset($recuperar["idtipoPonencia"])){
            $tipoPonencia_id=$recuperar["idtipoPonencia"];
        }
        else{
            $tipoPonencia_id=0;
        }

        if(isset($recuperar["tipoPonencia_description"])){
            $tipoPonencia_description=$recuperar["tipoPonencia_description"];
        }
        else{
            $tipoPonencia_description="";
        }
		$tipoPonencia="";
		$tipoPonencia=comboTipoPonencia($tipoPonencia_id);


		$html="<form name='tit_tipo_prepor' id='tit_tipo_prepor' onSubmit='xajax_registerTitTipo(xajax.getFormValues(\"tit_tipo_prepor\")); return false;'>
       	<div style='clear:both'></div>

       	<div class='campo-buscador' id='tit_tipoPonencia'>Tipo de ponencia</div>
       	<div class='contenedor-combo-buscador-1' id='tipoPonencia'>$tipoPonencia</div>
       	<input type='hidden' value='tipoPonencia_description' id='tipoPonencia_txt' name='tipoPonencia_txt' class='field'>
		<div style='clear:both'></div>
		<div class='campo-buscador'>Título:&nbsp;</div>
       	<div class='contenedor-caja-buscador-1'>
       	<input type='text' onchange='xajax_registerTitulo(this.value); return false;' value='$tit' id='title' name='title' class='caja-buscador-1' /></div>
		<div style='clear:both'></div>

       	<div class='txt-azul'>Presentado Por:</div>
       	<div class='campo-buscador'>Nombre:</div>
       	<div class='contenedor-caja-buscador-1'>
       	<input type='text' maxlength='1' onchange='xajax_registerPrePorNombre(this.value); return false;' value='$prePorNombre' id='prePorNombre' name='prePorNombre' class='caja-buscador-1'>
       	</div>
		<div style='clear:both'></div>
       	<div class='campo-buscador'>Apellido:</div>
       	<div class='contenedor-caja-buscador-1'>
       	<input type='text' onchange='xajax_registerPrePorApellido(this.value); return false;' value='$prePorApellido' id='prePorApellido' name='prePorApellido' class='caja-buscador-1'>
		</div>
		<div style='clear:both'></div>
       	</form>";

	    //$respuesta->script("xajax_comboTipoPonencia($tipoPonencia_id)");
	    $respuesta->Assign("titulo_tipo_prepor","innerHTML",$html);
		$respuesta->Assign($divTitulo,"innerHTML","<a href=#1 onclick=\"xajax_displaydiv('titulo_tipo_prepor','$divTitulo'); return false;\">$titulo</a>");
		return $respuesta;
	}

	function iniTitulo_Resumen(){

		//$respuesta = new xajaxResponse();

		//$objResponse->alert(print_r($_SESSION["tmp_edit"]["titulo"], true));

		if(isset($_SESSION["edit"])){
		    $recuperar=$_SESSION["edit"];
		}
		elseif(isset($_SESSION["tmp"])){
		    $recuperar=$_SESSION["tmp"];
		 }

		if(isset($recuperar["titulo"])){
			$tit=$recuperar["titulo"];
		}
                else{
			$tit="";
		}


		if(isset($recuperar["resumen"])){
			$abstract=$recuperar["resumen"];
		}
		else{
			$abstract="";
		}

		if(isset($recuperar["enlace"])){
			$link=$recuperar["enlace"];
	      //$link=substr($recuperar["enlace"], 7);
		}
		else{
			$link="http://";
		}


		$html="<form name='tit_res' id='tit_res' >
				<div class='campo-buscador'>Título:&nbsp;</div>
				<div class='contenedor-caja-buscador-1'>
					<input type='text' class='caja-buscador-1' size='30' name='title' id='title' value=\"$tit\" onchange='xajax_registerTitulo(this.value)'>
				</div>
				<div style='clear:both'></div>
				<div class='campo-buscador'>Resumen:&nbsp;</div>
				<div class='contenedor-caja-buscador-1'>
					<textarea class='caja-buscador-1' size='30' name='abstrac' id='abstrac' onchange='xajax_registerResumen(this.value)'>
					$abstract
					</textarea>
				</div>
				<div style='clear:both'></div>
				<div class='campo-buscador'>Enlace:&nbsp;</div>
				<div class='contenedor-caja-buscador-1'>
					<input type='text' class='caja-buscador-1' size='30' name='link' id='link' value=\"$link\" onchange='xajax_registerLink(this.value)'>
				</div>
				<div style='clear:both'></div>

		    </form>
		    <div id='titres_mensaje'></di>
		";

	        //$respuesta->alert(print_r($_SESSION, true));


		return $html;
	}

	function iniTitulo_Presentado($divTitulo){
	    $titulo="T&iacute;tulo / Presentado por";
		$respuesta = new xajaxResponse();

		if(isset($_SESSION["edit"])){
		    $recuperar=$_SESSION["edit"];
		}
		elseif(isset($_SESSION["tmp"])){
		    $recuperar=$_SESSION["tmp"];
		}

        if(isset($recuperar["titulo"])){
            $tit=$recuperar["titulo"];
        }
        else{
            $tit="";
        }

        if(isset($recuperar["prePorNombre"])){
            $prePorNombre=$recuperar["prePorNombre"];
        }
        else{
            $prePorNombre="";
        }

        if(isset($recuperar["prePorApellido"])){
            $prePorApellido=$recuperar["prePorApellido"];
        }
        else{
            $prePorApellido="";
        }


		$html="<form name='form_tit_prepor' id='form_tit_prepor' onSubmit='xajax_registerTitPrePor(xajax.getFormValues(\"form_tit_prepor\")); return false;'>
			  <div class='campo-buscador'><div>Título :</div></div>
			  <div class='contenedor-caja-buscador-1'>
			  	<input type='text' size='30' onchange='xajax_registerTitulo(this.value); return false;' value='$tit' id='title' name='title' class='caja-buscador-1'>
				</div>
				<div style='clear: both;'></div>";

		$html.="<div class='txt-azul'>Presentado Por:</div>";
		$html.="<div class='campo-buscador'>Nombre:</div>";
		$html.="<div class='contenedor-caja-buscador-1'><input type='text' maxlength='1' onchange='xajax_registerPrePorNombre(this.value); return false;' id='prePorNombre' name='prePorNombre' value='$prePorNombre' class='caja-buscador-2' /> <small>solo la primera letra</small></div>";
		$html.="<div style='clear: both;'></div>";
		$html.="<div class='campo-buscador'>Apellido:</div>";
		$html.="<div class='contenedor-caja-buscador-1'><input type='text' onchange='xajax_registerPrePorApellido(this.value); return false;' id='prePorApellido' name='prePorApellido' value='$prePorApellido' class='caja-buscador-2' /></div>";
		$html.="<div style='clear: both;'></div>";
		$html.="</form>";

	    $respuesta->Assign("titulo_presentado","innerHTML",$html);

		$respuesta->Assign($divTitulo,"innerHTML","<a href=#1 onclick=\"xajax_displaydiv('titulo_presentado','$divTitulo'); return false;\">$titulo</a>");
		return $respuesta;
	}



	function iniAreaResult($type,$idarea){
	    $resultSql= searchThemeSQL($type,$idarea);
	return $resultSql;
	}

	function iniOtrasAreasResult($idarea){
	    $resultSql= searchOtherAreaSQL($idarea);
	return $resultSql;
	}

	function iniAreasAdministrativasResult($idarea){
	    $resultSql= searchAreasAdministrativasSQL($idarea);
	return $resultSql;
	}

	function iniAreasAdministrativasShow($idarea){

		$respuesta = new xajaxResponse();

		if(isset($_SESSION["edit"])){
		    $recuperar=$_SESSION["edit"];
		}
		elseif(isset($_SESSION["tmp"])){
		    $recuperar=$_SESSION["tmp"];
		 }

		        $subcategory=$_SESSION["subcategory"];

		$divTitulo="";
		$titulo="";
		$divContenido="";

		switch ($subcategory) {
		    case "charlas_internas":
		    $divTitulo="titAreasAdministrativas";
		    $divContenido="cont_areasAdministrativas";
		    $titulo="&Aacute;reas de Administrativas";
		    break;
		}

	    $result=iniAreasAdministrativasResult($idarea);

	    $id=$result["idareaAdministrativa"];
	    $desc=$result["areaAdministrativa_description"];
	    $html="";
	    if($result["Error"]==0){

	        if($result["Count"]>0){
	            for($i=0;$i<count($id);$i++){
	                $key = $id[$i];
	                if(isset($recuperar["areasAdministrativas"][$key])){
	                    $html.="<p><input type=checkbox checked onclick=\"xajax_registerAreaAdministrativa('".$id[$i]."')\"  value=".$id[$i]."  />&nbsp;".$desc[$i]."</p>";
	                }
	                else{
	                    $html.="<p><input type=checkbox  onclick=\"xajax_registerAreaAdministrativa('".$id[$i]."')\"  value=".$id[$i]."  />&nbsp;".$desc[$i]."</p>";
	                }
	            }
	        }
	    }
	    else{
	        $html="Error SQL";
	    }
	    $respuesta->Assign("$divContenido","innerHTML",$html);
	    $respuesta->Assign("$divTitulo","innerHTML",$titulo);
	    return $respuesta;
	}


	function iniAreas($divTitulo){
		$objResponse = new xajaxResponse();
		$link="<a onclick=\"xajax_displaydiv('areas','$divTitulo'); return false;\" href='#'>&Aacute;rea</a>";
		$objResponse->Assign($divTitulo,"innerHTML",$link);

		if($_SESSION["idarea"]==1){
		    $objResponse->script("xajax_subArea()");
		}
		else{
		    $cadena="xajax_iniOtrasAreasShow('".$_SESSION["idarea"]."')";
		    $objResponse->script($cadena);
		}

	    $cadena="xajax_iniAreasAdministrativasShow('".$_SESSION["idarea"]."')";
	    $objResponse->script($cadena);

		$subcategory=$_SESSION["subcategory"];

		switch($subcategory){
		    case "charlas_internas":
		        $cadena="xajax_iniInstitucionExterna('tit_inst_ext','cont_inst_ext')";
		        $objResponse->script($cadena);
		    break;

		}
	    return $objResponse;
	}

	function iniInstitucionExterna($tit_div,$cont_div){

		$respuesta = new xajaxResponse();
		$subcategory=$_SESSION["subcategory"];

		switch($subcategory){
		    case "charlas_internas":
		        $fecha_txt="Fecha de Presentaci&oacute;n :";
		        break;
		}

		if(isset($_SESSION["edit"])){
			$recuperar=$_SESSION["edit"];
		}
		elseif(isset($_SESSION["tmp"])){
			$recuperar=$_SESSION["tmp"];
		}

		if(isset($recuperar["inst_ext"])){
			$inst_ext=$recuperar["inst_ext"];
		}
		else{
			$inst_ext="";
		}

		$html ="<form name='form_inst_ext' id='form_inst_ext' >";
		$html.="<input type='text' class='field' onchange='xajax_registerInst_Ext(this.value); return false;' value='$inst_ext' name='inst_ext' id='inst_ext'>";
		//$html.="<input class='button' type='submit' value='Guardar'  />";
		$html.="</form>";
		$respuesta->Assign("$cont_div","innerHTML",$html);
		$respuesta->Assign("$tit_div","innerHTML","Instituci&oacute;n Externa");

	    return $respuesta;
	}


/* --n function iniOtrosTemasShow(){
	$respuesta = new xajaxResponse();
    $titulo="Asociar a otros temas (debe de haber seleccionado por lo menos un área)";
	$respuesta->Assign("titOtrosTemas","innerHTML",$titulo);
	return $respuesta;
}*/
	function iniThemes_Book(){
		$objResponse = new xajaxResponse();
		$result = SelectThemeBoook();
		$html = "error al cargar themas de base de datos";
		if ($result["Error"]==0) {
			$html = "";
			for ($i=0; $i < $result["Count"]; $i++) {
				$html .= '<label class="checkbox">
							<input type="checkbox" value="'.$result["idtheme"][$i].'">'.$result["destheme"][$i].'
						</label>';
			}

		}

		$objResponse->assign("conte_temas","innerHTML",$html);
		return $objResponse;
	}

?>