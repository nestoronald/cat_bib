<?php

	/**************************************************
	Contiene funciones para la busqueda de datos

	***************************************************/
	function searchBookSQL($form, $currentPage="", $pageSize="",$idauthor=0,$idbook=0,$theme_tag="") {
	 $dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
	 $dbh->query("SET NAMES 'utf8'");
	 $sql = "Select * from book";
     $sede = " AND sede = '".$_SESSION['users_sede']."'";
	 if (isset($form) and !empty($form)) {
	 	$form["tituloSearch"]=trim($form["tituloSearch"]); //original
	 	$form["tituloSearch_1"]= sanear_string($form["tituloSearch"]); //sin caracteres especiales
	 }
		// buscar simple
	  if ($form["search_option"]=="s_simple") {
	  		if (isset($form["search_cat"])) {
	  			$sql .= " where (ExtractValue(book_data,'book/tipo') ";
				if ($form["search_cat"]=="b_libros") {
					$sql .= " like '%libro%' ";
				}
				elseif ($form["search_cat"]=="b_tesis") {
					$sql .= " like '%tesis%' ";
				}
				elseif ($form["search_cat"]=="b_pub_periodica") {
					$sql .= " like '%publicacion%' ";
				}
				elseif ($form["search_cat"]=="b_mapas") {
					$sql .= " like '%mapas%' ";
				}
				else //if ($form["search_cat"]=="b_otros")
				{
					$sql .= " not like '%libro%'
							  and  ExtractValue(book_data,'book/tipo') not like '%tesis%'
							  and  ExtractValue(book_data,'book/tipo') not like '%publicacion%'
							  and  ExtractValue(book_data,'book/tipo') not like '%mapas%'";
				}
				$sql .=")";
                $sql .= isset($_SESSION["users_sede"])?$sede:"";

	  		}

			if(isset($form["tituloSearch"])){
				if(strlen(trim($form["tituloSearch"]))>0){
					$r = search_str($form["tituloSearch"]);
					if ($r["error"]!=-100){
						switch ($form["query_type"]) {
							case 'title':
								switch ($r["s"]) {
									case '*':
										$sql .=	" AND (ExtractValue(book_data,'book/title') like '% ".$r["str"]."% ' )";
										break;
									case '-':
										$sql .= " AND  (ExtractValue(book_data,'book/title') like '% ".$r["str_0"]." %'
												  AND ExtractValue(book_data,'book/title') not like '% ".$r["str_1"]." %' )";
										break;
									case '+':
										$sql .= " AND  (ExtractValue(book_data,'book/title') like '% ".$r["str_0"]." %'
												  AND ExtractValue(book_data,'book/title') like '% ".$r["str_1"]." %' )";
										break;
									case '"':
										$sql .=	" AND (ExtractValue(book_data,'book/title') like '% ".$r["str"]." %' )";
										break;
									case 'NEAR':
										$sql .= " AND  (ExtractValue(book_data,'book/title') like '% ".$r["str_0"]." %'
												  AND ExtractValue(book_data,'book/title') like '% ".$r["str_1"]." %' )";
										break;
									default:
										# code...
										break;
								}

								break;
							case 'author':
								#consulta por author
							case 'tema':
								switch ($r["s"]) {
									case '*':
										$sql .=	" AND (ExtractValue(book_data,'book/Theme/*/child::*') like '% ".$r["str"]."% ' )";
										break;
									case '-':
										$sql .= " AND  (ExtractValue(book_data,'book/Theme/*/child::*') like '% ".$r["str_0"]." %'
												  AND ExtractValue(book_data,'book/Theme/*/child::*') not like '% ".$r["str_1"]." %' )";
										break;
									case '+':
										$sql .= " AND  (ExtractValue(book_data,'book/Theme/*/child::*') like '% ".$r["str_0"]." %'
												  AND ExtractValue(book_data,'book/Theme/*/child::*') like '% ".$r["str_1"]." %' )";
										break;
									case '"':
										$sql .=	" AND (ExtractValue(book_data,'book/Theme/*/child::*') like '% ".$r["str"]." %' )";
										break;
									case 'NEAR':
										$sql .=	" AND (ExtractValue(book_data,'book/Theme/*/child::*') like '% ".$r["str"]."%' )";
										break;
									default:
										# code...
										break;
								}
							default:

							case 'anio':
								switch ($r["s"]) {
									case '*':
										$sql .=	" AND (ExtractValue(book_data,'book/FxIng') like '% ".$r["str"]."% ' )";
										break;
									case '-':
										$sql .= " AND  (ExtractValue(book_data,'book/FxIng') like '% ".$r["str_0"]." %'
												  AND ExtractValue(book_data,'book/FxIng') not like '% ".$r["str_1"]." %' )";
										break;
									case '+':
										$sql .= " AND  (ExtractValue(book_data,'book/FxIng') like '% ".$r["str_0"]." %'
												  AND ExtractValue(book_data,'book/FxIng') like '% ".$r["str_1"]." %' )";
										break;
									case '"':
										$sql .=	" AND (ExtractValue(book_data,'book/FxIng') like '% ".$r["str"]." %' )";
										break;
									case 'NEAR':
										$sql .=	" AND (ExtractValue(book_data,'book/FxIng') like '% ".$r["str"]."%' )";
										break;
									default:
										# code...
										break;
								}
								#consulta por anio
							default:
								# code...
								break;
						}
					}
					else {
						if (isset($form["query_type"])) {
								//Titulo
							if ($form["query_type"]=="title") {
									$sql .=	" AND (ExtractValue(book_data,'book/title') like '% ".$form["tituloSearch"]." %' ";
									$sql .=	" or ExtractValue(book_data,'book/title') like '% ".$form["tituloSearch"].", %' ";
									$sql .=	" or ExtractValue(book_data,'book/title') like '% ".$form["tituloSearch"]."; %' ";
									$sql .=	" or ExtractValue(book_data,'book/title') like '% ".$form["tituloSearch"].". %' ";
									$sql .=	" or ExtractValue(book_data,'book/title') like '% ".$form["tituloSearch"].": %' ";
									$sql .=	" or ExtractValue(book_data,'book/title') like '% ".$form["tituloSearch"]."- %' ";
									$sql .=	" or ExtractValue(book_data,'book/title') like '% ".$form["tituloSearch"]."\_ %' ";
									$sql .=	" or ExtractValue(book_data,'book/title') like '% ".$form["tituloSearch"]."/ %' )";
									// $sql .=	" AND  ExtractValue(book_data,'book/title') REGEXP '[[:<:]]".$form["tituloSearch"]."[[:>:]]' ";
									// $sql .=	" or ExtractValue(book_data,'book/title') REGEXP '[[:<:]]".$form["tituloSearch_1"]."[[:>:]]'   )";
							}
								//Autor
							elseif ($form["query_type"]=="author") {

									$author_flag=-100;
									$result = searchAuthorID($author_flag,"",$form["tituloSearch"]);
									if ($result["Count"]>0) {
										$id_format= "(";
										foreach ($result["idauthor"] as $value) {
											$id_format .="'".$value."',";
										}
										$id_format = substr($id_format, 0,-1);
										$id_format .= ")";
										$sql .=	" AND (ExtractValue(book_data,'book/authorPRI/idauthor0') in $id_format )";
									}
									else{
									 	//$sql .= $result["Query"];
	                                    $sql .= " AND (ExtractValue(book_data,'book/authorPRI/idauthor0') in -100 )";
									 }


							}
							elseif ($form["query_type"]=="tema") {
									$sql .=	" AND (ExtractValue(book_data,'book/Theme/*/child::*') like '%".$form["tituloSearch"]."%')";
							}
							// elseif ($form["query_type"]=="resp_pub") {
							// 	$sql .=	" WHERE (ExtractValue(book_data,'book/MatEnt') like '%".$form["tituloSearch"]."%' ";
							// }
							// elseif ($form["query_type"]=="reg_geo") {
							// 	$sql .=	" WHERE (ExtractValue(book_data,'book/Descriptor_geo') like '%".$form["tituloSearch"]."%' ";
							// }
							elseif ($form["query_type"]=="anio") {
								$sql .=	" AND (ExtractValue(book_data,'book/FxIng') like '%".$form["tituloSearch"]."%')";
							}

							//Otros
							else /* ($form["query_type"]=="b_all")*/ {
								$form["tituloSearch"]=(str_replace("'","*",$form["tituloSearch"]));
	                            $sql .= " AND (
	                               (ExtractValue(book_data,'book/title') != '".$form["tituloSearch"]."') AND
	                                   (
	                                    (ExtractValue(book_data,'book/child::*') like '%".$form["tituloSearch"]."%')";
	                            $sql .= " ";
	                            $sql .= " OR (ExtractValue(book_data,'book/*/child::*') like '%".$form["tituloSearch"]."%')";
								$sql .=	" OR (ExtractValue(book_data,'book/*/*/child::*') like '%".$form["tituloSearch"]."%')
	                                   )
	                                )";
							}
						}
					}
				}
			}
	  }
	  //opciones avanzadas
	  elseif ($form["search_option"]=="s_advanced") {
	  	 // $sql .= " WHERE ( ExtractValue(book_data,'book/tipo') like '%".$form["query_type"]."%') ";
	  	if (isset($form["a_category"])) {
	  		$sql .= " WHERE ( ExtractValue(book_data,'book/tipo')";

		  	if ($form["a_category"]=="a_libros") {
		  		$sql .= " like '%libro%' ";
		  	}
		  	elseif ($form["a_category"]=="a_tesis") {
		  		$sql .= " like '%tesis%' ";
		  	}
		  	elseif ($form["a_category"]=="a_mapas") {
		  		$sql .= " like '%mapas%' ";
		  	}
		  	elseif ($form["a_category"]=="a_pub_periodica") {
		  		$sql .= " like '%publicacion%' ";
		  	}
		  	else{
		  		$sql .= " not like '%libro%'
						and  ExtractValue(book_data,'book/tipo') not like '%tesis%'
						and  ExtractValue(book_data,'book/tipo') not like '%publicacion%'
						and  ExtractValue(book_data,'book/tipo') not like '%mapas%  ";
		  	}
		  	$sql .=")";
            $sql .= isset($_SESSION["users_sede"])?$sede:"";
		}
		$sql .= "and (";
		if (isset($form["a_fields_01"]) and !empty($form["a_fields_01"])) {

			$sql .= advanced_type_select($form["a_text1"],$form["a_fields_01"]);

		}
		if (isset($form["a_oper_1"]) and $form["a_oper_1"]!="a_oper") {
			if ($form["a_oper_1"]=="a_and") {
		 		$sql .=" and ";
		  	}
			elseif ($form["a_oper_1"]=="a_or") {
			  	$sql .=" or ";
			}
			elseif ($form["a_oper_1"]=="a_not") {
			  	$sql .=" and not ";
			}
		}
		if (isset($form["a_fields_02"]) and !empty($form["a_fields_02"])) {

			$sql .= advanced_type_select($form["a_text2"],$form["a_fields_02"]);
		}
		// $sql .= $form["a_oper_1"]=="a_not"?" ) ":"";

		if (isset($form["a_oper_2"]) and $form["a_oper_2"]!="a_oper") {
		  if ($form["a_oper_2"]=="a_and") {
		 		$sql .=" and ";
		  }
		  elseif ($form["a_oper_2"]=="a_or") {
		  	$sql .=" or ";
		  }
		  elseif ($form["a_oper_2"]=="a_not") {
		  	$sql .=" and not  ";
		  }
		}
		if (isset($form["a_fields_03"]) and $form["a_text3"]!="") {

			$sql .= advanced_type_select($form["a_text3"],$form["a_fields_03"]);
		}

		if (isset($form["a_languaje"])) {
			if ($form["a_languaje"]=="l_esp") {
				$sql .= "and (ExtractValue(book_data,'book/languaje') like '%sp%')";
			}
			elseif ($form["a_languaje"]=="l_eng") {
				$sql .= "and (ExtractValue(book_data,'book/languaje') like '%en%')";
			}
			elseif ($form["a_languaje"]=="l_fr") {
				$sql .= "and (ExtractValue(book_data,'book/languaje') like '%fr%')";
			}
			// else{
			// 	$sql .= "and  ExtractValue(book_data,'book/languaje') like '%'";
			// }
		}

		// if (isset($form["a_country"])) {
		// 	if ($form["a_country"]=="PE") {
		// 		$sql .= "and (ExtractValue(book_data,'book/Edition/country') like '%lima%')";
		// 	}
		// 	else{
		// 		$sql .= "and (ExtractValue(book_data,'book/Edition/country') not like '%lima%')";
		// 	}
		// }
		if (isset($form["a_year_asc"]) && isset($form["a_year_desc"])) {
			$asc = (int)$form["a_year_asc"];
			$desc = (int)$form["a_year_desc"];
			$years = rangue_year($asc,$desc);
			$result_1= search_years_edition($form,$years);

			// if (count($result)>0) {
			// $sql.="-- ".$result_1["idbook"][0];
			if ($result_1["option"]==1) {
				// comprueba si existe mas de una edicion
				if (count($result_1["idbook"])>0) {
					$sql .= " and (";
					$j=1;
					foreach ($result_1["idbook"] as $key => $value) {
						$sql .= "idbook = $value";
						if ($j < count($result_1["idbook"])) {
							$sql .= " or ";
						}
						$j++;
					}
					$sql .= " or ExtractValue(book_data,'book/Edition/year') BETWEEN '".$form["a_year_asc"]."' AND '".$form["a_year_desc"]."'
					)";
				}

			}
			else{
				$sql .="and (ExtractValue(book_data,'book/Edition/year') BETWEEN '".$form["a_year_asc"]."' AND '".$form["a_year_desc"]."')";
			}

			// }
			// $sql .= "and (ExtractValue(book_data,'book/Edition/year') BETWEEN '".$form["a_year_asc"]."' AND '".$form["a_year_desc"]."')";
			// $sql .= "and idbook=".$result["idbook"];
		}
		$sql .= ")"; //fin condicionales de categoria
	  }
	  	//busqueda de author por id
	  if ($_SESSION["origin"]=="frond") {
	    if ($idauthor!=0) {
	    	$sql = "Select * from book where ExtractValue(book_data,'book/authorPRI/idauthor0') = $idauthor and idbook!=$idbook";
	    }
	    elseif (trim($theme_tag)!="") {
	    	$sql = "Select * from book where ExtractValue(book_data,'book/Theme/*/detalle') like '%$theme_tag' and idbook!=$idbook";
	    }
	  }
	  // elseif($_SESSION["origin"]=="back"){
	  // 	if (isset($_SESSION["users_sede"])) {
	  // 		$sql .= " AND sede =  '".$_SESSION["users_sede"]."'";
	  // 	}
	  // }


		//paginar
		if($currentPage<>"" and $pageSize<>""){
			$limitIni = ($currentPage-1)*$pageSize;
	        $limitLon = $pageSize;
	        if (isset($form["a_list"])) {
				$limitLon = $form["a_list"];
			}
			$sql .=	" LIMIT $limitIni,$limitLon";
		}

		if($dbh->query($sql)){
			$i=0;
			foreach($dbh->query($sql) as $row) {
				$result["book_data"][$i]= $row["book_data"];
				$result["idbook"][$i]= $row["idbook"];
				$result["sede"][$i]= $row["sede"];
				$i++;
			}


			if(isset($result["idbook"])){
				$result["Count"]=count($result["idbook"]);
			}
			else{
				$result["Count"]=0;
			}

			$result["Error"]=0;

		}
		else{
			$result["Error"]=1;
		}

		$dbh = null;
		$result["Query"]=$sql;

		return $result;
	}
	function search_str($str=""){
		$result["error"]=100;
		$pos_as = strrpos($str, "*");
		$pos_co = strrpos($str, "\"");
		$pos_or = strrpos($str, "-");
		$pos_and = strrpos($str, "+");
		$pos_n = strpos($str, "NEAR");

		if ( $pos_as!==false && $pos_as==(strlen($str)-1) ) {
			$str = str_replace("*","",$str);
			$result["s"]="*";
		}
		elseif ($pos_co!==false && $pos_co==0) {
		    $str_1 = substr($str, 1);
		    $pos=strpos($str_1,"\"");
		    $len = strlen($str_1);
		    if ($pos !== false && $pos==($len-1)) {
		        $str = str_replace("\"","",$str);
		        $result["s"]='"';
		    }else{
		        $result["error"]=-100;
		    }
		}
		elseif ($pos_or!==false && $pos_or>0) {
			$str_1 =explode("-", $str);
			$result["s"]="-";
			if (strlen($str_1[1])>0) {
				$result["str_0"] = trim($str_1[0]);
				$result["str_1"] = trim($str_1[1]);
			}
			else{
				$result["error"]=-100;
			}
		}
		elseif ($pos_and!==false && $pos_and>0) {
			$str_1 =explode("+", $str);
			$result["s"]="+";
			if (strlen($str_1[1])>0) {
				$result["str_0"] = trim($str_1[0]);
				$result["str_1"] = trim($str_1[1]);
			}
			else{
				$result["error"]=-100;
			}
		}
		elseif($pos_n!==false){
			$str_1 =explode("NEAR", $str);
			$str = $str_1[0];
			$result["s"]="NEAR";
		}
		else{
			$result["error"]=-100;
		}
		$result["str"]=$str;
		return $result;
	}
	function sanear_string($string){

	    $string = trim($string);

	    $string = str_replace(
	        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
	        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
	        $string
	    );

	    $string = str_replace(
	        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
	        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
	        $string
	    );

	    $string = str_replace(
	        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
	        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
	        $string
	    );

	    $string = str_replace(
	        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
	        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
	        $string
	    );

	    $string = str_replace(
	        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
	        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
	        $string
	    );

	    $string = str_replace(
	        array('ñ', 'Ñ', 'ç', 'Ç'),
	        array('n', 'N', 'c', 'C',),
	        $string
	    );

	    // //Esta parte se encarga de eliminar cualquier caracter extraño     //
		// $string = str_replace(     //     array("\\", "¨", "º", "-", "~",     //
		// "#", "@", "|", "!", "\"",     //          "·", "$", "%", "&", "/",     //
		// "(", ")", "?", "'", "¡",     //          "¿", "[", "^", "`", "]",     //
		// "+", "}", "{", "¨", "´",     //          ">", "< ", ";", ",", ":",     //
		// ".", " "),     //     '',     //     $string     // );


	    return $string;
	}
	function rangue_year($asc, $desc){
		$years_result = array();
		if ($asc == $desc) {
			$years_result[0] = $asc;
		}
		elseif ($asc < $desc) {
			for ($i=$asc; $i <= $desc ; $i++) {
				array_push($years_result, $i);
			}
		}
		else{
			for ($i=$desc; $i <= $asc ; $i++) {
				array_push($years_result, $i);
			}

		}
		return $years_result;
	}

	function advanced_type_select($text,$field){
		$sql .= " ( ";
			if ($field=="a_titulo") {
				$sql .= " ExtractValue(book_data,'book/title') like '%".$text."%' ";
			}
			elseif ($field=="a_author") {
				$author=explode(" ", $text);

				$idauthor=-100;

				$result = searchAuthorID($idauthor,"",$text);
				if ($result["Count"]>0) {
					$id_format= "(";
					foreach ($result["idauthor"] as $value) {
						$id_format .="'".$value."',";
					}
					$id_format = substr($id_format, 0,-1);
					$id_format .= ")";
					$sql .=	" ExtractValue(book_data,'book/authorPRI/idauthor0') in $id_format";
				}

			}
			elseif ($field=="a_tema") {
				$sql .= " ExtractValue(book_data,'book/Theme/*/child::*') like '%".$text."%' ";
			}
			elseif ($field=="a_editor") {
				$sql .= "  ExtractValue(book_data,'book/Edition/editorial') like '%".$text."%' ";
			}
			else{
				$sql .="  ExtractValue(book_data,'book/title') like '%".$text."%'
				       or  ExtractValue(book_data,'book/Edition/Theme/*/child::*') like '%".$text."%'
				       or  ExtractValue(book_data,'book/Edition/editorial') like '%".$text."%' ";
			}
			$sql .= ")";
		return $sql;
	}

	function search_years_edition($form,$years){
		$result = years_edition();
		$respuesta["idbook"] = array();
		for ($i=0; $i < $result["Count"] ; $i++) {
			$pos=strpos(trim($result["year"][$i]),' ');
			if (!empty($pos)) {
				$respuesta["option"] = 1;
				$years_array = explode(' ', trim($result["year"][$i]));
				$respuesta["explode"] = $years_array;
				foreach ($years_array as $value) {
					(int)$value;
					if (in_array($value, $years)) {
						array_push($respuesta["idbook"], $result["idbook"][$i]);
						break;
					}
				}

			}
			else{
				$respuesta["option"]=0;

			}
		}
		return $respuesta;
	}
	function membersQuery($username,$pwd){
		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");
		if ($stmt = $dbh->prepare("SELECT * FROM members WHERE username = ? and password = ? LIMIT 1")) {
			$username=trim($username);
			$clave=md5(trim($pwd));
			$stmt->execute(array($username,$clave));
			$result=$stmt->fetch(PDO::FETCH_ASSOC);
			$respuesta["email"]=$result["email"];
			$respuesta["users_name"]=$result["username"];
			$respuesta["id"]=$result["id"];

			if($stmt->rowCount() == 1) {// member existe
				return $respuesta;
			}
			else{
				return -100;
			}
		}
		else{
			return -100;
		}
		$dbh = null;
	}
	function membersQuery_01($iduser){
		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");
		if ($stmt = $dbh->prepare("SELECT * FROM members WHERE id = ? LIMIT 1")) {
			$stmt->execute(array($iduser));
			$result=$stmt->fetch(PDO::FETCH_ASSOC);
			$result["users_name"]=$result["username"];
			if($stmt->rowCount() == 1) {// member existe
				return $result;
			}
			else{
				return -100;
			}
		}
		else{
			return -100;
		}
		$dbh = null;
	}
	function membersIGPQuery($user,$pwd){

		$dbh=cons("10.10.30.27","personal","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");
		$usuario=trim($user);
	    $clave=md5(trim($pwd));

        if ($stmt = $dbh->prepare("SELECT * FROM v_empleado_users WHERE users_name = ? and users_password= ? LIMIT 1")) {
        	$stmt->execute(array($usuario,$clave));
        	$result=$stmt->fetch(PDO::FETCH_ASSOC);
        	$result["email"]=$result["users_name"]."@igp.gob.pe";
        	if($stmt->rowCount() == 1) {// member existe
        		return $result;
        	}
        	else{
        		return -100;
        	}
        }
        else{
        	return -100;
        }
        $dbh = null;

	}
	function membersIGPQuery_01($iduser){

		$dbh=cons("10.10.30.27","personal","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");

        if ($stmt = $dbh->prepare("SELECT * FROM v_empleado_users WHERE idempleado = ? LIMIT 1")) {
        	$stmt->execute(array($iduser));
        	$result=$stmt->fetch(PDO::FETCH_ASSOC);
        	$result["email"]=$result["empleado_correo"]."@igp.gob.pe";
        	if($stmt->rowCount() == 1) {// member existe
        		return $result;
        	}
        	else{
        		return -100;
        	}
        }
        else{
        	return -100;
        }
        $dbh = null;

	}
	function loanBookQuery($idloan){
		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");
		// if ($stmt = $dbh->prepare("SELECT * FROM loanbook l, book b WHERE ( l.id_book = b.idbook AND id_loan = ? )")) {
		// 	$stmt->execute(array($idloan));
		// 	$result=$stmt->fetch(PDO::FETCH_ASSOC);
		// 	if($stmt->rowCount() > 0) {// loanbook exist
		// 		return $result;
		// 	}
		// 	else{
		// 		return -100;
		// 	}
		// }
		// else{
		// 	return -100;
		// }
		$sql = "SELECT * FROM loanbook l, book b WHERE ( l.id_book = b.idbook AND id_loan = ".$idloan.")";
		$i=0;
		if($dbh->query($sql)){
		    foreach($dbh->query($sql) as $row) {
		        $result["id_loan"][$i]= $row["id_loan"];
		        $result["id_loanbook"][$i]= $row["id_loanbook"];
		        $result["idbook"][$i]= $row["idbook"];
		        $result["book_data"][$i]= $row["book_data"];
		        $i++;
		    }

		    if(isset($result["id_loanbook"])){
		        $result["Count"]=count($result["id_loanbook"]);
		    }
		    else{
		        $result["Count"]=0;
		    }

		    $result["Error"]=100;

		}
		else{
		    $result["Error"]=-100;
		}

		$dbh = null;
		$result["Query"]=$sql;

		return $result;
		$dbh = null;
	}

	function Query_search_advanced($value='')
	{
		if($dbh->query($sql)){
			$i=0;
			foreach($dbh->query($sql) as $row) {
				$result["book_data"][$i]= $row["book_data"];
				$result["idbook"][$i]= $row["idbook"];
				$i++;
			}
			if(isset($result["idbook"])){
				$result["Count"]=count($result["idbook"]);
			}
			else{
				$result["Count"]=0;
			}

			$result["Error"]=0;

		}
		else{
			$result["Error"]=1;
		}

		$dbh = null;
		$result["Query"]=$sql;

		return $result;
	}
	function insertReserva($form=""){
		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");

		// $sql="insert into reservation()";
  //       $sql.="values (";
  //       $sql.="'".$form["name"]."',";
  //       $sql.="'".$form["email"]."',";
  //       $sql.="'".$form["dni"]."',";
  //       $sql.="'".$form["dir"]."',";
  //       $sql.="'".$form["tel"]."',";
  //       $sql.="'".$form["fx_reserva"]."',";
  //       $sql.="'".$form["idbook"]."'";
  //       $sql.=")";

		$sql = "insert into reservation(data) values('$form')";
	    if($dbh->query($sql)){
	        $result["Error"]=100;
	    }
	    else{
	        $result["Error"]=-100;
	    }

	    $dbh = null;
	    $result["Query"]=$sql;
	    return $result;
	}

	// select Loan table
	function loanQuery($action="",$form=""){
		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");
	    $sql = "SELECT * FROM loan WHERE 1 ORDER BY id_loan DESC";
	    if ($action=="last") {
	    	$sql .= " LIMIT 0 , 1";
	    }
	    // if (isset($form) and !empty($form)) {
	    // 	$sql .= " WHERE ";
	    // 	foreach ($form as $key => $value) {
	    // 		$sql .= " id_loan like '%".$value."%' or fx_register ";
	    // 	}
	    // 	$sql .= " WHERE 1 ORDER BY id_loan DESC LIMIT 0 , 1";
	    // }
	    $i=0;
	    if($dbh->query($sql)){
	        foreach($dbh->query($sql) as $row) {
	            $result["id_loan"][$i]= $row["id_loan"];
	            $result["fx_register"][$i]= $row["fx_register"];
	            $result["fx_reserve"][$i]= $row["fx_reserve"];
	            $result["state"][$i]= $row["state"];
	            $result["fx_loan_ini"][$i]= $row["fx_loan_ini"];
	            $result["fx_loan_exit"][$i]= $row["fx_loan_exit"];
	            $result["iduser"][$i]= $row["iduser"];
	            $result["user_type"][$i]= $row["user_type"];
	            $i++;
	        }

	        if(isset($result["id_loan"])){
	            $result["Count"]=count($result["id_loan"]);
	        }
	        else{
	            $result["Count"]=0;
	        }

	        $result["Error"]=100;

	    }
	    else{
	        $result["Error"]=-100;
	    }

	    $dbh = null;
	    $result["Query"]=$sql;

	    return $result;
	}
	function book_query($idbook){
		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");
		if ($stmt = $dbh->prepare("SELECT * FROM book WHERE idbook = ? LIMIT 1")) {
			$stmt->execute(array($idbook));
			$result=$stmt->fetch(PDO::FETCH_ASSOC); ;
			if($stmt->rowCount() == 1) {
				return $result;
			}
			else {
				return -100;
			}
		}
	}
	function loanBookMembersQuery($action="",$iduser=0){
		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");
	    $sql = "SELECT * FROM loan as L, loanbook as LB, book as B
	    		where (
	    			L.id_loan = LB.id_loan AND
	    			LB.id_book = B.idbook ";
	   if ($iduser!=0) {
	    	$sql.= " AND L.iduser = $iduser";
	    }
	    $sql.=" ) ORDER BY L.id_loan DESC";
	    // if ($action=="last") {
	    // 	$sql .= " WHERE 1 ORDER BY id_loan DESC LIMIT 0 , 1";
	    // }

	    $i=0;
	    if($dbh->query($sql)){
	        foreach($dbh->query($sql) as $row) {
	            $result["id_loan"][$i]= $row["id_loan"];
	            $result["fx_register"][$i]= $row["fx_register"];
	            $result["fx_reserve"][$i]= $row["fx_reserve"];
	            $result["state"][$i]= $row["state"];
	            $result["fx_loan_ini"][$i]= $row["fx_loan_ini"];
	            $result["fx_loan_exit"][$i]= $row["fx_loan_exit"];
	            $result["iduser"][$i]= $row["iduser"];
	            $result["username"][$i]= $row["username"];
	            $result["email"][$i]= $row["email"];
	            $result["idbook"][$i]= $row["idbook"];
	            $result["book_data"][$i]= $row["book_data"];
	            $i++;
	        }

	        if(isset($result["id_loan"])){
	            $result["Count"]=count($result["id_loan"]);
	        }
	        else{
	            $result["Count"]=0;
	        }

	        $result["Error"]=100;

	    }
	    else{
	        $result["Error"]=-100;
	    }

	    $dbh = null;
	    $result["Query"]=$sql;

	    return $result;
	}
	// Insert loan table
	function insertloan($form=""){
		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");

		$sql="insert into loan(fx_register,fx_reserve,state,iduser,user_type)";
        $sql.="values (";
        $sql.="'".$form["fx_register"]."',";
        $sql.="'".$form["fx_reserve"]."',";
        $sql.="'".$form["state"]."',";
        $sql.="'".$form["username"]."',";
        $sql.="'".$form["usertype"]."'";
        $sql.=")";
		// $sql = "insert into reservation(data) values('$form')";
	    if($dbh->query($sql)){
	        $result["Error"]=100;
	    }
	    else{
	        $result["Error"]=-100;
	    }
	    $dbh = null;
	    $result["Query"]=$sql;
	    return $result;
	}
	// insert LoanBook
	function insertLoanBook($id_loan="",$idbook=""){
		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");

		$sql="insert into loanbook(id_loan,id_book)";
        $sql.=" values (";
        $sql.="'".$id_loan."',";
        $sql.="'".$idbook."'";
        $sql.=")";
		// $sql = "insert into reservation(data) values('$form')";
	    if($dbh->query($sql)){
	        $result["Error"]=100;
	    }
	    else{
	        $result["Error"]=-100;
	    }

	    $dbh = null;
	    $result["Query"]=$sql;
	    return $result;
	}
	function updateLoanBookState($id_loan,$idbook,$state){
		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");
		if ($stmt = $dbh->prepare("SELECT * FROM loan WHERE id_loan = ? LIMIT 1")) {
			$stmt->execute(array($id_loan));
			if($stmt->rowCount() == 1) { // If the loan exis
				$state = $state==1 ? 2 : 1;
	            $stmt = $dbh->prepare("UPDATE loan SET state = ? WHERE id_loan = ? ");
	            if ($stmt->execute(array($state,$id_loan))) {
	            	if(updateBookState($idbook,$state)){
	            		return true;
	            	}
	            	else{
	            		return false;
	            	}
	            	// return true;

	            }else{
	            	return false;
	            }
			} else {
				return false;
			}
		}
	}
	function updateBookState($idbook,$state){
		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");
		if ($stmt = $dbh->prepare("SELECT * FROM book WHERE idbook= ? LIMIT 1")) {
			$stmt->execute(array($idbook));
			if($stmt->rowCount() == 1) { // If the book exis
				$result=$stmt->fetch(PDO::FETCH_ASSOC);
				//recuperando book_data de tabla book
				$book_data=$result["book_data"];
				$array_data = xml_to_array($book_data);

				if (isset($array_data["state"])) {
					$array_data["state"] = $state;//1
					// return $array_data;
					$xml_data = array_to_xml($array_data);

					$stmt = $dbh->prepare("UPDATE book SET book_data = ? WHERE idbook = ? ");
					if ($stmt->execute(array($xml_data, $idbook))) {
						return true;
					}else{
						return false;
					}
				}
				else{
					return false;
				}
			}
			else {
				return false;
			}
		}
	}
	function xml_to_array($xmldata){
		$xmlt = simplexml_load_string($xmldata);
        // if (!$xmlt) {

        //     foreach(libxml_get_errors() as $error) {
        //         echo "\t", $error->message;
        //     }
        //     return "Error cargando XML \n";
        // }
        $json = json_encode($xmlt);
        $data_array = json_decode($json,TRUE);
		return $data_array;
	}

	function array_to_xml($array,$lastkey='book'){
		$buffer="";
	    $buffer.="<".$lastkey.">";
	    if (!is_array($array)){
			$buffer.=$array;}
	    else{
	        foreach($array as $key=>$value){
	            if (is_array($value)){
	                if ( is_numeric(key($value))){
	                    foreach($value as $bkey=>$bvalue){
	                        $buffer.=array_to_xml($bvalue,$key);
	                    }
	                }
	                else{
	                    $buffer.=array_to_xml($value,$key);
	                }
	            }
	            else{
	                    $buffer.=array_to_xml($value,$key);
	            }
	        }
	    }
	    $buffer.="</".$lastkey.">\n";
	    return $buffer;
	}
	function UpdateBook($idbook=0,$book_data){
		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");
		$stmt = $dbh->prepare("UPDATE book SET book_data = ? WHERE idbook = ? ");
		if ($stmt->execute(array($book_data, $idbook))) {
			return true;
		}else{
			return false;
		}

	}
	function updateLoanState($id_loan=0,$state){
		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");
		$stmt = $dbh->prepare("UPDATE loan SET state = ? WHERE id_loan = ? ");
		// $state = $state==1 ? 2 : 3;
	    if ($stmt->execute(array($state,$id_loan))) {
	     	return true;
	    }else{
	     	return false;
	    }

	}

	function searchPublicationSQL($idcategory,$form,$idfrom,$currentPage= '', $pageSize= '', $idarea=0){

		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");

		//ponencia: idfrom=2  idarea=0
		if($idfrom==2){
		    if($idarea==1){
		      $sql = "SELECT * FROM data d, subcategory s, category c WHERE d.idsubcategory=s.idsubcategory AND s.idcategory=c.idcategory";
		      }

		    else{
		      // $sql = "SELECT * FROM data d, subcategory s, category c WHERE d.idsubcategory=s.idsubcategory AND s.idcategory=c.idcategory ";
		      $sql = "SELECT idbook, book_data FROM book ";
		    }
		}

		if($idfrom==1){
			// $sql = "SELECT * FROM data d, subcategory s, category c WHERE d.idsubcategory=s.idsubcategory and s.idcategory=c.idcategory ";
			$sql = "SELECT * FROM book ";
                        //$sql .=	" and ExtractValue(data_content,'publicaciones/pdf')!="."''";
		}


		if($idfrom==0){
			$sql = "SELECT * FROM data d, subcategory s, category c WHERE d.idsubcategory=s.idsubcategory AND s.idcategory=c.idcategory and s.idcategory=$idcategory ";
		}

		if($idfrom==3){
			$sql = "SELECT * FROM data d, subcategory s, category c WHERE d.idsubcategory=s.idsubcategory and s.idcategory=c.idcategory ";
			$sql .=	" AND  (ExtractValue(data_content,'publicaciones/authorPRI/idauthor0')= "."'".$_SESSION["idautor"]."'";
			$sql .=	" OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor0') ="."'".$_SESSION["idautor"]."'";
			$sql .=	" OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor1') ="."'".$_SESSION["idautor"]."'";
			$sql .=	" OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor2') ="."'".$_SESSION["idautor"]."'";
			$sql .=	" OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor3') ="."'".$_SESSION["idautor"]."'";
			$sql .=	" OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor4') ="."'".$_SESSION["idautor"]."'";
			$sql .=	" OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor5') ="."'".$_SESSION["idautor"]."'";
			$sql .=	" OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor6') ="."'".$_SESSION["idautor"]."'";
			$sql .=	" OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor7') ="."'".$_SESSION["idautor"]."'";
			$sql .=	" OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor8') ="."'".$_SESSION["idautor"]."'";
			$sql .=	" OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor9') ="."'".$_SESSION["idautor"]."'";
			$sql .=	" OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor10')="."'".$_SESSION["idautor"]."'";
			$sql .=	" OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor11')="."'".$_SESSION["idautor"]."'";
			$sql .=	" OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor12')="."'".$_SESSION["idautor"]."'";
			$sql .=	" OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor13')="."'".$_SESSION["idautor"]."'";
			$sql .=	" OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor14')="."'".$_SESSION["idautor"]."'";
			$sql .=	" OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor15')="."'".$_SESSION["idautor"]."'";
			$sql .=	" OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor16')="."'".$_SESSION["idautor"]."'";
			$sql .=	" OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor17')="."'".$_SESSION["idautor"]."'";
			$sql .=	" OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor18')="."'".$_SESSION["idautor"]."'";
			$sql .=	" OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor19')="."'".$_SESSION["idautor"]."'";
			$sql .=	" OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor20')="."'".$_SESSION["idautor"]."'";
			$sql .=	" OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor21')="."'".$_SESSION["idautor"]."'";
			$sql .=	" OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor22')="."'".$_SESSION["idautor"]."'";
			$sql .=	" OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor23')="."'".$_SESSION["idautor"]."'";
			$sql .=	" OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor24')="."'".$_SESSION["idautor"]."'";
			$sql .=	" OR ExtractValue(data_content,'publicaciones/authorSEC/idauthor25')="."'".$_SESSION["idautor"]."'";

			$sql .= ")";
		}

		// TITULO
		// **********************************************************************************************
		if(isset($form["tituloSearch"])){
			if(strlen($form["tituloSearch"])>0){
                                $form["tituloSearch"]=(str_replace("'","*",$form["tituloSearch"]));
				$sql .=	" AND ExtractValue(data_content,'book/title') LIKE '%".$form["tituloSearch"]."%'";
			}
		}
		// AUTOR
		// **********************************************************************************************
		if(isset($form["author"])){
			if(strlen($form["author"])>0){
                                $form["author"]=(str_replace("'","*",$form["author"]));
				$sql .=	" AND ( ExtractValue(data_content,'publicaciones/authorPRI/author_surname0') LIKE '%".$form["author"]."%'";

				$sql .=	" OR ExtractValue(data_content,'publicaciones/authorSEC/child::*') LIKE '%".$form["author"]."%') ";

			}
		}
		// TIPO DE PUBLICACION
		// **********************************************************************************************
		if(isset($form["idcategory"])){
			if ($form["idcategory"]<>0){
				$sql .=	" AND c.idcategory=".$form["idcategory"];
                                //$sql .=	" OR c.idcategory=5) ";
			}
		}

		// SUBCATEGORIA DE PUBLICACION
		// **********************************************************************************************


		if(isset($form["idsubcategory"])){
			if ($form["idsubcategory"]<>0){
				$sql .=	" AND s.idsubcategory=".$form["idsubcategory"];
			}
		}

		if(isset($form["selectTypePublication"])){
			if ($form["selectTypePublication"]<>10){
				$sql .=	" AND s.idsubcategory=".$form["selectTypePublication"];
			}
		}

                if(isset($form["selectTypeCategory"])){
                    if ($form["selectTypeCategory"]<>0){
                        $sql .=	" AND s.idsubcategory=".$form["selectTypeCategory"];
                    }
                }
                else{
                    if(isset($form["tip_inf"])){
                        if($form["tip_inf"]!=0){
                            $sql .=	" AND s.idsubcategory=".$form["tip_inf"];
                        }

                    }
                    elseif(isset($_SESSION["tip_inf"])){
                        if($_SESSION["tip_inf"]!=0){
                            $sql .=	" AND s.idsubcategory=".$_SESSION["tip_inf"];
                        }

                    }
                }
                if(isset($_SESSION["iddata"])){
                    if($_SESSION["iddata"]!=0){
                        $sql .=	" AND d.iddata=".$_SESSION["iddata"];
                    }

                }
                if(isset($form["iddata"])){
                    if($form["iddata"]!=0){
                        $sql .=	" AND d.iddata=".$form["iddata"];
                    }

                }

		// TIPO DE FECHA
		// **********************************************************************************************
		if(isset($form["selectTypeDate"])){

			// 1: Fecha ingreso
			if ($form["selectTypeDate"]==1 and $form["searchYear"]<>0){
				$sql .=	" AND ExtractValue(data_content,'publicaciones/date_ing') LIKE '%".$form["searchYear"]."%'";
			}

			if ($form["selectTypeDate"]==1 and $form["searchMonth"]<>0){
				$numberMonth=sprintf("%02d",$form["searchMonth"]);
				$sql .=	" AND ExtractValue(data_content,'publicaciones/date_ing') LIKE '%-".$numberMonth."-%'";

	 		}

			// 2: Fecha de publicacion
			if ($form["selectTypeDate"]==2 and $form["searchYear"]<>0){
				$sql .=	" AND (ExtractValue(data_content,'publicaciones/date_pub') LIKE '%".$form["searchYear"]."%' ";
                                $sql .=	" OR ExtractValue(data_content,'publicaciones/year_pub') LIKE '%".$form["searchYear"]."%') ";

                                //$sql .=	" AND ExtractValue(data_content,'publicaciones/date_pub') LIKE '%".$form["searchYear"]."%'";
			}

			if ($form["selectTypeDate"]==2 and $form["searchMonth"]<>0){
				$numberMonth=sprintf("%02d",$form["searchMonth"]);
				$sql .=	" AND ExtractValue(data_content,'publicaciones/date_pub') LIKE '%-".$numberMonth."-%'";

	 		}

			// 3: Fecha de presentacion
			if ($form["selectTypeDate"]==3 and $form["searchYear"]<>0){
				$sql .=	" AND (ExtractValue(data_content,'publicaciones/date_pub') LIKE '%".$form["searchYear"]."%' ";
                                $sql .=	" OR ExtractValue(data_content,'publicaciones/year_pub') LIKE '%".$form["searchYear"]."%') ";
			}

			if ($form["selectTypeDate"]==3 and $form["searchMonth"]<>0){
				$numberMonth=sprintf("%02d",$form["searchMonth"]);
				$sql .=	" AND ExtractValue(data_content,'publicaciones/date_pub') LIKE '%-".$numberMonth."-%'";

	 		}

			// 4: Fecha de sismo
			if ($form["selectTypeDate"]==4 and $form["searchYear"]<>0){
				$sql .=	" AND (ExtractValue(data_content,'publicaciones/date_pub') LIKE '%".$form["searchYear"]."%' ";
                                $sql .=	" OR ExtractValue(data_content,'publicaciones/year_pub') LIKE '%".$form["searchYear"]."%') ";
			}

			if ($form["selectTypeDate"]==4 and $form["searchMonth"]<>0){
				$numberMonth=sprintf("%02d",$form["searchMonth"]);
				$sql .=	" AND ExtractValue(data_content,'publicaciones/date_pub') LIKE '%-".$numberMonth."-%'";
	 		}

	            $selectTypeAcademicos=isset($form["selectTypeAcademicos"])?$form["selectTypeAcademicos"]:"";
	            $selectTypeCategory=isset($form["selectTypeCategory"])?$form["selectTypeCategory"]:"";
                    $idsubcategory=isset($form["idsubcategory"])?$form["idsubcategory"]:"";


			if ($idsubcategory==11 and $form["searchYear"]<>0){
				$sql .=	" AND ExtractValue(data_content,'publicaciones/year') LIKE '%".$form["searchYear"]."%'";
			}

			if ($idsubcategory==12 and $form["searchYear"]<>0){
				$sql .=	" AND ExtractValue(data_content,'publicaciones/year') LIKE '%".$form["searchYear"]."%'";
			}

			if ($idsubcategory==13 and $form["searchYear"]<>0){
				$sql .=	" AND ExtractValue(data_content,'publicaciones/year') LIKE '%".$form["searchYear"]."%'";
			}

			if ($selectTypeCategory==7 and $form["searchYear"]<>0){
				//$sql .=	" AND ExtractValue(data_content,'publicaciones/year') LIKE '%".$form["searchYear"]."%'";
				$sql .=	" AND ExtractValue(data_content,'publicaciones/year_pub') LIKE '%".$form["searchYear"]."%'";
			}

		}
		else{
			// Para el buscador de la pagina general
			/*if ($_SESSION["idfrom"]==1 and $form["yearDesde"]<>0){
				$sql .=	" AND ExtractValue(data_content,'publicaciones/date_pub') LIKE '%".$form["yearDesde"]."%'";
			}
                        /*
			if ($_SESSION["idfrom"]==1 and $form["month"]<>0){
				$numberMonth=sprintf("%02d",$form["month"]);
				$sql .=	" AND ExtractValue(data_content,'publicaciones/date_ing') LIKE '%-".$numberMonth."-%'";
	 		}
	 		*/
                    if(isset($form["yearDesde"]) or isset($form["yearHasta"])){
			            $selectTypeCategory=isset($form["selectTypeCategory"])?$form["selectTypeCategory"]:0;
                        //Rango de Fechas
			if ($form["yearDesde"]<>0 AND $form["yearHasta"]==0 and $_SESSION["idfrom"]==1 or $_SESSION["idfrom"]==2  or $_SESSION["idfrom"]==3){
				if ($selectTypeCategory==7){
					$sql .=	" AND ExtractValue(data_content,'publicaciones/yearQuarter') >= '".$form["yearDesde"]."' AND ExtractValue(data_content,'publicaciones/yearQuarter') !='' ";
				}
				else{
                                    if ($form["yearDesde"]<>0 ){
				/*
				$sql .=	" AND (ExtractValue(data_content,'publicaciones/date_pub') >= '".$form["yearDesde"]."' AND ExtractValue(data_content,'publicaciones/date_pub') !='' ";
                                $sql .=	" OR ExtractValue(data_content,'publicaciones/year_pub') >= '".$form["yearDesde"]."' AND ExtractValue(data_content,'publicaciones/year_pub') !='') ";
				*/

				$sql .= " AND (SUBSTR(ExtractValue(data_content,'publicaciones/date_pub'),1,4) between '".$form["yearDesde"]."' AND '".$form["yearHasta"]."' ";
                                $sql .= " OR SUBSTR(ExtractValue(data_content,'publicaciones/year_pub'),1,4) between '".$form["yearDesde"]."' AND '".$form["yearHasta"]."') ";
	                        $sql .= " AND (ExtractValue(data_content,'publicaciones/date_pub') !='' ";
                                $sql .= " OR ExtractValue(data_content,'publicaciones/year_pub') !='') ";

                                    }
				}
	 		}

			elseif ($form["yearHasta"]<>0 AND $form["yearDesde"]==0 and $_SESSION["idfrom"]==1 or $_SESSION["idfrom"]==2 or $_SESSION["idfrom"]==3 ){
				if ($selectTypeCategory==7){
					$sql .=	" AND ExtractValue(data_content,'publicaciones/yearQuarter') <= '".$form["yearHasta"]."' AND ExtractValue(data_content,'publicaciones/yearQuarter') !='' ";
				}
				else{
				/*
				$sql .=	" AND (ExtractValue(data_content,'publicaciones/date_pub') <= '".$form["yearHasta"]."' AND ExtractValue(data_content,'publicaciones/date_pub') !='' ";
                                $sql .=	" OR ExtractValue(data_content,'publicaciones/year_pub') <= '".$form["yearHasta"]."' AND ExtractValue(data_content,'publicaciones/year_pub') !='') ";
				*/

				$sql .= " AND (SUBSTR(ExtractValue(data_content,'publicaciones/date_pub'),1,4) between '".$form["yearDesde"]."' AND '".$form["yearHasta"]."' ";
                                $sql .= " OR SUBSTR(ExtractValue(data_content,'publicaciones/year_pub'),1,4) between '".$form["yearDesde"]."' AND '".$form["yearHasta"]."') ";
	                        $sql .= " AND (ExtractValue(data_content,'publicaciones/date_pub') !='' ";
                                $sql .= " OR ExtractValue(data_content,'publicaciones/year_pub') !='') ";

				}
	 		}

			elseif ($form["yearDesde"]<>0 and $form["yearHasta"]<>0 and $_SESSION["idfrom"]==1 or $_SESSION["idfrom"]==2 or $_SESSION["idfrom"]==3 ){
				if ($selectTypeCategory==7){
					/*
					$sql .=	" AND ExtractValue(data_content,'publicaciones/year') between '".$form["yearDesde"]."' AND '".$form["yearHasta"]."' ";
		                        $sql .= " AND ExtractValue(data_content,'publicaciones/year') !='' ";
					*/
					$sql .=	" AND ExtractValue(data_content,'publicaciones/year_pub') between '".$form["yearDesde"]."' AND '".$form["yearHasta"]."' ";
		                        $sql .= " AND ExtractValue(data_content,'publicaciones/year_pub') !='' ";

		                        //$sql .= " AND ExtractValue(data_content,'publicaciones/year') !=str_to_date('000-00-00','%Y-%m-%d' ) ";
				}
				else{

					$sql .=	" AND (SUBSTR(ExtractValue(data_content,'publicaciones/date_pub'),1,4) between '".$form["yearDesde"]."' AND '".$form["yearHasta"]."' ";
                                        $sql .=	" OR SUBSTR(ExtractValue(data_content,'publicaciones/year_pub'),1,4) between '".$form["yearDesde"]."' AND '".$form["yearHasta"]."') ";
		                        $sql .= " AND (ExtractValue(data_content,'publicaciones/date_pub') !='' ";
                                        $sql .= " OR ExtractValue(data_content,'publicaciones/year_pub') !='') ";
		                        //$sql .= " AND ExtractValue(data_content,'publicaciones/date_pub') !=str_to_date('000-00-00','%Y-%m-%d' ) ";
				}
	 		}
                    }
		}

		// REFERENCIA
		// **********************************************************************************************
		if(isset($form["selectReferencia"])){
			if ($form["selectReferencia"]<>0){
				$sql .=	" AND ExtractValue(data_content,'publicaciones/idreference') =".$form["selectReferencia"];
			}
		}

		// ESTADO
		// **********************************************************************************************
		if(isset($form["selectStatus"])){
			if ($form["selectStatus"]<>0){
				$sql .=	" AND ExtractValue(data_content,'publicaciones/status')=".$form["selectStatus"];
			}
		}

		// TESIS - TIPO
		// **********************************************************************************************
		if(isset($form["tipoTesis"])){
			if ($form["tipoTesis"]<>0){
				$sql .=	" AND ExtractValue(data_content,'publicaciones/tipo_tesis')=".$form["tipoTesis"];
			}
		}


		// TESIS - PAIS
		// **********************************************************************************************
		if(isset($form["pais"])){
			if (strlen($form["pais"])>0){
				$sql .=	" AND ExtractValue(data_content,'publicaciones/pais') LIKE '%".$form["pais"]."%'";
			}
		}



		// TESIS - UNIVERSIDAD
		// **********************************************************************************************
		if(isset($form["uni"])){
			if (strlen($form["uni"])>0){
				$sql .=	" AND ExtractValue(data_content,'publicaciones/uni') LIKE '%".$form["uni"]."%'";
			}
		}


		// PONENCIA - TIPO
		// **********************************************************************************************
		if(isset($form["tipoPonencia"])){
			if ($form["tipoPonencia"]<>0){
				$sql .=	" AND ExtractValue(data_content,'publicaciones/idtipoPonencia') =".$form["tipoPonencia"];
			}
		}

		// PONENCIA - PRESENTADO POR
		// **********************************************************************************************
		if(isset($form["prePorNombre"])){
			if (strlen($form["prePorNombre"])!=""){
				$sql .=	" AND ExtractValue(data_content,'publicaciones/prePorNombre') LIKE '%".$form["prePorNombre"]."%'";
			}
		}

		if(isset($form["prePorApellido"])){
			if (strlen($form["prePorApellido"])>0){
				$sql .=	" AND ExtractValue(data_content,'publicaciones/prePorApellido') LIKE '%".$form["prePorApellido"]."%'";
			}
		}

		// PONENCIA - NOMBRE DEL EVENTO
		// **********************************************************************************************
		if(isset($form["evento"])){
			if (strlen($form["evento"])>0){
				$sql .=	" AND ExtractValue(data_content,'publicaciones/evento') LIKE '%".$form["evento"]."%'";
			}
		}

		// PONENCIA - CATEGORIA DEL EVENTO
		// **********************************************************************************************
		if(isset($form["selectCategoriaEvento"])){
			if ($form["selectCategoriaEvento"]<>0){
				$sql .=	" AND ExtractValue(data_content,'publicaciones/idcategoriaEvento') = '".$form["selectCategoriaEvento"]."'";
			}
		}

		// PONENCIA - CLASE DEL EVENTO
		// **********************************************************************************************
		if(isset($form["selectClaseEvento"])){
			if ($form["selectClaseEvento"]<>0){
				$sql .=	" AND ExtractValue(data_content,'publicaciones/idclaseEvento') = '".$form["selectClaseEvento"]."'";
			}
		}

		// ASUNTOS ACADEMICOS - TIPO
		// **********************************************************************************************
		if(isset($form["selectTypeAcademicos"])){
			if ($form["selectTypeAcademicos"]<>0){
				$sql .=	" AND s.idsubcategory=".$form["selectTypeAcademicos"];
			}
		}

		// Geofisica sociedad - TIPO
		// **********************************************************************************************
		if(isset($form["selectTypeGeofisica"])){
			if ($form["selectTypeGeofisica"]<>0){
				$sql .=	" AND s.idsubcategory=".$form["selectTypeGeofisica"];
			}
		}

		// ASUNTOS ACADEMICOS - AREA
		// **********************************************************************************************
		if(isset($form["area"])){
			if (strlen($form["area"])>0){
				$sql .=	" AND ExtractValue(data_content,'publicaciones/area') LIKE '%".$form["area"]."%'";
			}
		}

		// ASUNTOS ACADEMICOS - AREA
		// **********************************************************************************************
		if(isset($form["area"])){
			if (strlen($form["area"])>0){
				$sql .=	" AND ExtractValue(data_content,'publicaciones/area') LIKE '%".$form["area"]."%'";
			}
		}

		// ASUNTOS ACADEMICOS - NRO COMPENDIO
		// **********************************************************************************************

		if(isset($form["nro_compendio"])){
			if (strlen($form["nro_compendio"])>0){
				$sql .=	" AND ExtractValue(data_content,'publicaciones/nroCompendio')='".$form["nro_compendio"]."'";
			}
		}


		// ASUNTOS ACADEMICOS - TRIMESTRE
		// **********************************************************************************************

		if(isset($form["trimestre"])){
			if ($form["trimestre"]<>0){
				$sql .=	" AND ExtractValue(data_content,'publicaciones/idquarter')='".$form["trimestre"]."'";
			}
		}


		// BOLETINES SISMICOS - MAGNITUD
		// **********************************************************************************************

		if(isset($form["selectMagnitud"])){
			if ($form["selectMagnitud"]<>0){
				$sql .=	" AND ExtractValue(data_content,'publicaciones/idmagnitud')='".$form["selectMagnitud"]."'";
			}
		}

		if(isset($form["selectRegion"])){
			if ($form["selectRegion"]<>0){
				$sql .=	" AND ExtractValue(data_content,'publicaciones/idRegion')='".$form["selectRegion"]."'";
			}
		}

		if(isset($form["selectDepartamento"])){
			if ($form["selectDepartamento"]<>0){
				$sql .=	" AND ExtractValue(data_content,'publicaciones/idDepartamento')='".$form["selectDepartamento"]."'";
			}
		}

		// AREA
		// **********************************************************************************************
		if(isset($form["selectAreas"])){
			if ($form["selectAreas"]<>0){
				$sql .=	" AND ExtractValue(data_content,'publicaciones/areaPRI') =".$form["selectAreas"];

			}
		}//viene del gráfico general de áreas
	        else{
	            if (isset($idarea)){
	                if ($idarea<>0){
                            if ($idarea<>9){
	                        $sql .= " AND ExtractValue(data_content,'publicaciones/areaPRI')=".$idarea;
                            }
	                }
	            }
	        }

		/******************************************************************/
	        $order_by="";

                if(isset($form["selectAreas"])){
                    if($form["selectAreas"]==0){
                        $order_by=" ORDER BY s.idsubcategory ";
        		//$order_by.=", ExtractValue(data_content,'publicaciones/year_pub') DESC ";
                        $order_by.=", SUBSTR(ExtractValue(data_content,'publicaciones/date_pub'),1,4) DESC ";
                        $order_by.=", ExtractValue(data_content,'publicaciones/titulo') ";
                    }
                }
                //Ordenar Publicaciones (publicaciones=1, ponencias=2, inofrmacion interna=4)//
                if(isset($form["idcategory"])){
                    if($form["idcategory"]==2){
        		$order_by=" ORDER BY ExtractValue(data_content,'publicaciones/year_pub') DESC ";
                        $order_by.=", ExtractValue(data_content,'publicaciones/titulo') ";
                    }

                    elseif($form["idcategory"]==4){
                        $order_by=" ORDER BY s.idsubcategory DESC ";
        		$order_by.=", ExtractValue(data_content,'publicaciones/year_pub') DESC ";
                        $order_by.=", SUBSTR(ExtractValue(data_content,'publicaciones/date_pub'),1,4) DESC ";
                        $order_by.=", ExtractValue(data_content,'publicaciones/titulo') ";
                    }


                }/*
                if(isset($form["idsubcategory"])){
                    if($form["idsubcategory"]==13){//Informe Trimestral de Geofísica y Sociedad
                        $order_by=" ORDER BY ExtractValue(data_content,'publicaciones/yearQuarter') desc ";
                        $order_by.=", ExtractValue(data_content,'publicaciones/idquarter') desc ";
                    }
                }
                */
                //Ordenar Artículos Indexados, Tesis, Otras Publicaciones//
                if(isset($form["selectTypePublication"])){
        		$order_by=" ORDER BY SUBSTR(ExtractValue(data_content,'publicaciones/date_pub'),1,4) DESC ";
                        $order_by.=", ExtractValue(data_content,'publicaciones/year_pub') DESC ";
                        $order_by.=", ExtractValue(data_content,'publicaciones/titulo') ";
                }
                if(isset($form["selectTypeGeofisica"])){
                        $order_by=" ORDER BY ExtractValue(data_content,'publicaciones/yearQuarter') desc ";
                        $order_by.=", ExtractValue(data_content,'publicaciones/idquarter') desc ";
                }
                //Ordenar Informes Trimestrales//
                if(isset($form["selectTypeCategory"])){
                    if($form["selectTypeCategory"]==7){
                        $order_by=" ORDER BY ";
                        $order_by.=" ExtractValue(data_content,'publicaciones/areaPRI') asc ";
                        $order_by.=", ExtractValue(data_content,'publicaciones/yearQuarter') desc ";
                        $order_by.=", ExtractValue(data_content,'publicaciones/idquarter') desc ";

                    }
                    elseif($form["selectTypeCategory"]==8){
                        $order_by=" ORDER BY SUBSTR(ExtractValue(data_content,'publicaciones/date_pub'),1,4) DESC ";
                        $order_by.=", ExtractValue(data_content,'publicaciones/year_pub') DESC ";
                        $order_by.=", cast(ExtractValue(data_content,'publicaciones/nroBoletin') as UNSIGNED) desc ";

                    }

                }
                else{

                    if($form["tip_inf"]!=0){
                        if($form["tip_inf"]==7){
                            $order_by=" ORDER BY ";
                            $order_by.=" ExtractValue(data_content,'publicaciones/areaPRI') asc ";
                            $order_by.=", ExtractValue(data_content,'publicaciones/yearQuarter') desc ";
                            $order_by.=", ExtractValue(data_content,'publicaciones/idquarter') desc ";

                        }
                        elseif($form["tip_inf"]==8){
                            $order_by=" ORDER BY SUBSTR(ExtractValue(data_content,'publicaciones/date_pub'),1,4) DESC ";
                            $order_by.=", ExtractValue(data_content,'publicaciones/year_pub') DESC ";
                            $order_by.=", cast(ExtractValue(data_content,'publicaciones/nroBoletin') as UNSIGNED) desc ";

                        }

                    }

                }



                $sql .= $order_by;
		/****************************************************************/
		//$sql .=	" ORDER BY SUBSTR(ExtractValue(data_content,'publicaciones/date_pub'),1,4) DESC ";
                //$sql .=	" , ExtractValue(data_content,'publicaciones/year_pub') DESC ";
		//$sql .=	" , ExtractValue(data_content,'publicaciones/titulo')";


		if($currentPage<>'' and $pageSize<>''){
			$limitIni = ($currentPage-1)*$pageSize;
	        $limitLon = $pageSize;
			$sql .=	" LIMIT $limitIni,$limitLon";
		}


		$i=0;
	    if($dbh->query($sql)){
	        foreach($dbh->query($sql) as $row) {

	   //      	$result["book_data"][$i]= $row["book_data"];
				// $result["idbook"][$i]= $row["idbook"];

	            $result["idbook"][$i]= $row["idbook"];
	            $result["book_data"][$i]= $row["book_data"];
	            // $result["idcategory"][$i]= $row["idcategory"];
	            // $result["idsubcategory"][$i]= $row["idsubcategory"];

	            $i++;
	        }

	        if(isset($result["idbook"])){
	            $result["Count"]=count($result["idbook"]);
	        }
	        else{
	            $result["Count"]=0;
	        }

	        $result["Error"]=0;

	    }
	    else{
	        $result["Error"]=1;
	    }

	    $dbh = null;
	    $result["Query"]=$sql;

	    return $result;
	}

	function ReservationQuery(){
		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");
	    $sql = "SELECT * FROM reservation";

	    $i=0;
	    if($dbh->query($sql)){
	        foreach($dbh->query($sql) as $row) {
	            $result["id"][$i]= $row["id"];
	            $result["data"][$i]= $row["data"];
	            $i++;
	        }

	        if(isset($result["id"])){
	            $result["Count"]=count($result["id"]);
	        }
	        else{
	            $result["Count"]=0;
	        }

	        $result["Error"]=0;

	    }
	    else{
	        $result["Error"]=1;
	    }

	    $dbh = null;
	    $result["Query"]=$sql;

	    return $result;
	}
	function searchBookID($idbook=0) {
		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");
		$sql = "Select * from book where idbook=$idbook";
	 	if($dbh->query($sql)){
	 		$i=0;
	 		foreach($dbh->query($sql) as $row) {
	 			$result["book_data"][$i]= $row["book_data"];
	 			$result["idbook"][$i]= $row["idbook"];
	 			$i++;
	 		}

	 		if(isset($result["idbook"])){
	 			$result["Count"]=count($result["idbook"]);
	 		}
	 		else{
	 			$result["Count"]=0;
	 		}

	 		$result["Error"]=0;

	 	}
	 	else{
	 		$result["Error"]=1;
	 	}

	 	$dbh = null;
	 	$result["Query"]=$sql;

	 	return $result;
	}
       function query_themes_related($tag="",$idbook=0){
		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");
		if ($stmt = $dbh->prepare("SELECT * FROM book WHERE ExtractValue(book_data,'book/Theme/*/detalle') LIKE ? AND idbook!=?")) {
	        $stmt->execute(array($tag,$idbook));
	        /*comprobar que el registro existe*/
	        if($stmt->rowCount() > 0) {
	        	return true;
	        }
	        else{
	        	return false;
	        }
	    }
		$dbh = null;
      }
      function query_sede($idsede=0){
		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");
		if ($stmt = $dbh->prepare("SELECT * FROM sede WHERE id = ?")) {
	        $stmt->execute(array($idsede));
	        $result=$stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() == 1) {// sede existe
				return $result;
			}
			else{
				return -100;
			}
	    }
		$dbh = null;
      }

?>
