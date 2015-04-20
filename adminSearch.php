<?php

function editBook($idbook=0,$currentPage){

	$objResponse = new xajaxResponse();
	$result=searchPublication_iddataSQL($idbook);
		// $idCategory=$result["idcategory"][0];

	if ($_SESSION["users_sede"]==$result["sede"][0]) {
		$idCategory=2;

		if(isset($_SESSION["edit"])){
		    unset($_SESSION["edit"]);
		}

        $_SESSION["edit"]["idbook"]=$idbook;

		if($result["Count"]==1){



            if (isset($_SESSION["publicaciones"]["authorPRI"])){
                unset($_SESSION["publicaciones"]["authorPRI"]);
            }

            if (isset($_SESSION["publicaciones"]["authorSEC"])){
                unset($_SESSION["publicaciones"]["authorSEC"]);
            }

			if(isset($_SESSION["edit"]["authorPRI"])){
		        //Limpiamos los valores de la sesión
		        unset($_SESSION["edit"]["authorPRI"]);
			}

			if(isset($_SESSION["edit"]["authorSEC"])){
		        //Limpiamos los valores de la sesión
		        unset($_SESSION["edit"]["authorSEC"]);
			}

			foreach ($result["book_data"] as $xmldata){
                $xmldata = str_replace('&', '&amp;', $xmldata);
				$xmlt = simplexml_load_string($xmldata);

		        if (isset($xmlt->title)) {
		        	$title = (string)$xmlt->title;
		        }

		        $date_ing=(string)$xmlt->date_ing;

                $desc_month_pub=(string)$xmlt->desc_month_pub;

                $idfbook=(string)$xmlt->idfbook;
                $fbook_descripcion=(string)$xmlt->formatbook;
                if (isset($xmlt->files)) {
                	// $_SESSION["edit"]["files"]=array();
                	//$img_portada = $xmlt->files;
     				// $json_files = json_encode($xmlt->files);
					// $array_files = json_decode($json,TRUE);
					$array_files = (array)$xmlt->files;
					// $objResponse->alert(print_r($array_files,TRUE));
                }

                if (isset($xmlt->ax_files)) {
		        	$ax_files = (string)$xmlt->ax_files;
		        }
		        if (isset($xmlt->tipo)) {
		        	$TypeMatBib = (string)$xmlt->tipo;
		        }
		        else{
		        	$TypeMatBib = "Otro material";
		        }
		        if (isset($xmlt->FxReg)) {
		        	$FxReg = (string)$xmlt->FxReg;
		        }
		        if (isset($xmlt->ISBN)) {
		        	$ISBN = (string)$xmlt->ISBN;
		        }
		        if (isset($xmlt->ISSN)) {
		        	$ISSN = (string)$xmlt->ISSN;
		        }
		        if (isset($xmlt->Edition)) {
		        	$Edition = (array)$xmlt->Edition;
					foreach ($Edition as $key => $value) {
		        		if (empty($value)) {
		        			$Edition[$key] = (string)$xmlt->Edition->$key;
		        		}
		        		if (is_array($value)) {
		        			foreach ($value as $key1 => $value1) {
		        				if (empty($value1)) {
		        					$Edition[$key][$key1] = (string)$value1;
		        				}
		        			}
		        		}
		        	}
		        }
		        if (isset($xmlt->numEdition)) {
		        	$numEdition = (string)$xmlt->numEdition;
		        }

		        if (isset($xmlt->Resumen)) {
		        	$Resumen = (string)$xmlt->Resumen;
		        }
		        if (isset($xmlt->Description)) {
		        	// $Description = (string)$xmlt->Description;
		        	$Description = (array)$xmlt->Description;
					foreach ($Description as $key => $value) {
		        		if (empty($value)) {
		        			$Description[$key] = (string)$xmlt->Description->$key;
		        		}
		        	}
		        }
		        if (isset($xmlt->Theme)) {
		        	$Theme = (array)$xmlt->Theme;

		        	foreach ($Theme as $key => $value) {
		        	 	$Theme[$key] =(array)$xmlt->Theme->$key;
                        foreach ($Theme[$key] as $key1 => $value1) {
                            if (empty($value1)) {
                                unset($Theme[$key][$key1]);
                            }
                        }
		        	}
		        }
		        if (isset($xmlt->FxIng)) {
		        	$FxIng = (string)$xmlt->FxIng;
		        }
		        if (isset($xmlt->UbicFis)) {
		        	$UbicFis = (string)$xmlt->UbicFis;
		        }
		        if (isset($xmlt->NumReg)) {
		        	$NumReg = (string)$xmlt->NumReg;
		        }
                if (isset($xmlt->languaje)) {
                	$languaje = (array)$xmlt->languaje;
					foreach ($languaje as $key => $value) {
		        		if (empty($value)) {
		        			unset($languaje[$key]);
		        		}
		        	}
                }
                if (isset($xmlt->NumLC)) {
                	$NumLC = (array)$xmlt->NumLC;
					foreach ($NumLC as $key => $value) {
		        		if (empty($value)) {
		        			unset($NumLC[$key]);
		        		}
		        	}
                }
                if (isset($xmlt->NumDewey)) {
                	$NumDewey = (string)$xmlt->NumDewey;
                }
                if (isset($xmlt->Class_IGP)) {
                	$Class_IGP = (string)$xmlt->Class_IGP;
                }
                if (isset($xmlt->EncMat)) {
                	$EncMat = (array)$xmlt->EncMat;
					foreach ($EncMat as $key => $value) {
		        		if (empty($value)) {
		        			unset($EncMat[$key]);
		        		}
		        	}
                }
                if (isset($xmlt->OtherTitles)) {
                	$OtherTitles = (array)$xmlt->OtherTitles;
					foreach ($OtherTitles as $key => $value) {
		        		if (empty($value)) {
		        			unset($OtherTitles[$key]);
		        		}
		        	}
                }
                if (isset($xmlt->Periodicidad)) {
                	$Periodicidad = (string)$xmlt->Periodicidad;
                }
                if (isset($xmlt->Serie)) {
                	$Serie = (array)$xmlt->Serie;
					$Serie = (array)$xmlt->Serie;
                	foreach ($Serie as $key => $value) {
		        		if (empty($value)) {
		        			unset($Serie[$key]);
		        		}
		        	}
                }
                if (isset($xmlt->NoteGeneral)) {
                	$NoteGeneral = (array)$xmlt->NoteGeneral;
					foreach ($NoteGeneral as $key => $value) {
		        		if (empty($value)) {
		        			unset($NoteGeneral[$key]);
		        		}
		        	}
                }
                if (isset($xmlt->NoteTesis)) {
                	$NoteTesis = (string)$xmlt->NoteTesis;
                }
                if (isset($xmlt->NoteBiblio)) {
                	$NoteBiblio = (string)$xmlt->NoteBiblio;
                }
                if (isset($xmlt->NoteConte)) {
                	$NoteConte = (string)$xmlt->NoteConte;
                }
                if (isset($xmlt->DesPersonal)) {
                	$DesPersonal = (array)$xmlt->DesPersonal;
					foreach ($DesPersonal as $key => $value) {
		        		if (empty($value)) {
		        			unset($DesPersonal[$key]);
		        		}
		        	}
                }
                if (isset($xmlt->MatEntidad)) {
                	$MatEntidad = (array)$xmlt->MatEntidad;
					foreach ($MatEntidad as $key => $value) {
		        		if (empty($value)) {
		        			unset($MatEntidad[$key]);
		        		}
		        	}
                }
                if (isset($xmlt->Descriptor)) {
                	$Descriptor = (array)$xmlt->Descriptor;
					foreach ($Descriptor as $key => $value) {
		        		if (empty($value)) {
		        			unset($Descriptor[$key]);
		        		}
		        	}
                }
                if (isset($xmlt->Descriptor_geo)) {
                	$Descriptor_geo = (array)$xmlt->Descriptor_geo;
					foreach ($Descriptor_geo as $key => $value) {
		        		if (empty($value)) {
		        			unset($Descriptor_geo[$key]);
		        		}
		       		}
                }
                if (isset($xmlt->CongSec)) {
                	$CongSec = (string)$xmlt->CongSec;
                }
                if (isset($xmlt->TitSec)) {
                	$TitSec = (array)$xmlt->TitSec;
                }
                if (isset($xmlt->Fuente)) {
                	$Fuente = (array)$xmlt->Fuente;
					foreach ($Fuente as $key => $value) {
		        		if (empty($value)) {
		        			unset($Fuente[$key]);
		        		}
		        	}
                }
                if (isset($xmlt->NumIng)) {
                	$NumIng = (array)$xmlt->NumIng;
					foreach ($NumIng as $key => $value) {
		        		if (empty($value)) {
		        			unset($NumIng[$key]);
		        		}
		        	}
                }
                if (isset($xmlt->UbicElect)) {
                	$UbicElect = (string)$xmlt->UbicElect;
                }
                if (isset($xmlt->ModAdqui)) {
                	$ModAdqui = (string)$xmlt->ModAdqui;
                }
                if (isset($xmlt->Catalogador)) {
                	$Catalogador = (string)$xmlt->Catalogador;
                }
                if (isset($xmlt->NumEjem)) {
                	$NumEjem = (string)$xmlt->NumEjem;
                }
                if (isset($xmlt->state)) {
	        		$state = (array)$xmlt->state;
					foreach ($state as $key => $value) {
		        		if (empty($value)) {
		        			unset($state[$key]);
		        		}
		        	}
	       		}
		        if (isset($xmlt->temas)) {
		        	$temas_recovery = (string)$xmlt->temas;
		        }

		        //----------
		        // $autorPRI=(string)$xmlt->authorPRI->idauthor0;
                $autorPRI="";
                if(isset($xmlt->authorPRI)){
                        //Preguntamos si hay mas de un autor secundario
                    if(isset($xmlt->authorPRI->idauthor1)){
                            for($j=0;$j<100;$j++){
                                    eval('if (isset($xmlt->authorPRI->idauthor'.$j.')){$xmlflag=TRUE;} else {$xmlflag=FALSE;}');
                                    if($xmlflag){
                                            eval('$xmlidauthor=$xmlt->authorPRI->idauthor'.$j.';');
                                            $autorPRI=(string)$xmlidauthor;
                                            //se inicializa el array para que no de error scalar
                                            $_SESSION["edit"]["authorPRI"][$autorPRI]=1;

                                    }
                            }

                    }
                    //Solo un autor secundario
                    else{
                            $autorPRI=(string)$xmlt->authorPRI->idauthor0;
                            $_SESSION["edit"]["authorPRI"][$autorPRI]=1;
                    }

                }
                else{
                        $autorPRI="";
                }

		        $autorSEC="";
		        if(isset($xmlt->authorSEC)){
		                //Preguntamos si hay mas de un autor secundario
		            if(isset($xmlt->authorSEC->idauthor1)){
		                    for($j=0;$j<100;$j++){
		                            eval('if (isset($xmlt->authorSEC->idauthor'.$j.')){$xmlflag=TRUE;} else {$xmlflag=FALSE;}');
		                            if($xmlflag){
		                                    eval('$xmlidauthor=$xmlt->authorSEC->idauthor'.$j.';');
		                                    $autorSEC=(string)$xmlidauthor;
		                                    //se inicializa el array para que no de error scalar
		                                    //$_SESSION["edit"]["authorSEC"]=array();
		                                    $_SESSION["edit"]["authorSEC"][$autorSEC]=1;

		                            }
		                    }

		            }
		            //Solo un autor secundario
		            else{
		                    $autorSEC=(string)$xmlt->authorSEC->idauthor0;
		                    $_SESSION["edit"]["authorSEC"][$autorSEC]=1;
		            }

		        }
		        else{
		                $autorSEC="";
		        }
		        //--------
            }
		}
		$_SESSION["edit"]["idfbook"]=$idfbook;
		$_SESSION["edit"]["fbook_descripcion"]=$fbook_descripcion;

		$_SESSION["edit"]["date_ing"]=$date_ing;
		$_SESSION["edit"]["authorPRI"][$autorPRI]=1;
		if (isset($title)) {
			$_SESSION["edit"]["title"]=$title;
		}

        $_SESSION["edit"]["desc_month_pub"]=$desc_month_pub;
        if(isset($array_files)){
        		$_SESSION["edit"]["files"] = $array_files;
        		// $objResponse->alert(print_r($_SESSION["edit"],TRUE));
		}
		if (isset($ax_files)) {
			$_SESSION["edit"]["ax_files"]=$ax_files;
		}
		if (isset($Edition)) {
			$_SESSION["edit"]["Edition"]=$Edition;
		}
		if (isset($numEdition)) {
			$_SESSION["edit"]["numEdition"]=$numEdition;
		}
		if (isset($TypeMatBib)) {
			$_SESSION["edit"]["TypeMatBib"]=$TypeMatBib;
		}
		if (isset($ISBN)) {
			$_SESSION["edit"]["ISBN"] = $ISBN;
		}
		if (isset($FxReg)) {
			$_SESSION["edit"]["FxReg"] = $FxReg;
		}
		if (isset($ISSN)) {
			$_SESSION["edit"]["ISSN"] = $ISSN;
		}
		if (isset($Resumen)) {
			$_SESSION["edit"]["Resumen"] = $Resumen;
		}
		if (isset($Description)) {
			$_SESSION["edit"]["Description"] = $Description;
		}
		if (isset($Theme)) {
			$_SESSION["edit"]["Theme"] = $Theme;
		}
		if (isset($FxIng)) {
			$_SESSION["edit"]["FxIng"] = $FxIng;
		}
		if (isset($UbicFis)) {
			$_SESSION["edit"]["UbicFis"] = $UbicFis;
		}
		if (isset($NumReg)) {
			$_SESSION["edit"]["NumReg"] = $NumReg;
		}
		if (isset($languaje)) {
			$_SESSION["edit"]["languaje"] = $languaje;
		}

		if (isset($NumLC)) {
			$_SESSION["edit"]["NumLC"] = $NumLC;
		}
		if (isset($NumDewey)) {
			$_SESSION["edit"]["NumDewey"] = $NumDewey;
		}
		if (isset($Class_IGP)) {
			$_SESSION["edit"]["Class_IGP"] = $Class_IGP;
		}
		if (isset($EncMat)) {
			$_SESSION["edit"]["EncMat"] = $EncMat;
		}
		if (isset($OtherTitles)) {
			$_SESSION["edit"]["OtherTitles"] = $OtherTitles;
		}
		if (isset($Periodicidad)) {
			$_SESSION["edit"]["Periodicidad"] = $Periodicidad;
		}
		if (isset($Serie)) {
			$_SESSION["edit"]["Serie"] = $Serie;
		}
		if (isset($NoteGeneral)) {
			$_SESSION["edit"]["NoteGeneral"] = $NoteGeneral;
		}
		if (isset($NoteTesis)) {
			$_SESSION["edit"]["NoteTesis"] = $NoteTesis;
		}
		if (isset($NoteBiblio)) {
			$_SESSION["edit"]["NoteBiblio"] = $NoteBiblio;
		}
		if (isset($NoteConte)) {
			$_SESSION["edit"]["NoteConte"] = $NoteConte;
		}
		if (isset($DesPersonal)) {
			$_SESSION["edit"]["DesPersonal"] = $DesPersonal;
		}
		if (isset($MatEntidad)) {
			$_SESSION["edit"]["MatEntidad"] = $MatEntidad;
		}
		if (isset($Descriptor)) {
			$_SESSION["edit"]["Descriptor"] = $Descriptor;
		}
		if (isset($Descriptor_geo)) {
			$_SESSION["edit"]["Descriptor_geo"] = $Descriptor_geo;
		}
		if (isset($CongSec)) {
			$_SESSION["edit"]["CongSec"] = $CongSec;
		}
		if (isset($TitSec)) {
			$_SESSION["edit"]["TitSec"] = $TitSec;
		}
		if (isset($Fuente)) {
			$_SESSION["edit"]["Fuente"] = $Fuente;
		}
		if (isset($NumIng)) {
			$_SESSION["edit"]["NumIng"] = $NumIng;
		}
		if (isset($UbicElect)) {
			$_SESSION["edit"]["UbicElect"] = $UbicElect;
		}
		if (isset($ModAdqui)) {
			$_SESSION["edit"]["ModAdqui"] = $ModAdqui;
		}
		if (isset($Catalogador)) {
			$_SESSION["edit"]["Catalogador"] = $Catalogador;
		}
		if (isset($NumEjem)) {
			$_SESSION["edit"]["NumEjem"] = $NumEjem;
		}
		if (isset($state)) {
			$_SESSION["edit"]["state"]=$state;
		}
		if (isset($temas_recovery)) {
			$_SESSION["edit"]["temas_recovery"]=$temas_recovery;
		}
		$_SESSION["editar"]=1;
		$_SESSION["edit"]["sede"]=$result["sede"][0];
        // $objResponse->alert(print_r($_SESSION,TRUE));
		$objResponse->script("xajax_formPonenciasShow(".$idbook.")");
		$objResponse->assign('paginator', 'style.display',"none");
		$objResponse->assign('resultSearch', 'style.display',"none");
	}

	return $objResponse;
}

