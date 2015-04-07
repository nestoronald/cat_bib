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
