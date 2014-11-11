<?php

	function registerArea($idarea){
	    $respuesta = new xajaxResponse();
	
		if(isset($_SESSION["editar"])){
		    if(isset($_SESSION["edit"]["areasSEC"][$idarea])){
		        unset($_SESSION["edit"]["areasSEC"][$idarea]);
		
		        $range=readSessionArea();
		        $script="xajax_otrosTemasShow('$range')";
		        $respuesta->script($script);
		
		    }
		    else{
		        $_SESSION["edit"]["areasSEC"][$idarea]=1;
		        $range=readSessionArea();
		        $script="xajax_otrosTemasShow('$range')";
		        $respuesta->script($script);
		    }
		}
		else{
		    if(isset($_SESSION["tmp"]["areasSEC"][$idarea])){
		        unset($_SESSION["tmp"]["areasSEC"][$idarea]);
		
		        $range=readSessionArea();
		        $script="xajax_otrosTemasShow('$range')";
		        $respuesta->script($script);
		
		    }
		    else{
		        $_SESSION["tmp"]["areasSEC"][$idarea]=1;
		        $range=readSessionArea();
		        $script="xajax_otrosTemasShow('$range')";
		        $respuesta->script($script);
		    }
		}
	    //$respuesta->alert(print_r($_SESSION, true));
	    return $respuesta;
	}


	function registerTheme($idtheme,$theme_description){
	    $respuesta = new xajaxResponse();
	
		if(isset($_SESSION["editar"])){
		    if(isset($_SESSION["edit"]["themes"][$idtheme])){
		        unset($_SESSION["edit"]["themes"][$idtheme]);
		    }
		    else{
		        $_SESSION["edit"]["themes"][$idtheme]=1;
		    }
		
		
		    if(isset($_SESSION["edit"]["themes_description"][$idtheme])){
		        unset($_SESSION["edit"]["themes_description"][$idtheme]);
		    }
		    else{
		        $_SESSION["edit"]["themes_description"][$idtheme]=$theme_description;
		    }
		}
		else{
		    if(isset($_SESSION["tmp"]["themes"][$idtheme])){
		        unset($_SESSION["tmp"]["themes"][$idtheme]);
		    }
		    else{
		        $_SESSION["tmp"]["themes"][$idtheme]=1;
		    }
		
		
		    if(isset($_SESSION["tmp"]["themes_description"][$idtheme])){
		        unset($_SESSION["tmp"]["themes_description"][$idtheme]);
		    }
		    else{
		        $_SESSION["tmp"]["themes_description"][$idtheme]=$theme_description;
		    }
		}
	    //$respuesta->alert(print_r($_SESSION["tmp"]["themes"], true));
	    return $respuesta;
	}




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




	

	function registerLugar($lugar){
	    $respuesta = new xajaxResponse();
	
	    if($lugar==""){
	        $respuesta->alert("Ingrese Lugar");
	    }
	    else{
	        if(isset($_SESSION["edit"])){
	            $_SESSION["edit"]["lugar"]=$lugar;
	        }
	        else{
	
	            $_SESSION["tmp"]["lugar"]=$lugar;
	        }
		}
	
	    return $respuesta;
	}



	function registerNomEvento($evento_description){
	    $respuesta = new xajaxResponse();
	
	    if($evento_description==""){
	        $respuesta->alert("Ingrese Nombre del Evento");
	    }
	    else{
	        if(isset($_SESSION["editar"])){
	            if($_SESSION["editar"]==1){
	                $_SESSION["edit"]["evento_description"]=$evento_description;
	            }
	        }
	        else{
	            $_SESSION["tmp"]["evento_description"]=$evento_description;
	        }
	
	
	        }
	    return $respuesta;
	}

	function registerCatEvento($categoriaEvento_id,$categoriaEvento_description){
	    $respuesta = new xajaxResponse();
	    
if(isset($_SESSION["edit"])){
    $recuperar=$_SESSION["edit"];
}
elseif(isset($_SESSION["tmp"])){
    $recuperar=$_SESSION["tmp"];
}
            
	    if($categoriaEvento_id==0){
	        $respuesta->alert("Necesita seleccionar la categoría del evento");
                $recuperar["idcategoriaEvento"]=0;
                $recuperar["categoriaEvento_description"]="";
	    }
	    else{
	        if(isset($_SESSION["editar"])){
	            if($_SESSION["editar"]==1){
	                $_SESSION["edit"]["idcategoriaEvento"]=$categoriaEvento_id;
	                $_SESSION["edit"]["categoriaEvento_description"]=$categoriaEvento_description;
	            }
	        }
	        else{
	            $_SESSION["tmp"]["idcategoriaEvento"]=$categoriaEvento_id;
	            $_SESSION["tmp"]["categoriaEvento_description"]=$categoriaEvento_description;
	        }
	
		}
                
                //$respuesta->alert(print_r($_SESSION["tmp"], true));
	    return $respuesta;
	}

	function registerClaseEvento($claseEvento_id,$claseEvento_description){
	    $respuesta = new xajaxResponse();

if(isset($_SESSION["edit"])){
    $recuperar=$_SESSION["edit"];
}
elseif(isset($_SESSION["tmp"])){
    $recuperar=$_SESSION["tmp"];
}
            
	    if($claseEvento_id==0){
	        $respuesta->alert("Necesita seleccionar la clase del evento");
                $_SESSION["edit"]["idclaseEvento"]=0;
                $_SESSION["edit"]["claseEvento_description"]="";                
	    }
	    else{
	        if(isset($_SESSION["editar"])){
	            if($_SESSION["editar"]==1){
	                $_SESSION["edit"]["idclaseEvento"]=$claseEvento_id;
	                $_SESSION["edit"]["claseEvento_description"]=$claseEvento_description;
	            }
	        }
	        else{
	            $_SESSION["tmp"]["idclaseEvento"]=$claseEvento_id;
	            $_SESSION["tmp"]["claseEvento_description"]=$claseEvento_description;
	        }
	
            }
                
                //$respuesta->alert(print_r($_SESSION["tmp"], true));
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



	function registerYearCompendio($yearCompendio){
	    $respuesta = new xajaxResponse();
	
	    if($yearCompendio==0){
	        $respuesta->alert("Ingrese Año de Compendio");
	    }
	    else{
	        if(isset($_SESSION["edit"])){
	            $_SESSION["edit"]["yearCompendio"]=$yearCompendio;
	
	        }
	        else{
	            $_SESSION["tmp"]["yearCompendio"]=$yearCompendio;
	        }
		}
                
                $respuesta->alert(print_r($_SESSION["tmp"], true));
	    return $respuesta;
	}
        
	



	function registerReference($referencia_id,$reference_description){
	    $respuesta = new xajaxResponse();

if(isset($_SESSION["edit"])){
    $recuperar=$_SESSION["edit"];
}
elseif(isset($_SESSION["tmp"])){
    $recuperar=$_SESSION["tmp"];
}
            
	    if($referencia_id==0){
	        $respuesta->alert("Necesita seleccionar una referencia");
                $recuperar["idcategoriaEvento"]=0;
                $recuperar["categoriaEvento_description"]="";
                
	    }
	    else{
	        if(isset($_SESSION["editar"])){
	            if($_SESSION["editar"]==1){                
	                $_SESSION["edit"]["idreference"]=$referencia_id;
                        $reference_description=addslashes($reference_description);
	                $_SESSION["edit"]["reference_description"]=$reference_description;
	                }
	        }
	        else{
	                $_SESSION["tmp"]["idreference"]=$referencia_id;
                        $reference_description=addslashes($reference_description);
	                $_SESSION["tmp"]["reference_description"]=$reference_description;
	        }
	
                //$respuesta->alert(print_r($_SESSION["tmp"], true));
		}
	
	    
	    return $respuesta;
	}



	
	function registerTitRes($form){
	    $respuesta = new xajaxResponse();
	
	
	    $title=addslashes($form["title"]);
	    $abstract=addslashes($form["abstrac"]);
	
	    if($title==""){
	        $respuesta->alert("Ingrese Título");
	    }
	    elseif($abstract==""){
	        $respuesta->alert("Ingrese Resumen");
	    }
	    else{
	        if(isset($_SESSION["edit"])){
	            $_SESSION["edit"]["titulo"]=$title;
	            $_SESSION["edit"]["resumen"]=$abstract;
	        }
	        else{
	            $_SESSION["tmp"]["titulo"]=$title;
	            $_SESSION["tmp"]["resumen"]=$abstract;
	        }
	        $respuesta->alert("Título y Resumen guardados correctamente");
	    }
            
            //$respuesta->alert(print_r($_SESSION["edit"]["titulo"], true));
	    return $respuesta;
	}

	function registerTipoPonencia($tipoPonencia_id,$tipoPonencia_txt){
	    $respuesta = new xajaxResponse();
	    
	    if($tipoPonencia_id==0){
	        $respuesta->alert("Seleccione Tipo Ponencia");
	    }   
	    else{
	        if(isset($_SESSION["edit"])){
	            $_SESSION["edit"]["idtipoPonencia"]=$tipoPonencia_id;
	            $_SESSION["edit"]["tipoPonencia_description"]=$tipoPonencia_txt;           
	        }
	        else{
	            $_SESSION["tmp"]["idtipoPonencia"]=$tipoPonencia_id;
	            $_SESSION["tmp"]["tipoPonencia_description"]=$tipoPonencia_txt;         
	        }
		}
                
                //$respuesta->alert(print_r($_SESSION["tmp"], true));
                
	    return $respuesta;
	}

	function registerPrePorNombre($prePorNombre){
	    $respuesta = new xajaxResponse();
	
	    if($prePorNombre==""){
	        $respuesta->alert("Ingrese Nombre del presentador");
	    } 
	    else{
	        if(isset($_SESSION["edit"])){
	            $_SESSION["edit"]["prePorNombre"]=$prePorNombre;           
	        }
	        else{
	            $_SESSION["tmp"]["prePorNombre"]=$prePorNombre;           
	        }
		}
                //$respuesta->alert(print_r($_SESSION["tmp"], true));
	    return $respuesta;
	}

	function registerPrePorApellido($prePorApellido){
	    $respuesta = new xajaxResponse();
	
	    if($prePorApellido==""){
	        $respuesta->alert("Ingrese apellido del presentador");
	    } 
	    else{
	        if(isset($_SESSION["edit"])){
	            $_SESSION["edit"]["prePorApellido"]=$prePorApellido;           
	        }
	        else{
	            $_SESSION["tmp"]["prePorApellido"]=$prePorApellido;           
	        }
	
		}
                
                //$respuesta->alert(print_r($_SESSION["tmp"], true));
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

	function registerDatePub($value){
	
	     if(isset($_SESSION["edit"]["date_pub"])){
	         unset($_SESSION["edit"]["date_pub"]);
	         $_SESSION["edit"]["date_pub"]=$value;
	     }
	     else{
	         $_SESSION["tmp"]["date_pub"]=$value;
	     }
	}	

	function registerPermissionKey($idclave){
	
		if(isset($_SESSION["editar"])){
		    if(isset($_SESSION["edit"]["key"][$idclave])){
		        unset($_SESSION["edit"]["key"][$idclave]);
		    }
		    else{
		        $_SESSION["edit"]["key"][$idclave]=1;
		    }
		}
		else{
		    if(isset($_SESSION["tmp"]["key"][$idclave])){
		        unset($_SESSION["tmp"]["key"][$idclave]);
		    }
		    else{
		        $_SESSION["tmp"]["key"][$idclave]=1;
		    }
		}
	    //echo print_r($_SESSION["edit"]);
	}

	function registerStatus($idstatus){
	    
	     if(isset($_SESSION["edit"]["status"])){
	         unset($_SESSION["edit"]["status"]);
	         $_SESSION["edit"]["status"]=$idstatus;
	     }
	     else{
	        $_SESSION["tmp"]["status"]=$idstatus;
	     }        
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

	// function registerTitulo($title){
	//     $objResponse = new xajaxResponse();
            
           
	//     if($title==""){
	//         $objResponse->alert("Ingrese título");
	//         $objResponse->script("$('#title').focus()");                
	//     }
	//     else{
	//         if(isset($_SESSION["edit"])){
	//             $_SESSION["edit"]["titulo"]=addslashes($title);
	//         }
	//         else{
	//             $_SESSION["tmp"]["titulo"]=addslashes($title);
	//         }
	        
	//     }

            
	//     return $objResponse;
	// }
	function registerfbook($fbook_id, $fbook_des=""){
	    $objResponse = new xajaxResponse();           
	    if(isset($_SESSION["edit"])){
                $recuperar=$_SESSION["edit"];
            }
        elseif(isset($_SESSION["tmp"])){
                $recuperar=$_SESSION["tmp"];
            }

	    if($fbook_id==999){
	        $objResponse->alert(print_r("Seleccione un formato",TRUE));
	        if(isset($_SESSION["edit"])){
                    $_SESSION["edit"]["idfbook"]=999;
                    $_SESSION["edit"]["fbook_descripcion"]="";
                }
                else{
                    $_SESSION["tmp"]["idfbook"]=999;
                    $_SESSION["tmp"]["fbook_descripcion"]="";

                }
	    }
	    
	    else{
	        if(isset($_SESSION["edit"])){
	            $_SESSION["edit"]["idfbook"]=$fbook_id;
	            $_SESSION["edit"]["fbook_descripcion"]=$fbook_des;
	        }
	        else{
	            $_SESSION["tmp"]["idfbook"]=$fbook_id;
	            $_SESSION["tmp"]["fbook_descripcion"]=$fbook_des;
	        }
	
		}

		//$objResponse->alert(print_r($_SESSION["tmp"], true));
		//$objResponse->alert(print_r($fbook_id,TRUE));
	    return $objResponse;
	}
	function new_registerfbook($new_fbook){
		 $objResponse = new xajaxResponse();
		 if ($new_fbook=="") {
		 	$objResponse->alert("Debe ingresar nuevo formato");
		 }
		 else{
		 	if (isset($_SESSION["edit"])) {
		 		$_SESSION["edit"]["fbook_descripcion"]=$new_fbook;
		 	}
		 	else{
		 		$_SESSION["tmp"]["fbook_descripcion"]=$new_fbook;
		 	}		 	
		 }
		 
		 return $objResponse;

	}

	function registerISBN($ISBN){
		$objResponse = new xajaxResponse();

		if($ISBN==""){
	        $objResponse->alert("Debe Ingrese código ISBN");
	        $objResponse->script("$('#ISBN').focus()");                
	    }
	    else{
	        if(isset($_SESSION["edit"])){
	            $_SESSION["edit"]["ISBN"]=addslashes($ISBN);
	        }
	        else{
	            $_SESSION["tmp"]["ISBN"]=addslashes($ISBN);
	        }
	        
	    }

		return $objResponse ;
	}

	function registerCallNumber($CallNumber){
			$objResponse = new xajaxResponse();
			if($CallNumber==""){
	        	$objResponse->alert("Debe Ingresar código de ubicación física");
   		        $objResponse->script("$('#CallNumber').focus()");
	   		 }
	    	else{
	        	if(isset($_SESSION["edit"])){
	            $_SESSION["edit"]["CallNumber"]=addslashes($CallNumber);
	        	}
	        	else{
	            $_SESSION["tmp"]["CallNumber"]=addslashes($CallNumber);
	        }
	        
	    }

			return $objResponse;
		}


	// function registerPublication($publication){
	// 	$objResponse = new xajaxResponse();

	// 	if($publication==""){
	//         $objResponse->alert("Debe Ingrese lugar y fecha de publicación");
	//         $objResponse->script("$('#publication').focus()");
	//     }
	//     else{
	//         if(isset($_SESSION["edit"])){
	//             $_SESSION["edit"]["publication"]=addslashes($publication);
	//         }
	//         else{
	//             $_SESSION["tmp"]["publication"]=addslashes($publication);
	//         }
	        
	//     }

	// 	return $objResponse ;
	// }

	function registerEdition($edition){
		$objResponse = new xajaxResponse();

		if($edition==""){
	        $objResponse->alert("Debe Ingresar la edición");
	        $objResponse->script("$('#edition').focus()");                
	    }
	    else{
	        if(isset($_SESSION["edit"])){
	            $_SESSION["edit"]["edition"]=addslashes($edition);
	        }
	        else{
	            $_SESSION["tmp"]["edition"]=addslashes($edition);
	        }
	        
	    }

		return $objResponse ;
	}
	// function registerSubject($subject){
	// 	$objResponse = new xajaxResponse();

	// 	if($subject==""){
	//         $objResponse->alert("Debe Ingresar temas relacionados");                
	//         $objResponse->script("$('#subject').focus()");
	//     }
	//     else{
	//         if(isset($_SESSION["edit"])){
	//             $_SESSION["edit"]["subject"]=addslashes($subject);
	//         }
	//         else{
	//             $_SESSION["tmp"]["subject"]=addslashes($subject);
	//         }
	        
	//     }

	// 	return $objResponse ;
	// }

	function registerSumary($summary){
		$objResponse = new xajaxResponse();

		if($summary==""){
	        $objResponse->alert("Debe ingresar un Resumen");
	        $objResponse->script("$('#summary').focus()");                
	    }
	    else{
	        if(isset($_SESSION["edit"])){
	            $_SESSION["edit"]["summary"]=addslashes($summary);
	        }
	        else{
	            $_SESSION["tmp"]["summary"]=addslashes($summary);
	        }
	        
	    }
	    

		return $objResponse ;
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

	// function registerISSN($ISSN){
	//     $objResponse = new xajaxResponse();
 //        $_SESSION["required"]=1;
	//     if($ISSN==""){
	//         $objResponse->alert("Ingrese ISSN");
	//         $objResponse->script("$('#ISSN').focus()");                
	//     }
	//     else{
	//         if(isset($_SESSION["edit"])){
	//             $_SESSION["edit"]["ISSN"]=addslashes($ISSN);
	//         }
	//         else{

	//             $_SESSION["tmp"]["ISSN"]=addslashes($ISSN);
	//         }
	        
	//     }

	//     return $objResponse;
	// }

	// function registerlanguaje($languaje){
	//     $objResponse = new xajaxResponse();
        
	//     if($languaje==""){
	//         $objResponse->alert("Ingrese Idioma");
	//         $objResponse->script("$('#languaje').focus()");                
	//     }
	//     else{
	//         if(isset($_SESSION["edit"])){
	//             $_SESSION["edit"]["languaje"]=addslashes($languaje);
	//         }
	//         else{
	//             $_SESSION["tmp"]["languaje"]=addslashes($languaje);
	//         }
	        
	//     }

	//     return $objResponse;
	// }

	// function registerLC($numLC){
	//     $objResponse = new xajaxResponse();
        
	//     if($numLC==""){
	//         $objResponse->alert("Ingrese Numero de Clasificación LC");
	//         $objResponse->script("$('#numLC').focus()");                
	//     }
	//     else{
	//         if(isset($_SESSION["edit"])){
	//             $_SESSION["edit"]["numLC"]=addslashes($numLC);
	//         }
	//         else{
	//             $_SESSION["tmp"]["numLC"]=addslashes($numLC);
	//         }
	        
	//     }

	//     return $objResponse;
	// }
	// function registerNumDewey($numDewey){
	//     $objResponse = new xajaxResponse();
        
	//     if($numDewey==""){
	//         $objResponse->alert("Ingrese Número de Clasificación Dewey");
	//         $objResponse->script("$('#numDewey').focus()");                
	//     }
	//     else{
	//         if(isset($_SESSION["edit"])){
	//             $_SESSION["edit"]["numDewey"]=addslashes($numDewey);
	//         }
	//         else{
	//             $_SESSION["tmp"]["numDewey"]=addslashes($numDewey);
	//         }
	        
	//     }

	//     return $objResponse;
	// }
	// function registerClassIGP($ClassIGP){
	//     $objResponse = new xajaxResponse();
        
	//     if($ClassIGP==""){
	//         $objResponse->alert("Ingrese Número de Clasificación IGP");
	//         $objResponse->script("$('#ClassIGP').focus()");                
	//     }
	//     else{
	//         if(isset($_SESSION["edit"])){
	//             $_SESSION["edit"]["ClassIGP"]=addslashes($ClassIGP);
	//         }
	//         else{
	//             $_SESSION["tmp"]["ClassIGP"]=addslashes($ClassIGP);
	//         }
	        
	//     }

	//     return $objResponse;
	// }
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

	function registraReferenciaShow($referencia="",$abrev=""){
	    $respuesta = new xajaxResponse();
	    $result=registraReferenciaResult($referencia,$abrev);
	    $idreferenceultimo=$result["idreferenceultimo"];
	    $referenceultimo_txt=$result["reference_description_ultimo"];
	    $detalleReferenceultimo_txt=$result["reference_description_ultimo"];
	
	    $cadena="xajax_comboReferenciaShow($idreferenceultimo,1)";
	    $respuesta->script($cadena);
	
	        if(isset($_SESSION["editar"])){
	            if($_SESSION["editar"]==1){
	                $_SESSION["edit"]["idreference"]=$idreferenceultimo;
	                $_SESSION["edit"]["reference_description"]=$referenceultimo_txt;
	                $_SESSION["edit"]["reference_details"]=$detalleReferenceultimo_txt;
	            }
	        }
	        else{
	            if($_SESSION["tmp"]){
	                $_SESSION["tmp"]["idreference"]=$referencia_id;
	                $_SESSION["tmp"]["reference_description"]=$referenceultimo_txt;
	                $_SESSION["tmp"]["reference_details"]=$detalleReferenceultimo_txt;
	
	            }
	        }

	    $respuesta->assign("divNuevaRefe", "innerHTML", "");
	    return $respuesta;
	}


?>