function categoryResult($sessionidarea=0){
        $resultSql= searchCategorySQL($sessionidarea);

        return $resultSql;
}

function searchAuthorSesionPriResult($idauthor=""){

	if(isset($_SESSION["edit"])){
	    $recuperar=$_SESSION["edit"];
	}
	elseif(isset($_SESSION["tmp"])){
	    $recuperar=$_SESSION["tmp"];
	}

    $strSQL="";

    if(isset($_SESSION["edit"]["authorPRI"])){
        foreach($_SESSION["edit"]["authorPRI"] as $newlist=>$counter){
            if( $counter == 1) {
                /*en la sentencia SQL se utiliza in o notin entonces se necesita
                que los numeros enten entre comillas*/
                // $strSQL.="'".$newlist."'";
                $strSQL.="'".$newlist."',";
            }
        }
    }

    else{
        if(isset($_SESSION["tmp"]["authorPRI"])){
            foreach($_SESSION["tmp"]["authorPRI"] as $newlist=>$counter){
                if( $counter == 1) {
                    /*en la sentencia SQL se utiliza in o notin entonces se necesita
                    que los numeros esten entre comillas*/
                    // $strSQL.="'".$newlist."'";
                    $strSQL.="'".$newlist."',";
                }
            }
        }
    }

    $strSQL = substr($strSQL, 0,-1);
    $result=searchAuthorSessionSQL($strSQL);

    return $result;
}

