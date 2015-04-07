<?php


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