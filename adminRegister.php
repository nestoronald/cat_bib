<?php

	function registerAreaAdministrativa($idarea){
	    $respuesta = new xajaxResponse();

		if(isset($_SESSION["editar"])){
		    if(isset($_SESSION["edit"]["areasAdministrativas"][$idarea])){
		        unset($_SESSION["edit"]["areasAdministrativas"][$idarea]);

		    }
		    else{
		        $_SESSION["edit"]["areasAdministrativas"][$idarea]=1;

		    }
		}
		else{
		    if(isset($_SESSION["tmp"]["areasAdministrativas"][$idarea])){
		        unset($_SESSION["tmp"]["areasAdministrativas"][$idarea]);
		    }
		    else{
		        $_SESSION["tmp"]["areasAdministrativas"][$idarea]=1;
		    }
		}
	    //$respuesta->alert(print_r($_SESSION["edit"], TRUE));
	    return $respuesta;
	}


/**************************************************
Funcion que muestra un combo
***************************************************/

	function registerDayPub($day_pub){
	    $respuesta = new xajaxResponse();

	    if($day_pub==0){
	        $respuesta->alert("Ingrese día de publicación");
	    }
	    else{
	        if(isset($_SESSION["edit"])){
	            $_SESSION["edit"]["day_pub"]=$day_pub;

	        }
	        else{
	            $_SESSION["tmp"]["day_pub"]=$day_pub;
	        }
            }

                //$respuesta->alert(print_r($_SESSION["edit"], true));
	    return $respuesta;
	}

	function registerYearPub($year_pub){
	    $respuesta = new xajaxResponse();

	    if($year_pub==0){
	        $respuesta->alert("Ingrese Año de Publicación");
	    }
	    else{
	        if(isset($_SESSION["edit"])){
	            $_SESSION["edit"]["year_pub"]=$year_pub;

	        }
	        else{
	            $_SESSION["tmp"]["year_pub"]=$year_pub;
	        }
            }

                //$respuesta->alert(print_r($_SESSION["edit"]["year_pub"], true));
	    return $respuesta;
	}

	function registerMonthPub($month_pub,$desc_month_pub=""){
	    $respuesta = new xajaxResponse();

            if(isset($_SESSION["edit"])){
                $recuperar=$_SESSION["edit"];
            }
            elseif(isset($_SESSION["tmp"])){
                $recuperar=$_SESSION["tmp"];
            }

	    if($month_pub==0){
	        $respuesta->alert("Ingrese mes");
                if(isset($_SESSION["edit"])){
                    $_SESSION["edit"]["month_pub"]=0;
                    $_SESSION["edit"]["desc_month_pub"]="";
                }
                else{
                    $_SESSION["tmp"]["month_pub"]=0;
                    $_SESSION["tmp"]["desc_month_pub"]="";

                }
	    }
	    else{
	        if(isset($_SESSION["edit"])){
	            $_SESSION["edit"]["month_pub"]=$month_pub;
                    $_SESSION["edit"]["desc_month_pub"]=$desc_month_pub;

	        }
	        else{
	            $_SESSION["tmp"]["month_pub"]=$month_pub;
                $_SESSION["tmp"]["desc_month_pub"]=$desc_month_pub;
	        }
            }

                //$respuesta->alert(print_r($_SESSION["tmp"], true));
                //$respuesta->alert($month_pub);
	    return $respuesta;
	}

	function registerInst_Ext($inst_ext){
	    $respuesta = new xajaxResponse();

	    //$inst_ext=$form["inst_ext"];

	    if($inst_ext==""){
	        $respuesta->alert("Ingrese institución externa");
	    }
	    else{
	        if(isset($_SESSION["edit"])){
	            $_SESSION["edit"]["inst_ext"]=$inst_ext;
	        }
	        else{
	            $_SESSION["tmp"]["inst_ext"]=$inst_ext;

	        }
	    }

            //$respuesta->alert(print_r($_SESSION["tmp"], TRUE));

	    return $respuesta;
	}

	function registerTitPrePor($form){
	    $respuesta = new xajaxResponse();

	    $title=addslashes($form["title"]);
	    $prePorNombre=$form["prePorNombre"];
	    $prePorApellido=$form["prePorApellido"];

	    if($title==""){
	        $respuesta->alert("Ingrese Título");
	    }
	    elseif($prePorNombre==""){
	        $respuesta->alert("Ingrese nombre del presentado");
	    }
	    elseif($prePorApellido==""){
	        $respuesta->alert("Ingrese Apellido del Presentado");
	    }
	    else{
	        if(isset($_SESSION["edit"])){
	            $_SESSION["edit"]["titulo"]=$title;
	            $_SESSION["edit"]["prePorNombre"]=$prePorNombre;
	            $_SESSION["edit"]["prePorApellido"]=$prePorApellido;
	        }
	        else{
	            $_SESSION["tmp"]["titulo"]=$title;
	            $_SESSION["tmp"]["prePorNombre"]=$prePorNombre;
	            $_SESSION["tmp"]["prePorApellido"]=$prePorApellido;

	        }

	        $respuesta->alert("Titulo y presentado por guardados correctamente");

	        }



	    return $respuesta;
	}

	function registerDateIng(){
            $respuesta = new xajaxResponse();
            /*
             $fecha=date("Y-m-d");
	     if(isset($_SESSION["edit"]["date_ing"])){
	         unset($_SESSION["edit"]["date_ing"]);
	         $_SESSION["edit"]["date_ing"]=$fecha;
	     }
	     else{
	     */
	     if(!isset($_SESSION["edit"]["date_ing"])){
		$fecha=date("Y-m-d");
		$_SESSION["tmp"]["date_ing"]=$fecha;
	     }

             //$respuesta->alert(print_r($_SESSION["tmp"], true));
             return $respuesta;
	}

	function registerSubAreas($idarea){
	    $respuesta = new xajaxResponse();

		if(isset($_SESSION["editar"])){
		    if(isset($_SESSION["edit"]["subAreas"][$idarea])){
		        unset($_SESSION["edit"]["subAreas"][$idarea]);
		    }
		    else{
		        $_SESSION["edit"]["subAreas"][$idarea]=1;
		    }
		}
		else{
		    if(isset($_SESSION["tmp"]["subAreas"][$idarea])){
		        unset($_SESSION["tmp"]["subAreas"][$idarea]);
		    }
		    else{
		        $_SESSION["tmp"]["subAreas"][$idarea]=1;
		    }
		}
		//    $respuesta->alert(print_r($_SESSION["tmp"], TRUE));
	    return $respuesta;
	}

	function registerDescription_Physical($description_physical){
		$objResponse = new xajaxResponse();

		if($description_physical==""){
	        $objResponse->alert("Debe ingresar un Resumen");
	        $objResponse->script("$('#description_physical').focus()");
	    }
	    else{
	        if(isset($_SESSION["edit"])){
	            $_SESSION["edit"]["description_physical"]=addslashes($description_physical);
	        }
	        else{
	            $_SESSION["tmp"]["description_physical"]=addslashes($description_physical);
	        }

	    }

		return $objResponse ;
	}


	function register_input($val_input,$label,$idinput, $index=""){
		// $respuesta = new RegisterInput();
		// $objresponse = new xajaxResponse();
		// $_SESSION["required"]["$idinput"]=1;

		// $reg_response = $respuesta->register("$val_input",$label,$idinput, $index="");

		// if (isset($reg_response["msj"]) and $reg_response["msj"]!="") {
		// 	$objresponse->alert(print_r($reg_response["msj"],TRUE));
		// 	$objresponse->script($reg_response["script"]);
		// }

		// return $objresponse;
	}


	function registraAuthorResult($form_entrada){
	    $resultCheck=checkDataForm($form_entrada);
	    if ($resultCheck["Error"]==1){
	            $result["Msg"]=$resultCheck["Msg"];
	            $result["Error"]="completar";
	    }

	    else{
/*
	        $pNombre=strtolower($form_entrada["pNombre"]);
	        $sNombre=strtolower($form_entrada["sNombre"]);
	        $apellido=strtolower($form_entrada["apellido"]);
*/

	        //$pNombre=$form_entrada["pNombre"];
	        //$sNombre=$form_entrada["sNombre"];
	        //$apellido=$form_entrada["apellido"];

/*                if(ereg("'",$form_entrada["apellido"])){
                    $apellido=explode("'",$form_entrada["apellido"]);
                    $antes_caracter=ucfirst($apellido[0]);
                    $despues_caracter=$apellido[1];
                    $apellido=$antes_caracter."'".$despues_caracter;
                }
                else{
                    $apellido=strtolower($form_entrada["apellido"]);
                }
*/
	        $result=registraAuthorSQL($form_entrada);
	    }

		return $result;
	}

	function registraAuthorShow($form_entrada=""){
		$respuesta = new xajaxResponse();
                //$respuesta->alert(print_r($form_entrada, true));


		$result=registraAuthorResult($form_entrada);
                //$respuesta->alert(print_r($result, true));

		$error=isset($result["Error"])?$result["Error"]:"";

		switch($error){
		case "completar":
		    $respuesta->alert($result["Msg"]);
		break;
		case "existe":
		    $respuesta->alert($result["Msg"]);
		    $respuesta->assign('pNombre', 'value','');
		    $respuesta->assign('sNombre', 'value','');
		    $respuesta->assign('apellido', 'value','');

		break;
		case "registrado":
		    $respuesta->alert($result["Msg"]);
                    //$respuesta->alert($error);
		            $apellido=$result["apellido"];
		            $respuesta->assign('sAuthor', 'value',$apellido);
		            $respuesta->assign('author_name', 'value','');
		            $respuesta->assign('author_surname', 'value','');

		$respuesta->script("xajax_auxAuthorPriShow(5,1,xajax.getFormValues('autorPRI'))");

		break;
                case 4:
                    $respuesta->alert($result["Msg"]);
		break;
		}

		return $respuesta;
	}


	function registraReferenciaResult($referencia="",$abrev=""){
    	$result=registraReferenciaSQL($referencia,$abrev);
    	return $result;
	}


?>