/*************************************************
       Funcion simple javascript sin xajax
**************************************************/
function searchAuthorSesionPriShow_sinXajax($idauthor="",$action="",$catAuthor=""){

	    $html="";
        $result= searchAuthorSesionPriResult($idauthor);
	    // $result= searchAuthorSesionSecResult($idauthor);
		//Verificamos el array es null
        if($result["Error"]==2){
           $html="<table align='center'><tr><td>Añadir autor de la lista.</td></tr></table>";
        }

		if($result["Error"]==0){
		    $query=$result["Query"];
		    $count=$result["Count"];
		    $idauthor = $result["idauthor"];
		    $author_name = $result["author_name"];
		    $author_surname =$result["author_surname"];

                    if(isset($_SESSION["editar"])){
                        if($_SESSION["editar"]==1){
                            $_SESSION["edit"]["authorPRI"]["idauthor"]=$idauthor;
                            $_SESSION["edit"]["authorPRI"]["author_name"]=$author_name;
                            $_SESSION["edit"]["authorPRI"]["author_surname"]=$author_surname;
                        }
                    }
                    else{
						    // $_SESSION["tmp"]["authorPRI"]["idauthor"]=$idauthor;
						    // $_SESSION["tmp"]["authorPRI"]["author_name"]=$author_name;
						    // $_SESSION["tmp"]["authorPRI"]["author_surname"]=$author_surname;
                            $_SESSION["tmp"]["authorPRI"]["idauthor"]=$idauthor;
                            $_SESSION["tmp"]["authorPRI"]["author_first_name"]=$author_first_name;
                            $_SESSION["tmp"]["authorPRI"]["author_second_name"]=$author_second_name;
                            $_SESSION["tmp"]["authorPRI"]["author_surname"]=$author_surname;
                    }

		    //$objResponse->script("xajax_arrayAuthor()");
            $catAuthor_html = ($catAuthor=="AuthorPer") ? "Apellidos y Nombres" : "Jurisdicción - Institución";
		    $html='<table class="tablacebra-2" cellspacing="0" cellpadding="0" border="0" align="center" width="200px">
					<tr class="cab" style="text-align: left;">';
		    $html.= "<td width='20px'>N°</td>";
		    $html.= "<td width='120px'>".$catAuthor_html."</td>";
		    $html.= "<td width='15px'>Quitar</td>";
		    $html.= "</tr>";


		    for($i=0;$i<$count;$i++){
		            $nro=$i+1;
                            $html.= "<tr class='impar'>";
		            $html.= "<td>".$nro."</td>";
		        $id_author = $idauthor[$i];
				if(ereg("'",$author_surname[$i])){
				    $apellido=explode("'",$author_surname[$i]);
				    $antes_caracter=ucfirst($apellido[0]);
				    $despues_caracter=ucfirst($apellido[1]);
				    $apellido=$antes_caracter."'".$despues_caracter;
				}
				else{
				    $apellido=ucfirst($author_surname[$i]);
				}
                    $surname = !empty($apellido)?$apellido.", ":"";
		            $html.= "<td>".$surname." ".ucfirst($author_name[$i])."
		            		<!--input name='authorPRI[idauthor0]' type='hidden' value='$id_author'/>
		            		<input name='authorPRI[author_surname0]' type='hidden' value='$id_author'/-->
		            		</td>";
		            $html.= "<td><a href='#formulario'><img alt='Eliminar' onclick='xajax_delSearchAuthorSesionPriShow(\"$idauthor[$i]\",\"".$action."\",\"".$catAuthor."\"); return false;' src='img/iconos/userDEL.png' /></a></td>";
		            $html.= "</tr>";
		    }

		    $html.= "</table>";
		}

                    /********arrayAuthor*************/
		if(isset($_SESSION["edit"])){
		    $recuperar=$_SESSION["edit"];
		}
		elseif(isset($_SESSION["tmp"])){
		    $recuperar=$_SESSION["tmp"];
		}

		// $objResponse = new xajaxResponse();
		if (isset($recuperar["authorPRI"]["idauthor"])){
	            if (isset($_SESSION["publicaciones"]["authorPRI"])){
	                unset($_SESSION["publicaciones"]["authorPRI"]);
	            }

	            $idx=$recuperar["authorPRI"]["idauthor"];
	            $i=0;
	            foreach($idx as $key => $idauthor){
	                $_SESSION["publicaciones"]["authorPRI"]["idauthor$i"]=$idauthor;
	                $i++;
	            }

	            $firstx=$recuperar["authorPRI"]["author_first_name"];
	            $i=0;
	            foreach($firstx as $key => $author_first_name){
	                $_SESSION["publicaciones"]["authorPRI"]["author_first_name$i"]=$author_first_name;
	                $i++;
	            }

	            $secondx=$recuperar["authorPRI"]["author_second_name"];
	            $i=0;
	            foreach($secondx as $key => $author_second_name){
	                $_SESSION["publicaciones"]["authorPRI"]["author_second_name$i"]=$author_second_name;
	                $i++;
	            }

	            $surnamex=$recuperar["authorPRI"]["author_surname"];
	            $i=0;
	            foreach($surnamex as $key => $author_surname_name){
	                $author_surname_name=(str_replace("'","*",$author_surname_name));
	                $_SESSION["publicaciones"]["authorPRI"]["author_surname$i"]=$author_surname_name;
	                $i++;
	            }

	    }

		if (isset($recuperar["authorSEC"]["idauthor"])){
	            if (isset($_SESSION["publicaciones"]["authorSEC"])){
	                unset($_SESSION["publicaciones"]["authorSEC"]);
	            }


	                $idx=$recuperar["authorSEC"]["idauthor"];
	                $i=0;
	                foreach($idx as $key => $idauthor){
	                    $_SESSION["publicaciones"]["authorSEC"]["idauthor$i"]=$idauthor;
	                    $i++;
	                }

	                $firstx=$recuperar["authorSEC"]["author_first_name"];
	                $i=0;
	                foreach($firstx as $key => $author_first_name){
	                    $_SESSION["publicaciones"]["authorSEC"]["author_first_name$i"]=$author_first_name;
	                    $i++;
	                }

	                $secondx=$recuperar["authorSEC"]["author_second_name"];
	                $i=0;
	                foreach($secondx as $key => $author_second_name){
	                    $_SESSION["publicaciones"]["authorSEC"]["author_second_name$i"]=$author_second_name;
	                    $i++;
	                }

	                $surnamex=$recuperar["authorSEC"]["author_surname"];
	                $i=0;
	                foreach($surnamex as $key => $author_surname_name){
	                    $author_surname_name=(str_replace("'","*",$author_surname_name));
	                    $_SESSION["publicaciones"]["authorSEC"]["author_surname$i"]=$author_surname_name;
	                    $i++;
	                }

	    }
            /*****************arrayAuthor**************/

                //$objResponse->alert(print_r($_SESSION["tmp"], true));


	    return $html;
}

/*************************************************

**************************************************/
function searchAuthorSesionPriShow($idauthor=""){

	    $objResponse = new xajaxResponse();
	    $html="";
	    $result= searchAuthorSesionPriResult($idauthor);

		//Verificamos el array es null
		if($result["Error"]==2){
		    $html="<table align='center'><tr><td>Añadir autor de la lista.</td></tr></table>";
		    //$objResponse->alert(print_r($result,TRUE));
		}

		if($result["Error"]==0){
		    $query=$result["Query"];

		    $count=$result["Count"];
		    $idauthor = $result["idauthor"];
		    $author_first_name = $result["author_name"];
		    // $author_first_name = $result["author_first_name"];
		    $author_second_name = $result["author_second_name"];
		    $author_surname =$result["author_surname"];

                    if(isset($_SESSION["editar"])){
                        if($_SESSION["editar"]==1){
                            $_SESSION["edit"]["authorPRI"]["idauthor"]=$idauthor;
                            $_SESSION["edit"]["authorPRI"]["author_first_name"]=$author_first_name;
                            $_SESSION["edit"]["authorPRI"]["author_second_name"]=$author_second_name;
                            $_SESSION["edit"]["authorPRI"]["author_surname"]=$author_surname;
                        }
                    }
                    else{
					    $_SESSION["tmp"]["authorPRI"]["idauthor"]=$idauthor;
					    $_SESSION["tmp"]["authorPRI"]["author_first_name"]=$author_first_name;
					    $_SESSION["tmp"]["authorPRI"]["author_second_name"]=$author_second_name;
					    $_SESSION["tmp"]["authorPRI"]["author_surname"]=$author_surname;
                    }

		    $objResponse->script("xajax_arrayAuthor()");
		    $html='<table class="tablacebra-2" cellspacing="0" cellpadding="0" border="0" align="center" width="200px">
					<tr class="cab" style="text-align: left;">';
		    $html.= "<td width='20px'>N°</td>";
		    $html.= "<td width='120px'>Apellidos y  Nombres</td>";
		    $html.= "<td width='15px'>Quitar</td>";
		    $html.= "</tr>";


		    for($i=0;$i<$count;$i++){
                    $surname = !empty($author_surname[$i])?$author_surname[$i].", ":"";
		            $nro=$i+1;
		            $id_author = $idauthor[$i];
		    		$html.= "<tr class='impar'>";
		            $html.= "<td>".$nro."</td>";
		            $html.= "<td>".ucfirst($surname)." ".ucfirst($author_first_name[$i])."
		            		<!--input name='authorPRI[idauthor0]' type='hidden' value='$id_author'/>
		            		<input name='authorPRI[author_surname0]' type='hidden' value='$id_author'/-->
		            		</td>";
		            $html.= "<td><a href='#formulario'><img alt='Eliminar' style='cursor: pointer; border:0;' onclick='xajax_delSearchAuthorSesionPriShow(\"$idauthor[$i]\"); return false;' src='img/iconos/userDEL.png' /></a></td>";
		            $html.= "</tr>";
		    }

		    $html.= "</table>";
		}

                //$objResponse->alert(print_r($_SESSION["tmp"], true));
		$objResponse->assign('sesion_authorPRI', 'innerHTML',$html);
		// if ($catAuthor=="AuthorPer") {
		// 	$objResponse->assign('sesion_authorPRI', 'innerHTML',$html);
		// }
		// elseif ($catAuthor=="AuthorInst") {
		// 	$objResponse->assign('sesion_authorPRI_02', 'innerHTML',$html);
		// }


	    return $objResponse;
}

/*************************************************

**************************************************/
function searchAuthorSesionSecResult($idauthor=""){

	$recuperar="";
	$strSQL="";

	if(isset($_SESSION["edit"])){
	    $recuperar=$_SESSION["edit"];
	}
	elseif(isset($_SESSION["tmp"])){
	    $recuperar=$_SESSION["tmp"];
	}

	/*esta es la menera simplificada*/
    if(isset($recuperar["authorSEC"])){
        //Recorremos el array en busca de los valores que tienen valor 1
        //y lo formateamos a Ejm. $strSQL='A1','B2','C3',
        foreach ( $recuperar["authorSEC"] as $key => $valor ) {
            if( $valor == 1) {
                //en la sentencia SQL se utiliza in o notin entonces se necesita
                //que los numeros enten entre comillas
                $strSQL.="'".$key."',";
            }
        }
    }

    //A Ejm. $strSQL=1,2,3, le quitamos el último caracter que en este caso es una coma ","
    $strSQL = substr($strSQL, 0,-1);

    //para enviarselo a la funcion SQL
    $result=searchAuthorSessionSQL($strSQL);

    return $result;
}


