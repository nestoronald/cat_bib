<?php

	require("indexSearchSQL.php");

	function auxSearchShow($pageSize,$currentPage,$form="",$seccion="",$idarea=0,$idauthor=0){

		$respuesta = new xajaxResponse();
		// $respuesta->alert(print_r($form, TRUE));
		if ($form["search_option"]=="s_simple") {
			$form_get = "xajax.getFormValues('formSearch')";

			// $respuesta->Alert($form_get);
		}
		elseif($form["search_option"]=="s_advanced"){
			$form_get = "xajax.getFormValues('frm_search_ad')";

		}
		else{
			$form_get="''";

		}
		$result=searchBookSQL($form);
		$total=$result["Count"];
		$idarea =1;

		//$result=searchPublicationSQL("","",$_SESSION["idfrom"],"","",$idarea);
		//$respuesta->script("xajax_formConsultaShow(".$_SESSION["idfrom"].")");
		$respuesta->script("xajax_searchPublicationShow($form_get,'2','$currentPage','$pageSize','$idarea',$idauthor)");
		$respuesta->script("xajax_paginatorSearch($currentPage,$pageSize,$total,$form_get,$idarea);");
		//$respuesta->alert($_SESSION["idfrom"]);
        $respuesta->assign('paginator', 'style.display',"block");
        $respuesta->assign('author_section', 'style.display','none');
        $respuesta->assign('paginatorAuthor', 'style.display','none');
        $respuesta->assign('searchCat', 'style.display','none');
        $respuesta->assign('div-search-advanced', 'style.display','none');

         // $respuesta->alert(print_r($total,TRUE));

		return $respuesta;
	}

	function searchPublicationShow($form,$searchFrom=0, $currentPage="", $pageSize="", $idarea=0,$idauthor=0, $idbook=0,$theme_tag=""){
		$objResponse = new xajaxResponse();
		//$objResponse->alert(arrayToXml($form,"search"));
		// $objResponse->alert(print_r($_SESSION,TRUE));

		if($searchFrom==2){
			list($html,$total)=searchbook($form, $currentPage, $pageSize,$idauthor, $idbook,$theme_tag);
		}

		//temporalmente
		$objResponse->assign('formulario','style.display','none');
		$objResponse->assign('consultas','style.display','none');
		$objResponse->assign("imghome","style.display","none");
		$objResponse->assign("option_category","style.display","none");


		$objResponse->assign("resultSearch1","style.display","block");
		$objResponse->assign("resultSearch1","innerHTML",$html);
		$objResponse->script("

					$('#delRegisterBib').dialog({
						autoOpen: false,
						modal: true,
						show: 'fade',
						hide: 'fade',
			            height: 'auto',
			            width: 500
					});
					$('#delRegisterBib').dialog({title:'Eliminar registro Bibliográfico'});
					$('.del_register').click(function(){
						$('#delRegisterBib').dialog('open');
						return false;
					});
					");

		return $objResponse;
	}
	function searchbook($form, $currentPage='', $pageSize='', $idauthor=0,$idbook=0,$theme_tag=""){

		$result = searchBookSQL($form, $currentPage, $pageSize,$idauthor,$idbook,$theme_tag);
		$resultTotal = searchBookSQL($form, "", "",0,$idbook,$theme_tag);
		//$resultTotal=searchPublicationSQL($idcategory,$form,$idfrom,'','',$idarea,$tip_inf);
		$result_exact = search_exact($form,$result);

		$html = "<div class='clear'></div>";
		$sql = $result["Query"];
		if ($form["search_cat"]=='b_libros' or $form["a_category"]=='a_libros') {
			$ht["title"] = "Libros";
		}
		elseif ($form["search_cat"]=="b_tesis" or $form["a_category"]=='a_libros') {
			$ht["title"] = "Tesis";
		}
		elseif ($form["search_cat"]=='b_pub_periodica'  or $form["a_category"]=='a_pub_periodica') {
			$ht["title"] = "Publicaciones periódicas";
		}
		elseif ($form["search_cat"]=='b_mapas' or $form["a_category"]=='a_mapas') {
			$ht["title"] = "Mapas";
		}
		elseif ($form["search_cat"]=='b_otros' or $form["a_category"]=='a_otros') {
			$ht["title"] = "Otros materiales";
		}
		else{
			$ht["title"]="";
		}
		$i = 0;

		if($result["Count"]>0){
			$_SESSION["r_count"] = $result["Count"];
			foreach ($result["book_data"] as $xmldata){

				//libxml_use_internal_errors(true);
                // $xmldata = utf8_decode($xmldata);
                $xmldata = str_replace('&', '&amp;', $xmldata);
				$xmlt = simplexml_load_string($xmldata);
				if (!$xmlt) {

					foreach(libxml_get_errors() as $error) {
						echo "\t", $error->message;
					}
					return "Error cargando XML (searchPublication)\n";

				}
				   // origen
				   $f_evento= "xajax_show_details(".$result["idbook"][$i]."); return false;";
				   if ($_SESSION["origin"]=="back") {
				   		if ($_SESSION["users_sede"]==$result["sede"][$i]) {
					   		$f_evento= "xajax_editBook(".$result["idbook"][$i].",1); return false;";
					   	}
				   }

                   $titulo="<p><b>Título: </b>".ucfirst((string)$xmlt->title)."</p>";
                                // $author=ucfirst((string)$xmlt->authorPRI->author_surname0);
                   $idauthor= (int)$xmlt->authorPRI->idauthor0;

                   $result_author = searchAuthorID($idauthor);
                   if ($result_author["Count"]>0) {
                   		$author = "<p><b>Autor:</b> ".$result_author["author_surname"][0].",".$result_author["author_name"][0]."
                   					<input type='hidden' value=$idauthor  name='idauthor'/></p>";
                   }

                   if ($result_author["Error"]==1) {
                   	$author ="";
                   }
                   $resumen="";
                   if (isset($xmlt->Resumen)) {
                   	$resumen = (string)$xmlt->Resumen;
                   	$resumen = "<p class='res'>".substr($resumen, 0,400)."...</p>";
                   }
                   $UbicFis="";
                   if (isset($xmlt->NoteConte)) {
                   	$NoteConte = (string)$xmlt->NoteConte;
                   	$NoteConte = "<p class='res'>".substr($NoteConte, 0,400)."...</p>";
                   }
                   $UbicFis="";
                   if (isset($xmlt->UbicFis)) {
                   	$UbicFis = (string)$xmlt->UbicFis;
                   	$UbicFis = "<p class='res'>".$UbicFis."...</p>";
                   }
                   $Edition_html="";
                   if (isset($xmlt->Edition) and !empty($xmlt->Edition)) {
                   	$Edition = (array)$xmlt->Edition;
	                   	$h[0]="";$h[1]="";$h[2]="";
						foreach ($Edition as $key => $value) {
							if (is_array($value)) {
								foreach ($value as $key_1 => $value_1) {
									// $html_input =
									$h[$key_1] .= "<span class='$key'> $value_1 </span>";
								}
							}
							else{
								$h[0] .= "<span class='$key'> $value </span>";
							}
						}
						$Edition_html="<b>Publicación: </b>";
						for ($f=0; $f < count($h); $f++) {
							$Edition_html .= "<span>".$h[$f]."</span>" ;
						}
                   }
                   $state_html = "";
                   if (isset($xmlt->NumEjem)) {
                   		$NumEjem = (string)$xmlt->NumEjem;
                   		$state_html = ($NumEjem)." Ejemplares";
                   }
                   $ClassIGP="";
                   if (isset($xmlt->tipo)) {
                   		if ($xmlt->tipo=="tesis" and isset($xmlt->ClassIGP)) {
                   			$ClassIGP = "<p><b>Código IGP</b>: ".(string)$xmlt->ClassIGP."</p>";
                   		}
                   }
                   $NumDewey="";
                   if (isset($xmlt->NumDewey)) {
                   		$NumDewey = "<p><b>Código</b>: ".(string)$xmlt->NumDewey."</p>";
                   }

				$titulo="<a  href='#' onclick='$f_evento' class='resultado' >".$titulo."</a>";
				$del_order="";
				if ($_SESSION["origin"]=="back") {

				}
				if (isset($_SESSION["idfrom"])) {
					if ($_SESSION["users_sede"]==$result["sede"][$i]) {
					   	$del_order="<a class='del_register' href='#' onclick='xajax_delBook(".$result["idbook"][$i].",$currentPage); return false;' ><i class='icon-trash'></i>Eliminar</a>";
					}

				}
				else{
					$del_order="<a class='give' href='#' onclick='xajax_Reservation(".$result["idbook"][$i]."); return false;' ><i class='icon-plus'></i>Reservar</a>";
				}
				$ejem = "";$estado = "";
				if (isset($xmlt->state)) {
					$state = (array)$xmlt->state;
					$c=0;
					foreach ($state as $key => $value) {
						if ($value==1 or $value==2) {
							$c+=1;
						}
					}
					$dis = (count($state)-$c)>=1?" Disponible( ".(count($state)-$c)." )":" No disponible";
					$ejem = "<p class='res'>Nro. ejemplares: ".count($state)."</p>";
                   	$estado = "<p>".$dis."</p>";
				}

				$class_list ="";
				// if (($i+1)%2==0) {
				// 	$class_list="list_block";
				// 	}
				// else{
				// 	$class_list = "list_block_0";
				// }
				$class_list = (($i+1)%2==0) ? "list_block" : "list_block_0";

				$html.="<div class='resultado-busqueda ".$class_list."'>";
				$pag=($currentPage-1)*$pageSize+($i+1);
				$html.="<span class='span1 number'>
						<span class='list_number'>" .$pag.".</span>
						</span>
						<span class='span10'>

							".$titulo.$author.$Edition_html.$NumDewey.$ClassIGP.$ejem.$sql;
				$html.="</span>
						<span class='span1 state'>".$estado.$del_order."</span>
						<span class='msj-loan'></span>
						</div>
						<div class='clear'></div>";

			$i++;
			}
		}
		else{
			$html .= "<p>NO SE ENCONTRARON RESULTADOS <div class='none'>".$sql."</div> </p>";
		}

        //return array($html, $strModal2, $strAutor2,$md5iddata2,$count);

        if ($form["query_type"]=="title") {
        	$label = "Titulo";
        }
        elseif ($form["query_type"]=="author") {
        	$label = "Autor";
        }
        elseif ($form["query_type"]=="tema") {
        	$label = "Tema";
        }
        elseif ($form["query_type"]=="b_all") {
        	$label = "Todos los campos";
        }
        elseif ($form["query_type"]=="resp_pub") {
        	$label = "Responsable de la Publicación";
        }
        elseif ($form["query_type"]=="reg_geo") {
        	$label = "Región Geográfica";
        }
        elseif ($form["query_type"]=="univ") {
        	$label = "Universidad";
        }
        elseif ($form["query_type"]=="anio") {
        	$label = "Año";
        }

        if (trim($form["tituloSearch"])=="") {
        	$result_termino="<h2 class='txt-azul'>Catálogo en ".$ht["title"]."</h2> Todos los registros </br>";
        }
        else{
        	$result_termino = "<h2 class='txt-azul'>Catálogo en ".$ht["title"]."</h2>
        					Términos de Busqueda: <span class='label label-info'>".$label."<span class='label_1'>"
        					.trim($form["tituloSearch"])."</span></span></br>";
        }
        $html_conte ='
        		<div class="nav_page">
        		<a href="#" onclick="xajax_abstractShow(\'searchCat\'); xajax_abstractHide(\'consultas\'); xajax_abstractHide(\'paginator\');xajax_abstractHide(\'resultSearch1\'); return false;">Consulta</a> >> ';
        if ($form["search_option"]=="s_simple") {
        	$html_conte.='<a href="#" onclick="xajax_abstractShow(\'consultas\'); xajax_abstractShow(\'divformSearch\'); xajax_abstractHide(\'resultSearch\'); xajax_abstractHide(\'resultSearch1\'); xajax_abstractHide(\'paginator\'); ">'.$ht["title"].'</a> >>';
        	$html_conte .='<span class="fright"><a href="#" onclick="xajax_abstractShow(\'consultas\'); xajax_abstractShow(\'divformSearch\'); xajax_abstractHide(\'resultSearch\'); xajax_abstractHide(\'resultSearch1\'); xajax_abstractHide(\'paginator\'); "> << Retornar </a></span>';

        }
        elseif ($form["search_option"]=="s_advanced") {
        	$html_conte.='<a href="#" onclick="xajax_abstractShow(\'consultas\'); xajax_abstractHide(\'divformSearch\');xajax_abstractHide(\'resultSearch1\'); xajax_abstractHide(\'paginator\'); xajax_search_advanced(); return false;">Opciones Avanzadas</a> >>';
        	$html_conte .="<span class='fright'><a href='#' onclick='xajax_abstractShow(\"consultas\"); xajax_abstractHide(\"divformSearch\");xajax_abstractHide(\"resultSearch1\"); xajax_abstractHide(\"paginator\"); xajax_search_advanced(); return false;'> << Retornar </a></span>";
        }
		$html_conte .='
        		<span class="active">Resultados</span>
        		</div>
        		'.$html_back.'
        		<div class="title-result">
        		<span class="txt-azul">'.$result_termino.'</span>
        		<span class="txt-azul">Resultados ('.$resultTotal["Count"].') </span>
				</div>
			   <div id="count"></div><br>
			   <div id="delRegisterBib"></div>'.$html;

		return array($html_conte,$result["Count"]);
	}

	function languaje_details($val_idioma){
        $diccionary = file_get_contents("js/diccionary.json");
        $diccionary_a = json_decode($diccionary,TRUE);
        foreach ($diccionary_a["languaje"] as $id => $languaje) {
           if (eregi($id, $val_idioma)) {
              $html = $languaje;
           }
        }
        $html = (trim($html)!="")?$html:$val_idioma;
        return $html;
    }

	function show_details($idbook=0,$div_details=""){
        $objResponse   = new xajaxResponse();
        // $objResponse->alert(print_r($_SESSION["r_count"],TRUE));
        $result=searchPublication_iddataSQL($idbook);

        $diccionary = file_get_contents("js/diccionary.json");
        $diccionary_a = json_decode($diccionary,TRUE);

        $data_array = xmlToArray($result["book_data"][0]);

        $data_oculta = ["tipo","NumIng","FxIng","FxReg","NumLC","Class_IGP","Catalogador","ModAdqui","NumDewey","UbicFis","NumEjem"];
        $html_details="<dl class='dl-horizontal'>"; $html_img="";
        foreach ($diccionary_a["campos"] as $key => $value) {
            if (isset($data_array[$key])) {

                if (!in_array($key, $data_oculta)) {
                	// si el valor es tipo array
                    if (is_array($data_array[$key])) {
                        if($key=="authorPRI") {
                           $idauthor = $data_array["authorPRI"]["idauthor0"];
                           $result_author = searchAuthorID($idauthor);
                           $html_details .= "<dt><b>".$value."<span class='t-right'>:</span></b> </dt>
					     <dd>
                                                <a href='#' title='Verifique si el author tiene mas registro bibliográficos' id='author_details'>
                                                ".$result_author["author_surname"][0].", ".$result_author["author_name"][0]."
                                                <input type='hidden' value=$idauthor  name='idauthor'/>
                                                </a>
                                            </dd>";
                        }
                        elseif ($key=="state") {
                            foreach ($data_array[$key] as $id_state => $state) {
                                // $html_state[$id_state] = ($state==100 and ($state!=1 or $state!=2))?"Disponible":"No disponible";
                                if ($state==1 or $state==2) {
                                    $html_state[$id_state]="No Disponible";
                                }else{
                                    $html_state[$id_state] =  "Disponible";
                                }
                                // $html_state[$id_state] = ($state==1 or $state==2)?"No Disponible":"Disponible";
                            }
                        }

                        else{
                            $html_details .= "<dt><b>".$value."<span class='t-right'> :</span> </b> </dt> <dd>";
                            $h=1;$j=1;$k=1;
                            foreach ($data_array[$key] as $key1 => $value1) {
                                if ($key=="languaje") {
                                    $html_details .= languaje_details($data_array[$key][$key1]);
                                    $html_details .=(count($data_array[$key])>$h)?", ":"";
                                    $h++;
                                }
                                elseif ($key=="NoteGeneral") {
                                	$html_details .= $data_array[$key][$key1]."</br>";
                                	//$html_details .=(count($data_array[$key])>$k)?" <br>":"";
                                	$k++;
                                }

                                elseif ($key =="Theme") {

                                        foreach ($data_array[$key][$key1] as $key2 => $value2) {
                                            if ($key2=="detalle") {
                                                //$html_details .= $data_array[$key][$key1][$key2]."</br> ";
                                                /*$html_details .=(count($data_array[$key][$key1])>$j)?", ".count($data_array[$key][$key1])." ":"";*/
                                                // if (count($data_array[$key][$key1])>$j) {
                                                //    $html_details .= ", ";
                                                // }
                                                if (query_themes_related($data_array[$key][$key1][$key2],$idbook)){
                                                	$html_details .="<a href='#' title='Registros bibliográficos con el mismo tema' class='tag_theme'>
                                                				<input type='hidden' value='".$data_array[$key][$key1][$key2]."'  name='tagtheme' />";
                                                	$html_details .= $data_array[$key][$key1][$key2]."   </a><br>";
                                                }
                                                else{
                                                	$html_details .= $data_array[$key][$key1][$key2]."  <br>";
                                                }
                                                $j++;
                                            }
                                        }

                                }
                                else{
                                    $html_details .= $data_array[$key][$key1]." ";
                                }



                            }
                            $html_details .="</dd>";
                        }

                    }
                    else{

                        if ($key=="state") {
                            $html_state_1 = ($data_array[$key]==1 or $data_array[$key]==2)?"No Disponible":"Disponible";
                        }
                        elseif ($key=="languaje") {
                            $html_details .= "<dt><b>".$value."<span class='t-right'> :</span></b> </dt> <dd>";
                            $html_details .= languaje_details($data_array[$key]);
                            $html_details .= " </dd>";
                        }
                        elseif ($key=="ax_files") {
                            $nombre_fichero = "files/uploaded/".$data_array[$key];
                            if (file_exists($nombre_fichero)) {
                                $html_img ="<div id='d-img' class='cell'><img src='".$nombre_fichero."' /></div>";
                            }
                        }

                        else{
                            $html_details .= "<dt><b>".$value." <span class='t-right'>:</span></b> </dt> <dd> ";
                            $html_details .= $data_array[$key];
                            $html_details .= " </dd>";
                        }

                    }
                }
            }
        }
		$html_details .= "</dl>";
        if (trim($div_details)!="") {
        	$divConte = "divDetails_back";
        	$btn = "";
        	$title_class ="give";
        }
        else{
        	$divConte = "divDetails";
        	$btn = "<span class='fright'><a href='#' onclick='xajax_abstractShow(\"consultas\"); xajax_abstractShow(\"divformSearch\"); xajax_abstractHide(\"conte_details\"); xajax_abstractHide(\"resultSearch1\"); xajax_abstractHide(\"paginator\"); '> << </a></span>";
        	$btn_reserva = "<div class='plusReserva'><a onclick='xajax_Reservation(".$idbook."); return false;' href='#' class='reseva'><i class='icon-plus'></i>Reservar</a></div>";
        	$title_class = "";
        }

        $html = "<div class='$divConte'>
                    <span id='type'><h3 class='$title_class'>".$data_array["tipo"]."</h3></span>
         			".$btn."
                    <h3 class='txt-azul'> ".$data_array["title"]."</h3>
                    <div clas='d-conte'>
                        <div id='d-info' class='cell'>".$html_details."</div>
                        ".$html_img."
                    </div>
                    <table class='table table-striped table-bordered'>
                        <thead>
                            <tr>
                                <th>N° de Clasificación Dewey</th>
                                <th>Localización</th>
                                <th>Ejemplares </th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>";
        $state_int = "<tr>
                        <td>".$data_array["NumDewey"]."</td>
                        <td>".$data_array["UbicFis"]."</td>
                        <td>1</td>
                        <td>".$html_state_1."</td>
                    </tr>";
        if (isset($data_array["NumEjem"])) {
            if (is_array($data_array["state"])) {
                for ($i=0; $i < $data_array["NumEjem"]; $i++) {
                    $html .="<tr>
                            <td>".$data_array["NumDewey"]." - Ejm".($i+1)."</td>
                            <td>".$data_array["UbicFis"]."</td>
                            <td>1</td>
                            <td>".$html_state[$i]."</td>
                        </tr>";
                }
            }
            else{
                $html .=$state_int;
            }
        }
        else{
            $html .=$state_int;
        }

        $html .= "
                        <tbody>
                    </table>
                    ".$btn_reserva."
                   <!--div class='nav'>";

        $html .=" </div>";

        $objResponse->assign('paginator','style.display','none');
        $objResponse->assign('resultSearch1','style.display','none');

        if (trim($div_details)!="") {
        	$objResponse->assign('conte_details'.$div_details,'style.display','block');
        	$objResponse->assign("conte_details".$div_details, "innerHTML", $html);
        }
        else{
        	$objResponse->assign('conte_details','style.display','block');
        	$objResponse->assign("conte_details", "innerHTML", $html);

        	if (isset($idauthor)) {
        		$resultAuthor = searchBookSQL($form, $currentPage, $pageSize,$idauthor);
        	    if ($resultAuthor["Count"]>1) {

        	        $objResponse->script('
        	                $("#author_details").click(function() {
        	                    xajax_searchPublicationShow(xajax.getFormValues(\'formSearch\'),\'2\',\'1\',\'20\',\'0\','.$idauthor.','.$idbook.');
        	                    $("#conte_details").html("");
        	                }).append("<span class=\'count\'> (+'.($resultAuthor["Count"]-1).')</span>")
        	                .attr("title","Busque mas registros bibliográficos de este author");
        	            ');
        	    }
        	    else{
        	        $objResponse->script('$("#author_details").attr("title","Este author no tiene mas registros bibliograficos")');
        	    }
        	}
        }

        $objResponse->script('
                            $(".tag_theme").click(function() {
        						var theme = $(this).find("input").val();
        						xajax_searchPublicationShow(xajax.getFormValues(\'formSearch\'),\'2\',\'1\',\'20\',\'0\',\'0\','.$idbook.',theme);
        	                	$("#conte_details").html("");
        	            });
                            $(".block_2").each(function(index){
                                a=$(this).parents("p").height();
                                if (a>80) {
                                     a=a+21;
                                     if (a>138) {
                                         a=a+41;
                                     }
                                 }
                                $(this).css("min-height",a+"px")
                            });

                            ');
        return $objResponse;
    }
	function listReservation(){
		$objResponse = new xajaxResponse();
		if (isset($_SESSION["reserva"])) {
        // seleccionar los libros alamacenados en session por id
			$href = isset($_SESSION["iduser"])?"reservation.php":"login.php";
			$count = count($_SESSION["reserva"]["idbook"]);
        	// $html="<a href='#' title='Despliega para ver la lista de reservas'>Reservas ($count)</a> <a href='#' title='Elimina la Lista de reserva'><i class='icon-trash'></i></a>";
            foreach ($_SESSION["reserva"]["idbook"] as $key => $value) {
                $result=searchPublication_iddataSQL($value);
                $xmlt = simplexml_load_string($result["book_data"][0]);
                $json = json_encode($xmlt);
                $data_array = json_decode($json,TRUE);
                $html .= "<p><span>".($key+1).") </span>".$data_array["title"]."</p>";
            }

            $html.="<div class='actionbtn'><a href='".$href."' class='btn' title='Click aqui para completar su reserva'>Procesar Reserva<a>";
            $html.="<span><a href='#' class='btn' onclick='xajax_deleteReserva(); ' title='Elimina la Lista de reserva'><i class='icon-trash'></i></a> </span>
            		</div>";
            $objResponse->assign("conte_reserva","style.display","block");
        }
        else{
            $html ="<p>Usted no tiene ninguna reserva</p>";
        }
        $count = isset($_SESSION["reserva"])?count($_SESSION["reserva"]["idbook"]):0;
		$objResponse->assign("DivReserva","style.display","block");
		$objResponse->assign("DivReserva","innerHTML",$html);
		$objResponse->assign("ReservaCount","innerHTML",$count);
		$objResponse->script("$('html, body').animate({scrollTop: $('#DivReserva').offset().top + -50}, 1000);");
		// $objResponse->script("alert('hola mundo')");
		return $objResponse;
	}
	function deleteReserva(){
		$objResponse = new xajaxResponse();
		if (isset($_SESSION["reserva"])) {
			unset($_SESSION["reserva"]);
			$objResponse->script("xajax_listReservation();");
		}
		return $objResponse;
	}
	// Reserva frond
	function Reservation($idbook=0){
		$objResponse = new xajaxResponse();
		$href = isset($_SESSION["iduser"])?"reservation.php":"login.php";
		$title='<div class="block">
                    <h3>Lista de reserva</h3>
                    <div id="listbook"></div>
                    <a href='.$href.' title="Click aqui para procesar su reserva" class="btn" style="display:inline-block">Reservar</a>
                </div>';

        // $diccionary = file_get_contents("js/diccionary.json");
        // $diccionary_a = json_decode($diccionary,TRUE);
        $r = verificar_estado_book($idbook);
        // $objResponse->alert(print_r(verificar_estado_book($idbook),true));
        if (verificar_estado_book($idbook)!=-100 and verificar_estado_book($idbook) > 0) {
        	if (isset($_SESSION["reserva"])) {
        	        	// if ($_SESSION["reserva"]["count"]<2) {
        	    $count_res = array_count_values($_SESSION["reserva"]["idbook"]);

        	    if (count($_SESSION["reserva"]["idbook"])<2) {

        	     	// if ($count_res[$idbook]==0)
        	     	if ($_SESSION["reserva"]["idbook"][0]!= $idbook) {
        				array_push($_SESSION["reserva"]["idbook"], $idbook);
        				// $objResponse->alert(print_r($_SESSION["reserva"],true));
        	        }
        	        else{
        	        	        // $html="";
        	        	$objResponse->script('alert("El libro ya fue reservado");');
        	        }
        	    }
        	    else{
        	        	    // $html="";
        	        $objResponse->script('alert("Solo se puede reservar dos libros");window.close();');
        	    }
        	}
        	else{
        	    // $_SESSION["reserva"]["count"] = 1;
        	    $_SESSION["reserva"]["idbook"][0] = $idbook;
        	}
        }
        else{
        	$objResponse->alert(print_r("No hay ejemplares disponibles de este material",true));
        }
        // $objResponse->alert(print_r($_SESSION,true));
        $objResponse->script("xajax_listReservation();");

		return $objResponse;
	}
	function verificar_estado_book($idbook=0){
		// $mmm = book_query($idbook);
		//cont: cantida de ejemplares disponibles
		if (book_query($idbook)!=-100) {
			$result = book_query($idbook);
			$array_data =xmlToArray($result["book_data"]);
			$cont=0;
			if (is_array($array_data["state"])) {
				$cont = 0;
				foreach ($array_data["state"] as $key => $value) {
					if ($value!=1 and $value!=2 ) {
						$cont +=1;
					}
				}
			}
			else{
				if ($array_data["state"]!=1 and $array_data["state"]!=2 ) {
					$cont =1;
				}
				else{
					$cont =0;
				}
			}
			return $cont;
		}
		else{
			return -100;
		}

	}

	function FormReservar($idbook=0){
		$objResponse = new xajaxResponse();
		$book_reserva="";
		if (isset($_SESSION["reserva"]["idbook"])) {
			if (is_array($_SESSION["reserva"]["idbook"])) {
				foreach ($_SESSION["reserva"]["idbook"] as $key => $value) {
					$book_reserva .= "<input type='hidden' name='idbook[]' value='$value'>";
				}
			}
		}
		$html='<hr class="space">

			  <form action="process_login.php" method="post" name="login_form" class="form-horizontal" name="frmReserva">
			   '.$book_reserva.'
			   <label for="usuario">Usuario</label>
			   <input type="text" id="user" name="user">
			   <label for="clave">Clave</label>
			   <input type="password" id="password" name="password">
			   <button type="button" onclick="formhash(this.form, this.form.password);">Ingresar</button>
			   <span><a href="register.php">Registrarse</a></span>
			  </form>
			';
		$objResponse->append("listbook","innerHTML",$html);
		$objResponse->assign("conte_details","style.display","none");
		$objResponse->assign("resultSearch1","style.display","none");
		$objResponse->assign("paginator","style.display","none");

		$objResponse->script("
					$('.btnReserva').click(function(){
						xajax_confirmReserva(xajax.getFormValues(frmReserva));
 	                    return false;
					})
					$('#DivReserva a.btn').css('display','none')

					");

		return $objResponse;
	}
	function confirmReserva($form){
		$objResponse = new xajaxResponse();

		$result = insertloan($form);
		if ($result["Error"]==100) {
			$selected_loan = loanQuery("last");

			if ($selected_loan["Error"]==100) {
				foreach ($form["idbook"] as $key => $value) {
					$result_01[$key] = insertLoanBook($selected_loan["id_loan"][0],$value);
					updateBookState($value,1);
				}

				if ((isset($result_01[1]["Error"]) and $result_01[1]["Error"]==100) or (isset($result_01[0]["Error"]) and $result_01[0]["Error"]==100) ) {

					$html="<span class='success'><i class='icon-ok'></i>Su pedido ha sido procesado correctamente</span>";
					// $objResponse->alert(print_r($_SESSION,true));
					$result_mail = send_mail_prepare($_SESSION["user_id"],$_SESSION["reserva"]);
					if ($result_mail["user"]="enviado" or $result_mail["admin"]="enviado") {
						$objResponse->alert(print_r("Se le ha enviado un correo con los detalles de su reserva.",true));
					}
					else{
						$objResponse->alert(print_r("Hubo problemas en enviar el correo",true));
					}

					unset($_SESSION["reserva"]);
				}
					// $html="<span class='success'><i class='icon-ok'></i>Su pedido ha sido procesado correctamente</span>";
					// unset($_SESSION["reserva"]);
				else{
					$html="Ocurrió un problema inesperado, intente tarde ";
				}
			}
		}
		else{
			$html="Ocurrió un problema inesperado, intente mas tarde";
		}
		$objResponse->assign("listbook","innerHTML",$html);
		// $objResponse->Assign($div,"style.display","block");
		return $objResponse;
	}


	function send_mail_prepare($iduser, $data_loan) {
		$response["user"]="";$response["admin"]="";
		$email_admin="ronald4261@gmail.com";
		$book_html="";
		foreach ($data_loan["idbook"] as $key => $value) {
            $result=searchPublication_iddataSQL($value);
            $xmlt = simplexml_load_string($result["book_data"][0]);
            $json = json_encode($xmlt);
            $data_array = json_decode($json,TRUE);
            $book_html.= "<p><span>".($key+1).") </span>".$data_array["title"]."</p>";
         }

         if (membersIGPQuery_01($iduser)!=-100 and $_SESSION["usertype"]==1) {
         	$result = membersIGPQuery_01($iduser);
         	$subject = "Biblioteca IGP - haz realizado una reserva";
         	$message = "<p>Estimado usuario ".$result["users_name"].",</p> <p>Usted ha realizado una una reserva de los siguientes materialies bibliograficos:</p> ";
         	$message .=$book_html;
         	if (sendMail($result["email"],$subject,$message)) {
         		$response["user"] = "enviado";
         	}
         }
         elseif (membersQuery_01($iduser)!=-100) {
         	$result = membersQuery_01($iduser);
         	$subject = "Biblioteca IGP - haz realizado una reserva";
         	$message = "<p>Estimado usuario ".$result["users_name"].",</p> <p>Usted ha realizado una reserva de los siguientes materialies bibliograficos:</p> ";
         	$message .=$book_html;
         	if (sendMail($result["email"],$subject,$message)) {
         		$response["user"] = "enviado";
         	}
         }
         if ($email_admin) {
         	$subject = "Biblioteca IGP- El usuario ".$result["users_name"].", ha realizado una reserva";
         	$message = "<p>Se ha realizado una nueva reserva:</p> ";
         	$message .= "<p>Usuario:".$result["users_name"].",</p> ";
         	$message .= "<p>Materialies bibliograficos:</p> ";
         	$message .=$book_html;

         	if (sendMail($email_admin,$subject,$message)) {
         		$response["admin"] = "enviado";
         	}
         }

        return $response;
	}

	function sendMail($email,$subject,$message){
		$header = 'MIME-Version: 1.0' . "\r\n";
		$header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	    $header .= 'From: biblioteca@igp.gob.pe' . "\r\n";

	    if (mail($email, $subject, $message, $header)) {
	        return true;
	    }
	    else{
	        return false;
	    }

	}
	///--category catalog
	function onclick_category(){
        $objResponse = new xajaxResponse();
        $objResponse->script('
                        $(".searchSelect .span5").click(function(){
                            if ($(this).attr("id")=="b_libros"){
                                id = "b_libros";
                            }
                            else if($(this).attr("id")=="b_pub_periodica"){
                                id = "b_pub_periodica";
                            }
                            else if($(this).attr("id")=="b_mapas"){
                                id = "b_mapas";
                            }
                            else if($(this).attr("id")=="b_tesis"){
                                id = "b_tesis";
                            }
                            else if($(this).attr("id")=="b_otros"){
                                id = "b_otros";
                            }
                            else{
                                id = "";
                            }
                            xajax_formConsultaShow("","admin","",id,"'.$action.'");
                            //xajax_auxSearchShow(20,1,"");

                        }).tooltip({
                            trigger:"hover",
                            placement:"top"
                        });

                        ');
        return $objResponse;
    }
	function searchCategory(){
			$objResponse= new xajaxResponse();
	        $diccionary = file_get_contents("js/diccionary.json");
	        $diccionary_a = json_decode($diccionary,TRUE);
	        //$_SESSION["reserva"]["idbook"][0] = -1;
	        // unset($_SESSION["reserva"]);
	        $li ="";
	        foreach ($diccionary_a["categoria"] as $key => $value) {
	            $li .= "<li><a href='#".$key."' id='".$key."' class='span5' title='Busque en $value'><span>".$value."</span></a></li>";
	        }
			$html ="
				<div class='nav_page'>
					<span>Consulta</span>
				</div>
		    	<div class='span7 searchSelect'>
		    	<h2 class='center txt-azul'>Seleccione un catálogo</h2>
		    	<ul>".$li."</ul>
				</div>
		    	";
		    $objResponse->Assign("formulario","style.display","none");
	  		$objResponse->assign('paginator', 'style.display',"none");
	        $objResponse->assign("imghome", "style.display", "none");
	        $objResponse->assign("option_category", "style.display", "none");
	        $objResponse->assign("consultas", "style.display", "none");
	        $objResponse->assign("author_section", "style.display", "none");
	        $objResponse->assign("paginatorAuthor", "style.display", "none");
	        $objResponse->assign("conte_details", "style.display", "none");
            $objResponse->assign("ListReserva","style.display","none");
	        $objResponse->assign("about_admin","style.display","none");
	            //tempor...
	     	$objResponse->assign("resultSearch1", "style.display", "none");
	        $objResponse->assign("searchCat","style.display","block");
			$objResponse->assign("searchCat","innerHTML",$html);

	        $objResponse->script("xajax_onclick_category()");

			return $objResponse;

	}

	function formConsultaShow($idbutton,$seccion="",$idarea=0,$id=""){
		$objResponse = new xajaxResponse();
		if(isset($_SESSION["edit"])){
		    unset($_SESSION["edit"]);
            unset($_SESSION["editar"]);
		    unset($_SESSION["publicaciones"]);
		}

		if ($id=='b_libros') {
			$html["title"] = "Libros";
			$form = array('author'=>'Autor','tema'=>'Tema');
		}
		if ($id=="b_tesis") {
			$html["title"] = "Tesis";
			$form = array('author'=>'Autor','tema'=>'Tema','univ'=>'Universidad');
		}
		if ($id=='b_pub_periodica') {
			$html["title"] = "Publicaciones periódicas";
			$form = array('resp_pub'=>'Responsable de la publicación','tema'=>'Tema');
		}
		if ($id=='b_mapas') {
			$html["title"] = "Mapas";
			$form = array('author'=>'Autor','reg_geo'=>'Región geográfica');
		}
		if ($id=='b_otros') {
			$html["title"] = "Otros materiales";
			$form = array('author'=>'Autor','anio'=>'Año','tema'=>'Tema');
		}
		$j=1;
		$html_check = "";
		foreach ($form as $key => $value) {
			$html_check .= '<label class="checkbox inline">
					<input type="radio" id="query_type_'.$j.'" name="query_type" value="'.$key.'"> '.$value.'
					</label>';
			$j++;
		}

		$html='

			<div id="divformSearch">
			<div id="submenu_bar" class="nav_page">

			</div>
			<div class="span7 offset3">

			<h2 class="txt-azul">'.$html["title"].'</h2>
			<form id="formSearch">'.$formArea.'
					<p>Buscar por los siguientes criterios:</p>
					<input type="hidden" id="search_cat" name="search_cat" value="'.$id.'" >
					<input type="hidden" id="search_option0" name="search_option" value="s_simple" >
				    <label class="checkbox inline">
					  <input type="radio" id="query_type_1" name="query_type" value="title" checked> Título
					</label>';
		$html .=	$html_check.'
					<label class="checkbox inline">
					  <input type="radio" id="query_type_3" name="query_type" value="b_all" >Todos los campos
					</label>

				<div class="clear"></div>
				<div>
                    <div id="div_tituloSearch" class="contenedor-caja-buscador-1">
                        <input id="tituloSearch" name="tituloSearch" type="text" size="30" autocomplete="off" class="caja-buscador-1">
                    </div>
				</div>
                <button id="btn-search">Buscar</button>
                <button id="btn-clear">Limpiar</button>

				<div class="clear"></div>
				<div id="msj_query_type">
					<span>(Puede omitir las tildes y/o mayúsculas)</span>
					<span id="search-advanced"><a href="#search-advanced"  onclick="xajax_search_advanced(); return false;" title="Clik aquí para una búsqueda avanzada">Búsqueda avanzada</a></span>
				</div>
				<div id="moreOptions"></div>
			</form>
			</div>
			<div class="span3"></div>
			</div>

			<div id="div-search-advanced"></div>

			<!--div id="resultSearch" style="display: none;"></div-->
		';
	    $objResponse->Assign("consultas",'style.display',"block");
	    $objResponse->Assign("consultas","innerHTML",$html);
	    $objResponse->Assign("formulario","style.display","none");
	    $objResponse->Assign("searchCat","style.display","none");
	    $objResponse->assign("conte_details","style.display","none");
	    $objResponse->assign("resultSearch1","style.display","none");
	    //tempor...
	    // $objResponse->Assign("resultSearch1","style.display","block");
            $objResponse->assign('paginator', 'style.display',"none");
            $objResponse->assign("imghome", "style.display", "none");
            $objResponse->assign("option_category", "style.display", "none");
            $objResponse->assign("author_section", "style.display", "none");
            $objResponse->assign("paginatorAuthor", "style.display", "none");

        $data_sugerencia = file_get_contents("js/susgerencias.json");
		$sugerencia_a=json_decode($data_sugerencia,true);
		$sug = "[";

		foreach ($sugerencia_a["libro"]["title"] as $key => $value) {
			$sug .="'$value',";
		}
		$sug = substr($sug, 0,-1);
		$sug .= "]";
		// $objResponse->alert(print_r( $sug, TRUE));

	    //$objResponse->assign('botonGuardarEditar', 'innerHTML',$comboYear);
	    $objResponse->script('
            xajax_submenu_items("'.$id.'");
	    		function typesugerencias(cat,subcat){
	    			var sugerencias = new Array();
			    	$.getJSON("js/susgerencias.json", function(datos) {
		                $.each(datos[cat][subcat], function(idx,item) {
		                	sugerencias.push(item);
		                });
		            });
		            return sugerencias;
	    		}
	    		var subcat= $("input[name=query_type]").val();

	    		sug = typesugerencias("'.$id.'",subcat);
	    		$("input[name=query_type]").change(function(){

	    			var subcat= $(this).val();
	    			var sug = typesugerencias("'.$id.'",subcat);
	    			$("#tituloSearch").typeahead({source: sug});

	    		});
	    		$("#btn-search").button({
                    icons: {
                        primary: "ui-icon-search"
                    }
                }).
	    		click(function() {
                    xajax_auxSearchShow(20,1,xajax.getFormValues(formSearch),"","");
                    return false;
	    		});

	    		$("#btn-clear").button()
	    		.click(function(){
	    			$("#tituloSearch").val("").focus();
	    			return false;
	    		});
	    		$("#search-advanced").tooltip({
	    			trigger:"hover",
	    			placement:"bottom"});

		');
		return $objResponse;
	}

	function submenu_items($type){
	    $objResponse = new xajaxResponse();
	    $diccionary = file_get_contents("js/diccionary.json");
	    $diccionary_a = json_decode($diccionary,TRUE);
	    $item="";
	    if (isset($type)) {
	        foreach ($diccionary_a["categoria"] as $key => $value) {
	            if ($key!=$type) {
	                $item.="<li><a id='".$key."' onclick='xajax_formConsultaShow(\"\",\"admin\",\"\",\"$key\"); return false;' href='#".$key."'>".$value."</a></li>";
	            }
	        }
	    }

	    $html = '<div class="nav_page">
	                <a href="#Catalogo-busqueda" onclick="xajax_abstractShow(\'searchCat\'); xajax_abstractHide(\'consultas\'); xajax_abstractHide(\'paginator\');xajax_abstractHide(\'resultSearch1\');">Consulta</a> >>
	                <span class="realtive dropdown">
	                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
	                    '.$diccionary_a["categoria"][$type].'
	                    <b class="caret"></b>
	                    </a>
	                    <ul class="dropdown-menu">'.$item.'
	                    </ul></span>
	                    <span class="fright"><a onclick="xajax_abstractShow(\'searchCat\'); xajax_abstractHide(\'consultas\'); xajax_abstractHide(\'paginator\');xajax_abstractHide(\'resultSearch1\');" href="#Catalogo-busqueda"> << Volver a Catálogo</a></span>
	            </div>';
	    $objResponse->assign("submenu_bar","innerHTML",$html);
	    return $objResponse;
	}
	function search_advanced(){
    	$objResponse = new xajaxResponse();
    	$str_country = file_get_contents("./js/country.json");
    	$country = json_decode($str_country,true);

    	$html ='
    		<div class="nav_page">
    				<a href="#" onclick="xajax_abstractShow(\'searchCat\'); xajax_abstractHide(\'consultas\'); xajax_abstractHide(\'paginator\');xajax_abstractHide(\'resultSearch1\');">Consulta</a> >>
    				<span>Busqueda Avanzadas</span>
    				<span class="fright"><a onclick="xajax_abstractShow(\'divformSearch\'); xajax_abstractHide(\'paginator\');xajax_abstractHide(\'div-search-advanced\'); " href="#Catalogo-busqueda"> << Búsqueda simple </a></span>
    		</div>

    		<div class="block_1">
    		<h2>Búsqueda Avanzada</h2>
    		<p>Por favor rellene las casillas, seleccione los límites, y haga click en BUSCAR</p>

    		<form class="form-horizontal" name="frm_search_ad" id="frm_search_ad">
    		<div class="control-group">
    		<input type="hidden" id="search_option1" name="search_option" value="s_advanced" >
    		<label class="control-label" for="a_category">Buscar en</label>
    		 <div class="controls">
    		  <select name="a_category" class="span2">
    			<option value="a_libros">Libros</option>
    			<option value="a_tesis">Tesis</option>
    			<option value="a_mapas">Mapas</option>
    			<option value="a_pub_periodica">Publicaciones</option>
    			<option value="a_otros">Otros</option>
    		  </select>
    		 </div>
    		</div>
    		<div class="control-group">
    			<select name="a_fields_01" class="span2">
    				<option value="a_all">Todos los campos</option>
    				<option value="a_titulo">Título</option>
    				<option value="a_author">Author</option>
    				<option value="a_tema">Tema</option>
    				<option value="a_editor">Editor</option>
    			</select>
    			<input type="text" name="a_text1">
    			<select name="a_oper_1" class="span2">
    				<option value="a_oper">Operadores</option>
    				<option value="a_and">Y</option>
    				<option value="a_or">OR</option>
    				<option value="a_not">NO</option>
    			</select>
    		</div>
    		<div class="control-group">
    			<select name="a_fields_02" class="span2">
    				<option value="a_all">Todos los campos</option>
    				<option value="a_titulo">Título</option>
    				<option value="a_author">Author</option>
    				<option value="a_tema">Tema</option>
    				<option value="a_editor">Editor</option>
    			</select>
    			<input type="text" name="a_text2">
    			<select name="a_oper_2" class="span2">
    				<option value="a_oper">Operadores</option>
    				<option value="a_and">Y</option>
    				<option value="a_or">OR</option>
    				<option value="a_not">NO</option>
    			</select>
    		</div>
    		<div class="control-group">
    			<select name="a_fields_03" class="span2">
    				<option value="a_all">Todos los campos</option>
    				<option value="a_titulo">Título</option>
    				<option value="a_author">Author</option>
    				<option value="a_tema">Tema</option>
    				<option value="a_editor">Editor</option>
    			</select>
    			<input type="text" name="a_text3">
    		</div>
    		<div class="control-group">
    			<label class="control-label" for="a_languaje">Idioma</label>
    			   <div class="controls">
    				<select name="a_languaje" id="a_languaje" class="span2">
    					<option value="l_all">Cualquiera</option>
    					<option value="l_esp">Español</option>
    					<option value="l_eng">Ingles</option>
    					<option value="l_fr">Frances</option>
    				</select>
    		      </div>
    		</div>
    		<div class="control-group">
    			<label class="control-label" for="a_country">Pais</label>
    			  <div class="controls">
    				<select name="a_country" id="a_country" class="span2">
    					<option value="PE" selected >Perú</option>';
    	foreach ($country as $key => $value) {
    		$html .="<option value='$key'>$value</option>";
    	}
    	$html .='
    				</select>
    		      </div>
    		</div>
    		<div class="control-group">
    		<label for="year" class="control-label"> Publicado entre los años: </label>';
    	$year_present = date('Y'); 	$html_year_des=""; $html_year_asc ="";
    	for ($i=1950; $i < ($year_present+1); $i++) {
    		$html_year_asc .="<option value='$i'>$i</option>";
    	}
    	for ($i=$year_present; $i > 1949; $i--) {
    		$html_year_des .="<option value='$i'>$i</option>";
    	}
    	$html .='
    			<div class="controls">
    			<select name="a_year_asc" id="a_year_asc" class="len1"> '.$html_year_asc.'</select> Y
    			<select name="a_year_desc" id="a_year_desc" class="len1">'.$html_year_des.'</select>
    			</div>

    		</div>
    		<span class="" separation=""></span>
    		<div class="control-group">
    		  <label class="control-label" for="a_list">Ver en Lista</label>
    		    <div class="controls">
    				<select name="a_list" id="a_list" class="span2">
    					<option value="10">10</option>
    					<option value="15">25</option>
    					<option value="20">30</option>
    				</select>
    		    </div>
    		</div>
    		</form>
    		<div class="control-group">
    		<input type="button" id="btn_a_search" class="btn btn-search"  value="Buscar">
    		<input type="button" id="btn_a_clear" class="btn" value="Limpiar"></div>
    		<div class="control-group"><a href="#Catalogo-busqueda" onclick="xajax_abstractShow(\'searchCat\'); xajax_abstractHide(\'consultas\'); xajax_abstractHide(\'paginator\'); ">Busqueda simple</a></div>
    		<span class="separator"></span>
    	 </div>';
    		$objResponse->assign("divformSearch","style.display","none");
    		$objResponse->assign("author_section","style.display","none");
    		$objResponse->assign("div-search-advanced","style.display","block");
    		$objResponse->assign("div-search-advanced","innerHTML","$html");
    		$objResponse->script('
    			$("#btn_a_search").click(function() {
    				var text1 = $("input[name=\'a_text1\']").val().trim();
    				var text2 = $("input[name=\'a_text2\']").val().trim();
    				var text3 = $("input[name=\'a_text3\']").val().trim();
    				var oper1 = $("select[name=\'a_oper_1\']").val().trim();
    				var oper2 = $("select[name=\'a_oper_2\']").val().trim();
    				//l_text1 = text1.length;
    				//alert(l_text1);
    				if (text1=="") {
    					// alert("Debe Ingresar el primer texto");
    					if (text2=="") {
    						if (text3=="") {
    							alert("Los campos no deben estar vacios");
    							$("input[name=\'a_text1\']").focus();
    							return false;
    						}
    						else{
    							alert("Debe Ingresar el primer texto y segundo");
    							return false;
    						}
    					}
    					else{
    						alert("Debe Ingresar el primer texto ");
    						return false;
    					}
    				}
    				else{
    					if (oper1=="a_oper") {
    						alert("Debe seleccionar el primer operador AND u OR");
    					}
    					else{
    						if (text2=="") {
    							alert("Debe ingresar el segundo texto");
    						}
    						else{
    							if (oper2=="a_oper") {
    								if (text3=="") {
    									xajax_auxSearchShow(20,1,xajax.getFormValues(frm_search_ad));
    								}
    								else{
    									alert("Debe seleccionar el segundo operador AND u OR ")
    								}
    							}
    							else{
    								if (text3=="") {
    									alert("Seleccionó el segundo operador entonces debe insertar el tercer campo ")
    								}
    								else{
    									xajax_auxSearchShow(20,1,xajax.getFormValues(frm_search_ad));
    								}
    							}
    						}
    					}

    				}
    	    	});'
    		);

    		return $objResponse;
    }

	function delBook($idbook,$currentPage){
		$objResponse = new xajaxResponse();
		$html="<p class='msj'>Está seguro que desea eliminar el registro bibliográfico.</p>
	   <div class='btnActions'>
	   	<input type='button' class='btn' value='Eliminar' onclick='xajax_ConfirmDelBook($idbook,$currentPage)'>
	   	<input type='button' value='Cancelar' class='btn btnCancel'>
	   </div>";
		$objResponse->assign("delRegisterBib","innerHTML",$html);
		$objResponse->script("$('.btnCancel').click(function(){
				$('#delRegisterBib').dialog('close')
			});");
		return $objResponse;
	}

	function ConfirmDelBook($idbook,$currentPage)	{
		$objResponse = new xajaxResponse();
		$result = delRegisterBook($idbook);
		// $objResponse->alert(print_r($result["Query"], TRUE));
		if ($result["Error"]==0) {
			$html="<p class='msj'>Se ha eliminado el Registro Bibliográfico</p>";
			$objResponse->script("xajax_auxSearchShow(20,$currentPage,'Array');");
		}
		else{
			$html="<p class='msjdel'>No se ha podido eliminar el Registro Bibliográfico</p>";
		}
		$objResponse->assign("delRegisterBib","innerHTML", $html);
		return $objResponse;
	}
	function search_exact($form,$result){
		foreach ($result["book_data"] as $key => $value) {
			$count = 0;
			$xmlt = simplexml_load_string($value);
				if (!$xmlt) {
					foreach(libxml_get_errors() as $error) {
						echo "\t", $error->message;
					}
					return "Error cargando XML (searchPublication)\n";
				}
			$json = json_encode($xmlt);
  			$array = json_decode($json,TRUE);
  			$titulo = explode(" ", $array["title"]);
  			foreach ($titulo as $palabra) {
  			 	if ($palabra==$form["tituloSearch"]) {
  			 		$count = 1;
  			 	}
  			 }
  			if ($count==0) {
  				unset($result["book_data"][$key]);
  			}
		}
		return $result;
	}

	function formReset(){
		$objResponse = new xajaxResponse();
                $objResponse->script('document.getElementById(\'formSearch\').reset()');
		return $objResponse;
	}

	function abstractShow($div){
		$objResponse = new xajaxResponse();
		$objResponse->Assign($div,"style.display","block");
		// 	$objResponse->Assign('searchCat',"style.display","block");
		return $objResponse;
	}

	function abstractHide($div){
		$objResponse = new xajaxResponse();
		$objResponse->Assign($div,"style.display","none");
		return $objResponse;
	}

	function paginatorConstruct($page,$ipp,$total,$form="",$idarea=0){
		$pages = new Paginator;
		$pages->items_total = $total;
		$pages->mid_range = 9;

		$function="xajax_auxSearchShow";
		if ($form["search_option"]=="s_simple") {
			$form_get = "xajax.getFormValues('formSearch')";
			// $respuesta->Alert($form_get);
		}
		elseif($form["search_option"]=="s_advanced"){
			$form_get = "xajax.getFormValues('frm_search_ad')";
		}
		else{
			$form_get="''";
		}

		// if($form==""){
		// 	$pages->paginate($ipp,$page,$function,$idarea);
		// }
		// else{
			//$respuesta->alert($idarea);//0
			$pages->paginateSearch($ipp,$page,$form_get,$function,$idarea);
		// }

		$html="<p>";
		$html.= $pages->display_pages();
		//$html.= "<span class=\"\">".$pages->display_items_per_page($function)."</span>";
		$html.= "</p>";
		return $html;
	}


	function paginatorSearch($page,$ipp,$total,$form="",$idarea=0){
		$respuesta = new xajaxResponse();
		$html=paginatorConstruct($page,$ipp,$total,$form,$idarea);
		$respuesta->assign("paginator","innerHTML",$html);
		return $respuesta;
	}
	/******************************************/
	function arrayToXml($array,$lastkey='root'){

		$buffer="";
	    $buffer.="<".$lastkey.">";
	    if (!is_array($array)){
			$buffer.=$array;}
	    else{
	        foreach($array as $key=>$value){
	            if (is_array($value)){
	                if ( is_numeric(key($value))){
	                    foreach($value as $bkey=>$bvalue){
	                        $buffer.=arrayToXml($bvalue,$key);
	                    }
	                }
	                else{
	                    $buffer.=arrayToXml($value,$key);
	                }
	            }
	            else{
	                    $buffer.=arrayToXml($value,$key);
	            }
	        }
	    }
	    $buffer.="</".$lastkey.">\n";
	    return $buffer;
	}
	function xmlToArray($xml=""){
		$xmlt = simplexml_load_string($xml);
		if (!$xmlt) {

		    foreach(libxml_get_errors() as $error) {
		        echo "\t", $error->message;
		    }
		    return "Error cargando XML \n";
		}
		$json = json_encode($xmlt);
		$array= json_decode($json,TRUE);
		return $array;
	}

	/******* Busqueda**********/

	$xajax->registerFunction('pasaValor');
	$xajax->registerFunction('comboCategoryShow');
	$xajax->registerFunction('comboAreaShow');

	$xajax->registerFunction('comboReferenciaAutorShow');

	$xajax->registerFunction('paginatorSearch');
	$xajax->registerFunction('auxSearchShow');
	$xajax->registerFunction('formConsultaShow');
	$xajax->registerFunction('abstractHide');
	$xajax->registerFunction('abstractShow');
    $xajax->registerFunction('formReset');
	$xajax->registerFunction('searchPublicationShow');
	$xajax->registerFunction('searchPublication');
	$xajax->registerFunction('comboEstadoShow');
	$xajax->registerFunction('comboTipoPublicacionShow');
	$xajax->registerFunction('comboTipoFechasShow');
	$xajax->registerFunction('comboReferenciaShow');
	$xajax->registerFunction('comboMonthShow');
	$xajax->registerFunction('comboYearShow');
	$xajax->registerFunction('comboRegionShow');
	$xajax->registerFunction('comboDepartamentoShow');

	$xajax->registerFunction('searchCategory');
	$xajax->registerFunction('onclick_category');
	$xajax->registerFunction('formConsultaShow');
	$xajax->registerFunction('submenu_items');
	$xajax->registerFunction('search_advanced');
	$xajax->registerFunction('Reservation');
	$xajax->registerFunction('FormReservar');
	$xajax->registerFunction('confirmReserva');
	$xajax->registerFunction('listReservation');
	$xajax->registerFunction('deleteReserva');
	$xajax->registerFunction('show_details');

	$xajax->registerFunction('editPass');
	$xajax->registerFunction('editMyprofile');
	$xajax->registerFunction('editMyProfile_frm');
	$xajax->registerFunction('details_member');
?>