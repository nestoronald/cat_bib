<?php

	require ('../class/ClassCombo.php');
	require ('../class/ClassForm.php');
	require ('../class/ClassPaginado.php');
    require ('../class/ClassPaginator.php');
	require ('../class/dbconnect.php');
	require ('../class/xajax_core/xajax.inc.php');
    require ('../class/smarty/Smarty.class.php');
	// require ('../class/RegisterInput.php');
	$xajax=new xajax();
        //$xajax->configure("debug", true);
	$xajax->configure('javascript URI', 'js/');
 	date_default_timezone_set('America/Lima');

	require("adminIni.php");
	require("adminSearch.php");
	// require("adminRegister.php");
	require("indexSearch.php");

	//Ejecutamos el modelo
    require("adminModel.php");
	require("Security.php");

	if(isset($_GET["idarea"])){
		$idarea=$_GET["idarea"];
	}
	else{
		$idarea=0;
	}

	session_name("bib");
	session_start();

	/************************************************************
	Función que Verfica el Login
	************************************************************/
	function inicio(){
		$respuesta = new xajaxResponse();
                    /*
	            $iduser=$_SESSION["idusers"];
                    $respuesta->alert($iduser);
                    */
        $_SESSION["origin"]="back";
        $sessionidarea="";
		if(isset($_SESSION["admin"])){
		    if($_SESSION["admin"]!=""){
		    	if($_SESSION["idarea"]!=8){
                    $sessionidarea=$_SESSION["idarea"];
		            $cadena="xajax_menuShow($sessionidarea);";
                    $respuesta->script($cadena);
                }
                /*muestra el enlace del formulario modal*/
                $enlace='<a id="new-clave" href="#" class="blanco" >Cambiar Clave<img src="img/iconos/candado_llave_24.png"></img></a>';
                $respuesta->assign("menu_d", "innerHTML","$enlace");

		    	$respuesta->assign("loginform", "style.display","none");
		    	$respuesta->assign("subcontent1", "style.display","block");
		    }
			else{
		    	$cadena="xajax_formLoginShow();";
		    	$respuesta->script($cadena);
                $enlace='<a id="recuparar-clave" href="#" class="blanco" >Recuperar Clave<img src="img/iconos/candado_llave_24.png"></img></a>';
                $respuesta->assign("menu_d", "innerHTML","$enlace");
		    	$respuesta->assign("subcontent1", "style.display","none");
		    	$respuesta->assign("loginform", "style.display","block");
                $html='<table><tr><td style="text-align: center;">';
                $html.='<img src="img/login.jpg" />';
                $html.='</td></tr></table>';

                $respuesta->assign("imghome", "innerHTML", $html);

			}
		}
		else{
			$cadena="xajax_formLoginShow();";
            $enlace='<a id="recuparar-clave" href="#" class="blanco" >Recuperar Clave<img src="img/iconos/candado_llave_24.png"></img></a>';
            $respuesta->assign("menu_d", "innerHTML","$enlace");
            $respuesta->script($cadena);
            $respuesta->assign("subcontent1", "style.display","none");
            $respuesta->assign("loginform", "style.display","block");
		}
		//tab title tooltip
            $respuesta->script("$('[rel=propover]').popover({
            							animation : 0.05 ,
            							placement : 'top',
            							trigger: 'hover',
            							title:'Contáctenos',
            							html:'true',
            							content :\"Cualquier consulta escribanos a <span class='emailigp'>web@igp.gob.pe</span>\"
            							});
            					");

		return $respuesta;
	}
    function aboutAdmin(){
        $respuesta = new xajaxResponse();
        $smarty = new Smarty;
        detailsProfile();
        $html= $smarty->fetch('tpl/about.tpl');
        $respuesta->assign("imghome","style.display","none");
        $respuesta->assign("author_section","style.display","none");
        $respuesta->assign("paginatorAuthor","style.display","none");
        $respuesta->assign("ListReserva","style.display","none");
        $respuesta->assign("DivReserva","style.display","none");
        $respuesta->assign("option_category","style.display","none");
        $respuesta->assign("formulario","style.display","none");
        $respuesta->assign("searchCat","style.display","none");
        $respuesta->assign("consultas","style.display","none");
        $respuesta->assign("resultSearch1","style.display","none");
        $respuesta->assign("conte_details","style.display","none");
        $respuesta->assign("paginator","style.display","none");

        $respuesta->assign("about_admin","style.display","block");
        $respuesta->assign("about_admin","innerHTML",$html);
        $respuesta->script('
            $(".nav-sidebar a").click(function(e){
              $(".nav-sidebar li").removeClass("active");
              $(this).parent("li").addClass("active");
              conte = $(this).attr("href")
              $(".reser").hide();
              $(conte).show();
            })
             //$(".nav-list li").eq(1).find("a").trigger("click");
            ');
        return $respuesta;
    }
    function detailsProfile(){
        $user = $_SESSION["users_sede"];
        $data_array="";
        if (QuerySede($user)!=-100) {
            $result=QuerySede($user);
            $data_array = xmlToArray($result["data"]);
            $_SESSION["profile"]=$data_array;
        }
        return $data_array;
    }
    function editPassAdmin($form=""){
        $objResponse = new xajaxResponse();
        $msj="";
        if (isset($_SESSION["idusers"])) {
            $pass = $form["pass"];
            $newpass = $form["newpass"];
            $renewpass = $form["renewpass"];
            if (empty($pass) || empty($newpass) || empty($renewpass)) {
                $msj = "Ningun campo puede estar vacio";
            }
            else{
                $pass = md5($pass);
                $newpass = md5($newpass);
                $renewpass = md5($renewpass);
                if ($newpass!=$renewpass) {
                    $msj = "Las contraseñas no coinciden";
                    $objResponse->script("$('#renewpass').focus(); return false;");
                }
                elseif ($pass==$newpass) {
                    $msj = "La contraseña nueva debe ser diferente a la actual";
                }
                else{
                    if (newPasswordAdmin($form)) {
                        $html = "<div class='exito'>La Contraseña ha sido actualizado correctamente</div>";
                        $objResponse->assign("modalbody","innerHTML",$html);
                        $footer_html = '<button class="btn exit"  data-dismiss="modal" aria-hidden="true">Cerrar</button>';
                        $objResponse->assign("modalfooter","innerHTML",$footer_html);
                        $objResponse->script("$('.exit').click(function(e){console.log('update password'); xajax_aboutAdmin();})");
                    }
                    else{
                        $msj="Verifique que la contraseña actual sea la correcta";
                    }
                }
            }
        }
        $objResponse->assign("msj-pass","innerHTML",$msj);
        return $objResponse;
    }
    function editDataSede($form=""){
        $objResponse = new xajaxResponse();
        if (isset($_SESSION["idusers"])) {
            if (updateDataSede($form)) {
                $html = "<div class='exito'><i class='icon-ok'></i>Tus datos han sido actualizados correctamente</div>";
                $objResponse->assign("modalbody_pro","innerHTML",$html);
                $footer_html = '<button class="btn exit"  data-dismiss="modal" aria-hidden="true">Cerrar</button>';
                $objResponse->assign("modalfooter_pro","innerHTML",$footer_html);
                $objResponse->script("$('.exit').click(function(e){console.log('update datasede');
                                xajax_aboutAdmin();
                                $('.nav-list li').eq(1).find('a').trigger('click');
                                })");
            }
            else{
                $msj="Intente mas tarde, no se pudo actualizar tus datos";
            }
            $objResponse->script("$('.exit').click(function(e){console.log('update datasede');
                                $('.nav-list li').eq(1).find('a').trigger('click');
                                })");
        }
        $objResponse->assign("msj-sede","innerHTML",$msj);
        return $objResponse;
    }
	function formLoginShow(){
	    $respuesta = new xajaxResponse();

            $form= '
                <form class="form-inline" onsubmit="xajax_verificaUsuarioShow(xajax.getFormValues(formLogin)); return false;" id="formLogin" method="post">
                	<div class="input-prepend">
  					<span class="add-on label-id" ></span>
  					<input class="input-small" id="usuario" name="usuario" type="text" placeholder="Usuario">
					</div>
					<div class="input-prepend">
					<span class="add-on label-pw"></span>
  					<input class="input-small" id="clave" name="clave" type="password" placeholder="Contraseña">
					</div>
					<input type="submit" name="Login" class="btn" value="Ingresar">
					<div id="error"></div>
                </form>
                ';

	    $respuesta->Assign("divformlogin","innerHTML",$form);
	    return $respuesta;
	}


	/************************************************************
	Función que Cierra la Sesión
	************************************************************/
	function cerrarSesion(){
		$respuesta = new xajaxResponse();
		$_SESSION["admin"]="";
        $_SESSION["users_sede"]="";
        $_SESSION["idusers"]="";
		$respuesta->redirect("admin.php", 0);
		$respuesta->Assign("subcontent1","style.display","none");
	    return $respuesta;
	}

	function checkDataForm($form=""){

	    $check["Error"]=0;
	    if($form["author_name"]==""){
	        $check["Msg"]="Ingrese Nombre";
	        $check["Error"]=1;
	    }

	    elseif($form["author_surname"]==""){
	        $check["Msg"]="Ingrese Apellido";
	        $check["Error"]=1;
	    }
	    return $check;
	}

	function verificaUsuarioShow($form_entrada=""){
        $respuesta = new xajaxResponse();

        //$usuario=$form_entrada["usuario"];
        //$clave=$form_entrada["clave"];

        $result = verificaUsuarioSQL($form_entrada);

        $NroRegistros=$result["Count"];
        switch($NroRegistros){
            case 0:
                $divError="error";
                $respuesta->Assign("error","style.display","block");
                $respuesta->assign("error", "innerHTML","<span class='span3 offset7 alert alert-error'><span class='add-on i-error'></span>Usuario y/o clave incorrectos</span>");
            break;
			case 1:
                $idusers=$result["idusers"];
				$users_name=$result["users_name"];
				$area_description=$result["area_description"];
				$users_type=$result["users_type"];
                $users_sede=$result["sede"];
				session_unset();
				session_destroy();
				session_start();
                $_SESSION["idusers"]=$idusers;
				$_SESSION["users_type"]=$users_type;
				$_SESSION["idfrom"]=2;
				$_SESSION["admin"]=$users_name;
                $_SESSION["users_sede"]=$users_sede;
				$_SESSION["authorPRI"] = array();
				$_SESSION["authorSEC"] = array();
				$respuesta->script("xajax_menuShow()");
                $respuesta->script("xajax_inicio();");
                $enlace='<a id="new-clave" href="#" class="blanco" >Cambiar Clave<img src="img/iconos/candado_llave_24.png"></img></a>';
                $respuesta->assign("menu_d", "innerHTML","$enlace");

				$respuesta->Assign("subcontent1","style.display","block");
				//$respuesta->Assign("loginform","style.display","none");
                $respuesta->Assign("divformlogin","innerHTML","&nbsp");
                $respuesta->Assign("permiso","style.display","none");
                $respuesta->Assign("ingreso","style.display","block");

            break;
        }

        return $respuesta;
    }

	function displaydiv($div="",$idtitle="") {
	    $objResponse = new xajaxResponse();
	    $array=array("titulo_resumen","titulo_tipo_prepor","author","author_inst","evento","lugarPais","referencia","tipoTesis_pais_universidad","area_tema","fecha_estado_permisos","archivo","region_departamento","titulo","nro_magnitud","fecha_permisos","year_quarter","areas","compendio","titulo_presentado","areasAdministrativas","institucion_externa");
		$titulos=array("titulo1","titulo2","titulo2_A","titulo3","titulo4","titulo5","titulo6","titulo7");
		foreach ($array as $elemento){
			if($elemento==$div){
				$objResponse->assign($elemento,"style.display","block");
				foreach ($titulos as $nombre_titulos){
					if($nombre_titulos==$idtitle){
						$objResponse->script("
							$('body .tab').removeClass('tabactive');
							$('body #".$idtitle."').addClass('tabactive');

							");

					}

				}

			}
			else{
				$objResponse->assign($elemento,"style.display","none");
			}

		}

		$subcategory=$_SESSION["subcategory"];
		switch($subcategory){
	    case "charlas_internas":
			if($div=="areas"){
				$objResponse->assign("areasAdministrativas","style.display","block");
				$objResponse->assign("institucion_externa","style.display","block");
			}
		break;

		}
		//    $objResponse->alert(print_r($array, true));
	    return $objResponse;
	}


	/******************************************
	Función que muestra un Menú en el Template
	*******************************************/
	function menuShow(){
		$respuesta= new xajaxResponse();

                $menu="";
        if(isset($_SESSION["admin"])){

            $menu.='<li><a id="new_register" href="#nuevo-registro" title="Nuevo Registro"> Nuevo </a></li>';
            $menu.="<li><a href='#Catalogo-busqueda' onclick='xajax_searchCategory(); return false;' > Consultas</a></li>";
            $menu.="<li><a href='#Lista-reserva' onclick='xajax_ListReserva(); return false;' >Reservas</a></li>";
            $form["demo"]="12";
            $menu.="<li><a href='#autores' onclick='xajax_auxAuthorShow(5000,1,\"$form\"); return false;' > Autores</a></li>";
            $medu_rigth.='<li class="fright"><a href="Instructivo_uso_Administrador.pdf" target="__blank"><b> ? </b> </a></li>';
            $medu_rigth.='<li class="fright"><a href="#" onclick="xajax_cerrarSesion(); return false">Cerrar sesión</a></li>';
            $medu_rigth.='<li class="fright"><a href="#perfil" onclick="xajax_aboutAdmin(); return false;">'.$_SESSION["admin"].'</a></li>';
            $respuesta->assign("divformlogin", "style.display", "none");
            $html='<table><tr><td style="text-align: center;">';
            $html.='<img src="img/biblioteca.png" />';
            $html.='</td></tr></table>';
            $respuesta->assign("imghome", "innerHTML", $html);
        }

		$respuesta->assign("menu", "innerHTML", $menu);
        $respuesta->assign("menu_rigth", "innerHTML", $medu_rigth);
		$respuesta->script("
					$('#new_register').click(function(){
						xajax_formCategoryShow(2); return false;
					})
			");
		$respuesta->script('
                    $(function(){

                        $("ul.dropdown li").hover(function(){
                            $(this).addClass("hover");
                            $("ul:first",this).css("visibility", "visible");
                        }, function(){

                            $(this).removeClass("hover");
                            $("ul:first",this).css("visibility", "hidden");
                        });

                        $("ul.dropdown li ul li:has(ul)").find("a:first").append(" &raquo; ");

                    });
                ');
		return $respuesta;
	}


	function subcategoryResult($category=0,$idarea=0){
		$resultSql= searchSubCategorySQL($category,"","",$idarea);
		return $resultSql;
	}

	/******************************************
	Función que muestra un Menú en el Template
	*******************************************/
	function menuAAShow($idarea=0){
		$respuesta= new xajaxResponse();

		$result=subcategoryResult(3,$idarea);
		$count=$result["Count"];

		$menu="<div><h3  class='txt-rojo'>Nuevo Ingreso:</h3></div>";

		for($i=0;$i<$count;$i++){
			$desc = ucfirst($result["subcategory_description"][$i]);
			$id = $result["idsubcategory"][$i];
			//$idfrom=$_SESSION["idfrom"];
            $idfrom=isset($_SESSION["idfrom"])?$_SESSION["idfrom"]:0;
			$idarea=isset($_SESSION["idarea"])?$_SESSION["idarea"]:0;
	        $menu.="<div class='submenu'>»<a href='#' class='negro' onClick='xajax_formCategoryShow(3,$id); return false'>$desc</a></div>";
		}

                $menu.="<div><h3 class='txt-rojo'>Consultas :</h3>
                        <div class='submenu'>»<a class='negro' href='#'  onClick='xajax_formConsultaShow(\"$idfrom\",\"admin\",\"$idarea\"); return false'>B&uacute;squeda</a></div>
                        <div class='submenu'>»<a id='botonshow' class='negro' href='#'  >Estad&iacute;sticas   </a></div>
                        <div class='left-box'><h3 class='txt-rojo'>Salir :</h3></div>
                        <div class='submenu'>»<a href='#' class='negro' onClick='xajax_cerrarSesion(); return false'>Cerrar sesi&oacute;n</a></div>";
		$respuesta->assign("menuLateral", "innerHTML", $menu);

		$respuesta->assign("menuLateral", "innerHTML", $menu);
		return $respuesta;
	}

	/******************************************
	Función que muestra un Menú en el Template
	*******************************************/

	function formCategoryShow($idcategory=0,$idSubcategory=0){
	    $respuesta = new xajaxResponse();
		if(isset($_SESSION["editar"])){
			if($_SESSION["editar"]==1){
				unset($_SESSION["edit"]);
				unset($_SESSION["editar"]);
			}
		}
	    $html="<form name='frm_type' id='frm_type' class='center block_1'>
	    	<label for='type_material'>Selecione un tipo de material </label>
	    	<select name='type_material' id='type_material' class='span8'>
		    	<option value='999'>Selecione tipo de material</option>
		    	<option value='libros'>Libros</option>
		    	<option value='pub_perio'>Publicaciones Periódicas</option>
		    	<option value='mapas'>Mapas</option>
		    	<option value='tesis'>Tesis</option>
		    	<option value='other'>Otros materiales</option>
	    	</select>
	    	</form>";
        $respuesta->Assign("ListReserva","style.display","none");
        $respuesta->Assign("consultas","style.display","none");
		$respuesta->Assign("paginator","style.display","none");
		$respuesta->Assign("imghome","style.display","none");
		$respuesta->Assign("formulario","style.display","none");
		$respuesta->Assign("searchCat","style.display","none");
		$respuesta->Assign("resultSearch1","style.display","none");
		$respuesta->Assign("author_section","style.display","none");
        $respuesta->Assign("paginatorAuthor","style.display","none");
        $respuesta->Assign("conte_details","style.display","none");
		$respuesta->Assign("about_admin","style.display","none");
		$respuesta->Assign("option_category","style.display","block");
		$respuesta->Assign("option_category","innerHTML",$html);


                //$respuesta->alert(print_r($_SESSION, true));
		$respuesta->script('
						$("#type_material").change(function(){
                            if ($(this).val()!="999"){
                                sel = $(this).val();
                                xajax_formPonenciasShow(0,xajax.getFormValues("frm_type"));
                            }else{
                                if (confirm("Desea realizar la operacion...!")) {
                                    xajax_formCategoryShow(1,0);
                                }
                                else{
                                    $("#type_material option[value="+sel+"]").attr("selected",true);
                                }
                            }
						})
						');

		return $respuesta;
	}



	function formPonenciasShow($iddata=0,$form_1=""){
		$objResponse = new xajaxResponse();

		//Borramos las variables de sesion
		if (isset($form_1)) {
			switch ($form_1["type_material"]) {
				case 'libros':
					$form_label= "Libro";
					break;
				case 'pub_perio':
					$form_label= "Publicacion Periódica";
					break;
				case 'mapas':
					$form_label= "Mapa";
					break;
				case 'tesis':
					$form_label= "Tesis";
					break;
				case 'other':
					$form_label= "Otros Materiales";
					break;
				default:

					break;
			}
		 }
		if(isset($_SESSION["tmp"])){
		    unset($_SESSION["tmp"]);
		    unset($_SESSION["publicaciones"]);
		}
        // $objResponse->alert(print_r($_SESSION,TRUE));
		$result_book = searchBookSQL("","","");

		if(isset($_SESSION["editar"])){

		    if($_SESSION["editar"]==1){
		        $action="UPD";
		        $tituloBoton="ACTUALIZAR Y NUEVO";
		        $tituloBoton_sec="ACTUALIZAR";
		        $tituloGeneral="Editar $form_label";

				$recuperar = $_SESSION["edit"];

				//verificar los checked
				$ISBN_ch = (isset($recuperar["ISBN"])?"checked":"");
				$ISSN_ch = (isset($recuperar["ISSN"])?"checked":"");
				$Edition_ch = (isset($recuperar["Edition"])?"checked":"");
				$Edition_A_ch = (isset($recuperar["Edition_A"])?"checked":"");
				$Resumen_ch = (isset($recuperar["Resumen"])?"checked":"");
				$Description_ch = (isset($recuperar["Description"])?"checked":"");
				$FxIng_ch = (isset($recuperar["FxIng"])?"checked":"");
				$UbicFis_ch = (isset($recuperar["UbicFis"])?"checked":"");
				$NumReg_ch = (isset($recuperar["NumReg"])?"checked":"");

				$languaje_ch = (isset($recuperar["languaje"])?"checked":"");
				$NumLC_ch = (isset($recuperar["NumLC"])?"checked":"");

				$NumDewey_ch = (isset($recuperar["NumDewey"])?"checked":"");
				$Class_IGP_ch = (isset($recuperar["Class_IGP"])?"checked":"");
				$EncMat_ch = (isset($recuperar["EncMat"])?"checked":"");
				$OtherTitles_ch = (isset($recuperar["OtherTitles"])?"checked":"");
				$Periodicidad_ch = (isset($recuperar["Periodicidad"])?"checked":"");
				$Serie_ch = (isset($recuperar["Serie"])?"checked":"");
				$NoteGeneral_ch = (isset($recuperar["NoteGeneral"])?"checked":"");
				$NoteTesis_ch = (isset($recuperar["NoteTesis"])?"checked":"");
				$NoteBiblio_ch = (isset($recuperar["NoteBiblio"])?"checked":"");
				$NoteConte_ch = (isset($recuperar["NoteConte"])?"checked":"");
				$DesPersonal_ch = (isset($recuperar["DesPersonal"])?"checked":"");
				$MatEntidad_ch = (isset($recuperar["MatEntidad"])?"checked":"");
				$Descriptor_ch = (isset($recuperar["Descriptor"])?"checked":"");
				$Descriptor_geo_ch = (isset($recuperar["Descriptor_geo"])?"checked":"");
				$CongSec_ch = (isset($recuperar["CongSec"])?"checked":"");
				$TitSec_ch = (isset($recuperar["TitSec"])?"checked":"");
				$Fuente_ch = (isset($recuperar["Fuente"])?"checked":"");
				$NumIng_ch = (isset($recuperar["NumIng"])?"checked":"");
				$UbicElect_ch = (isset($recuperar["UbicElect"])?"checked":"");
				$ModAdqui_ch = (isset($recuperar["ModAdqui"])?"checked":"");
				$Catalogador_ch = (isset($recuperar["Catalogador"])?"checked":"");

		    }
		}
		else{
			$action="INS";
            $tituloBoton="GUARDAR Y NUEVO";
            $tituloBoton_sec="GUARDAR";
            $tituloGeneral="Nuevo $form_label";
			$iddata=($result_book["Count"]+1);
		}

        $tipoDocumento="ponencias";
	    $_SESSION["tipoDocumento"]="ponencias";
	    $_SESSION["subcategory"]="ponencia";
        $_SESSION["idtypedocument"]=2;
        $_SESSION["idsubcategory"]=0;



	    $html='<h2 class="txt-azul">'.$tituloGeneral.'</h2>

		    <!-- List of register inputs -->
			    	<div class="list-campos span2">
			    		<form id="ListCampos" name="ListCampos">
						  <fieldset>
						    <legend>Lista de Campos </legend>
						    <p><small>Seleccione un campo para añadir al formulario.</small> </p>';
						    if ($form_1["type_material"]!="pub_perio") {
						    	$html .= '<label class="checkbox">
					      		<input class="ActionInput" type="checkbox" value="id_020" '.$ISBN_ch.'> ISBN
					    	</label>';
						    }
						    if ($form_1["type_material"]=="pub_perio") {
					    		$html .='<label class="checkbox checkbox1">
					      		<input class="ActionInput" type="checkbox" value="001" '.$ISSN_ch.'> ISSN
					    		</label>';
					    	}
						    $html.='
					    	<label class="checkbox">
					      		<input class="ActionInput" type="checkbox" value="002" '.$languaje_ch.'> Idiomas (R)
					    	</label>
					    	<label class="checkbox">
					      		<input class="ActionInput" type="checkbox" value="003" '.$NumLC_ch.'> Clasificación LC
					    	</label>';

						   	$html .='
						   	<label class="checkbox">
					      		<input class="ActionInput" type="checkbox" value="005" '.$Class_IGP_ch.'> Clasificación IGP
					    	</label>
					    	<label class="checkbox">
					      		<input class="ActionInput" type="checkbox" value="id_0250_A" '.$Edition_A_ch.'> Edición
					    	</label>
						   ';
						   if ($form_1["type_material"]=="pub_perio") {
					    		$html.='<label class="checkbox">
					      		<input class="ActionInput" type="checkbox" value="006" '.$EncMat_ch.'> Congresos
					    	</label>';
					    	}
					    	if ($form_1["type_material"]!="pub_perio") {
					    		$html.= '
					    		<label class="checkbox">
					      			<input class="ActionInput" type="checkbox" value="011" '.$NoteTesis_ch.'> Notas Tesis
					    		</label>
					    		<label class="checkbox">
					      			<input class="ActionInput" type="checkbox" value="012" '.$NoteBiblio_ch.'> Notas de Bibliografía
					    		</label>
					    		<label class="checkbox">
					      		<input class="ActionInput" type="checkbox" value="013" '.$NoteConte_ch.'> Notas de Contenido
					    	</label>';
							}
						   	$html .='
					    	<!--label class="checkbox">
					      		<input class="ActionInput" type="checkbox" value="id_0250" '.$Edition_ch.'> Publicación
					    	</label-->
					    	<!--label class="checkbox">
					      		<input class="ActionInput" type="checkbox" value="id_0920" '.$FxIng_ch.'> Fecha de Ingreso
					    	</label-->
					    	<!--label class="checkbox">
					      		<input class="ActionInput" type="checkbox" value="id_0924" '.$UbicFis_ch.'> Ubicación Física
					    	</label-->
					    	';
					    	if ($form_1["type_material"]!="pub_perio") {
					    		$html.='
					    		<label class="checkbox">
					      		<input class="ActionInput" type="checkbox" value="id_0520" '.$Resumen_ch.'> Resumen
					    		</label>
					    	<!--label class="checkbox">
					      		<input class="ActionInput" type="checkbox" value="014" '.$DesPersonal_ch.'> Tema como Persona
					    	</label>
					    	<label class="checkbox">
					      		<input class="ActionInput" type="checkbox" value="017" '.$Descriptor_geo_ch.'> Tema Geográfico
					    	</label>
					    	<label class="checkbox">
					      		<input class="ActionInput" type="checkbox" value="015" '.$MatEntidad_ch.'> Tema como entidad
					    	</label-->';
					    	}

					    	$html .='
					    	<!--label class="checkbox">
					      		<input class="ActionInput" type="checkbox" value="id_0925" '.$NumReg_ch.'> Número de Registro
					    	</label-->

					    	<!--label class="checkbox">
					      		<input class="ActionInput" type="checkbox" value="id_0300" '.$Description_ch.'> Descripción
					    	</label-->

					    	<!--label class="checkbox checkbox1">
					      		<input class="ActionInput" type="checkbox" value="004" '.$NumDewey_ch.'> Número de Clasificación Dewey
					    	</label-->
					    	<!--label class="checkbox checkbox1">
					      		<input class="ActionInput" type="checkbox" value="007" '.$OtherTitles_ch.'> Otros Títulos
					    	</label-->
					    	<!--label class="checkbox">
					      		<input class="ActionInput" type="checkbox" value="008" '.$Periodicidad_ch.'> Periodicidad
					    	</label-->
                            <label class="checkbox">
    					      	<input class="ActionInput" type="checkbox" value="009" '.$Serie_ch.'> Serie
    					    </label>
					    	<label class="checkbox checkbox1">
					      		<input class="ActionInput" type="checkbox" value="010" '.$NoteGeneral_ch.'> Notas Generales
					    	</label>
					    	<!--label class="checkbox">
					      		<input class="ActionInput" type="checkbox" value="011" '.$NoteTesis_ch.'> Notas Tesis
					    	</label-->
					    	';
					    	if ($form_1["type_material"]!="pub_perio") {
					    		$html .= '<!--label class="checkbox">
					      			<input class="ActionInput" type="checkbox" value="016" '.$Descriptor_ch.'> Tema
					    		</label-->
					    		<label class="checkbox">
					      		<input class="ActionInput" type="checkbox" value="018" '.$CongSec_ch.'> Congresos Secundarios
					    	</label>
					    	<label class="checkbox">
					      		<input class="ActionInput" type="checkbox" value="019" '.$TitSec_ch.'> Titulos Secundarios (R)
					    	</label>
					    	<label class="checkbox">
					      		<input class="ActionInput" type="checkbox" value="022" '.$UbicElect_ch.'> Ubicación Electrónica
					    	</label>';
					    	}
					    	$html .='
					    	<!--label class="checkbox">
					      		<input class="ActionInput" type="checkbox" value="020" '.$Fuente_ch.'> Fuente
					    	</label-->
					    	<label class="checkbox">
					      		<input class="ActionInput" type="checkbox" value="021" '.$NumIng_ch.'> Número de Ingreso
					    	</label>


					    	<!--label>
					      		<input class="ActionInput" type="checkbox" value="023" '.$ModAdqui_ch.'> Modalidad Adquisición
					    	</label-->
					    	<label class="checkbox">
					      		<input class="ActionInput" type="checkbox" value="024" '.$Catalogador_ch.'> Catalogador
					    	</label>
						  </fieldset>
						</form>
			    	</div>
			<!-- fin register inputs -->

			   	<span id="botonRegresar"></span>
			<!-- form conte-->
			    <div class="conte-form cols1">';

		   $option_nav ="";
		   if (isset($_SESSION["edit"])) {
		   		for ($i=0; $i < $result_book["Count"]; $i++) {
		   			$option_nav .="<option value='".($i+1)."' ";
		   			$option_nav .=$iddata==($i+1)?"selected":"";
					$option_nav .= ">".($i+1)."</option>";
		   		}

			    $html .= '
			    <div class="controls fright">
			    	<input type="text" id="searchbox" name="searchbox" class="span3"/>
			    	<select name="b_navigation" id="b_navigation" class="span3" OnChange="xajax_editBook(this.value,1); return false;">
			    	<option value="0" >#</option>'.$option_nav.'
			    	</select>
			    </div>
			    <div class="controls fright">';
			    if ($iddata>$result_book["idbook"][0]) {
                                $idback=$iddata-1;
                                while (!search_book_id($idback)) {
                                  $idback-=1;
                                }
			    	$html .= '<a href="#" title="Primer Registro" class="btn btn-mini" onclick="xajax_editBook('.$result_book["idbook"][0].',1); return false;">
			    	<span class="inline ui-icon ui-icon-seek-first"></span>Primero
			     	</a>
			     	<a href="#" title="Anterior registro" class="btn btn-mini" onclick="xajax_editBook('.$idback.',1); return false;">
			    	<span class="inline ui-icon ui-icon-seek-prev"></span>Anterior
			     	</a>'
			     	;
			    }
			    if ($iddata<$result_book["Count"]) {
                                $idnext=$iddata+1;
                		while (!search_book_id($idnext)) {
                      		  $idnext+=1;
		                }
			    	$html .= ' <a href="#" title="Siguiente registro" class="btn btn-mini" onclick="xajax_editBook('.$idnext.',1); return false;">
					<span class="inline ui-icon ui-icon-seek-next"></span>Siguiente
					 </a>
				 	<a href="#" title="Último registro" class="btn btn-mini" onclick="xajax_editBook('.$result_book["Count"].',1); return false;">
					<span class="inline ui-icon ui-icon-seek-end"></span>Ultimo
					</a>';
			    }
			    $html .='
				</div>';
			}

			$html.='
					<div style="padding-top:20px;">
				        <span class="tab" id="titulo1"></span>
				        <span class="tab" id="titulo2"></span>
				        <span class="tab" id="titulo2_A"></span>

				        <!--span class="tab" id="titulo5"></span-->
				        <!-span class="tab" id="titulo6"></span-->
				        <span class="tab" id="titulo7"></span>
					</div>
					<form id="frmBiblio" name="frmBiblio">
			        <div id="idcontactform" class="listado-busqueda form-horizontal">

				            <div  id="titulo_tipo_prepor"></div>

					        <div id="author" style="display:none">
								<div class="ll" id="author_section_int"></div>
								<div class="author_select">
									<p class="txt-azul">Autor principal</p>
									<div id="sesion_authorPRI">
										<table align="center"><tbody><tr><td>Añadir autor de la lista.</td></tr></tbody></table>
									</div>

									<div class="linea-separacion"></div>

									<p class="txt-azul">Autor secundario</p>
									<div id="sesion_authorSEC">
										<table align="center"><tbody><tr><td>Añadir autor(es) de la lista.</td></tr></tbody></table>
									</div>
								</div>
					        </div>
				            <div id="author_inst" style="display:none">
				            	<div id="author_section_int_02"></div>
								<div class="author_select">
									<p class="txt-azul">Autor principal</p>
									<div id="sesion_authorPRI_02">
										<table align="center"><tbody><tr><td>Añadir autor de la lista.</td></tr></tbody></table>
									</div>

									<div class="linea-separacion"></div>

									<p class="txt-azul">Autor secundario</p>
									<div id="sesion_authorSEC_02">
										<table align="center"><tbody><tr><td>Añadir autor(es) de la lista.</td></tr></tbody></table>
									</div>
								</div>
				            </div>
				            <div id="referencia" style="display:none"></div>

				            <div id="area_tema" style="display:none">
				                <div class="txt-azul" id="conte_temas"></div>
								<div  class="linea-separacion"></div>
								<a class="showdiv txt-azul" onclick="$(\'.showdiv\').toggle()"> <i class="icon-chevron-right"></i>  Nuevo Tema</a>
								<a class="showdiv hide txt-azul divactive" onclick="$(\'.showdiv\').toggle()"> <i class="icon-chevron-down"></i> Nuevo Tema</a>
				                <div class="hide showdiv" id="nuevo_tema_publicacion"></div>
				            </div>

							<div id="fecha_permisos" style="display:none">
								<div id="fechasTesis"></div>
								<div  class="linea-separacion"></div>

							</div>
							<div id="archivo" style="display:none"></div>
						</form>
					</div>

		            <div class="action-btn">
		            	<input class="btn"  type="button" onclick="xajax_newRegisterBiblio('.$iddata.',\''.$action.'\',xajax.getFormValues(\'frmBiblio\'),1);" value="'.$tituloBoton.'"  />
		            	<input class="btn"  type="button" onclick="xajax_newRegisterBiblio('.$iddata.',\''.$action.'\',xajax.getFormValues(\'frmBiblio\'),2);" value="'.$tituloBoton_sec.'"/>
		            </div>
		        </div>

		    <!-- fin form conte -->	';
	    $objResponse->assign("formulario","style.display","block");
	    $objResponse->assign("formulario","innerHTML",$html);

	    $objResponse->script("xajax_auxAuthorShow(5000,1,'Array','int','','AuthorInst')");
	    $objResponse->script("xajax_auxAuthorShow(5000,1,'Array','int','','AuthorPer')");
	    $objResponse->script("xajax_displaydiv('titulo_tipo_prepor','titulo1')");

	    $input_array = array("TypeMatBib","title","state","FxIng","Description","NoteGeneral","MatEntidad","temas_recovery","sede");

	    $titulo="Detalle";
		if(isset($_SESSION["edit"])){
		    $recuperar=$_SESSION["edit"];
		     foreach ($input_array as $value) {
	    		$input[$value] = (isset($recuperar[$value])?$recuperar[$value]:"");
	    	}
	    	$html_state = "";
            foreach ($recuperar["state"] as $key => $value) {
                $sel =($value==1)?"selected":" ";
                $sel1 =($value==2)?"selected":" ";

                $html_state .= "
                        <label class='control-label' >Ejm - ".($key+1)."</label>
                        <div class='controls'>
                        <select name='state[]' >
                            <option value='100'>Disponible</option>
                            <option value='1' ".$sel.">Reservado</option>
                            <option value='2' ".$sel1.">Prestado</option>
                         </select>
                         </div>";
            }
		}
		elseif(isset($_SESSION["tmp"])){
		    $recuperar=$_SESSION["tmp"];
		 }
	    $date_ing = isset($recuperar["FxReg"])?$recuperar["FxReg"]:date("d/m/Y");
	    $TypeMatBib = isset($recuperar["TypeMatBib"])?$recuperar["TypeMatBib"]:$form_1["type_material"];

	    if (eregi("lib", $TypeMatBib)) {
	    	$TypeMatBib="Libro";
	    }
	    elseif (eregi("pub", $TypeMatBib)) {
	    	$TypeMatBib="Publicaciones";
	    }
	    elseif (eregi("map", $TypeMatBib)) {
	    	$TypeMatBib="Mapas";
	    }
	    elseif (eregi("tes", $TypeMatBib)) {
	    	$TypeMatBib="Tesis";
	    }else{
	    	$TypeMatBib="Otros";
	    }
	    //tipo de material
		$type_array = array("Libro"=>"Libros","Publicaciones"=>"Publicaciones Periódicas","Mapas"=>"Mapas","Tesis"=>"Tesis","Otros"=>"Otros Materiales");
		$type_html="";
		foreach ($type_array as $key => $value) {
			$type_html .="<option value='$key' ";
			if ($TypeMatBib==$key) {
				$type_html .="selected";
			}
			$type_html .= " >$value</option>";
		}
		//campos requeridos para publicaciones

		$html="
			<div class='clear'></div>
			<!--campos requeridos -->
			<div class='control-group'>
				<label class='control-label' for='TypeMatBib'>Tipo de Material</label>
				<div class='controls'>";
				if (!(isset($recuperar["TypeMatBib"]))) {
					$html .="<input type='text' value='".$TypeMatBib."' READONLY id='TypeMatBib' name='tipo' >";
				}
				else{
					$html .="<select class='span4' id='type_material' name='tipo'>
			    	<option value='999'>seleccione Tipo de Material</option>
			    	".$type_html."
	    			</select>";
				}
		$html .="
				<span id='TypeMatBib_error' class='msg_error color_red'></span>
				</div>
			</div>

			<div class='control-group'>
				<label class='control-label' for='NumCorr'>Numeración Correlativa</label>
				<div class='controls'>
				<input type='text' value='".$iddata."' READONLY id='NumCorr' name='NumCorr'  />
				<span id='FxReg_error' class='msg_error color_red'></span>
				</div>
			</div>
			<div class='control-group'>
				<label class='control-label' for='FxReg'>Fecha de Registro</label>
				<div class='controls'>
				<input type='text' value='".$date_ing."' READONLY id='FxReg' name='FxReg'  />
				<span id='FxReg_error' class='msg_error color_red'></span>
				</div>
			</div>
			<div id='id_020_c'></div>
			<div id='001_c'></div>
			<div id='002_c'></div>
			<div id='003_c'></div>";
			$NumDewey = Query_input('004');
			$html .= $NumDewey["html"];

			$html .= "<div id='005_c'></div>
			<div class='control-group'>
				<label class='control-label' for='title'>Título</label>
				<div class='controls'>
				<textarea name='title' class='textarea span7' placeholder='Escribe título…'>".$input["title"]."</textarea>
				<span id='title_error' class='msg_error color_red'></span>
				</div>
			</div>
			<div id='id_0250_A_c'></div>";
			$publication = Query_input("id_0250");
			$html .= $publication["html"];
			$Description = Query_input('id_0300');
			$html .= $Description["html"];

			$html .= "<div id='009_c'></div>
                      <div id='010_c'></div>
					  <div id='011_c'></div>
					  <div id='012_c'></div>";
			if ($form_1["type_material"]=="pub_perio") {
				$NoteContent1 = Query_input('013');
				$html .= $NoteContent1["html"];
			}
			else{
				$html .= "<div id='013_c'></div>";
			}
			//resumen
			$html .= "<div id='id_0520_c'></div>";
            /*temas borrador
            $html .= "
                    <div class='control-group'>
                        <label class='control-label' for='title'>temas(borrador)</label>
                        <div class='controls'>
                        <textarea class='textarea span7' >".$input["temas_recovery"]."</textarea>
                        <span id='title_error' class='msg_error color_red'></span>
                        </div>
                    </div>";*/

            $Themes_1 = Query_input('016');
            $html .= $Themes_1["html"];
			//congreso y titulo secundario
			$html .= "<div id='018_c'></div>
					  <div id='019_c'></div>";

			$FxIng = Query_input('id_0920');
			$html .= $FxIng["html"];
			$ModAdqui = Query_input('023');
			$html .= $ModAdqui["html"];
			//catalogador
			$html .= "<div id='024_c'></div>";
			$UbicFis = Query_input('id_0924');
            //ubicacion fisica, Número de ingreso, Ubicación electrónica
			$html .= $UbicFis["html"].
				"<div id='021_c'></div>
				<div id='022_c'></div>
				<div class='files_c'></div>";
            $ejemplares = Query_input("25");
            $html_state1 = "<label class='control-label' for='state'>Estado</label>
                    <div class='controls'>
                    <select name='state[]' >
                    <option value='100'>Disponible</option>
                    <option value='1'>Reservado</option>
                    <option value='2'>Prestado</option>
                    </select>
                    </div>";
            $html .= $ejemplares["html"]."
				    <div class='control-group' id='25_state'>";
            $html .= $_SESSION["edit"]?$html_state:$html_state1;
			$html .="</div>";
            $idsede = isset($_SESSION["edit"]["sede"])?$_SESSION["edit"]["sede"]:$_SESSION["users_sede"];
            $result_sede = query_sede($idsede);
            $location_sede=$result_sede!=-100?$result_sede["descripcion"]:"Sede desconocida";
            //$location_sede = (isset($_SESSION["edit"]))?($_SESSION["edit"]["sede"]==1?"Mayorazgo":"Jicamarca"):($_SESSION["users_sede"]==1?"Mayorazgo":"Jicamarca");
            $html .= "
                    <div class='control-group'>
                        <label class='control-label' for='title'>Sede</label>
                        <div class='controls'>
                        <input type='text' READONLY value='".$location_sede."'>
                        <input type='hidden' name='sede' value=".$idsede.">
                        </div>
                    </div>";

	    // $objResponse->Assign("titulo_tipo_prepor","innerHTML","");
	    $objResponse->Assign("titulo_tipo_prepor","innerHTML",$html);
        $state_select = "
                        <select name='state[]' >
                            <option value='1'>Disponible</option>
                            <option value='0'>No Disponible</option>
                         </select>";

        $state_select = eregi_replace("[\n|\r|\n\r]", ' ', $state_select);
        $state_select = addslashes($state_select);
        $objResponse->script("
                            $('.ui-icon').parents('a').tooltip();
                            function msj_info(){
                                $('#msj-ejem').html('<span class=\"msj exito\"><i class=\"icon-info-sign\"></i> Debe ingresar un número</span>');
                                setTimeout(function(){ $(\".msj\").fadeIn(800).fadeOut(500);}, 3000);
                            }
                            $('#NumEjem').keydown(function(event) {
                                if(event.shiftKey) {
                                    event.preventDefault();
                                }
                                if (event.keyCode == 46 || event.keyCode == 8) {
                                }
                                else {
                                    if (event.keyCode < 95) {
                                        if (event.keyCode < 48 || event.keyCode > 57) {
                                            event.preventDefault();
                                            msj_info()
                                        }
                                    }
                                    else {
                                            if (event.keyCode < 96 || event.keyCode > 105) {
                                                event.preventDefault();
                                                msja_info()
                                            }
                                    }
                                }
                            }).
                            change(function(){
                                    ej= $(this).val()
                                    if (ej>20) {
                                        if (confirm('Ha ingresado un numero demasiado grande. ¿Esta seguro de realizar dicha operacion?')) {
                                           html_combo()
                                        }
                                        else{
                                            $('#NumEjem').focus()
                                        }
                                    }
                                    else{
                                        html_combo()
                                    }

                            });
                        function html_combo(){
                            ej = $('#NumEjem').val()
                            html = ''
                            for (i=0; i < ej; i++) {
                                html += '<div class=\"control-group\"><label class=\"control-label\">Ejem - '+(i+1)+' </label><div class=\"controls\">$state_select</div></div>'
                            }
                            $('#25_state').html(html)
                        }

            ");

		$objResponse->Assign('titulo1',"innerHTML","<a href='#1' onclick=\"xajax_displaydiv('titulo_tipo_prepor','titulo1'); return false;\"  rel='tooltip' data-toggle='tooltip' title='Información General!'>Detalle</a>");


    	//###############################################################
		//PARA EL AUTOR

    	$objResponse->assign("titulo2","innerHTML","<a class='tab-title' href='#' onclick=\"xajax_displaydiv('author','titulo2'); return false;\" rel='tooltip' data-toggle='tooltip' title='Gestione Autores aqui!' >Autor Personal</a>");
    	$objResponse->assign("titulo2_A","innerHTML","<a class='tab-title' href='#' onclick=\"xajax_displaydiv('author_inst','titulo2_A'); return false;\" rel='tooltip' data-toggle='tooltip' title='Gestione Autores aqui!' >Autor Institucional</a>");
    	$objResponse->script("xajax_searchAuthorSesionPriShow()");
    	$objResponse->script("xajax_searchAuthorSesionSecShow()");

    	$objResponse->assign("search_authorPRI","innerHTML",iniAuthorPriShow("AuthorPer"));
    	$objResponse->assign("search_authorPRI_02","innerHTML",iniAuthorPriShow("AuthorInst"));
        $objResponse->assign("newFormAuthor","innerHTML",formAuthorShow());
        $objResponse->assign("newFormAuthor_02","innerHTML",formAuthorShow());


	    // Temas relacionados
		$link="<a onclick=\"xajax_displaydiv('area_tema','titulo5'); return false;\" class='tab-title' href='#' rel='tooltip' title='Temas Relacionados'>Temas Relacionados</a>";
		$objResponse->assign('titulo5',"innerHTML",$link);


        $objResponse->Assign('titulo7',"innerHTML","<a class='tab-title' href='#1' onclick=\"xajax_displaydiv('archivo','titulo7'); return false;\" rel='tooltip' title='Imagen de Portada'>Imagen</a>");
        // $objResponse->Assign("archivo","innerHTML",$htmlArchivo);
    	$objResponse->Assign("archivo","innerHTML","<div id='carga_archivo'></div>");
    	$objResponse->script("xajax_carga_archivo()");


		$objResponse->Assign("formulario","style.display","block");
		$objResponse->Assign("resultSearch","style.display","none");
		//select 	catalago para consulta
		$objResponse->Assign("searchCat","style.display","none");
		$objResponse->Assign("author_section","style.display","none");
		$objResponse->Assign("paginatorAuthor","style.display","none");

		// $objResponse->Assign("estadisticas", "style.display", "none");

        /*$years_help = "[";
		for ($i=1950; $i < (date('Y')-1); $i++) {
			$years_help .= "'$i',";
		}
		$years_help = substr($years_help, 0,-1);
		$years_help .= "]";*/
	   $years_help = generate_dictionary($type="date");
        //diccionario de temas sugeridos
        $themes = tags_temas();
        $theme_dictionary = generate_dictionary($type="",$themes);

        $objResponse->script("
                				var years = ['1950','1960'];
       						$('.span2.year').typeahead({source: ".$years_help."});
						$('#themes_bib input').typeahead({source:".$theme_dictionary."});
                				$('#list_fbook').change(function(){
                					var sel_html = $('#list_fbook option:selected').html();
                					if (sel_html == 'Nuevo Formato') {
                						$('#newformat').removeClass('divnone');
                						$('#newformat').addClass('divblock');
                					}
                					else{
                							$('#newformat').removeClass('divblock');
                							$('#newformat').addClass('divnone');
                						}

                					});
                				");

        $objResponse->assign("imghome", "style.display", "none");
        $objResponse->assign("consultas", "style.display", "none");
        $objResponse->assign("resultSearch1", "style.display", "none");
        //Nuevo formato tipo modal y formulario dinamico
        $objResponse->script("
                				  //reduccion de las imagenes cuando son muy grandes
					        		if ($('#myModal_1').length) {
										// w_img.removeAttr('width')
										// w_img.removeAttr('height')
										var w_img = $('#myModal_1 img').width()
										if (w_img>=500) {
											$('#myModal_1 img').width(500)
										}
									}
                				  $('.textarea').focus(function(){
                				  	$(this).addClass('focus');
                				  }).focusout(function(){
                				  	$(this).removeClass('focus');
                				  });
                				  $('.accordion').collapse();
                				  $('#searchbox').keydown(function(event) {
								   if(event.shiftKey) {
								        event.preventDefault();
								   }
								   if (event.keyCode == 46 || event.keyCode == 8) {
								   }
								   else {
								        if (event.keyCode < 95) {
								          if (event.keyCode < 48 || event.keyCode > 57) {
								                event.preventDefault();
								          }
								        }
								        else {
								              if (event.keyCode < 96 || event.keyCode > 105) {
								                  event.preventDefault();
								              }
								        }
								      }
								   });
                					$('#searchbox').bind('keypress', function(e) {
                						var val_text = $(this).val();

										if(e.keyCode==13){
											if (val_text>0 && val_text<".$result_book["Count"].") {
                								$('#b_navigation option[value='+val_text+']').attr('selected',true);
												xajax_editBook(val_text,1); return false;
                							}
                							else{
											 alert('El número ingresado está fuera del rango de registros');
											 $(this).val('').focus();
											}
										}
									});
                					$( '#FxIng' ).datepicker({
								      showOn: 'button',
								      buttonImage: './img/calendar.gif',
								      buttonImageOnly: true,
								      changeMonth: true,
      								  changeYear: true,
      								  //minDate: '-15Y', maxDate: '+1M +10D',
      								  yearRange: '-70:+0'
								    }).mask('99/99/9999');
									$('#divNewFormat').dialog({
									autoOpen: false,
									modal: true,
									title: 'Nuevo Formato',
									show: 'fade',
									hide: 'fade',
						            height: 'auto',
						            width: 350 });

						            $('.btnOpen').click(function() {
										$('#divNewFormat').dialog('open');
										return false;
									});
                					//alert('verificando los checked para incrementarlo al form');
                					$('.ActionInput').each(function(){
                						var id = $(this).val();
				                		if($(this).is(':checked')) {
								            xajax_ListCampos(id);
								        } else {
								            xajax_delCampos(id);
								        }
                					});

									$('.ActionInput').change(function(){
				                		var id = $(this).val();
				                		if($(this).is(':checked')) {
								            xajax_ListCampos(id);
								        } else {
								            xajax_delCampos(id);
								        }
				                	});

         ");

		return $objResponse;

	}
    function generate_dictionary($type="", $array=array()){

        $dictionary = "[";
        if ($type=="date") {
            for ($i=1950; $i < (date('Y')-1); $i++) {
                $dictionary .= "'$i',";
            }
        }
        else{
            foreach ($array as $key => $value) {
                $dictionary .= "'$value',";
            }
        }
        $dictionary = substr($dictionary, 0,-1);
        $dictionary .= "]";
        return $dictionary;
    }
    function tags_temas(){
        $temas = temas_query();
        $html = "";
        $theme = array(0 => "-");
        if ($temas["error"]==100) {
            foreach ($temas as $key => $value) {
                if (is_array($value)) {
                    $resultado = array_unique($value);
                    foreach ($resultado as $key1 => $value1) {
                        if (!empty($value1) and (trim($value1)!="-") ) {
                            array_push($theme,$value1);
                        }
                    }
                }
            }
            $theme = array_unique($theme);
        }
        return $theme;
    }
    function tags_temas_html(){
        $theme = tags_temas();
        $tag="";
        foreach ($theme as $key => $value) {
             $tag.=$value."</br>";
        }
        $html .= '<a href="#booTag" role="button" data-toggle="modal" class="inline help" title="Ver sugerencia de temas"> (?)<i class="icon-tag"></i> </a>';
        $html .= '<div id="booTag" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel"> Sugerencia de temas </h3>
              </div>
              <div class="modal-body">
                <div>'.$tag.'</div>
              </div>
              <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
              </div>
            </div>';
        return $html;
    }
	function ListCampos($id=""){
		$objResponse = new xajaxResponse();

		$result = Query_input($id);

		$html = $result["html"];

 		$html = eregi_replace("[\n|\r|\n\r]", ' ', $html);
 		$html = addslashes($html);
		// $objResponse->alert(print_r($id, TRUE));

		$objResponse->script('
							var1 = "'.$html.'";
							var var2=var1.replace("\n"," ");
							$(""+var2+"").appendTo("#'.$id.'_c").fadeIn(800).fadeOut(400).fadeIn(800);

							');
		$objResponse->script("
					$('.textarea').focus(function(){
                				  	$(this).addClass('focus');
                				  }).focusout(function(){
                				  	$(this).removeClass('focus');
                	});
					$('#".$id." > div').each(function(index){
						$(this).attr('id','".$id."_'+(index+1));
						$(this).find('a').attr('id','a_".$id."_'+(index+1));
					});
					var languajes = ['ESP','ENG','FRE','RUS'];
					var Catalogador = ['CBV'];

					$('#002 input').typeahead({source: languajes})
					 .attr('maxlength','3');
					$('#002 input, #024 input')
					.addClass('uppercase')
					.keypress(function(e) {
					   key = e.keyCode || e.which;
				       tecla = String.fromCharCode(key).toLowerCase();
				       letras = ' áéíóúabcdefghijklmnñopqrstuvwxyz';
				       especiales = [8,37,39,46];

				       tecla_especial = false
				       for(var i in especiales){
				            if(key == especiales[i]){
				                tecla_especial = true;
				                break;
				            }
				        }

				        if(letras.indexOf(tecla)==-1 && !tecla_especial){
				            return false;
				        }
					});
					$('#024 input').attr('maxlength','4').typeahead({source: Catalogador});

					function soloLetras(e){
				       key = e.keyCode || e.which;
				       tecla = String.fromCharCode(key).toLowerCase();
				       letras = ' áéíóúabcdefghijklmnñopqrstuvwxyz';
				       especiales = [8,37,39,46];

				       tecla_especial = false
				       for(var i in especiales){
				            if(key == especiales[i]){
				                tecla_especial = true;
				                break;
				            }
				        }

				        if(letras.indexOf(tecla)==-1 && !tecla_especial){
				            return false;
				        }
				    }
		");

		if (isset($_SESSION["edit"])) {
			$recuperar = $_SESSION["edit"];
		}
		return $objResponse;

	}
	function delCampos($id="",$id2=""){
		$objResponse = new xajaxResponse();

		if (isset($id) and $id!="") {
			$result = Query_input($id);
			$idinput = $result["idinput"];

			if ($_SESSION["required"]["$idinput"]) {
				unset($_SESSION["required"]["$idinput"]);
			}
		}
		if (isset($id2) and $id2!="") {
			$id = $id2;

		}

		$objResponse->remove("$id");
		$objResponse->script("
					$('#".$id." > div').each(function(index){
						$(this).attr('id','".$id."_'+(index+1));
						$(this).find('a').attr('id','a_".$id."_'+(index+1));
						$(this).find('input').change(function(){
							//alert(index);
						});
					});
		");

		return $objResponse;
	}

	function Query_input($id="") {

		// $recuperar = (isset($_SESSION["edit"])?$_SESSION["edit"]:"");
		if (isset($_SESSION["edit"])) {
			$recuperar = $_SESSION["edit"];
		}
		$respuesta["html"] = "<div class='control-group' id='$id'>";
		//agregar y eliminar input

		// $respuest["add"] = "<span><a href='#' onclick='xajax_AddInput(\"".$id."\",\"".$respuesta["labelinput"]."\",\"".$respuesta["idinput"]."\"); return false;'>(+)Aumentar</a></span>";
		// $respuesta["del"] = "<span><a href='#' onclick='$(\"#".$id."_".$k."\").remove(); return false;'>(-)Eliminar</a></span>";
		$repetibles = array("id_0250","002","006","007","009","010","014","015","016","017","019","020","021");
		$textarea = array("013","012","010","id_0520","011");
		switch ($id) {

				case 'id_020':
					$respuesta["idinput"] = "ISBN";
					$respuesta["labelinput"] = "ISBN";
					break;
				case 'id_0250':
					$respuesta["idinput"] = "Edition";
					$respuesta["labelinput"] = "Publicación";
					$respuesta["labelSec"] = array("country"=>"Pais","editorial"=>"Editorial","year"=>"Año");
					break;
				case 'id_0250_A':
					$respuesta["idinput"] = "numEdition";
					$respuesta["labelinput"] = "Edición";
					break;
				case 'id_0520':
					$respuesta["idinput"] = "Resumen";
					$respuesta["labelinput"] = "Resumen";
					break;
				case 'id_0300':
					$respuesta["idinput"] = "Description";
					$respuesta["labelinput"] = "Descripción física";
					break;
				case 'id_0920':
					$respuesta["idinput"] = "FxIng";
					$respuesta["labelinput"] = "Fecha de ingreso";
					break;
				case 'id_0924':
					$respuesta["idinput"] = "UbicFis";
					$respuesta["labelinput"] = "Ubicación física";
					break;
				case 'id_0925':
					$respuesta["idinput"] = "NumReg";
					$respuesta["labelinput"] = "Número de registro";
					break;

				case '001':
					$respuesta["idinput"] = "ISSN";
					$respuesta["labelinput"] = "ISSN";
					break;

				case '002':
					$respuesta["idinput"] = "languaje";
					$respuesta["labelinput"] = "Idioma";
					break;

				case '003':
					$respuesta["idinput"] = "NumLC";
					$respuesta["labelinput"] = "N° clasificación LC";
					break;

				case '004':
					$respuesta["idinput"] = "NumDewey";
					$respuesta["labelinput"] = "N° de Clasificación Dewey";
					break;

				case '005':
					$respuesta["idinput"] = "Class_IGP";
					$respuesta["labelinput"] = "N° de clasificación IGP";
					break;

				case '006':
					$respuesta["idinput"] = "EncMat";
					$respuesta["labelinput"] = "Congreso";
					break;

				case '007':
					$respuesta["idinput"] = "OtherTitles";
					$respuesta["labelinput"] = "Otros títulos";
					break;

				case '008':
					$respuesta["idinput"] = "Periodicidad";
					$respuesta["labelinput"] = "Periodicidad";
					break;

				case '009':
					$respuesta["idinput"] = "Serie";
					$respuesta["labelinput"] = "Serie";
					break;

				case '010':
					$respuesta["idinput"] = "NoteGeneral";
					$respuesta["labelinput"] = "Notas generales";
					break;

				case '011':
					$respuesta["idinput"] = "NoteTesis";
					$respuesta["labelinput"] = "Notas de tesis";
					break;

				case '012':
					$respuesta["idinput"] = "NoteBiblio";
					$respuesta["labelinput"] = "Notas de bibliografía";
					break;

				case '013':
					$respuesta["idinput"] = "NoteConte";
					$respuesta["labelinput"] = "Notas de contenido";
					break;

				case '014':
					$respuesta["idinput"] = "DesPersonal";
					$respuesta["labelinput"] = "Tema como persona";
					break;

				case '015':
					$respuesta["idinput"] = "MatEntidad";
					$respuesta["labelinput"] = "Tema como entidad";
					break;

				case '016':
					$respuesta["idinput"] = "Theme";
					$respuesta["labelinput"] = "Tema";
					$respuesta["labelSec"] = array("Principal"=>"Principal","secundary1"=>"Secundario","secundary2"=>"Secundario") ;
					break;

				case '017':
					$respuesta["idinput"] = "Descriptor_geo";
					$respuesta["labelinput"] = "Tema geográfico";
					break;

				case '018':
					$respuesta["idinput"] = "CongSec";
					$respuesta["labelinput"] = "Congresos secundarios";
					break;

				case '019':
					$respuesta["idinput"] = "TitSec";
					$respuesta["labelinput"] = "Titulos secundarios";
					break;

				case '020':
					$respuesta["idinput"] = "Fuente";
					$respuesta["labelinput"] = "Fuente";
					break;

				case '021':
					$respuesta["idinput"] = "NumIng";
					$respuesta["labelinput"] = "Número de ingreso";
					break;

				case '022':
					$respuesta["idinput"] = "UbicElect";
					$respuesta["labelinput"] = "Ubicación electrónica";
					break;

				case '023':
					$respuesta["idinput"] = "ModAdqui";
					$respuesta["labelinput"] = "Modalidad adquisición";
					break;

				case '024':
                    $respuesta["idinput"] = "Catalogador";
                    $respuesta["labelinput"] = "Catalogador";
                    break;
                case '025':
					$respuesta["idinput"] = "NumEjem";
					$respuesta["labelinput"] = "Número de ejemplares";
					break;

				default:
					$respuesta["html"] = "";
					break;
			}

			$idinput = $respuesta["idinput"];
			//para campos repetidos
			if (is_array($recuperar[$idinput])) {

				if (isset($recuperar[$idinput])) {
						$respuesta["html"] .= "<label class='control-label' for='$idinput'>".$respuesta["labelinput"]."</label>";
						if ($id=="id_0250" ) {
							$respuesta["html"] .= optionEdition($id,$idinput,$respuesta["labelinput"],$respuesta["labelSec"]);
						}
						elseif ($id=="016") {
							$respuesta["html"] .= optionTheme($id,$idinput,$respuesta["labelinput"],$respuesta["labelSec"]);
						}
						elseif ($id=="id_0300") {
							$respuesta["html"].="<div class='controls'>";
							foreach ($recuperar[$idinput] as $key => $value) {
								$respuesta["html"] .= "<input class='span3' placeholder='".$key."' name='".$idinput."[".$key."]' type='text' value='$value'>";
							}
							$respuesta["html"].="</div>";
						}
						else{

							for ($k=0; $k < count($recuperar[$idinput]); $k++) {
								 $val_input=(isset($recuperar[$idinput][$k])?$recuperar[$idinput][$k]:"");

								$respuesta["html"] .= "
									    <div class='controls' id='".$id."_".($k+1)."'>";
								if (in_array($id, $textarea)) {
									$respuesta["html"].="<textarea class='textarea' name='".$idinput."[]' placeholder='".$respuesta["labelinput"]."'>$val_input</textarea>";
								}
								else{
									$respuesta["html"].="<input type='text' name='".$idinput."[]' placeholder='".$respuesta["labelinput"]."'  value='$val_input'>";
								}


								// $respuesta["html"] .=($k==0?$respuesta["add"]:$respuesta["del"]);
								  if ($k==0) {

									$respuesta["html"] .= "<span><a href='#' tittle='Aumentar ".$respuesta["labelinput"]."' onclick='xajax_AddInput(\"".$id."\",\"".$respuesta["labelinput"]."\",\"".$respuesta["idinput"]."\"); return false;'>
																<span class='ui-icon ui-icon-circle-plus inline'></span></a>
															</span> ";
								  }
								  else{
									$respuesta["html"] .= "<span><a href='#' title='Eliminar ".$respuesta["labelinput"]."' onclick='xajax_delCampos(\" \",\"".$id."_".($k+1)."\");  return false;'>
																<span class='ui-icon ui-icon-circle-minus inline'></span></a>
															</span>";
								  }
								 $respuesta["html"] .="</div>";
								}
						}

					}

					else{
						$respuesta["html"] .= "
							    <label class='control-label' for='$idinput'>".$respuesta["labelinput"]."</label>
							    <div class='controls' id='".$id."_".($k+1)."'>
							      <input type='text' name='".$idinput."[]' placeholder='".$respuesta["labelinput"]."' onchange='xajax_register_input(this.value,\"".$respuesta["labelinput"]."\",\"".$respuesta["idinput"]."\"); return false;' value=''>
							      <span><a href='#' title='Aumentar ".$respuesta["labelinput"]."' onclick='xajax_AddInput(\"".$id."\",\"".$respuesta["labelinput"]."\",\"".$respuesta["idinput"]."\"); return false;'>
							      	<span class='ui-icon ui-icon-circle-plus inline'></span></a></span>
							    </div>
							";
					}
			}

			else{
				//new register
				if (in_array($id, $repetibles)) {
						//case edition
					$respuesta["html"].= "<label class='control-label' for='$idinput'>".$respuesta["labelinput"]."</label> ";
						if ($id=="id_0250") {
							$respuesta["html"].= optionEdition($id,$idinput,$respuesta["labelinput"],$respuesta["labelSec"]);
						}
						elseif ($id=="016") {
							$respuesta["html"].= optionTheme($id,$idinput,$respuesta["labelinput"],$respuesta["labelSec"]);
						}
						else{
						$respuesta["html"] .= "
							    <div class='controls' id='".$id."_1'> ";
							    if (in_array($id, $textarea)) {
									$respuesta["html"].="<textarea class='textarea span7' name='".$idinput."[]' placeholder='".$respuesta["labelinput"]."'></textarea>";
								}
								else{
									$respuesta["html"].="<input type='text' name='".$idinput."[]' placeholder='".$respuesta["labelinput"]."'  value=''>";
								}

						$respuesta["html"].="  <span><a href='#' title='Aumnetar ".$respuesta["labelinput"]."' onclick='xajax_AddInput(\"".$id."\",\"".$respuesta["labelinput"]."\",\"".$respuesta["idinput"]."\"); return false;'>
							      	<span class='ui-icon ui-icon-circle-plus inline'></span></a>
							      </span>
							      <span id='".$respuesta["idinput"]."_0_error' class='msg_error color_red'></span>
							    </div>
						";
						}
				}

				elseif ($id=="id_0300") {
						$recuperar[$idinput] =array("Paginación"=>"","Ilustración"=>"","Dimensión"=>"");
						$respuesta["html"].="
							<label class='control-label' for='$idinput'>".$respuesta["labelinput"]."</label>
							<div class='controls'>";
							foreach ($recuperar[$idinput] as $key => $value) {
								$respuesta["html"] .= "<input class='span3' placeholder='".$key."' name='".$idinput."[".$key."]' type='text' value='$value'> ";
							}
							$respuesta["html"].="</div>";
					}

			//para campos no repetidos
				else{
					$val_input=(isset($recuperar[$idinput])?$recuperar[$idinput]:"");
					$respuesta["html"] .="
								<label class='control-label' for='$idinput'>".$respuesta["labelinput"]."</label>
								<div class='controls'>";

						if (in_array($id, $textarea)) {
							$respuesta["html"].="<textarea class='textarea span7' name='".$idinput."[]' placeholder='".$respuesta["labelinput"]."'>$val_input</textarea>";
						}
						else{
							$respuesta["html"].="<input type='text' placeholder='".$respuesta["labelinput"]."' value='$val_input' id='$idinput' name='$idinput'  />";
						}
					$respuesta["html"].="	<span id='".$respuesta["idinput"]."_error' class='msg_error color_red'></span>";

                        if ($id=="025") {
                            $respuesta["html"].="<span id='msj-ejem'></span>";
                        }
                    $respuesta["html"].="</div>";
				}


			}

		$respuesta["html"]	.= '</div>';
		return $respuesta;

	}
	function optionEdition($id="",$idinput="",$label="",$labelSec=""){
		// $recuperar = (isset($_SESSION["edit"])?$_SESSION["edit"]:"");
		if (isset($_SESSION["edit"])) {
			$recuperar = $_SESSION["edit"];
		}
		$span = "span3";
		$html="";
		if (isset($recuperar[$idinput])) {
			$i=0;
			foreach ($recuperar[$idinput] as $key => $value) {
				if ($key=='year') {
					$span="span2";
				}
				if ($key=='editorial') {
					$span="span4";
				}
				if (is_array($value)) {
					$j=0;
					foreach ($value as $key_1 => $value_1) {
						// $html_input =
						$h[$j] .= "<input class='$span $key' name='".$idinput."[".$key."][]' type='text' value='$value_1'> ";
						$j++;
					}
				}
				else{
					$h[0] .= "<input class='$span $key' name='".$idinput."[".$key."][]' type='text' value='$value'> ";
				}
				$i++;
			}
			$html="";
			for ($i=0; $i < count($h) ; $i++) {
				$html .="<div class='controls' >";
				if ($i==0) {
					$html .= $h[$i]."<span><a href='#' title='Aumentar ".$label."' onclick='xajax_AddInput(\"".$id."\",\"".$label."\",\"".$idinput."\",".$labelSec."); return false;'>
									<span class='ui-icon ui-icon-circle-plus inline'></span></a>
								</span>";
				}else{
					$html .= $h[$i]."<span><a href='#' title='Elimine ".$label."' onclick='xajax_delCampos(\" \",\"".$id."_".($i+1)."\");  return false;'>
										<span class='ui-icon ui-icon-circle-minus inline'></span></a>
								</span>";
				}
				$html .="<span id='".$idinput."_0_error' class='msg_error color_red'></span>
						</div>";
			}

		}
		//temp
		else{
			$html= "
					<div class='controls' id='".$id."_1'>";
					foreach ($labelSec as $key => $value ) {
						if ($key=='year') {
							$span="span2";
						}
						if ($key=='editorial') {
							$span="span4";
						}
						$html .= "<input class='$span $key' type='text' name='".$idinput."[".$key."][]' placeholder='".$value."' value=''> ";
					}

			$html .="		<span><a href='#' title='aumentar ".$label."' onclick='xajax_AddInput(\"".$id."\",\"".$label."\",\"".$idinput."\",".$labelSec."); return false;'>
							<span class='ui-icon ui-icon-circle-plus inline'></span></a>
						</span>
						<span id='".$idinput."_0_error' class='msg_error color_red'></span>
					</div>
		";
		}
		return $html;
	}
	function optionTheme($id="",$idinput="",$label="",$labelSec=""){
		$html = "<table id='themes_bib' class='table table-striped table-bordered'>
				    <thead>
					    <tr>
					    <th>#</th>
					    <th>Principal</th>
					    <th>Secundario</th>
					    </tr>
				    </thead>
				    <tbody>";

		if (isset($_SESSION["edit"])) {
			$recuperar = $_SESSION["edit"];
		}
		$span = "span3";
		$i=0;
                $html.=tags_temas_html();
		if (isset($recuperar[$idinput])) {
			$html_table="";
			$h=0;
			foreach ($recuperar[$idinput] as $key => $value) {
				$html_table.="<tr id='idtr".($h+1)."'>";
				if (is_array($value)) {
					foreach ($value as $key_1 => $value_1) {
						if (!is_array($value_1)) {
							if ($key_1=="detalle") {
								$html_table .= "<td>";
								if ($key!="pri01") {
									$html_table .= "<a href='#' title='Elimine este tema secundario' class='del_tr' onclick='xajax_delInput($(this).parents(\"tr\").attr(\"id\"),\"".$labelinput."\",\"".$idinput."\");return false;'>
								  					<span class='ui-icon ui-icon-circle-minus inline'></span>
													</a>";
								}
								$html_table .= "<span class='number'>".($h+1)."</span></td>
								<td><input class='span11' type='text' name='".$idinput."[".$key."][detalle]' value='".$value_1."' placeholder='Principal'></td>";
							}
							else{
								$html_table .= "
								<td id='idtd1'><input type='text' name='".$idinput."[".$key."][secundary][]' value='".$value_1."' placeholder='Principal'>
								<a href='#' title='Agregar nuevo tema secundario' onclick='xajax_AddInput($(this).parents(\"td\").attr(\"id\"),\"".$label."\",\"".$idinput."\",".$labelSec."); return false;'>
								<span class='ui-icon ui-icon-circle-plus inline'></span>
								</a></td>";
							}
						}
						//array - secundary
						else{
							$i=0;
							$html_table.="<td id='idtd".($i+1)."'>";
							foreach ($value_1 as $key_2 => $value_2) {
								$html_table.="<div id='idtd".($h+1)."_".($key_2+1)."'>
									<input type='text' name='".$idinput."[".$key."][secundary][]' value='".$value_2."' placeholder='Secundario'>";
								if ($key_2==0) {
									$html_table.=" <a href='#' title='Agregar nuevo tema secundario' onclick='xajax_AddInput($(this).parents(\"td\").attr(\"id\"),\"".$label."\",\"".$idinput."\",".$labelSec."); return false;'>
										<span class='ui-icon ui-icon-circle-plus inline'></span>
										</a>";
								}
								else{
									$html_table.="<a href='#' title='Elimine este tema secundario' class='del_input' onclick='xajax_delInput($(this).parents(\"div\").attr(\"id\"),\"".$labelinput."\",\"".$idinput."\");return false;'>
										<span class='ui-icon ui-icon-circle-minus inline'></span>
										</a>";
								}
								$html_table.="</div>";
							}
							$html_table.="</td>";
						}
					}
				}
				$h++;
				$html_table.="</tr>";
			}
			$html .= $html_table;
		}

		//temp
		else{
			$html .= "
					    <tr>
					    <td>
					    	<span class='number'>1</span>
					    </td>
					    <td><input class='span11' type='text' name='".$idinput."[pri01][detalle]' placeholder='Principal' value=''></td>
					    <td id='idtd1'>
					    	<input  type='text' name='".$idinput."[pri01][secundary][]' placeholder='Secundario' value=''>
					    	<a href='#' title='Agregar nuevo tema secundario' onclick='xajax_AddInput($(this).parents(\"td\").attr(\"id\"),\"".$label."\",\"".$idinput."\",".$labelSec."); return false;'>
								<span class='ui-icon ui-icon-circle-plus inline'></span>
							</a>
					    </td>
					    </tr>
				   ";
		}
		$html .=" </tbody>

			    </table>
			    <div class='panel'>
			    	<a title='Insertar nuevo tema' href='#' onclick='xajax_AddInput(\"".$id."\",\"".$label."\",\"".$idinput."\",".$labelSec."); return false;'>
							<span class='ui-icon ui-icon-circle-plus inline'></span></a>
			    </div>";

		return $html;
	}
	function AddInput($id="",$labelinput="",$idinput="",$labelSec){
		$objResponse = new xajaxResponse();
		// $objResponse->alert(print_r($labelSec, TRUE));
		$span= "span3";
		if ($id=="id_0250") {
			if ($id=="id_0250") {
			    $labelSec = array("country"=>"Pais","editorial"=>"Editorial","year"=>"Año");
			}
			else{
				$labelSec = array("Principal"=>"Principal","secundary1"=>"Secundario","secundary2"=>"Secundario");
			}
			$html = "<div class='controls'>";
			foreach ($labelSec as $key => $value) {
				if ($key=='year') {
					$span="span2";
				}
				if ($key=='editorial') {
					$span="span4";
				}
				$html .= "<input class='$span' type='text' name='".$idinput."[".$key."][]' placeholder='".$value."' value=''> ";
			}
			$html .="
						<span><a href='#' title='Elimine ".$labelinput."' class='del_input' onclick='xajax_delInput($(this).parents(\"div\").attr(\"id\"),\"".$labelinput."\",\"".$idinput."\");return false;'>
						<span class='ui-icon ui-icon-circle-minus inline'></span></a>
						</span>
						<span  class='msg_error color_red'></span>
						</div>";
		}
		elseif ($id=="016") {
			$html ="<tr>
					    <td>
					    	<a href='#' title='Elimine ".$labelinput."' class='del_tr' onclick='xajax_delInput($(this).parents(\"tr\").attr(\"id\"),\"".$labelinput."\",\"".$idinput."\");return false;'>
								  <span class='ui-icon ui-icon-circle-minus inline'></span>
							</a>
					    	<span class='number'> 	</span>
					    </td>
					    <td><input class='span10' type='text' name='".$idinput."[principal][]' placeholder='Principal' value=''></td>
					    <td>
					    	<input  type='text' name='".$idinput."[secundary][]' placeholder='Secundario' value=''>
					    	<a href='#' title='Aumentar nuevo tema secundario' onclick='xajax_AddInput($(this).parents(\"td\").attr(\"id\"),\"\",\"Theme\",\"\"); return false;'>
								<span class='ui-icon ui-icon-circle-plus inline'></span>
							</a>
					   </td>
					 </tr>";
		}
		elseif (eregi("idtd", $id)) {
			$html = "<div>
							<input type='text'  value=''  name='".$idinput."[secundary][]' placeholder='Secundario' />
							<span>
								<a href='#' title='Elimine este tema secundario' class='del_input' onclick='xajax_delInput($(this).parents(\"div\").attr(\"id\"),\"".$labelinput."\",\"".$idinput."\");return false;'>
									<span class='ui-icon ui-icon-circle-minus inline'></span>
								</a>
							</span>
							<span  class='msg_error color_red'></span>
					</div>
					";
		}
		else{
			$html = "<div class='controls' >
						<input type='text'  value=''  name='".$idinput."[]'  />
						<span><a href='#' title='Elimine ".$labelinput."' class='del_input' onclick='xajax_delInput($(this).parents(\"div\").attr(\"id\"),\"".$labelinput."\",\"".$idinput."\");return false;'>
							<span class='ui-icon ui-icon-circle-minus inline'></span></a>
						</span>
						<span  class='msg_error color_red'></span>
					</div>";
		}
		$html = eregi_replace("[\n|\r|\n\r]", ' ', $html);
 		$html = addslashes($html);
 		$themes = tags_temas();
                $theme_dictionary = generate_dictionary($type="",$themes);
		$objResponse->script("

			if ('".$id."'=='016') {
				$('".$html."').appendTo('#themes_bib tbody');
				$('#themes_bib tbody tr').each(function(index){
					$(this).attr('id','idtr'+(index+1)).children('td:eq(0)').children('span:eq(0)').html(index+1);
					$(this).children('td:eq(1)').find('input').attr('name','".$idinput."[pri0'+(index+1)+'][detalle]');
					$(this).children('td:eq(2)').attr('id','idtd'+(index+1)).find('input').attr('name','".$idinput."[pri0'+(index+1)+'][secundary][]');
				});
			}
			else{
				$('".$html."').appendTo('#".$id."');
				if ('".$id."'.match('^idtd')) {
					$('td#".$id." > div').each(function(index){
						$(this).attr('id','".$id."_'+(index+1));
					});
					$('#themes_bib tbody tr').each(function(index){
						$(this).find('td#".$id." input').attr('name','".$idinput."[pri0'+(index+1)+'][secundary][]');
					})
				}
				else{
					//reconteo('".$id."');
					$('#".$id." > div').each(function(index){
						$(this).attr('id','".$id."_'+(index+1));
						$(this).find('a').attr('id','a_".$id."_'+(index+1));
						$(this).find('span.msg_error').attr('id','".$idinput."_'+(index)+'_error');
						});
					function reconteo(iddd){
						$('#'+iddd+' > div').each(function(index){
							$(this).attr('id',iddd+'_'+(index+1));
							$(this).find('a').attr('id','a_".$id."_'+(index+1));
							$(this).find('span.msg_error').attr('id','".$idinput."_'+(index)+'_error');
							return false;
						});
					}
				}
			}
            $('.ui-icon').parents('a').tooltip();
            $('#themes_bib input').typeahead({source:".$theme_dictionary."});
			");
		// if (eregi("idtd", $id)) {
		// 	$objResponse->script("
		// 		$('td#".$id." > div').each(function(index){
		// 				$(this).attr('id','".$id."_'+(index+1));
		// 			})
		// 	");
		// }
		return $objResponse;
	}
	function delInput($idDiv="",$labelinput="",$idinput=""){
		$objResponse = new xajaxResponse();
		$objResponse->script("
					var idDiv = $('#".$idDiv."').parents('div').attr('id');
					$('#".$idDiv."').remove();
					if ('".$idinput."'=='Descriptor') {
						$('#themes_bib tbody tr').each(function(index){
							$(this).attr('id','idtr'+(index+1)).children('td:eq(0)').children('span:eq(0)').html(index+1);
						});
					}
					else{
						$('#'+idDiv+' > div').each(function(index){
							$(this).attr('id',idDiv+'_'+(index+1));
							$(this).find('span.msg_error').attr('id','".$idinput."_'+(index)+'_error');
						});
					}
                    if (idDiv!='id_0250') {
                        $('#themes_bib tbody tr').each(function(index){
                            $(this).children('td:eq(1)').find('input').attr('name','".$idinput."[pri0'+(index+1)+'][detalle]');
                            $(this).children('td:eq(2)').attr('id','idtd'+(index+1)).find('input').attr('name','".$idinput."[pri0'+(index+1)+'][secundary][]');
                            $(this).attr('id','idtr'+(index+1)).children('td:eq(0)').children('span:eq(0)').html(index+1);
                        });
                    }

			");
		return $objResponse;

	}

    function ListReserva(){
        $objResponse = new xajaxResponse();
        $html ="
                <table id='dt_reservas' width='100%' class='listAuthor tablacebra-2' cellspacing='0' cellpadding='0' border='0' width='380px'>

                <tbody>
             ";

        //new coding
        $result = loanQuery("");
        // $objResponse->alert(print_r($result,true));
        if ($result["Count"]>0) {
        	for ($i=0; $i < $result["Count"]; $i++) {
                $html .="<tr>
                        <td> ";
        		$html .="<div class='list_block row'>
                          <span class='span9'>";
                $html .= "  <p class='res'><i class='icon-info-sign'></i>ID-".$result["id_loan"][$i]."</p>";
                $html .= "  <p class='res'><i class='icon-calendar'></i>".$result["fx_reserve"][$i]."</p>";
                $state = $result["state"][$i]==1 ? "<span class='text-success'>Reservado</span>":($result["state"][$i]==2 ? "<span class='text-error'>Prestado</span>":"<span class='text-info'>Devuelto</span>");
                $html .= "  <p class='res'><i class='icon-info-sign'></i> ".$state."</p>";
                $html .= member_details($result["iduser"][$i],$result["user_type"][$i]);
                $html .= loanbook_details($result["id_loan"][$i]);

                if ($result["state"][$i]==1 or $result["state"][$i]==2) {
                    $lbl_action = $result["state"][$i]==1 ? "Procesar prestamo" : "Registrar Devolución";
                    $state = $result["state"][$i]==1 ? 2 : 100;
                    $html .= "
                              </span>
                              <span class='span3'>
                                    <a href='#' class='btn process' onClick='xajax_cambiar_estado(".$result["id_loan"][$i].",".$state."); return false;'>".$lbl_action."</a>
                                    <a href='#' class='btn cancel' onClick='xajax_delete_reserva(".$result["idbook"][$i]."); return false;'>Eliminar</a>
                              </span>
                            ";
                }
                $html .="</div>";
                $html .="</td>";
                // $html .=" <td >".$result["fx_reserve"][$i]."</td>";
                $html .=" <td>".strftime("%d/%m/%y  ",strtotime($result["fx_reserve"][$i]))."</td>";
                $html .=" </tr>";
        	}
            $html .="</tbody>
                </table>";
            $html = "<h2 class='txt-azul'>Lista de Reservas </h2>".$html;
        }
        else{
            $html = "<h4> <i class='icon-info-sign'></i>No se encontraron reserva</h4>";
        }
        //fin new codng

        $objResponse->assign("author_section","style.display","none");
        $objResponse->assign("imghome","style.display","none");
        $objResponse->assign("option_category","style.display","none");
        $objResponse->assign("formulario","style.display","none");
        $objResponse->assign("searchCat","style.display","none");
        $objResponse->assign("consultas","style.display","none");
        $objResponse->assign("resultSearch1","style.display","none");
        $objResponse->assign("paginator","style.display","none");
        $objResponse->assign("conte_details","style.display","none");
        $objResponse->assign("about_admin","style.display","none");
        $objResponse->assign("ListReserva","style.display","block");
        $objResponse->assign("ListReserva","innerHTML",$html);
        $objResponse->script("
                              $('#ListReserva .list_block').hover(function(){
                                $(this).addClass('block_3');
                              },function(){
                                $(this).removeClass('block_3');
                              });
                             $('#ListReserva .process').hover(function(){
                                $(this).addClass('btn-inverse');
                              },function(){
                                $(this).removeClass('btn-inverse')
                             });
                             $('#ListReserva .cancel').hover(function(){
                                $(this).addClass('btn-danger');
                              },function(){
                                $(this).removeClass('btn-danger')
                             });
            ");
        $objResponse->script(" $('#dt_reservas').dataTable({
                            'bJQueryUI': true,
                            'bPaginate': true,
                            'bSort': false, //ordenar por columnas
                            'sPaginationType': 'full_numbers',
                            'iDisplayLength': 10,
                            'aoColumnDefs': [
                                { 'sType': 'string', 'aTargets': [1] }
                            ],
                            'aoColumns': [
                                              null,
                                             {'sType': 'fecha','bVisible': false }
                                            ],
                            //'aoColumns': [null,{'bVisible': false}],
                            'oLanguage': {'sUrl': 'js/js_DataTables1.9.4/es_ES.txt'}

                        });
                        jQuery.fn.dataTableExt.oSort['fecha-asc']  = function(a,b) {
                                var ukDatea = a.split('/');
                                var ukDateb = b.split('/');

                                var x = (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
                                var y = (ukDateb[2] + ukDateb[1] + ukDateb[0]) * 1;

                                return ((x < y) ? -1 : ((x > y) ?  1 : 0));
                            };

                            jQuery.fn.dataTableExt.oSort['fecha-desc'] = function(a,b) {
                                var ukDatea = a.split('/');
                                var ukDateb = b.split('/');

                                var x = (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
                                var y = (ukDateb[2] + ukDateb[1] + ukDateb[0]) * 1;

                                return ((x < y) ? 1 : ((x > y) ?  -1 : 0));
                            };
        ");
        return $objResponse;
    }
    function member_details($iduser=0,$usertype=0){
        // $result = membersQuery($iduser);
        $html = "";
        if ($usertype==1) {
            $result = membersIGPQuery_01($iduser);
            if (isset($result)) {
                if ($result!=-100) {
                    $html .= "  <p class='res'><i class='icon-user'></i> ".$result["users_name"]."</p>";
                    $html .= "  <p class='res'><i class='icon-envelope'></i> ".$result["users_name"]."@igp.gob.pe</p>";
                }
                else{
                    $html.="Usuario no existe";
                }
            }
        }
        elseif ($usertype==2) {
            $result_1 = membersQuery_01($iduser);
            if (isset($result_1)) {
                if ($result_1!=-100) {
                    $html .= "  <p class='res'><i class='icon-user'></i> ".$result_1["users_name"]."</p>";
                    $html .= "  <p class='res'><i class='icon-envelope'></i> ".$result_1["email"]."</p>";
                }
                else{
                    $html.="Usuario no existe";
                }
            }
        }

        return $html;
    }
    function loanbook_details($idloan=0){
        $result = loanBookQuery($idloan);
        $html="";
        if (isset($result)) {
            if ($result["Count"]>0) {
                for ($i=0; $i < $result["Count"]; $i++) {
                    $div_details = $idloan.'_'.$i;
                    $book = book_xml_html($result["book_data"][$i]);
                    $html .="<p class='res'><i class='icon-book'></i>". $book["title"];
                    $html .= '<a href="#booDetails'.$idloan.'_'.$i.'" role="button" data-toggle="modal" class="inline">+ Detalle</a>';
                    $html .= '<div id="booDetails'.$idloan.'_'.$i.'" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 id="myModalLabel">Detalle de material bibliográfico</h3>
                          </div>
                          <div class="modal-body">

                            <div id="conte_details'.$div_details.'"></div>
                            <script> xajax_show_details('.$result["idbook"][$i].',"'.$div_details.'")</script>
                          </div>
                          <div class="modal-footer">
                            <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
                          </div>
                        </div></p>';
                }
            }
            else{
                $html.="Material bibliografico no existe";
            }
        }
        return $html;
    }
    function show_details_back($idbook=0){
        $objResponse = new xajaxResponse();
        // $objResponse->alert(print_r("hola",true));
        $objResponse->script("xajax_show_details(".$idbook."); return false;");
        return $objResponse;
    }
    function book_xml_html($book_xml){

        $xmlt = simplexml_load_string($book_xml);
        if (!$xmlt) {

            foreach(libxml_get_errors() as $error) {
                echo "\t", $error->message;
            }
            return "Error cargando XML \n";
        }
        $json = json_encode($xmlt);
        $data_array= json_decode($json,TRUE);
        // book details
        $html["details"]="";
        if (isset($data_array["title"])) {
            $html["title"] = " ".$data_array["title"]." ";
            $html["details"] .= $html["title"] ;
        }
        if (isset($data_array["tipo"])) {
            $html["details"] .= "<p class='res'>Tipo de material >> ".$data_array["tipo"]."</p>";
        }
        if (isset($data_array["FxReg"])) {
            $html["details"] .= "<p class='res'> Fecha registro >> ".$data_array["FxReg"]."</p>";
        }
        if (isset($data_array["NumDewey"])) {
            $html["details"] .= "<p class='res'> Dewey >>".$data_array["NumDewey"]."</p>";
        }
        if (isset($data_array["Resumen"])) {
            $html["details"] .= "<p class='res'> Fecha de ingreso >> ".$data_array["Resumen"]."</p>";
        }
        if (isset($data_array["FxIng"])) {
            $html["details"] .= "<p class='res'> Fecha de ingreso >> ".$data_array["FxIng"]."</p>";
        }
        if (isset($data_array["ModAdqui"])) {
            $html["details"] .= "<p class='res'> Modalidad de Adquisición >> ".$data_array["ModAdqui"]."</p>";
        }
        if (isset($data_array["UbicFis"])) {
            $html["details"] .= "<p class='res'> Modalidad de Adquisición >> ".$data_array["UbicFis"]."</p>";
        }
        if (isset($data_array["languaje"])) {
            $html["details"] .= "<p class='res'>Idioma >> ".$data_array["languaje"]."</p>";
        }

        if (isset($data_array["Serie"])) {
            $html["details"] .= "<p class='res'>Serie: >> ".$data_array["ax_files"]."</p>";
        }
        if (isset($data_array["ax_files"])) {
            $html["details"] .= "<p class='res'> >> ".$data_array["ax_files"]."</p>";
        }
        // $html = $title;
        return $html;
    }
    function cambiar_estado($idloan="",$state=""){
    	$objResponse = new xajaxResponse();
    	$result = loanBookQuery($idloan);
    	$c  = 0;
    	if (updateLoanState($idloan,$state)) {//update state in table loan
    		for ($i=0; $i < $result["Count"]; $i++) {
                // actualizar el estado del material bibliografico
    		    $data_book = update_book_state($result["book_data"][$i],$state);
    			if (UpdateBook($result["idbook"][$i],$data_book)) {
    			    $c +=1 ;
    			}
    		}
    	}
    	if ($c==$result["Count"]) {
    		$objResponse->alert(print_r("Actualizado correctamente",TRUE));
    		$objResponse->script("xajax_ListReserva()");
    	}
        else{
            $objResponse->alert(print_r("Ocurrió un error, intentalo nuevamente",TRUE));
        }
    	return $objResponse;
	}

	function update_book_state($book_data="",$state="") {
		$array_data = xmlToArray($book_data);
		$array_data["state"] = $state;
		$xml_data = arrayToXml($array_data,"book");
		return $xml_data;
	}

    function procesar_reserva($idloan="",$idbook="",$state="") {
        $objResponse = new xajaxResponse();
        if (updateLoanBookState($idloan,$idbook,$state)) {
            $objResponse->alert(print_r("Actualizado correctamente",TRUE));
            $objResponse->script("xajax_ListReserva()");
        }else{
            $objResponse->alert(print_r("Debió ocurrir un error",TRUE));
        }
        return $objResponse;
    }
    function delete_reserva($idbook=""){
        $objResponse = new xajaxResponse();
        if (updateBookState($idbook,0)) {
            $objResponse->alert(print_r(updateBookState($idbook),TRUE));
            $objResponse->script("xajax_ListReserva()");
        }else{
            $objResponse->alert(print_r("Debió ocurrir un error",TRUE));
        }

        return $objResponse;
    }

    function auxAuthorShow($pageSize="",$currentPage="",$iddiv="",$action="",$idauthor="",$catAuthor="",$typeAuthor=""){
    	$objResponse = new xajaxResponse();
    	// $result= searchAuthorPriResult($idSearch,$currentPage,$pageSize,$sAuthor,$idauthor);
    	// $result= xajax_auxAuthorPriShow(5,1,"");
    	if($idauthor!=""){
            //$respuesta->script("xajax_searchAuthorSesionPriShow(".$idauthor.")");
            // $objResponse->script("xajax_arrayAuthor()");
            if ($catAuthor=="AuthorPer") {

                if ($typeAuthor=="primary") {
                	if(isset($_SESSION["edit"]["authorPRI"])){
    		                //Limpiamos los valores de la sesión
    		            //unset($_SESSION["edit"]["authorPRI"]);
    		            $_SESSION["edit"]["authorPRI"][$idauthor]=1;
    		        }
    		        else{
    		                //Limpiamos los valores de la sesión
    		            // unset($_SESSION["tmp"]["authorPRI"]);
    		            $_SESSION["tmp"]["authorPRI"][$idauthor]=1;
    		        }
    		        // $_SESSION["autor"]=$idauthor;
    		        $html_pri=searchAuthorSesionPriShow_sinXajax($idauthor,$action,$catAuthor);
                	$objResponse->Assign("sesion_authorPRI","innerHTML",$html_pri);
                }
                elseif ($typeAuthor=="secundary") {
                	if(isset($_SESSION["edit"])){
    	                $_SESSION["edit"]["authorSEC"][$idauthor]=1;
    	            }
    	            else{
    	                $_SESSION["tmp"]["authorSEC"][$idauthor]=1;
    	            }
    	            $html_sec=searchAuthorSesionSecShow_sinXajax($idauthor,$action,$catAuthor);
    	            $objResponse->Assign("sesion_authorSEC","innerHTML",$html_sec);
                }

            }
            elseif ($catAuthor=="AuthorInst") {

                if ($typeAuthor=="primary") {
                	if(isset($_SESSION["edit"]["authorPRI"])){
    		                //Limpiamos los valores de la sesión
    		            unset($_SESSION["edit"]["authorPRI"]);
    		            $_SESSION["edit"]["authorPRI"][$idauthor]=1;
    		        }
    		        else{
    		                //Limpiamos los valores de la sesión
    		            unset($_SESSION["tmp"]["authorPRI"]);
    		            $_SESSION["tmp"]["authorPRI"][$idauthor]=1;
    		        }

    		        $_SESSION["autor"]=$idauthor;
    		        $html_pri=searchAuthorSesionPriShow_sinXajax($idauthor,$action,$catAuthor);
                	$objResponse->Assign("sesion_authorPRI_02","innerHTML",$html_pri);
                }
            	elseif ($typeAuthor=="secundary") {
                	if(isset($_SESSION["edit"])){
    	                $_SESSION["edit"]["authorSEC"][$idauthor]=1;
    	            }
    	            else{
    	                $_SESSION["tmp"]["authorSEC"][$idauthor]=1;
    	            }
    	            $html_sec=searchAuthorSesionSecShow_sinXajax($idauthor,$action,$catAuthor);
    	            $objResponse->Assign("sesion_authorSEC_02","innerHTML",$html_sec);
                }

            }
        }
    	$result= searchAuthorPriResult('AUTHOR',$currentPage,$pageSize,'',$catAuthor);
    	// $objResponse->alert(print_r($result, TRUE));
    	$idauthor = $result["idauthor"];
    	$author_name = $result["author_name"];
    	$author_surname =$result["author_surname"];
    	$author_type =$result["author_type"];

    	if ($catAuthor=="AuthorPer") {
    		$idtable="listtable_per";
            $html_label= "Apellidos y Nombres";
            $html_label_1= "Fechas asociadas";
    		$html_label_2= "Termino de relación";
    	}
    	elseif ($catAuthor=="AuthorInst") {
    		$idtable="listtable_inst";
            $html_label="Jurisdicción - Institución";
            $html_label_1="Unidad Subordinada";
    		$html_label_2="Afiliación";
    	}
    	else{
    		$idtable="listtable";
    		$html_label ="Apellidos y Nombres / Jurisdicción - Institución";
            $html_label_1="Fechas asociadas / Unidad Subordinada";
            $html_label_2="Termino de relación / Afiliación";
    	}

    	$html_search="
    		<input type='text' name='searchAuthor'/>
    		<input type='button' class='btn' value='Buscar'/>
    		";
    	$html= '
    			<a href="#" id="newAuthor_'.$catAuthor.'" class="openNewAuthor fright"><span class="icon-plus-sign"></span>Nuevo</a>
                <span style="display:none">'.$result["query_autor"].'</span>
    			<table id="'.$idtable.'" width="100%" class="listAuthor tablacebra-2" cellspacing="0" cellpadding="0" border="0" width="380px">
    			<thead>
    			<tr class="cab">
    				<th>N°</th>
                    <th>'.$html_label.'</th>
                    <th>'.$html_label_1.'</th>
    				<th>'.$html_label_2.'</th>';
    	if ($action=="int") {
    		$html.='<th>Principal</th>
    				<th>Secundario</th>';
    	}
    	$html.='<th>Editar</th>
    			<th>Eliminar</th>
    			</tr>
    			</thead>
    			<tbody>';

    	for($i=0;$i<$result["Count"];$i++){
    	    $nro=$i+1;
            $html.= "<tr class='impar'>";
    		$html.= "<td class='span1'>".$nro."</td>";

    		if(ereg("'",$author_surname[$i])){
    		    $apellido=explode("'",$author_surname[$i]);
    		    $antes_caracter=ucfirst($apellido[0]);
    		    $despues_caracter=ucfirst($apellido[1]);
    		    $apellido=$antes_caracter."'".$despues_caracter;
    				//cadena que se buscará en el array de sesion de autores
    				$apellidoArray=addslashes($antes_caracter."'".$despues_caracter);
    		}
    		else{
    		    $apellido=ucfirst($author_surname[$i]);
    		    $apellidoArray=$author_surname[$i];
    		}
    		//$html.= "<td>".ucfirst($author_surname[$i]).", ".ucfirst(substr($author_first_name[$i],0,2))."</td>";
            $html_date_1 = ($result["author_type"][$i]==0) ? $result["fx_asoc"][$i] : $result["unid_sub"][$i];
            $html_date_2 = ($result["author_type"][$i]==0) ? $result["tag_relation"][$i] : $result["affiliate"][$i];
            $surname = !empty($apellido)?$apellido.", ":"";
            $html.= "<td>
                        <input type='hidden' name='author_type[".$idauthor[$i]."]' value='".$author_type[$i]."'>
            			<input type='hidden' name='author_surname[".$idauthor[$i]."]' value='".$apellido."'>
                        <input type='hidden' name='author_name[".$idauthor[$i]."]' value='".$author_name[$i]."'>
                        ".$surname."
            		    ".$author_name[$i]."
                    </td>
                    <td>".$html_date_1."</td>
                    <td>".$html_date_2."</td>
                    ";
            if ($action=="int") {
           		$html.= "<td><a href=\"#formulario\"><img alt=\"autor primario\" onclick=\"xajax_auxAuthorShow($pageSize,$currentPage,'','int',".$idauthor[$i].",'".$catAuthor."','primary');return false;\" src=\"img/iconos/userPRI.png\" /></a></td>";
    			$html.= "<td>
    		            <a href=\"#formulario\"><img alt=\"autor secundario\" onclick=\"xajax_auxAuthorShow($pageSize,$currentPage,'','int',".$idauthor[$i].",'".$catAuthor."','secundary');return false;\" src=\"img/iconos/userSEC.png\" /></a>
    		            </td>";
            }
    		$html.= "<td class='span1'>
    					<a href=\"#formulario\" class='editAuthor' style=\"cursor: pointer;\"><img alt=\"editar autor\"  onclick=\"xajax_editAuthor(".$idauthor[$i].",'$apellidoArray','".$action."','".$catAuthor."'); return false;\" src=\"img/iconos/editar.gif\" />
    					</a>
    				 </td>";
    		$html.= "<td class='span1'>
    		      	 <a href=\"#formulario\" class='delAuthor' style=\"cursor: pointer;\">
    		      		<img alt=\"autor secundario\" style=\"cursor: pointer;border:0;\" onclick=\"xajax_deleteAuthor(".$idauthor[$i].",'".$action."','".$catAuthor."'); return false;\" src=\"img/iconos/incorrecto.png\" />
    		      	 </a>
    		       </td>";
    		$html.= "</tr>";
    	}
    	$html.= "</tbody>
    			</table>
    			<div id='eAuthor'></div>
    			<div id='dAuthor'></div>
    			<div id='divNewAuthor'></div>";


    	$objResponse->assign("imghome","style.display", "none");
    	//$objResponse->assign("option_category","style.display", "none");
    	// $objResponse->assign("formulario","style.display", "none");
        $objResponse->assign("ListReserva","style.display", "none");
    	$objResponse->assign("searchCat","style.display", "none");
    	$objResponse->assign("consultas","style.display", "none");
    	$objResponse->assign("resultSearch1","style.display", "none");
        $objResponse->assign("paginator","style.display", "none");
        $objResponse->assign("conte_details","style.display", "none");
    	$objResponse->assign("about_admin","style.display", "none");
    	// $objResponse->assign("author_section","style.display", "block");


    	if ($action=="int") {
    		if ($catAuthor=="AuthorPer") {
    			$objResponse->assign("author_section_int","innerHTML", $html);

    		}
    		elseif($catAuthor=="AuthorInst"){
    			$objResponse->assign("author_section_int_02","innerHTML", $html);
    		}

    	}
    	else{
    		$objResponse->assign("option_category","style.display", "none");
    		$objResponse->assign("formulario","style.display", "none");
    		$objResponse->assign("author_section","style.display", "block");
    		$objResponse->assign("author_section","innerHTML", $html);
    	}

    	$objResponse->script("
    					// $.fn.dataTableExt.oApi.fnPagingInfo = function ( oSettings )
    				 //      {
    				 //        return {
    				 //          'iStart':         oSettings._iDisplayStart,
    				 //          'iEnd':           oSettings.fnDisplayEnd(),
    				 //          'iLength':        oSettings._iDisplayLength,
    				 //          'iTotal':         oSettings.fnRecordsTotal(),
    				 //          'iFilteredTotal': oSettings.fnRecordsDisplay(),
    				 //          'iPage':          Math.ceil( oSettings._iDisplayStart / oSettings._iDisplayLength ),
    				 //          'iTotalPages':    Math.ceil( oSettings.fnRecordsDisplay() / oSettings._iDisplayLength )
    				 //        };
    				 //      };
                        $.fn.DataTable.ext.type.search.string = function ( data ) {
                                           return ! data ?
                                               '' :
                                               typeof data === 'string' ?
                                                   data
                                                       .replace(/\\n/g,' ')
                                                       .replace( /[áàâ]/g, 'a' )
                                                       .replace( /[éèê]/g, 'e' )
                                                       .replace( /[íìî]/g, 'i' )
                                                       .replace( /[óòô]/g, 'o' )
                                                       .replace( /[úùû]/g, 'u' ) :
                                                   data;
                        }

        				$('#".$idtable."').dataTable({
        					// 'fnDrawCallback': function () {
          					// alert( 'Pagina N: '+ this.fnPagingInfo().iTotal );
          					//},
          					// $.fn.dataTableExt.sErrMode = 'throw',
        					'bJQueryUI': true,
        					'bSort': false,
                            'bPaginate': true,
                            'sPaginationType': 'full_numbers',
                            'iDisplayLength': 10,
                            'aoColumnDefs': [
                                { 'sType': 'string', 'aTargets': [1] }
                            ],
                            // 'bLengthChange': false,
                            'oLanguage': {'sUrl': 'js/js_DataTables1.9.4/es_ES.txt'}

        				});


    	");
    	$objResponse->script("
    			$('#eAuthor, #dAuthor, #divNewAuthor').dialog({
    						autoOpen: false,
    						modal: true,
    						show: 'fade',
    						hide: 'fade',
    			            height: 'auto',
    			            width: 500
    					});
    			$('#eAuthor').dialog({title:'Editar Author'});
    			$('#dAuthor').dialog({title:'Eliminar Author'});
    			$('#divNewAuthor').dialog({title:'Nuevo Author'});

    			$('.editAuthor').click(function() {
    					$('#eAuthor').dialog('open');
    					return false;
    				});
    			$('.delAuthor').click(function() {
    					$('#dAuthor').dialog('open');
    					return false;
    				});
    			$('#newAuthor_".$catAuthor."').click(function() {
    					$('#divNewAuthor').dialog('open');
    					// var catAuthor = '".$catAuthor."';
    					// alert(catAuthor);
    					xajax_NewAuthor('".$catAuthor."','".$action."');
    					return false;
    				});
    			$('.listAuthor #listAuthor_length select').addClass('span2');

    	");

    	return $objResponse;
    }
    function editAuthor($idauthor="",$surname="",$action="",$catAuthor=""){
    	$objResponse = new xajaxResponse();
    	// 	$objResponse->alert(print_r($form, TRUE));
    	$html = "<form name='eFrmAuthor' id='eFrmAuthor'>
    			<label for='tAuthor'>Tipo </label>
    			<select name='editAuthor_type' class='NewtypeAuthor' id='tAuthor'>
    				<option value='0'>Personal</option>
    				<option value='1'>Institucional</option>
    			</select>
    			<input type='hidden' value='$idauthor' name='sidauthor' READONLY>
    			<label class='label_01' for='nAuthor'>Nombre: </label><input type='text' class='nameAuthor' name='nAuthor' id='nAuthor' value=''>
    			<label class='label_02' for='sAuthor'>Apellido: </label><input type='text' class='surnameAuthor' name='sAuthor' id='sAuthor'>
    			<div class='btnActions'>
                <input type=\"button\" value=\"Guardar\" class='btn'
                onclick=\"xajax_updateAuthor(xajax.getFormValues('eFrmAuthor'),$idauthor,'".$action."','".$catAuthor."')\">

                <input type=\"button\" value=\"Cancelar\" class=\"btnCancel btn\">
                </div>
                </form>";
    	$objResponse->assign("eAuthor","innerHTML","$html");
    	$objResponse->script("
    			$('.btnCancel').click(function(){
    					$('#eAuthor').dialog('close')
    				});
    			var val_name = $('input[name=\"author_name[$idauthor]\"]').val();
    	        var val_surname = $('input[name=\"author_surname[$idauthor]\"]').val();
    	        $('.nameAuthor').attr('value',val_name);
    	        $('.surnameAuthor').attr('value',val_surname);
    	        var val_type = $('input[name=\"author_type[$idauthor]\"]').val();
    	        $('.NewtypeAuthor option[value='+val_type+']').attr('selected',true);
    			if (val_type==0){
    				$('.label_01').html('Nombre: ');
    				$('.label_02').html('Apellido: ');
    			}
    			else{
    				$('.label_01').html('Institución: ');
    				$('.label_02').html('Pais: ');
    			}
    			$('.NewtypeAuthor').change(function(){
    				opt_sel = $(this).val();
    				if (opt_sel==0) {
    					$('.label_01').html('Nombre: ');
    					$('.label_02').html('Apellido: ');
    				}
    				else{
    					$('.label_01').html('Institución: ');
    					$('.label_02').html('Pais: ');
    				}
    			});
    			");
    	return $objResponse;
    }
    function updateAuthor($form,$id,$action="",$catAuthor=""){
    	$objResponse = new xajaxResponse();
    	$result = updateAuthor_sql($form);
    	if ($result["Error"]==0) {
    		$html.="<p class='msj'> El Author fue actualizado correctamente</p>";
    		$objResponse->script("xajax_auxAuthorShow(5000,1,\"$form\",'".$action."','','".$catAuthor."')");
    	}
    	else{
    		$html.="<p class='msjdel'> Debió ocurrir un error, intentalo mas tarde</p>";
    	}

    	$objResponse->assign("eAuthor","innerHTML","$html");
    	// $objResponse->alert(print_r($result["Query"], TRUE));
    	return $objResponse;
    }

    function deleteAuthor($idauthor="",$action="",$catAuthor=""){
    	$objResponse = new xajaxResponse();
    	$html="<p class='msj'>Está seguro que desea eliminar el Author.</p>
    	   <div class='btnActions'>
    	   	<input type='button' class='btn' value='Eliminar' onclick='xajax_ConfirmdeleteAuthor(".$idauthor.",\"".$action."\",\"".$catAuthor."\"); return false;'>
    	   	<input type='button' value='Cancelar' class='btn btnCancel'>
    	   </div>";

    	$objResponse->assign("dAuthor","innerHTML",$html);
    	$objResponse->script("$('.btnCancel').click(function(){
    				$('#dAuthor').dialog('close')
    			});");
    	return $objResponse;
    }

    function ConfirmdeleteAuthor($idauthor="",$action="",$catAuthor=""){
    	$objResponse = new xajaxResponse();
    	$result = deleteAuthor_sql($idauthor);
    	if ($result["Error"]==0) {
    		$html="<p class='msj'>Se ha eliminado el autor</p>";
    		$objResponse->script("xajax_auxAuthorShow(5000,1,\"$form\",'".$action."','','".$catAuthor."')");
    	}
    	else{
    		$html="<p class='msjdel'>No se ha podido eliminar el author</p>";
    	}
    	$objResponse->assign("dAuthor","innerHTML",$html);
    	return $objResponse;
    }

    function NewAuthor($catAuthor="",$action=""){
    	$objResponse= new xajaxResponse();

    	if ($action=="int") {
    		$value_t = $catAuthor=='AuthorPer' ? 0 : 1;
    		$html_type = "<input type='hidden' name='newAuthor_type' value='".$value_t."'>";
    	}
    	else{
    		$html_type = "<label for='tAuthor'>Tipo </label>
    		<select name='newAuthor_type' class='NewtypeAuthor' id='tAuthor'>
    			<option value='0'>Personal</option>
    			<option value='1'>Institucional</option>
    		</select>";
    	}
    	$html="<form name='nFrmAuthor' id='nFrmAuthor'>
    	".$html_type."
    	<label class='label_01' for='nAuthor'>Nombre: </label><input type='text' class='nameAuthor' name='author_name' id='author_name' value=''>
    	<label class='label_02' for='sAuthor'>Apellido: </label><input type='text' class='surnameAuthor' name='author_surname' id='author_surname'>
    	<div class='btnActions'>
              <input type=\"button\" value=\"Guardar\" class='btn'
              onclick=\"xajax_saveAuthor(xajax.getFormValues('nFrmAuthor'),'".$catAuthor."','".$action."')\">

              <input type=\"button\" value=\"Cancelar\" class=\"btnCancel btn\">
              </div>
              </form>";
    	$objResponse->assign("divNewAuthor","innerHTML",$html);
    	$objResponse->script('
    		if ("'.$catAuthor.'"=="AuthorPer"){
    			$(".label_01").html("Nombres: ");
    			$(".label_02").html("Apellidos: ");
    		}
    		else if ("'.$catAuthor.'"=="AuthorInst"){
    			$(".label_01").html("Institución: ");
    			$(".label_02").html("Pais: ");
    		}
    		$(".NewtypeAuthor").change(function(){
    			opt_sel = $(this).val();
    			if (opt_sel==0) {
    				$(".label_01").html("Nombres: ");
    				$(".label_02").html("Apellidos: ");
    			}
    			else{
    				$(".label_01").html("Institución: ");
    				$(".label_02").html("Pais: ");
    			}
    		});
    		$(".btnCancel").click(function(){
    				$("#divNewAuthor").dialog("close")
    			});
    	');
    	return $objResponse;
    		}
    function saveAuthor($form="",$catAuthor="",$action=""){
    		$objResponse= new xajaxResponse();
    		$result = registraAuthorSQL($form);

    		 if ($result["Error"]=="registrado") {
    		 	$html="<p class='msj'>Datos guardados correctamente";
    		  	$objResponse->script("xajax_auxAuthorShow(5000,1,\"$form\",'".$action."','','".$catAuthor."')");
    		  }
    		  else {
    		  	$html="<p class='msjdel'>No fue posible insertar el nuevo Author";
    		  }
    		//$objResponse->alert(print_r($formUM,TRUE));
    		$objResponse->assign("divNewAuthor","innerHTML",$html);
    		return $objResponse;
    }


    function carga_archivo($img_portada=""){
        $respuesta = new xajaxResponse();
        if(isset($_SESSION["edit"])){
        	$recuperar=$_SESSION["edit"];
        	if (count($recuperar["files"])==0) {
        		$_SESSION["edit"]["files"]= array();
        	}
    	}
    	elseif(isset($_SESSION["tmp"])){
    		$_SESSION["tmp"]["files"]= array();
    	    $recuperar=$_SESSION["tmp"];
    	}
    	// $dir = "librerias/ax-jquery-multiuploader/examples/uploaded/";
    	$dir = "files/uploaded/";
        // $respuesta->alert(print_r($_SESSION["edit"], TRUE));
        $html='
                <table class="options">
                <thead>
                        <tr>

                        </tr>
                </thead>
                <tbody>
                        <tr>
                                <td>';

                                if(isset($_SESSION["edit"])){
               						// 	if ($gestor = opendir('librerias/ax-jquery-multiuploader/examples/uploaded')) {
    							    //     $html.="<ul>";
    							    //     while (false !== ($arch = readdir($gestor))) {
    							    //            if ($arch != "." && $arch != "..") {
    							    //                    $html.="<li><a href=\"librerias/ax-jquery-multiuploader/examples/uploaded/".$arch."\" class=\"linkli\">".$arch."</a></li>\n";
    							    //            }
    							    //     }
    							    //     closedir($gestor);

    							    //     $html.="</ul>";
    							    // }
    							    // $respuesta->alert(print_r($_SESSION["edit"],TRUE));

    							    if (isset($recuperar["ax_files"])) {
    							    	$html .= "<div id='edit_files' style='width:500px'>
                                				<ul>

    	                            			<div id='myModal_1' class='modal hide fade' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
    												    <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>×</button>

    												  <div class='modal-body'>
    												    <img alt='Preview' src='$dir".$recuperar["ax_files"]."'/>
    												  </div>
    												  <div class='modal-footer'>
    												  	<span id='myModalLabel'>".$recuperar["ax_files"]."</span>
    												  </div>
    											</div>

    	                            	    		<li id='file_1'>
    	                            	    		<a role='button' data-toggle='modal' class='linkli' href='#myModal_1'  style='background-image: none; background-position: initial initial; background-repeat: initial initial;' >
    	                            	    			<img class='ax-preview' alt='Preview' src='$dir".$recuperar["ax_files"]."'/>
    	                            	    			".$recuperar["ax_files"]."
    	                            	    		</a>
    	                            	    		<input type='hidden' value='".$recuperar["ax_files"]."' name='ax_files[]' READONLY>
    	                            	    		<span>
    	                            	    			<a href='#' id='del-file' class='del-file' onclick='xajax_DeleteImg(\"".$recuperar["ax_files"]."\",\"1\")'>
    	                            	    				<i class='icon-trash'></i>
    	                            	    			</a>
    	                            	    		</span>
    	                            	    		</li>\n";

    	                            	$html .= '<div id="msj-del-file" title="Eliminar Imagen"> </div>';
    	                            	$html .= '</ul></div>';
    							    }
    							    else{
    							    	$html .= '<div id="up_files" style="width:500px"></div>';
    							    }
                                }
                                else{
                                    $html .= '<div id="up_files" style="width:500px"></div>';
                                }

        $html .='					</td>
                        </tr>
                </tbody>
                </table>
        ';


        $respuesta->assign("carga_archivo", "innerHTML", $html);
        $respuesta->script("
    							    	$(function(){
    							    		 $('#msj-del-file').dialog({
    											autoOpen: false,
    											width: 350,
    											modal: true
    											});
    							    		$('.del-file').click(function(){
    							    			$('#msj-del-file').dialog('open');
    							    		});
    							    	});

    							    	");

        $respuesta->script('
        		$("#up_files").ajaxupload({
    				//url:"librerias/ax-jquery-multiuploader/examples/upload.php",
    				url:"files/upload.php",
    					maxFiles: 1,
                        allowExt:["png","gif","jpg","jpeg"],
    					remotePath:"uploaded/",
                                finish:function(files)
                                    {
                                            alert("La imagen ha sido subido con exito");
                                            $(".ax-toolbar .ax-upload").addClass("hide");
                                            //var conteo=files.length
                                            //alert(files);
                                            $(".files_c").html("<input id=\'ax_files\' type=\'hidden\' name=\'ax_files[]\' value=\'"+files+"\'>");
                                            //xajax_lista_archivos();

                                        },
                                    success:function(fileName)
                                        {

                                            texto = fileName.split(".");
                                            name=texto[0];
                                            //alert(name);

                                            //$("#report").append("<p>"+fileName+" uploaded.</p>");
                                            //$("#report").append("<input class=\'filesupload\' type=\'text\' name=\'"+name+"\' id=\'"+name+"\' value=\'"+fileName+"\'></input>");
                                            xajax_save_files(fileName);
                                        }
    			});
                $("fieldset .ax-upload-all").addClass("hide");
    			$("#myLightbox").lightbox();

        ');

        return $respuesta;
    }


    function save_files($namefile=""){
        $respuesta = new xajaxResponse();

        if(isset($_SESSION["edit"])){

        	$recuperar=$_SESSION["edit"];
    	}
    	elseif(isset($_SESSION["tmp"])){

    	    $recuperar=$_SESSION["tmp"];
    	}

        $texto = explode('.',$namefile);
        $name=$texto[0];

        $str_name=(str_replace(" ","-",$name));


    	// $respuesta->alert(print_r($_SESSION["tmp"]["files"],TRUE));

        return $respuesta;
    }

    function delete_file($namefile=""){
        $respuesta = new xajaxResponse();

        $car_especiales = array(':','?','"','\\');
        $namefile = str_replace($car_especiales,'', $namefile);

        $dir="files/uploaded/";

        	if (is_file($dir.$namefile)) {
    	      if ( unlink($dir.$namefile) ){
    	        $respuesta->assign($namefile, "value", "");
    	        if (isset($_SESSION["temp"])) {
    	        	unset($_SESSION["tem"]["ax_files"]);
    	        }
    	        elseif (isset($_SESSION["edit"])) {
    	        	unset($_SESSION["edit"]["ax_files"]);
    	        }

    	        $respuesta->script("
    	        	control = $(\"input.ax-browse\");
    	        	control.replaceWith(control=control.clone(true));
    	        	$('.files_c').html('');
    	        	");
    	      }
         }
        $texto = explode('.',$namefile);
        $name=$texto[0];

        /*
         //Eliminar el input creado para el archivo cargado
         $respuesta->script("
            $('#$name').remove();
         ");
        */

        return $respuesta;
    }

    function DeleteImg($namefile="",$id=""){
        $objResponse = new xajaxResponse();
        $html = "<p class='msj'>Está seguro que desea eliminar el programa.</p>
    		   <div class='btnActions'>
    		   	<input type='button' value='Eliminar' onclick='xajax_ConfirmDeleteImg(\"".$namefile."\", \"".$id."\")' class='btn btnCancel'>
    		   	<input type='button' value='Cancelar' class='btn btnCancel'>
    		   </div>";

        $objResponse->assign("msj-del-file","innerHTML",$html);
    		$objResponse->script("
    					$('.btnCancel').click(function(){
    						$('#msj-del-file').dialog('close')
    					});
    		");

        return $objResponse;
    }

    function ConfirmDeleteImg($namefile="",$id=""){
    	$objResponse = new xajaxResponse();
    	$dir="librerias/ax-jquery-multiuploader/examples/uploaded/";

        if (is_file($dir.$namefile)) {
    	      if ( unlink($dir.$namefile) ){
    	        $objResponse->assign($namefile, "value", "");
    	        unset($_SESSION["edit"]["ax_files"]);
    	        //var_dump($_SESSION["edit"]["files"]);

    	        $objResponse->script("
    	        		$('#file_1').remove();
    	        	");

    	        if (count($_SESSION["edit"]["ax_files"]) ==0) {
    	        	unset($_SESSION["edit"]["ax_files"]);
    	         	$objResponse->script("xajax_carga_archivo()");
    				}
    	        // $objResponse->alert(print_r($_SESSION["edit"], true));
    	      }
         }

        else{
        	$objResponse->alert(print_r("El origin del archivo no existe, ingrese otra imagen", TRUE));
        	unset($_SESSION["edit"]["ax_files"]);
        	$objResponse->script("xajax_carga_archivo()");
        }
        return $objResponse;

    }


	/*******************************************************************
	Registrar las Funciones
	*******************************************************************/

	$xajax->registerFunction('newRegisterBiblio');
	$xajax->registerFunction('subArea');
	$xajax->registerFunction('registerSubAreas');
	$xajax->registerFunction('menuAAShow');
	$xajax->registerFunction('formCategoryShow');

    $xajax->registerFunction('formPonenciasShow');
	$xajax->registerFunction('arrayAuthor');
	$xajax->registerFunction('newPonencia');
	$xajax->registerFunction('displaydiv');

	$xajax->registerFunction('iniAuthorShow');
	$xajax->registerFunction('auxAuthorSecShow');
	// $xajax->registerFunction('searchAuthorPriResult');
	$xajax->registerFunction('searchAuthorPriShow');

	$xajax->registerFunction('iniAuthorPriShow');
	$xajax->registerFunction('iniAuthorSecShow');

	$xajax->registerFunction('paginatorShow');

	$xajax->registerFunction('registraReferenciaResult');

	$xajax->registerFunction('formAuthorShow');

	$xajax->registerFunction('registraAuthorResult');
	$xajax->registerFunction('registraAuthorShow');

	$xajax->registerFunction('inicio');
	$xajax->registerFunction('cerrarSesion');

	/*************Registrar Funciones de Autores****************/
	$xajax->registerFunction('searchAuthorSesionPriShow');
	$xajax->registerFunction('searchAuthorSesionPriResult');

	$xajax->registerFunction('searchAuthorSesionSecShow');
	$xajax->registerFunction('searchAuthorSesionSecResult');

	$xajax->registerFunction('delSearchAuthorSesionPriShow');
	$xajax->registerFunction('delSearchAuthorSesionPriResult');

	$xajax->registerFunction('delSearchAuthorSesionSecShow');
	$xajax->registerFunction('delSearchAuthorSesionSecResult');

	$xajax->registerFunction('searchAuthorPriShow');
	$xajax->registerFunction('searchAuthorPriResult');

	$xajax->registerFunction('searchAuthorSecShow');
	$xajax->registerFunction('searchAuthorSecResult');

	/*************Registrar Funciones de Autores****************/
	$xajax->registerFunction('menuShow');
	$xajax->registerFunction('formLoginShow');
	$xajax->registerFunction('verificaUsuarioShow');
    $xajax->registerFunction('mostrarBusquedaAutores');
    $xajax->registerFunction('ocultarBusquedaAutores');

    $xajax->registerFunction('click_checked');
    $xajax->registerFunction('carga_archivo');
    $xajax->registerFunction('save_files');
    $xajax->registerFunction('delete_file');
    $xajax->registerFunction('registerDescription_Physical');
    $xajax->registerFunction('editBook');
    $xajax->registerFunction('DeleteImg') ;
    $xajax->registerFunction('ConfirmDeleteImg');
    $xajax->registerFunction('iniThemes_Book');
    $xajax->registerFunction('ListCampos');
    $xajax->registerFunction('delCampos');
    $xajax->registerFunction('registerlanguaje');
    $xajax->registerFunction('AddInput');
    $xajax->registerFunction('delInput');
    $xajax->registerFunction('PubLanguaje');

    $xajax->registerFunction('auxAuthorShow');
    $xajax->registerFunction('editAuthor');
    $xajax->registerFunction('updateAuthor');
    $xajax->registerFunction('deleteAuthor');
    $xajax->registerFunction('ConfirmdeleteAuthor');
    $xajax->registerFunction('NewAuthor');
    $xajax->registerFunction('saveAuthor');
    $xajax->registerFunction('delBook');
    $xajax->registerFunction('ConfirmDelBook');
    $xajax->registerFunction('ListReserva');
    $xajax->registerFunction('procesar_reserva');
    $xajax->registerFunction('delete_reserva');
    $xajax->registerFunction('aboutAdmin');
    $xajax->registerFunction('editPassAdmin');

    $xajax->registerFunction('cambiar_estado');
    $xajax->registerFunction('show_details_back');
    $xajax->registerFunction('editDataSede');

	$xajax->processRequest();

	//Mostramos la pagina
	// require("adminView.php");
    $smarty = new Smarty;
    $smarty->assign("xajax",$xajax->printJavascript());
    if(isset($_GET["about"]) ){
        if ($_GET["about"]=="admin") {
            $smarty->display('tpl/about.tpl');
        }
            else{
            $smarty->display('admin.tpl');
        }
    }
    else{
        $smarty->display('admin.tpl');
    }

?>