/*************************************************

**************************************************/
function searchAuthorSesionSecShow_sinXajax($idauthor="",$action="",$catAuthor=""){

	//$objResponse = new xajaxResponse();
	// Aqui construimos el paginador

	$html="";
	$result= searchAuthorSesionSecResult($idauthor);

		//Verificamos el array es null
		if($result["Error"]==2){
		    $html="<table align='center'><tr><td>Añadir autor(es) de la lista.</td></tr></table>";
		}
		else{
		    $query=$result["Query"];
		    $count=$result["Count"];
		    $idauthor = $result["idauthor"];
		    $author_first_name = $result["author_name"];
		    $author_second_name = $result["author_second_name"];
		    $author_surname =$result["author_surname"];

                    if(isset($_SESSION["editar"])){
                        if($_SESSION["editar"]==1){
                            $_SESSION["edit"]["authorSEC"]["idauthor"]=$idauthor;
                            $_SESSION["edit"]["authorSEC"]["author_first_name"]=$author_first_name;
                            $_SESSION["edit"]["authorSEC"]["author_second_name"]=$author_second_name;
                            $_SESSION["edit"]["authorSEC"]["author_surname"]=$author_surname;
                        }
                    }
                    else{
                            $_SESSION["tmp"]["authorSEC"]["idauthor"]=$idauthor;
                            $_SESSION["tmp"]["authorSEC"]["author_first_name"]=$author_first_name;
                            $_SESSION["tmp"]["authorSEC"]["author_second_name"]=$author_second_name;
                            $_SESSION["tmp"]["authorSEC"]["author_surname"]=$author_surname;
                    }

		    //$objResponse->script("xajax_arrayAuthor()");
            $catAuthor_html = ($catAuthor=="AuthorPer") ? "Apellidos y Nombres" : "Jurisdicción - Institución";
		    $html='<table class="tablacebra-2" cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
						<tr class="cab" style="text-align: left;">';
		    $html.= "<td width='20px'>N°</td>";
		    $html.= "<td>".$catAuthor_html."</td>";
		    $html.= "<td width='15px'>Quitar</td>";
		    $html.= "</tr>";

		    for($i=0;$i<$count;$i++){
		            $nro=$i+1;
		            $html.= "<tr class='impar'>";
		            $html.= "<td>".$nro."</td>";
            	if(ereg("'",$author_surname[$i])){
            	    $apellido=explode("'",$author_surname[$i]);
            	    $antes_caracter=ucfirst($apellido[0]);
            	    $despues_caracter=ucfirst($apellido[1]);
            	    $apellido=$antes_caracter."'".$despues_caracter;
            	}
            	else{
            	    $apellido=ucfirst($author_surname[$i]);
            	}
                $surname = !empty($apellido)?$apellido.", ":"";
		        $html.= "<td>".$surname." ".ucfirst($author_first_name[$i])."</td>";
		        $html.= "<td><a href='#formulario'><img alt='selet'  onclick='xajax_delSearchAuthorSesionSecShow(\"$idauthor[$i]\",\"".$action."\",\"".$catAuthor."\"); return false;' src='img/usersDEL.png'/></a></td>";
		        $html.= "</tr>";
		    }
		    $html.= "</table>";
	   }
	    //$objResponse->assign('sesion_authorSEC', 'innerHTML',$html);

                                    /********arrayAuthor*************/
	if(isset($_SESSION["edit"])){
	    $recuperar=$_SESSION["edit"];
	}
	elseif(isset($_SESSION["tmp"])){
	    $recuperar=$_SESSION["tmp"];
	}
    //Edición: Recuperación rgistros de la base de datos
	if (isset($recuperar["authorPRI"]["idauthor"])){
            if (isset($_SESSION["publicaciones"]["authorPRI"])){
                unset($_SESSION["publicaciones"]["authorPRI"]);
            }

            $idx=$recuperar["authorPRI"]["idauthor"];
            $i=0;
            foreach($idx as $key => $idauthor){
                $_SESSION["publicaciones"]["authorPRI"]["idauthor$i"]=$idauthor;
                $i++;
            }

            $firstx=$recuperar["authorPRI"]["author_first_name"];
            $i=0;
            foreach($firstx as $key => $author_first_name){
                $_SESSION["publicaciones"]["authorPRI"]["author_first_name$i"]=$author_first_name;
                $i++;
            }

            $secondx=$recuperar["authorPRI"]["author_second_name"];
            $i=0;
            foreach($secondx as $key => $author_second_name){
                $_SESSION["publicaciones"]["authorPRI"]["author_second_name$i"]=$author_second_name;
                $i++;
            }

            $surnamex=$recuperar["authorPRI"]["author_surname"];
            $i=0;
            foreach($surnamex as $key => $author_surname_name){
                $author_surname_name=(str_replace("'","*",$author_surname_name));
                $_SESSION["publicaciones"]["authorPRI"]["author_surname$i"]=$author_surname_name;
                $i++;
            }

    }

	if (isset($recuperar["authorSEC"]["idauthor"])){
            if (isset($_SESSION["publicaciones"]["authorSEC"])){
                unset($_SESSION["publicaciones"]["authorSEC"]);
            }


                $idx=$recuperar["authorSEC"]["idauthor"];
                $i=0;
                foreach($idx as $key => $idauthor){
                    $_SESSION["publicaciones"]["authorSEC"]["idauthor$i"]=$idauthor;
                    $i++;
                }

                $firstx=$recuperar["authorSEC"]["author_first_name"];
                $i=0;
                foreach($firstx as $key => $author_first_name){
                    $_SESSION["publicaciones"]["authorSEC"]["author_first_name$i"]=$author_first_name;
                    $i++;
                }

                $secondx=$recuperar["authorSEC"]["author_second_name"];
                $i=0;
                foreach($secondx as $key => $author_second_name){
                    $_SESSION["publicaciones"]["authorSEC"]["author_second_name$i"]=$author_second_name;
                    $i++;
                }

                $surnamex=$recuperar["authorSEC"]["author_surname"];
                $i=0;
                foreach($surnamex as $key => $author_surname_name){
                    $author_surname_name=(str_replace("'","*",$author_surname_name));
                    $_SESSION["publicaciones"]["authorSEC"]["author_surname$i"]=$author_surname_name;
                    $i++;
                }

    }
            /*****************arrayAuthor**************/

	return $html;
}

/*************************************************

**************************************************/
function searchAuthorSesionSecShow($idauthor=""){

	    $objResponse = new xajaxResponse();
	    // Aqui construimos el paginador

	    $html="";
	    $result= searchAuthorSesionSecResult($idauthor);

		//Verificamos el array es null
		if($result["Error"]==2){
		    $html="<table align='center'><tr><td>Añadir autor(es) de la lista.</td></tr></table>";
		}
		else{
		    $query=$result["Query"];
		    $count=$result["Count"];
		    $idauthor = $result["idauthor"];
		    $author_first_name = $result["author_name"];
		    // $author_first_name = $result["author_first_name"];
		    $author_second_name = $result["author_second_name"];
		    $author_surname =$result["author_surname"];

                    if(isset($_SESSION["editar"])){
                        if($_SESSION["editar"]==1){
                            $_SESSION["edit"]["authorSEC"]["idauthor"]=$idauthor;
                            $_SESSION["edit"]["authorSEC"]["author_first_name"]=$author_first_name;
                            $_SESSION["edit"]["authorSEC"]["author_second_name"]=$author_second_name;
                            $_SESSION["edit"]["authorSEC"]["author_surname"]=$author_surname;
                        }
                    }
                    else{
                            $_SESSION["tmp"]["authorSEC"]["idauthor"]=$idauthor;
                            $_SESSION["tmp"]["authorSEC"]["author_first_name"]=$author_first_name;
                            $_SESSION["tmp"]["authorSEC"]["author_second_name"]=$author_second_name;
                            $_SESSION["tmp"]["authorSEC"]["author_surname"]=$author_surname;
                    }

		    $objResponse->script("xajax_arrayAuthor()");
		    $html='<table class="tablacebra-2" cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
						<tr class="cab" style="text-align: left;">';
		    $html.= "<td width='20px'>N°</td>";
		    $html.= "<td>Apellidos Nombres</td>";
		    $html.= "<td width='15px'>Quitar</td>";
		    $html.= "</tr>";

		    for($i=0;$i<$count;$i++){
                    $surname = !empty($author_surname[$i])?$author_surname[$i].", ":"";
		            $nro=$i+1;
		            $html.= "<tr class='impar'>";
		            $html.= "<td>".$nro." </td>";
		            $html.= "<td>".ucfirst($surname)." ".ucfirst($author_first_name[$i])."</td>";

		            $html.= "<td><a href='#formulario'><img alt='selet' style='cursor: pointer; border:0;' onclick='xajax_delSearchAuthorSesionSecShow(\"$idauthor[$i]\"); return false;' src='img/usersDEL.png'/></a></td>";
		            $html.= "</tr>";
		    }
		    $html.= "</table>";
		}
	    $objResponse->assign('sesion_authorSEC', 'innerHTML',$html);
	    return $objResponse;
}


function delSearchAuthorSesionPriResult($idauthor=""){

		if(isset($_SESSION["edit"])){
		    if(isset($_SESSION["edit"]["authorPRI"][$idauthor])){
		        unset($_SESSION["edit"]["authorPRI"][$idauthor]);
                        unset($_SESSION["edit"]["authorPRI"]["idauthor"]);
                        unset($_SESSION["edit"]["authorPRI"]["author_first_name"]);
                        unset($_SESSION["edit"]["authorPRI"]["author_second_name"]);
                        unset($_SESSION["edit"]["authorPRI"]["author_surname"]);

		    }
		    return $_SESSION["edit"]["authorPRI"];
		}
		else{
		        unset($_SESSION["tmp"]["authorPRI"][$idauthor]);
                        unset($_SESSION["tmp"]["authorPRI"]["idauthor"]);
                        unset($_SESSION["tmp"]["authorPRI"]["author_first_name"]);
                        unset($_SESSION["tmp"]["authorPRI"]["author_second_name"]);
                        unset($_SESSION["tmp"]["authorPRI"]["author_surname"]);

		        return $_SESSION["tmp"]["authorPRI"];

		}

	    //unset($_SESSION["autor"]);
}

function delSearchAuthorSesionPriShow($idauthor="",$action="",$catAuthor=""){
    $objResponse = new xajaxResponse();

            if (isset($_SESSION["publicaciones"]["authorPRI"])){
                unset($_SESSION["publicaciones"]["authorPRI"]);
            }

	if(isset($_SESSION["edit"])){
	    $recuperar=$_SESSION["edit"];
	}
	elseif(isset($_SESSION["tmp"])){
	    $recuperar=$_SESSION["tmp"];
	 }

    delSearchAuthorSesionPriResult($idauthor);

    $html="";
    if(!isset($recuperar["authorPRI"])){
        $objResponse->assign('sesion_authorPRI', 'innerHTML',$html);

    }
    else{

    //$objResponse->script("xajax_searchAuthorSesionPriShow()");

    $html=searchAuthorSesionPriShow_sinXajax($idauthor);
    $objResponse->assign("sesion_authorPRI","innerHTML",$html);

    // $objResponse->script("xajax_auxAuthorPriShow(5,1,xajax.getFormValues('autorPRI'))");
    //$objResponse->script("xajax_auxAuthorShow(5000,1,'','".$action."','','".$catAuthor."')");
    //$objResponse->script("xajax_auxAuthorSecShow(5,1,xajax.getFormValues('autorSEC'))");
    }

    //$objResponse->alert(print_r($_SESSION["tmp"], true));
    return $objResponse;
}


function delSearchAuthorSesionSecResult($idauthor=""){

    if (isset($_SESSION["publicaciones"]["authorSEC"])){
        unset($_SESSION["publicaciones"]["authorSEC"]);
    }

	if(isset($_SESSION["edit"])){
	    if(isset($_SESSION["edit"]["authorSEC"][$idauthor])){
	        unset($_SESSION["edit"]["authorSEC"][$idauthor]);
	        unset($_SESSION["edit"]["authorSEC"]["idauthor"]);
	        unset($_SESSION["edit"]["authorSEC"]["author_first_name"]);
	        unset($_SESSION["edit"]["authorSEC"]["author_second_name"]);
	        unset($_SESSION["edit"]["authorSEC"]["author_surname"]);


	    }
	    return $_SESSION["edit"]["authorSEC"];
	}
	else{
	        unset($_SESSION["tmp"]["authorSEC"][$idauthor]);
	        unset($_SESSION["tmp"]["authorSEC"]["idauthor"]);
	        unset($_SESSION["tmp"]["authorSEC"]["author_first_name"]);
	        unset($_SESSION["tmp"]["authorSEC"]["author_second_name"]);
	        unset($_SESSION["tmp"]["authorSEC"]["author_surname"]);

	        return $_SESSION["tmp"]["authorSEC"];

	}

}

function delSearchAuthorSesionSecShow($idauthor=""){
    $objResponse = new xajaxResponse();

	if(isset($_SESSION["edit"])){
	    $recuperar=$_SESSION["edit"];
	}
	elseif(isset($_SESSION["tmp"])){
	    $recuperar=$_SESSION["tmp"];
	 }

    delSearchAuthorSesionSecResult($idauthor);

    $html="";
    if(!isset($recuperar["authorSEC"])){
        $objResponse->assign('sesion_authorSEC', 'innerHTML',$html);
    }
    else{
        //$objResponse->script("xajax_searchAuthorSesionSecShow()");
        $html=searchAuthorSesionSecShow_sinXajax($idauthor);
        $objResponse->assign("sesion_authorSEC","innerHTML",$html);
        //$objResponse->script("xajax_auxAuthorSecShow(5,1,xajax.getFormValues('autorSEC'))");
        $objResponse->script("xajax_auxAuthorPriShow(5,1,xajax.getFormValues('autorPRI'))");
    }

    //$objResponse->alert(print_r($_SESSION,true));
    return $objResponse;
}


function paginatorShow($page,$ipp,$total,$divAutor,$idauthor="",$catAuthor=""){
        $respuesta = new xajaxResponse();
        $pages = new Paginator;
        $pages->items_total = $total;
        $pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
        $dPaginator = $catAuthor=="AuthorPer"?"paginatorPRI":"paginatorPRI_02";
        // $respuesta->alert(print_r($catAuthor,TRUE));
        if($divAutor=="autorPRI"){
            $divPaginator="$dPaginator";
            $function="xajax_auxAuthorPriShow";
        }
        elseif($divAutor=="autorSEC"){
            $divPaginator="paginatorSEC";
            $function="xajax_auxAuthorSecShow";
        }
        elseif ($divAutor=="author_section") {
        	$divPaginator="paginatorAuthor";
            $function="xajax_auxAuthorShow";
        }

        $pages->paginateAuthor($ipp,$page,$function,$divAutor);
        $html="";
        $html.= $pages->display_pages();
        $respuesta->assign($divPaginator,"innerHTML",$html);
        return $respuesta;
}

/*************************************************
Autor Principal
**************************************************/

function auxAuthorPriShow($pageSize,$currentPage,$formSearch="",$idauthor="",$apellidoArray="",$catAuthor=""){
        $respuesta = new xajaxResponse();

        $sAuthor=isset($formSearch["sAuthor"])?$formSearch["sAuthor"]:"";
        if($idauthor!=""){
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
        }

        // $result=searchAuthorPriResult('ALL',$currentPage,$pageSize,$sAuthor);
        // if(isset($result["Count"])){
        //     if($result["Count"]==0){
        //         $total=0;
        //     }
        //     else{
        //         $total=$result["Count"];
        //     }
        // }
        // else{
        //     $total=0;
        // }

        $html="";

        if($idauthor!=""){

            //$respuesta->script("xajax_searchAuthorSesionPriShow(".$idauthor.")");
            $html=searchAuthorSesionPriShow_sinXajax($idauthor);

            if ($catAuthor=="AuthorPer") {
            	$respuesta->Assign("sesion_authorPRI","innerHTML",$html);
            }
            // elseif ($catAuthor=="AuthorInst") {
            	$respuesta->Assign("sesion_authorPRI_02","innerHTML",$html);
            // }
        }
        // $respuesta->alert(print_r($catAuthor,TRUE));
        // $respuesta->script('xajax_searchAuthorPriShow("AUTHOR",'.$currentPage.','.$pageSize.',"'.$sAuthor.'","'.$idauthor.'","'.$apellidoArray.'","'.$catAuthor.'")');
        //$respuesta->script("xajax_paginatorShow($currentPage,$pageSize,$total,'autorPRI','','".$catAuthor."')");

        if ($catAuthor=="AuthorPer") {
        	$respuesta->Assign("rq_authorPRI","style.display","block");
        	$respuesta->Assign("paginatorPRI","style.display","block");
        }
        elseif ($catAuthor=="AuthorInst") {
        	$respuesta->Assign("rq_authorPRI_02","style.display","block");
        	$respuesta->Assign("paginatorPRI_02","style.display","block");
        }
        $respuesta->Assign("div_autor","style.display","none");
        //$respuesta->alert(print_r($result, true));
        //$respuesta->alert(print_r($_SESSION["tmp"], true));
        return $respuesta;
}

/*************************************************


**************************************************/
function searchAuthorPriShow($idSearch,$currentPage,$pageSize,$sAuthor="",$idauthor=0,$apellidoArray="",$catAuthor=""){
	    $objResponse = new xajaxResponse();

	    $result= searchAuthorPriResult($idSearch,$currentPage,$pageSize,$sAuthor,$idauthor);
	    // $objResponse->alert(print_r($result, TRUE));

            if(isset($_SESSION["edit"])){
                $recuperar=$_SESSION["edit"];
            }
            else{
                if(isset($_SESSION["tmp"])){
                    $recuperar=$_SESSION["tmp"];
                }
            }
            // $objResponse->alert(print_r($result["Query"],TRUE));
            if($result["Error"]==1){

                $arrayAutorPRI=isset($recuperar["authorPRI"]["author_surname"])?$recuperar["authorPRI"]["author_surname"]:array();
                $arrayAutorSEC=isset($recuperar["authorSEC"]["author_surname"])?$recuperar["authorSEC"]["author_surname"]:array();

                /*
                $arrayAutorPRI=isset($recuperar["authorPRI"]["idauthor"])?$recuperar["authorPRI"]["idauthor"]:array();
                $arrayAutorSEC=isset($recuperar["authorSEC"]["idauthor"])?$recuperar["authorSEC"]["idauthor"]:array();
                */
                $result_array=array_merge_recursive($arrayAutorPRI,$arrayAutorSEC);
                //$sAuthor=$result["sAuthor"];
                //$apellidoArray=strtolower($apellidoArray);
                //$inAutor= in_array($idauthor,$result_array);
                $inAutor= in_array($apellidoArray,$result_array);
                /*******************************/
                //$clave = array_search($sAuthor, $result_array);
                //$objResponse->alert(print_r($result_array, TRUE));


                if($inAutor){
                //if (array_key_exists($clave, $result_array)) {

                    $html="<h5><p>El Autor ha sido asociado a la publicación<br>";
                }
                else{
                    $html="<h5><p>No existe el autor regístrelo como nuevo<br>";
                }

                $html.='<span style="float:left; font-size: 12px"><a href="#" onclick="xajax_mostrarBusquedaAutores(\''.$catAuthor.'\'); return false;" class="txt-rojo" id="boton_regreso"><img style="cursor: pointer; border:0;" width="12px" src="img/iconos/flecha-atras.jpg">&nbsp;&nbsp;&nbsp;Retornar a la búsqueda</a></span></div><br>';
                //$html.="<br>".$result["Query"];
            }
            elseif($result["Error"]==2){
                $html="<h5><p>Error SQL</p></h5>";
                $html.="<br>".$result["Query"];
            }
            elseif($result["Error"]==3){
                $html=$result["Msg"];
                $html.="<br>".$result["result_array"][0];
                $html.='<span style="float:left; font-size: 12px"><a href="#" onclick="xajax_mostrarBusquedaAutores(\''.$catAuthor.'\'); return false;" class="txt-rojo" id="boton_regreso"><img style="cursor: pointer; border:0;" width="12px" src="img/iconos/flecha-atras.jpg">&nbsp;&nbsp;&nbsp;Retornar a la búsqueda</a></span></div><br>';
            }

		else{
		    $query=$result["Query"];

		    $idauthor = $result["idauthor"];
		    $author_name = $result["author_name"];
		    $author_surname =$result["author_surname"];

		    $html="";
                    $html.='<span style="float:left; font-size: 12px;"><a href="#" onclick="xajax_mostrarBusquedaAutores(\''.$catAuthor.'\'); return false;" class="txt-rojo" id="boton_regreso" ><img style="cursor: pointer; border:0;" width="12px" src="img/iconos/flecha-atras.jpg">&nbsp;&nbsp;&nbsp;Retornar a la búsqueda</a></span></div><br>';
		    $html.= '<table class="tablacebra-2" cellspacing="0" cellpadding="0" border="0" width="380px">';
			$html.= '<tr style="text-align: left;" class="cab">';
		    $html.= "<td width='40px'>Nro</td>";
		    $html.= "<td width='200px'>Apellidos Nombres</td>";
		    $html.= "<td width='60px'>Principal</td>";
		    $html.= "<td width='60px'>Secundario</td>";
		    $html.= "</tr>";

		    for($i=0;$i<$result["Count"];$i++){
		            $nro=$i+1;
                            $html.= "<tr class='impar'>";
		            $html.= "<td>".$nro."</td>";
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
                    $html.= "<td>".$apellido.", ".$author_name[$i]."</td>";

		            $html.= "<td><a href=\"#formulario\" style=\"cursor: pointer;\"><img alt=\"autor primario\" style=\"cursor: pointer; border:0;\" onclick=\"xajax_auxAuthorPriShow(5,$currentPage,xajax.getFormValues('autorPRI'),$idauthor[$i],'$apellidoArray','$catAuthor'); return false;\" src=\"img/iconos/userPRI.png\" /></a></td>";
		            $html.= "<td>
		            <a href=\"#formulario\" style=\"cursor: pointer;\"><img alt=\"autor secundario\" style=\"cursor: pointer;border:0;\" onclick=\"xajax_auxAuthorSecShow(5,$currentPage,xajax.getFormValues('autorPRI'),$idauthor[$i],'$apellidoArray','$catAuthor'); return false;\" src=\"img/iconos/userSEC.png\" /></a>
		            </td>";
		            $html.= "</tr>";
		    }
		    $html.= "</table>";

		}
        if ($catAuthor=="AuthorPer") {
           	$objResponse->assign('rq_authorPRI', 'innerHTML',$html);
           	$objResponse->assign('paginatorPRI', 'style.display',"block");
        }
        elseif ($catAuthor=="AuthorInst") {
          	$objResponse->assign('rq_authorPRI_02', 'innerHTML',$html);
          	$objResponse->assign('paginatorPRI_02', 'style.display',"block");
        }
	    // $objResponse->assign('rq_authorPRI', 'innerHTML',$html);


	    return $objResponse;
}

function searchAuthorPriResult($idSearch,$currentPage,$pageSize,$sAuthor="",$catAuthor=""){
		$strSQL="";
		$strSQLPRI="";
		$strSQLSEC="";

		if(isset($_SESSION["edit"])){
		    $recuperar=$_SESSION["edit"];
		}
		elseif(isset($_SESSION["tmp"])){
		    $recuperar=$_SESSION["tmp"];
		 }

	    if(isset($recuperar["authorPRI"])){

	    //Recorremos el array en busca de los valores que tienen valor 1
	    //y lo formateamos a Ejm. $strSQL=1,2,3,

	    foreach($recuperar["authorPRI"] as $newlist=>$counter){
	        if( $counter == 1) {
	            //$strSQLPRI.=$newlist;
                // $strSQLPRI.="'".$newlist."'";
	            $strSQLPRI.="'".$newlist."',";
	        }
	    }
        $strSQLPRI = substr($strSQLPRI, 0,-1);
        $strSQL = $strSQLPRI;
	    //A Ejm. $strSQL=1,2,3, le quitamos el último caracter que en este caso es una coma ","
	    //$strSQL = substr($strSQL, 0,-1);
	    }

			//Verificamos el array secundario es null
		if(isset($recuperar["authorSEC"])){

		    //Recorremos el array en busca de los valores que tienen valor 1
		    //y lo formateamos a Ejm. $strSQL=1,2,3,
		    foreach ( $recuperar["authorSEC"] as $key => $valor ) {
		        if( $valor == 1) {
		            $strSQLSEC.="'".$key."',";
		            //$strSQLSEC.=$key.",";
		        }
		    }


		    if($strSQLPRI!=""){
                $strSQL=$strSQLSEC.$strSQLPRI;
		        // $strSQL=$strSQLSEC.$strSQL;
		    }
		    else{
		        //A Ejm. $strSQL=1,2,3, le quitamos el último caracter que en este caso es una coma ","
		        $strSQL = substr($strSQLSEC, 0,-1);
		    }
		}
	    elseif(!isset($recuperar["authorPRI"]) && !isset($recuperar["authorSEC"])){
	        $strSQL="";
	    }

	    $result=searchAuthorSQL($idSearch,$currentPage,$pageSize,$sAuthor,$strSQL,$catAuthor);

		return $result;
}

/*************************************************
Autor Secundario
**************************************************/

function auxAuthorSecShow($pageSize,$currentPage,$formSearch="",$idauthor="",$apellidoArray="",$catAuthor=""){
        $respuesta = new xajaxResponse();

        $sAuthor=isset($formSearch["sAuthor"])?$formSearch["sAuthor"]:"";
        if($idauthor!=""){
            //if(isset($_SESSION["edit"]["authorSEC"])){ //codigo anterior
	    if(isset($_SESSION["edit"])){
                $_SESSION["edit"]["authorSEC"][$idauthor]=1;
            }
            else{
                $_SESSION["tmp"]["authorSEC"][$idauthor]=1;
                //$respuesta->alert(print_r($_SESSION["tmp"], true));
                //$respuesta->alert("idautor esta seteado");
            }
        }

        $result=searchAuthorPriResult('ALL',$currentPage,$pageSize,$sAuthor);
        if(isset($result["Count"])){
            if($result["Count"]==0){
                $total=0;
            }
            else{
                $total=$result["Count"];
            }
        }
        else{
            $total=0;
        }

        $html="";
        if($idauthor!=""){
        	$html=searchAuthorSesionSecShow_sinXajax($idauthor);
        	if ($catAuthor=="AuthorPer") {
            	$respuesta->Assign("sesion_authorSEC","innerHTML",$html);
        	}
        	// elseif ($catAuthor=="AuthorInst") {
        		$respuesta->Assign("sesion_authorSEC_02","innerHTML",$html);
        	// }
            //$respuesta->script("xajax_searchAuthorSesionSecShow(".$idauthor.")");

        }

        //$respuesta->script("xajax_auxAuthorPriShow(5,$currentPage,xajax.getFormValues('autorPRI'),".$idauthor.",".$apellidoArray.")");
        $respuesta->script('xajax_searchAuthorPriShow("AUTHOR",'.$currentPage.','.$pageSize.',"'.$sAuthor.'","'.$idauthor.'","'.$apellidoArray.'")');
        $respuesta->script("xajax_paginatorShow($currentPage,$pageSize,$total,'autorPRI')");

        //$respuesta->alert(print_r($result["strSQL"],true));
        //$respuesta->alert($total);
        //$respuesta->alert($result["Query"]);
        $respuesta->Assign("rq_authorPRI","style.display","block");
        $respuesta->Assign("paginatorPRI","style.display","block");
        $respuesta->Assign("div_autor","style.display","none");

        return $respuesta;
}

	/*************************************************

	**************************************************/
function searchAuthorSecResult($idSearch,$currentPage,$pageSize,$sAuthor="",$radio=0){
	$strSQL="";
	$strSQLPRI="";
	$strSQLSEC="";

	//Verificamos el array principal es null
	if(isset($_SESSION["tmp"]["authorPRI"])){

	    //Recorremos el array en busca de los valores que tienen valor 1
	    //y lo formateamos a Ejm. $strSQL=1,2,3,
	    foreach ( $_SESSION["tmp"]["authorPRI"] as $newlist => $valor ) {
	        if( $valor == 1) {
	            $strSQLPRI.="'".$newlist."'";
	            //$strSQLPRI.=$newlist;
	        }
	    }
	}

	//Verificamos el array es null
	//if(isset($_SESSION["authorSEC"])){
		if(isset($_SESSION["tmp"]["authorSEC"])){
		    if($_SESSION["tmp"]["authorSEC"]!=""){

		        //Recorremos el array en busca de los valores que tienen valor 1
		        //y lo formateamos a Ejm. $strSQL=1,2,3,
		        foreach ( $_SESSION["tmp"]["authorSEC"] as $key => $valor ) {
		            if( $valor == 1) {
		                $strSQLSEC.="'".$key."',";
		                //$strSQLSEC.=$key.",";
		            }
		        }

				if($strSQLPRI!=""){
					$strSQL=$strSQLSEC.$strSQLPRI;
				}
				else{
					//A Ejm. $strSQL=1,2,3, le quitamos el último caracter que en este caso es una coma ","
					$strSQL = substr($strSQLSEC, 0,-1);
				}

		    }
			else{
		    	$strSQL="";
			}
		}
	    $result=searchAuthorSQL($idSearch,$currentPage,$pageSize,$sAuthor,$radio,$strSQL);
		return $result;
}

/*************************************************

**************************************************/

function formAuthorShow(){
	    //$respuesta = new xajaxResponse();
	    $html = '<p class="txt-azul">Registro de un nuevo autor</p>
	    <div id="mensaje"> </div>
	        <form id="autor" name="autor">
				<div class="campo-buscador">Nombre:</div>
				<div class="contenedor-caja-buscador-1">
					<input type="text" id="author_name" name="author_name" size="30" maxlength="30"  class="caja-buscador-2">
				</div>
				<div class="clear"></div>
				<div class="campo-buscador">Apellido Paterno:</div>
				<div class="contenedor-caja-buscador-1">
					<input type="text" id="author_surname" name="author_surname" class="caja-buscador-1">
				</div>
				<div style="clear:both"></div>
	            <input type="button" class="ui-state-default ui-corner-all" value="Registrar" onClick="xajax_registraAuthorShow(xajax.getFormValues(\'frmBiblio\'))"/>
	        </form>';
		    //$respuesta->Assign($divAuthor,"innerHTML",$html);
	    return $html;
}

function readSessionArea(){
	if(isset($_SESSION["edit"])){
	    $recuperar=$_SESSION["edit"];
	}
	elseif(isset($_SESSION["tmp"])){
	    $recuperar=$_SESSION["tmp"];
	 }

	    if(isset($recuperar["areasSEC"])){
	        $keys=array_keys($recuperar["areasSEC"]);
	        $range="";
	        for($i=0;$i<count($keys);$i++){
	            $range.=$keys[$i].",";
	        }
	        $range=substr($range,0, strlen($range)-1);
	    }
	    else{
	        $range="";
	    }
	    return (string)$range;
	}




function subArea($idarea=0){

	$titulo="Sub &Aacute;reas de ".$_SESSION["area_description"];
		$respuesta = new xajaxResponse();

	if(isset($_SESSION["edit"])){
	    $recuperar=$_SESSION["edit"];
	}
	elseif(isset($_SESSION["tmp"])){
	    $recuperar=$_SESSION["tmp"];
	}

    //$result=iniAreaResult("single",$idarea);
        $desc = array("IDI","Operaciones","Cielo","EI","IT");
	$id = array(1,2,3,4,5);

    $html="";

        for($i=0;$i<count($id);$i++){
            $key = $id[$i];
            if(isset($recuperar["subAreas"][$key])){
                $html.="<p><input type=checkbox checked onclick=\"xajax_registerSubAreas('".$id[$i]."','".$desc[$i]."');\" />&nbsp;".$desc[$i]."</p>";
            }
            else{
                $html.="<p><input type=checkbox onclick=\"xajax_registerSubAreas('".$id[$i]."','".$desc[$i]."');\"  />&nbsp;".$desc[$i]."</p>";
            }
        }

    $respuesta->Assign("divSubAreas","innerHTML",$html);
    $respuesta->Assign("titSubAreas","innerHTML",$titulo);

    return $respuesta;
}

function newThemeCheck($form){

    $check["Error"]=0;
	if($form["selectArea"]==0){
        $check["Msg"]="Seleccione al menos un área";
        $check["Error"]=1;
    }
	elseif(strlen(trim($form["theme_description"]))==0){
        $check["Msg"]="Debe escribir un tema";
        $check["Error"]=1;
	}
    return $check;
}

function URLopen($url){
        // Fake the browser type
        ini_set('user_agent','MSIE 4\.0b2;');

        $dh = fopen("$url",'r');
        $result = fread($dh,8192);
        return $result;
}



function newPonencia($iddata=0,$action){
    $objResponse = new xajaxResponse();

        if(isset($_SESSION["edit"])){
            $recuperar=$_SESSION["edit"];
        }
        else{
            $recuperar=$_SESSION["tmp"];
        }

        $date_ing=isset($recuperar["date_ing"])?$recuperar["date_ing"]:"";

        $areaPRI=isset($_SESSION["idarea"])?$_SESSION["idarea"]:"";
        $tipoDocumento=$_SESSION["tipoDocumento"];
        $subcategory=$_SESSION["subcategory"];
        $tipoDocumento=$_SESSION["tipoDocumento"];
        $subcategory=$_SESSION["subcategory"];

	/********************Validar*********************************************************/
	$idsubcategory=isset($_SESSION["idsubcategory"])?$_SESSION["idsubcategory"]:0;
	$resultCheck=validarPonencias($idsubcategory,$areaPRI);

 	// $objResponse->alert(print_r($resultCheck,TRUE));

	if ($resultCheck["Error"]==1){
        $objResponse->alert($resultCheck["Msg"]);
        $objResponse->script($resultCheck["funcion"]);
        $objResponse->script($resultCheck["focus"]);
	}
	/*****************************************************************************/
	else{
        $_SESSION["publicaciones"]["areaPRI"]=$areaPRI;
        $_SESSION["publicaciones"]["date_ing"]=$date_ing;
        $_SESSION["publicaciones"]["month_pub"]=$resultCheck["month_pub"];
        $_SESSION["publicaciones"]["year_pub"]=$resultCheck["year_pub"];

        $_SESSION["publicaciones"]["title"]=$resultCheck["title"];


        //format book
        if ($resultCheck["idfbook"]==3) {
	        $_SESSION["publicaciones"]["idfbook"]=$resultCheck["idfbook"];
        }
        else{
        	$_SESSION["publicaciones"]["idfbook"]=$resultCheck["idfbook"];
        }

        $_SESSION["publicaciones"]["idfbook"]=$resultCheck["idfbook"];
        $_SESSION["publicaciones"]["formatbook"]=$resultCheck["fbook_descripcion"];
        $_SESSION["publicaciones"]["idtipoPonencia"]=$resultCheck["idtipoPonencia"];

        $_SESSION["publicaciones"]["ISBN"]=$resultCheck["ISBN"];
        $_SESSION["publicaciones"]["CallNumber"]=$resultCheck["CallNumber"];
        $_SESSION["publicaciones"]["publication"]=$resultCheck["publication"];
        $_SESSION["publicaciones"]["edition"]=$resultCheck["edition"];
        $_SESSION["publicaciones"]["subject"]=$resultCheck["subject"];
        $_SESSION["publicaciones"]["description_physical"]=$resultCheck["description_physical"];
        $_SESSION["publicaciones"]["summary"]=$resultCheck["summary"];

        // if (isset($_SESSION["required"])) {
        	if ($resultCheck["ISSN"]!="") {
        		$_SESSION["publicaciones"]["ISSN"]=$resultCheck["ISSN"];
        	}
        	if ($resultCheck["languaje"]!="") {
        		$_SESSION["publicaciones"]["languaje"]=$resultCheck["languaje"];
        	}
        	if ($resultCheck["NumLC"]!="") {
        		$_SESSION["publicaciones"]["NumLC"]=$resultCheck["NumLC"];
        	}
	        if ($resultCheck["NumDewey"]!="") {
	        	$_SESSION["publicaciones"]["NumDewey"]=$resultCheck["NumDewey"];
	        }
	        if ($resultCheck["Class_IGP"]!="") {
	        	$_SESSION["publicaciones"]["Class_IGP"]=$resultCheck["Class_IGP"];
	        }
	        if ($resultCheck["EncMat"]!="") {
	        	$_SESSION["publicaciones"]["EncMat"]=$resultCheck["EncMat"];
	        }
	        if ($resultCheck["OtherTitles"]!="") {
	        	$_SESSION["publicaciones"]["OtherTitles"]=$resultCheck["OtherTitles"];
	        }
	        if ($resultCheck["Periodicidad"]!="") {
	        	$_SESSION["publicaciones"]["Periodicidad"]=$resultCheck["Periodicidad"];
	        }
	        if ($resultCheck["Serie"]!="") {
	        	$_SESSION["publicaciones"]["Serie"]=$resultCheck["Serie"];
	        }
	        if ($resultCheck["NoteGeneral"]!="") {
	        	$_SESSION["publicaciones"]["NoteGeneral"]=$resultCheck["NoteGeneral"];
	        }
	        if ($resultCheck["NoteTesis"]!="") {
	        	$_SESSION["publicaciones"]["NoteTesis"]=$resultCheck["NoteTesis"];
	        }
	        if ($resultCheck["NoteBiblio"]!="") {
	        	$_SESSION["publicaciones"]["NoteBiblio"]=$resultCheck["NoteBiblio"];
	        }
	        if ($resultCheck["NoteConte"]!="") {
	        	$_SESSION["publicaciones"]["NoteConte"]=$resultCheck["NoteConte"];
	        }
	        if ($resultCheck["DesPersonal"]!="") {
	        	$_SESSION["publicaciones"]["DesPersonal"]=$resultCheck["DesPersonal"];
	        }
	        if ($resultCheck["MatEntidad"]!="") {
	        	$_SESSION["publicaciones"]["MatEntidad"]=$resultCheck["MatEntidad"];
	        }
	        if ($resultCheck["Descriptor"]!="") {
	        	$_SESSION["publicaciones"]["Descriptor"]=$resultCheck["Descriptor"];
	        }
	        if ($resultCheck["Descriptor_geo"]!="") {
	        	$_SESSION["publicaciones"]["Descriptor_geo"]=$resultCheck["Descriptor_geo"];
	        }
	        if ($resultCheck["CongSec"]!="") {
	        	$_SESSION["publicaciones"]["CongSec"]=$resultCheck["CongSec"];
	        }
	        if ($resultCheck["TitSec"]!="") {
	        	$_SESSION["publicaciones"]["TitSec"]=$resultCheck["TitSec"];
	        }
	        if ($resultCheck["Fuente"]!="") {
	        	$_SESSION["publicaciones"]["Fuente"]=$resultCheck["Fuente"];
	        }
	        if ($resultCheck["NumIng"]!="") {
	        	$_SESSION["publicaciones"]["NumIng"]=$resultCheck["NumIng"];
	        }
	        if ($resultCheck["UbicElect"]!="") {
	        	$_SESSION["publicaciones"]["UbicElect"]=$resultCheck["UbicElect"];
	        }
	        if ($resultCheck["ModAdqui"]!="") {
	        	$_SESSION["publicaciones"]["ModAdqui"]=$resultCheck["ModAdqui"];
	        }
	        if ($resultCheck["Catalogador"]!="") {
	        	$_SESSION["publicaciones"]["Catalogador"]=$resultCheck["Catalogador"];
	        }

        // }

        // $_SESSION["publicaciones"]["idclaseEvento"]=$resultCheck["idclaseEvento"];
        // $_SESSION["publicaciones"]["claseEvento_description"]=$resultCheck["claseEvento_description"];


        $tipoDocumento=$_SESSION["tipoDocumento"];

        if(isset($_SESSION['edit'])){

            if(isset($_SESSION['edit']['pdf_upload'])){

                $archivoUpload=isset($recuperar["pdf"])?$recuperar["pdf"]:$_SESSION['edit']['pdf_upload'];
                $destino="data/$tipoDocumento/".$archivoUpload;

	//Reemplazar el Archivo//
        //Si se sube un nuevo archivo borrar el archivo anterior
        if (isset($_SESSION['edit']['pdf'])){//si parametro pdf del xml existe

            if($_SESSION['edit']['pdf']!=""){//si parametro pdf del xml no está vacío

                $ruta="data/$tipoDocumento/".$_SESSION['edit']['pdf'];
                if(is_file($ruta)){
                    exec("rm -rf ".$ruta);
                }
            }
        }
	//Reemplazar el Archivo//


                    if (copy($_SESSION['edit']['ruta_pdf_temporal'],$destino)){
	                $_SESSION["publicaciones"]["pdf"]=$archivoUpload;
	                @unlink($_SESSION['edit']['ruta_pdf_temporal']);
	                unset($_SESSION['edit']['ruta_pdf_temporal']);
	            }
	            else{
	                $objResponse->alert("No se pudo subir el archivo");
	            }
            }
        }
        else{

	        if(isset($_SESSION['tmp']['ruta_pdf_temporal'])){
                    $archivoUpload=isset($recuperar["pdf"])?$recuperar["pdf"]:"";
                    $destino="data/$tipoDocumento/".$archivoUpload;

	            if (copy($_SESSION['tmp']['ruta_pdf_temporal'],$destino)){
	                $_SESSION["publicaciones"]["pdf"]=$archivoUpload;
	                @unlink($_SESSION['tmp']['ruta_pdf_temporal']);
	                unset($_SESSION['tmp']['ruta_pdf_temporal']);
	            }
	            else{
	                $objResponse->alert("No se pudo subir el archivo");
	            }
	        }
            }

        //multi upload files
        $files_array = isset($recuperar["files"])?$recuperar["files"]:$_SESSION['edit']['files'];
        if (isset($_SESSION["tmp"]["files"])) {
        	$_SESSION["publicaciones"]["files"] = $files_array;
        }
        elseif (isset($_SESSION["edit"]["files"])) {
        	$_SESSION["publicaciones"]["files"] = $files_array;
        }
        //---

		// arrayTheme();
		// arrayAreas();
		// arrayPermission();
		// arrayPermissionKey();

	if(isset($_SESSION['edit']['pdf'])){
	    if($_SESSION['edit']['pdf']!=""){
	        $_SESSION["publicaciones"]["pdf"]=$_SESSION['edit']['pdf'];
	    }
	}
    // $objResponse->alert(print_r($_SESSION["publicaciones"],TRUE));

        $xml= arrayToXml($_SESSION["publicaciones"],"book");


		$newPonenciaSQL=newPonenciaSQL($action,$iddata,4,$xml);
		//$objResponse->alert(print_r($newPonenciaSQL,TRUE));

                if ($newPonenciaSQL["Error"]==-100){
                    //$objResponse->alert(print_r($newPublicationSQL,true));
                    //$objResponse->alert($newPublicationSQL["Count"]);
                    $objResponse->alert($newPonenciaSQL["Msg"]);
                }
                else{

                    $objResponse->alert("Material Bibliográfico guardado satisfactoriamente");

                    $objResponse->script("xajax_formPonenciasShow()");


                    //Borramos las variables de sesion
                    if (isset($_SESSION["tmp"])){
                            unset($_SESSION["tmp"]);
                            if (isset($_SESSION["required"])) {
                            	unset($_SESSION["required"]);
                            }

                    }

                    if (isset($_SESSION["edit"])){
                            unset($_SESSION["edit"]);
                            unset($_SESSION["editar"]);
                             if (isset($_SESSION["required"])) {
                            	unset($_SESSION["required"]);
                            }
                    }
                    if(isset($_SESSION["publicaciones"])){
                            unset($_SESSION["publicaciones"]);

                    }
                }

                }

	// $objResponse->alert(print_r($_SESSION["required"],TRUE));
	return $objResponse;
}

function newRegisterBiblio($iddata, $action=0, $form, $btn_action=""){
	$objResponse = new xajaxResponse();
	unset($form["sAuthor"]);//para busqueda de author
	unset($form["theme_description"]);//para nuevo tema
	unset($form["NumCorr"]);//Numero correlativo
	unset($form["author_name"]);//de nuevo author - nombre
	unset($form["author_surname"]);//de nuevo author - apellido
	unset($form["author_type"]);//tipo de author
	unset($form["b_navigation"]);//navegador de paginas
	unset($form["searchbox"]);//navegador de paginas input
	unset($form["listAuthor_length"]);//de paginacion author
	unset($form["listtable_per_length"]);//de paginacion author
	unset($form["listtable_inst_length"]);//de paginacion author
    unset($form["author_date_1"]);//Fechas asociadas / Unidad Subordinada
    unset($form["author_date_2"]);//Termino de relación / Afiliación
    unset($form["data"]);//
    unset($form["id"]);//

	//temporalmente
	// unset($form["Descriptor"]);

	// $objResponse->alert(print_r($form["Theme"], TRUE));
	// if (isset($form["ax-files"]) && !empty($form["ax-files"])) {
	//    // $objResponse->alert(print_r("hola mundo", TRUE));
	//    $form["ax_files"] = $form["ax-files"];
	//    if (empty($form["ax_files"][0])) {
	//    	   	unset($form["ax-file-list li"]);
	//    	}
	// }

    unset($form["ax-files"]);

    $newForm = $form;
    //no validar estos campos:
    unset($newForm["date_ing"]);
    unset($newForm["month"]);
    unset($newForm["Edition"]);
    unset($newForm["Theme"]);
    unset($newForm["Description"]);
    unset($newForm["year"]);
    unset($newForm["status"]);
    unset($newForm["ax_files"]);
    $diccionary = file_get_contents("js/diccionary.json");
    $diccionary_a = json_decode($diccionary,TRUE);
    // $exclude = array("date_ing"=>array(),"month"=>"0","year"=>"");
    // $newForm = array_diff($newForm, $exclude);
    if (isset($form["Theme"]) ) {
    	foreach ($form["Theme"] as $key => $value) {
    		if (trim($form["Theme"][$key]["detalle"])=="") {
    			$form["Theme"][$key]["detalle"]="&nbsp";
    		}
    		if (isset($form["Theme"][$key]["secundary"])) {
	    		if (is_array($form["Theme"][$key]["secundary"])) {
	    			foreach ($form["Theme"][$key]["secundary"] as $key_1 => $value_1) {
		    			if (trim($value_1)=="") {
		    				$form["Theme"][$key]["secundary"][$key_1]="&nbsp";
		    			}
		    		}
	    		}
	    		else{
	    			if (trim($form["Theme"][$key]["secundary"])=="") {
	    				$form["Theme"][$key]["secundary"]="&nbsp";
	    			}
	    		}
	    	}
    	}

    }
    // $objResponse->alert(print_r($newForm, TRUE));
    $i =0;
	foreach ($newForm as $key => $value) {
		//campos repetibles
		if (is_array($value)) {
			$j = $i;
			foreach ($newForm[$key] as $key1 => $value1) {
				if (is_array($value1)) {
					foreach ($newForm[$key][$key1] as $key2 => $value2) {
						if (trim($value2)=="") {
							$objResponse->assign($key."_0_error","innerHTML","Falta ingresar datos en este item");
							array_push($error, -100);
						// $error[$j] = -100;
						}
						else{
							$objResponse->assign($key."_0_error","innerHTML","");
							array_push($error, 100);
							// $error[$j] = 100;
						}
					}
				}
				else{
				// $newForm[$key][$key1] = addslashes($value1);
					if (trim($value1)=="") {
						$objResponse->assign($key."_".$key1."_error","innerHTML","Falta ingresar datos en este item");
						$error[$j] = -100;
					}
					else{
						$objResponse->assign($key."_".$key1."_error","innerHTML","");
						$error[$j] = 100;
					}
					$j++;
				}
			}
			$i = $j;

		}
		//campos no repetibles
		else{
			// $newForm[$key] = addslashes($value);
			if (trim($value)=="") {
				$objResponse->assign($key."_error","innerHTML","Ingrese dato en ".$diccionary_a["campos"][$key]."");
				$error[$i] = -100;
				}

			else{
				// validacion dewey repetido
				if ($key=="NumDewey") {

					$valid_dewey = search_dewey($form["NumDewey"],$iddata);
					if ($valid_dewey["Count"]>0) {
						array_push($error, -100);
						$objResponse->assign($key."_error","innerHTML","El número Dewey ya existe");
					}
					else{
						$objResponse->assign($key."_error","innerHTML","");
						$error[$i] = 100;
					}
				}
				elseif ($key=="NumEjem") {
					if ($form["NumEjem"]!=count($form["state"])) {
						array_push($error, -100);
						$objResponse->assign($key."_error","innerHTML","El número de ejemplares y cantidad de estados no coinciden, vuelva ingresar");
					}
					else{
						$objResponse->assign($key."_error","innerHTML","");
					}
				}
				else{
					$objResponse->assign($key."_error","innerHTML","");
					$error[$i] = 100;
				}

			}

		}
	$i++;
	}
    // $objResponse->alert(print_r($error,true));
	if (!(in_array(-100,$error))) {

		if (isset($_SESSION["publicaciones"])) {
			$_SESSION["publicaciones"] = array_merge($_SESSION["publicaciones"],$form);
		}

		else{
			$_SESSION["publicaciones"] = $form;
		}

		// $objResponse->alert(print_r($error,TRUE));
		// $_SESSION["publicaciones"]=array("dias"=>array("01"=>"lunes","02"=>"martes"),"meses"=>array("01"=>"enero","02"=>"marzo"));
		// $objResponse->alert(print_r($form["ax_files"],TRUE));

		$xml = arrayToXml($_SESSION["publicaciones"],"book");

		$newPonenciaSQL=newPonenciaSQL($action,$iddata,4,$xml,$form["sede"]);

                if ($newPonenciaSQL["Error"]==1){
                    //$objResponse->alert(print_r($newPublicationSQL,true));
                    //$objResponse->alert($newPublicationSQL["Count"]);
                    $objResponse->alert($newPonenciaSQL["Msg"]);
                }
                else{

                    $objResponse->alert("Material Bibliográfico guardado satisfactoriamente");
                    if ($btn_action==1) {
                    	$objResponse->script("xajax_formPonenciasShow()");

                    }else{
                    	// $objResponse->script('xajax_editBook($form["NumCorr"],1)');
                    	// $objResponse->alert(print_r($iddata, TRUE));
                    	// $objResponse->script('xajax_editBook($iddata,1)');
                    	if (isset($_SESSION["tmp"])) {
                    		$objResponse->script("
                    			$('input[value=\"GUARDAR Y NUEVO\"]')
                    			.attr({
                    				'onclick':'xajax_newRegisterBiblio(".$iddata.",\"UPD\",xajax.getFormValues(\"frmBiblio\"),1);',
                    				'value':'ACTUALIZAR Y NUEVO'
									});
                    			$('input[value=GUARDAR]')
                    			.attr({
                    				'onclick':'xajax_newRegisterBiblio(".$iddata.",\"UPD\",xajax.getFormValues(\"frmBiblio\"),2);',
                    				'value':'ACTUALIZAR'
                    				});
                    			");
                    	}
                    }
                    //Borramos las variables de sesion
                    if (isset($_SESSION["edit"])){
                            unset($_SESSION["edit"]);
                            unset($_SESSION["editar"]);
                            unset($form);
                        if (isset($_SESSION["required"])) {
                           	unset($_SESSION["required"]);
                        }
                    }
	                if(isset($_SESSION["publicaciones"])){
	                    if ($btn_action!="2") {
	                    	unset($_SESSION["publicaciones"]);
	                    }
	                }
                    if (isset($_SESSION["tmp"])){
                            unset($_SESSION["tmp"]);
                            if (isset($_SESSION["required"])) {
                            	unset($_SESSION["required"]);
                            }
                    }
                }
	}
	else{
		$objResponse->alert(print_r("Algunos campos requeridos todavia no se han completado",TRUE));
	}


	return $objResponse;
}






function PubLanguaje($lan){
	$objResponse = new xajaxResponse();
	$html = "Esto son los valores de Lenguaje";
	$_SESSION["publicaciones"]["languaje"] = $lan;
	// foreach ($lan as $value) {
	// 	$html .= "<p>languaje 01: ".$value."</p>";
	// }
	// $objResponse->assign("prueba1","innerHTML",$html);
	$objResponse->alert(print_r($_SESSION["publicaciones"]["languaje"],TRUE));
	return $objResponse;
}


function arrayAuthor(){
	if(isset($_SESSION["edit"])){
	    $recuperar=$_SESSION["edit"];
	}
	elseif(isset($_SESSION["tmp"])){
	    $recuperar=$_SESSION["tmp"];
	}

	$objResponse = new xajaxResponse();
		if (isset($recuperar["authorPRI"]["idauthor"])){
	            if (isset($_SESSION["publicaciones"]["authorPRI"])){
	                unset($_SESSION["publicaciones"]["authorPRI"]);
	            }

	            $idx=$recuperar["authorPRI"]["idauthor"];
	            $i=0;
	            foreach($idx as $key => $idauthor){
	                $_SESSION["publicaciones"]["authorPRI"]["idauthor$i"]=$idauthor;
	                $i++;
	            }

	            $firstx=$recuperar["authorPRI"]["author_first_name"];
	            $i=0;
	            foreach($firstx as $key => $author_first_name){
	                $_SESSION["publicaciones"]["authorPRI"]["author_first_name$i"]=$author_first_name;
	                $i++;
	            }

	            $secondx=$recuperar["authorPRI"]["author_second_name"];
	            $i=0;
	            foreach($secondx as $key => $author_second_name){
	                $_SESSION["publicaciones"]["authorPRI"]["author_second_name$i"]=$author_second_name;
	                $i++;
	            }

	            $surnamex=$recuperar["authorPRI"]["author_surname"];
	            $i=0;
	            foreach($surnamex as $key => $author_surname_name){
	                $author_surname_name=(str_replace("'","*",$author_surname_name));
	                $_SESSION["publicaciones"]["authorPRI"]["author_surname$i"]=$author_surname_name;
	                $i++;
	            }

	        }

		if (isset($recuperar["authorSEC"]["idauthor"])){
	            if (isset($_SESSION["publicaciones"]["authorSEC"])){
	                unset($_SESSION["publicaciones"]["authorSEC"]);
	            }


	                $idx=$recuperar["authorSEC"]["idauthor"];
	                $i=0;
	                foreach($idx as $key => $idauthor){
	                    $_SESSION["publicaciones"]["authorSEC"]["idauthor$i"]=$idauthor;
	                    $i++;
	                }

	                $firstx=$recuperar["authorSEC"]["author_first_name"];
	                $i=0;
	                foreach($firstx as $key => $author_first_name){
	                    $_SESSION["publicaciones"]["authorSEC"]["author_first_name$i"]=$author_first_name;
	                    $i++;
	                }

	                $secondx=$recuperar["authorSEC"]["author_second_name"];
	                $i=0;
	                foreach($secondx as $key => $author_second_name){
	                    $_SESSION["publicaciones"]["authorSEC"]["author_second_name$i"]=$author_second_name;
	                    $i++;
	                }

	                $surnamex=$recuperar["authorSEC"]["author_surname"];
	                $i=0;
	                foreach($surnamex as $key => $author_surname_name){
	                    $author_surname_name=(str_replace("'","*",$author_surname_name));
	                    $_SESSION["publicaciones"]["authorSEC"]["author_surname$i"]=$author_surname_name;
	                    $i++;
	                }

	        }

		return $recuperar;
}

function arrayPermission(){
	if(isset($_SESSION["edit"])){
	    $recuperar=$_SESSION["edit"];
	}
	elseif(isset($_SESSION["tmp"])){
	    $recuperar=$_SESSION["tmp"];
	 }

	if (isset($recuperar["permission"])){
		$i=0;

		if(isset($_SESSION["publicaciones"]["permisos"])){
			unset($_SESSION["publicaciones"]["permisos"]);
		}

		foreach($recuperar["permission"] as $key=>$value){
			$_SESSION["publicaciones"]["permisos"]["idpermission$key"]=$key;
			$i++;
		}

	}
}

function arrayPermissionKey(){
	if(isset($_SESSION["edit"])){
	    $recuperar=$_SESSION["edit"];
	}
	elseif(isset($_SESSION["tmp"])){
	    $recuperar=$_SESSION["tmp"];
	 }

	if (isset($recuperar["key"])){
		$i=0;

		if(isset($_SESSION["publicaciones"]["claves"])){
			unset($_SESSION["publicaciones"]["claves"]);
		}

		foreach($recuperar["key"] as $key=>$value){
			$_SESSION["publicaciones"]["claves"]["idkey$key"]=$key;
			$i++;
		}

	}
}

function arrayStatus(){

    if (isset($_SESSION["edit"]["status"])){
        $_SESSION["publicaciones"]["status"]=$_SESSION["edit"]["status"];

    }

}

?>
