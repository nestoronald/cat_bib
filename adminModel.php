<?php

	/**************************************************
	Contiene funciones que modelan el ingreso de datos

	***************************************************/

	function searchAuthorSessionSQL($strSQL="",$idauthor=-100){

		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");


		//$sAuthor vale Ejm. 1,2,3
		//se lo pasamos al sql
		//ORDER BY field = ordenar los id's deacuerdo como estan definidos en IN
		        //Ejm. IN (571,1) devuelve el listado de la consulta en ese orden
		        //$sql = "select * from author ";
		        //$sql.= "where idauthor in ($strSQL)";

		if($strSQL!=""){
		        $sql = "select * from author ";
		        //$sql.= "where idauthor in ('$strSQL')";
		        $sql.= "where idauthor in ($strSQL) ORDER BY field(idauthor, $strSQL)";
		        $i=0;
		        if ( $strSQL=="q" and $idauthor!=-100) {
		        	$sql= "select * from author where idauthor=$idauthor";
		        }

			if($dbh->query($sql)){
			        foreach($dbh->query($sql) as $row) {
			            $result["idauthor"][$i]= $row["idauthor"];
			            $result["author_name"][$i]= $row["author_name"];
			            $result["author_surname"][$i]= $row["author_surname"];
			            $i++;
			        }
			                if(isset($result["idauthor"])){
			                    $result["Error"]=0;
			                    $result["Query"]=$sql;
			                    $result["Count"]=count($result["idauthor"]);

			                }
			                else{
			                    $result["Error"]=1;
			                    //$result["Count"]=count($result["idauthor"]);
			                    $result["Count"]=0;
			                }
			}
		}
	    else{
	        $result["Error"]=2;
	        //$result["Query"]=$sql;
	    }

	    $dbh = null;

	    return $result;
	}

	function searchAuthorID($idauthor=-100,$surname="",$author=""){

		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");

		    $sql= "select * from author where idauthor= ".$idauthor;
		     if ($idauthor==-100) {
		     	// $author_keyword = explode(" ", "james");
		     	$author_keyword = explode(" ", $author);
		     	$sql= "select * from author where" ;
		     	$i=1;
		     	foreach ($author_keyword as $key => $value) {
		     		$sql.= " author_surname like '%".$value."%' or author_name like '%".$value."%'";
		     		if ($i!=count($author_keyword)) {
		     			$sql.="or";
		     		}
		     		$i++;
		     	}
		   //   	$sql= "select * from author where (
		   //   		(author_surname like '%".$surname."%' or author_name like '%".$name."%') or
					// (author_surname like '%".$name."%' or author_name like '%".$surname."%')
		   //   		)";
		     }

			$i=0;
			if($dbh->query($sql)){
			        foreach($dbh->query($sql) as $row) {
			            $result["idauthor"][$i]= $row["idauthor"];
			            $result["author_name"][$i]= $row["author_name"];
			            $result["author_surname"][$i]= $row["author_surname"];
			            $i++;
			        }
			                if(isset($result["idauthor"])){
			                    $result["Error"]=0;
			                    $result["Query"]=$sql;
			                    $result["Count"]=count($result["idauthor"]);

			                }
			                else{
			                    $result["Error"]=1;
			                    //$result["Count"]=count($result["idauthor"]);
			                    $result["Count"]=0;
			                }
			}
	    $dbh = null;

	    return $result;
	}

	function searchAuthorSQL($idSearch,$currentPage,$pageSize,$sAuthor="",$strSQL="",$catAuthor=""){
		$catAuthor_val = $catAuthor=="AuthorPer" ? 0 : 1;
		if ($catAuthor=="AuthorPer") {
			$catAuthor_sql ="and author_type=0";
		}
		elseif ($catAuthor=="AuthorInst") {
			$catAuthor_sql ="and author_type=1";
		}
		else{
			$catAuthor_sql ="";
		}
		$notin=" where author_enabled=1 $catAuthor_sql";
		$notin2=" and author_enabled=1 ";
	    if($strSQL!="" and $catAuthor_sql!=""){
	        $notin= " where idauthor not in ($strSQL) $catAuthor_sql and author_enabled=1 ";
	        $notin2= " and idauthor not in ($strSQL) $catAuthor_sql and author_enabled=1 ";
	    }
	    $dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
	    $dbh->query("SET NAMES 'utf8'");

		if($idSearch=="AUTHOR"){
		    $limitIni = ($currentPage-1)*$pageSize;
		    $limitLon = $pageSize;

		    if($sAuthor==""){
		        $sql="select * from author $notin order by author_surname LIMIT $limitIni,$limitLon";
		    }
		    else{
		        $campo=' where author_surname like "'.$sAuthor.'%" '.$notin2.' LIMIT '.$limitIni.','.$limitLon.' ';
		        $sql="select * from author ".$campo;
		    }
		}

		if($idSearch=="ALL"){

		    if($sAuthor==""){
		        $sql="select * from author $notin ";
		    }
		    else{
		        $campo="where author_surname like '$sAuthor%' $notin2 ";
		        $sql="select * from author ".$campo;
		    }
		}

	    $i=0;
	    if($dbh->query($sql)){

	        foreach($dbh->query($sql) as $row) {
	            $result["idauthor"][$i]= $row["idauthor"];
	            $result["author_name"][$i]= $row["author_name"];
	            $result["author_surname"][$i]= $row["author_surname"];
	            $result["author_type"][$i]= $row["author_type"];
	            $i++;
	        }
	                if(isset($result["idauthor"])){
	                    $result["Error"]=0;
	                    $result["Query"]=$sql;
                        $result["query_autor"]=$strSQL;
	                    $result["Count"]=count($result["idauthor"]);

	                }
	                else{

                            $result["Error"]=1;
                            $result["sAuthor"]=$sAuthor;
                            /*
                            if($inAutor){
	                    $result["Error"]=3;
                            $result["Msg"]="<h5><p>El Autor ha sido asociado a la publicación";
                            $result["result_array"]=$result_array;
                            }
                            elseif($inAutor==""){
	                    $result["Error"]=1;
                            $result["Msg"]="<h5><p>No existe el autor registrelo como nuevo";
                            }
                            */

	                }
	    }
	    else{
	        $result["Error"]=2;
            $result["query_autor"]=$strSQL;
	        $result["Query"]=$sql;
	    }

	    $dbh = null;
	    $result["strSQL"]=$strSQL;
            $result["Query"]=$sql;
	    return $result;
	}

	function registraAuthorSQL($form_entrada){

		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
        $dbh->query("SET NAMES 'utf8'");

	    $author_name=$form_entrada["author_name"];
	    $author_surname=$form_entrada["author_surname"];

	    /******Verificar si existe autor******/
	    $campo=' where author_name = "'.$author_name.'" and author_surname = "'.$author_surname.'"  ';
	    $sql='select * from author '.$campo;

	    $i=0;
	    if($dbh->query($sql)){

	        foreach($dbh->query($sql) as $row) {
	            $result["idauthor"][$i]= $row["idauthor"];
	            $i++;
	        }

	        if(isset($result["idauthor"])){
	                for($i=0;$i<count($result["idauthor"]);$i++){
	                        $result["idauthor"]=$result["idauthor"][$i];
	                }
	                $result["Count"]=count($result["idauthor"]);
	        }

	        else{
	                $result["Count"]=0;
	        }


		    if($result["Count"]==0){
		        $sql='insert into author(author_name,author_surname,author_type,author_enabled)
		        values ("'.$author_name.'","'.$author_surname.'","'.$form_entrada["newAuthor_type"].'",1)';

		        $dbh->query($sql);

				/************/
		        foreach($dbh->query("SELECT * FROM author order by idauthor desc limit 1") as $row) {
		            $result["idauthorultimo"][0]= $row["idauthor"];
		            $i++;
		        }

		        if(isset($result["idauthorultimo"])){
		                        $result["idauthorultimo"]=$result["idauthorultimo"][0];
		        }
				/************/

		        $dbh = null;
		        $result["Error"]="registrado";
		        $result["Msg"]="Registrado Correctamente";
		        $result["author_surname"]=$author_surname;
		  	}
			else{
		        //$result["Error"]=3;
		        $result["Error"]="existe";
                $result["Msg"]="Existe Autor";

		    }


	    }
	    else{
	        $result["Error"]=4;
                $result["Msg"]="Error en la consulta de existencia";
	    }

	    $dbh = null;
	    $result["Query"]=$sql;

	    return $result;


	}

	function searchPublication_iddataSQL($iddata=0){

		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");

	    $sql = "SELECT * FROM book WHERE idbook=$iddata";

	    $i=0;
	    if($dbh->query($sql)){
	        foreach($dbh->query($sql) as $row) {
	            $result["idbook"][$i]= $row["idbook"];
	            $result["book_data"][$i]= $row["book_data"];
	            $result["sede"][$i]= $row["sede"];

	            $i++;
	        }
// 	        if(isset($result["iddata"])){
// -              $result["Count"]=count($result["iddata"]);}

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

	function verificaUsuarioSQL($formLogin){

	    $usuario=trim($formLogin["usuario"]);
	    $clave=md5(trim($formLogin["clave"]));

		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");

	        //$sql="select a.idarea,a.area_description, u.idusers, u.users_name, u.users_type from area a, users u where a.idarea=u.idarea and u.users_name='$usuario' and users_password='$clave' and users_type='1'";
        $sql="select idusers, users_name, users_type, sede from users u where u.users_name='$usuario' and users_password='$clave' and users_state='1'";

	    $i=0;
	    if($dbh->query($sql)){
	        foreach($dbh->query($sql) as $row) {
                $result["idusers"][$i]=$row["idusers"];
	            $result["users_name"][$i]=$row["users_name"];
	            $result["users_type"][$i]=$row["users_type"];
	            $result["sede"][$i]=$row["sede"];
	            $i++;
	        }

	        if(isset($result["idusers"])){
	                for($i=0;$i<count($result["idusers"]);$i++){
                            $result["idusers"]=$result["idusers"][$i];
	                        $result["users_name"]=$result["users_name"][$i];
	                        $result["users_type"]=$result["users_type"][$i];
	                        $result["sede"]=$result["sede"][$i];
	                }
	                $result["Count"]=count($result["idusers"]);
	        }
	        else{
	            $result["Count"]=0;
	        }

	    }
	else{
	    $result["Error"]=1;
	    $result["Msg"]="No se ejecuto el select";

	}

	    $dbh = null;
	    $result["Query"]=$sql;

	                return $result;

	}


	/**************************************************

	***************************************************/
    function cambiarTempClaveSQL($iduser,$user,$correo,$clave_temp){

        $dbh=conx("DB_ITS","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");

                $clave_temp=md5(trim($clave_temp));

                $sqlUpdate="update users set users_password='$clave_temp', users_email='$correo' where idusers=$iduser ";

                if($dbh->query($sqlUpdate)){

                    $mensaje="Se ha asignado una clave nueva";

                    $result["Error"]=0;
                    $result["Msg"]=$mensaje;
                }
                else{
                    $result["Error"]=1;
                    $result["Msg"]="No se pudo realizar la actualización";
                }

                $dbh = null;
                $result["Query_update"]=$sqlUpdate;
                return $result;
    }

	/**************************************************

	***************************************************/

	function newPonenciaSQL($action,$iddata=0,$idsubcategory,$xml,$sede=1){
		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");


	    /*if ($action=="INS"){
	        $sql="INSERT INTO book(book_data, book_enabled)";
	        $sql.=" VALUES('$xml',1)";
	    }

	    if ($action=="UPD"){
	        $sql="UPDATE book SET";
	        $sql.=" book_data='$xml'";
	        $sql.="WHERE idbook=$iddata";
	    }

	    if($dbh->query($sql)){
	        $result["Error"]=0;
	        $result["Msg"]="error";
	    }
	    else{
	        $result["Error"]=1;
	    }*/
	    $result["Error"]=-100;
	    if ($action=="INS"){

	        /*if ($stmt = $dbh->prepare("SELECT * FROM book WHERE idbook = ? LIMIT 1")) {
	        	$stmt->execute(array($iddata));
	        	//comprobar que el registro existe
	        	if($stmt->rowCount() == 0) {*/
	        		$stmt = $dbh->prepare("INSERT INTO book(book_data, book_enabled,sede) value(?,?,?) ");
	        		$stmt->execute(array($xml,1,$sede));
	        		$result["Error"]=100;
	        	/*}
	        	else{
	        		$result["Msg"]="Error de almacenamiento en base de datos es psoble que el ID de registro ya existe. Intente mas luego";
	        	}
	        }*/
	    }

	    if ($action=="UPD"){
	        if ($stmt = $dbh->prepare("SELECT * FROM book WHERE idbook = ? LIMIT 1")) {
	        	$stmt->execute(array($iddata));
	        	/*comprobar que el registro existe*/
	        	if($stmt->rowCount() == 1) {
	        		$stmt = $dbh->prepare("UPDATE book SET book_data= ? WHERE idbook= ? ");
            		$stmt->execute(array($xml,$iddata));
            		$result["Error"]=100;
            		$error=100;
	        	}
	        	else{
	        		$result["Msg"]="Error de almacenamiento en base de datos podría haberse eliminado el registro. Intenta mas luego";
	        	}
	        }
	    }
	    $dbh = null;
	    return $result;

	}

    function select_format(){

		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");

	    $sql = "SELECT * FROM format";

	    $i=0;

	    if($dbh->query($sql)){
	        foreach($dbh->query($sql) as $row) {
	        	$result["idformat"][$i]= $row["idformat"];
	            $result["fdescription"][$i]= $row["fdescription"];
	            $i++;
	        }
	        $result["Count"]=count($result["idformat"]);
	        $result["Error"]=0;

	    }
	    else{
	        $result["Error"]=1;
	    }

	    $dbh = null;
	    $result["Query"]=$sql;

	    return $result;
	}
	function insertFormat($form){
		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");
		$sql = "insert into format(fdescription) values(";
		$sql .= "'".$form["fdescription"]."'";
		$sql .=")";

		if($dbh->query($sql)){
			$result["Error"]=0;
		}
		else{
			$result["Error"]=1;
		}

		$dbh = null;
		$result["Query"]=$sql;
		return $result;

	}
	// --- Themes book
	function SelectThemeBoook($form){

		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");

	    $sql = "SELECT * FROM theme_book";

	    $i=0;

	    if($dbh->query($sql)){
	        foreach($dbh->query($sql) as $row) {
	        	$result["idtheme"][$i]= $row["idtheme"];
	            $result["destheme"][$i]= $row["destheme"];
	            $i++;
	        }
	        $result["Count"]=count($result["idtheme"]);
	        $result["Error"]=0;

	    }
	    else{
	        $result["Error"]=1;
	    }

	    $dbh = null;
	    $result["Query"]=$sql;

	    return $result;
	}

	function InsertThemeBoook($form){
		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");
		$sql = "insert into theme_book(destheme) values(";
		$sql .= "'".$form["theme_description"]."'";
		$sql .=")";

		if($dbh->query($sql)){
			$result["Error"]=0;
		}
		else{
			$result["Error"]=1;
		}

		$dbh = null;
		$result["Query"]=$sql;
		return $result;

	}
	function updateAuthor_sql($form){

		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");
		$sql = "UPDATE author SET ";
		$sql .= "author_name='".$form["nAuthor"]."',";
		$sql .= " author_surname ='".$form["sAuthor"]."',";
		$sql .= " author_type ='".$form["editAuthor_type"]."'";
		$sql .= " WHERE idauthor=".$form["sidauthor"];


		if($dbh->query($sql)){
			$result["Error"]=0;
		}
		else{
			$result["Error"]=1;
		}

		$dbh = null;
		$result["Query"]=$sql;
		return $result;

	}
	function deleteAuthor_sql($idauthor){
		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");

		$sql = "DELETE FROM author WHERE idauthor=".$idauthor."";

		if($dbh->query($sql) ){
				$result["Error"]=0;
		}
		else{
			$result["Error"]=1;
		}

		$dbh = null;
		$result["Query"]=$sql;
		return $result;
	}
	function delRegisterBook($idbook){
		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");

		$sql = "DELETE FROM book WHERE idbook=".$idbook."";

		if($dbh->query($sql) ){
				$result["Error"]=0;
		}
		else{
			$result["Error"]=1;
		}

		$dbh = null;
		$result["Query"]=$sql;
		return $result;
	}
	function years_edition(){
		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");
		// mysql_real_escape_string($data);
		$sql = "SELECT idbook, ExtractValue( book_data, 'book/Edition/year' ) AS year
			FROM book";
		// $sql .= "'".$data."'";
		// $sql .=")";
		$i=0;
		if($dbh->query($sql)){
			foreach($dbh->query($sql) as $row) {
				$result["idbook"][$i]= $row["idbook"];
				$result["year"][$i]= $row["year"];
				$i++;
				if(isset($result["idbook"])){
				    $result["Error"]=0;
				    $result["Query"]=$sql;
				    $result["Count"]=count($result["idbook"]);
				    }
				else{
				    $result["Error"]=1;
				    $result["Count"]=0;
				}
			}
		}
		else{
			$result["Error"]=1;
		}
		$dbh = null;
		return $result;
	}
	function search_dewey($dewey_search,$iddata=0){
		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");

		$sql = "SELECT idbook, book_data
				FROM book where
				ExtractValue( book_data, 'book/NumDewey') = '".$dewey_search."' and
				ExtractValue( book_data, 'book/NumDewey') != ' '  and idbook != $iddata" ;

		$i=0;
		if($dbh->query($sql)){
			foreach($dbh->query($sql) as $row) {
				$result["idbook"][$i]= $row["idbook"];
				$i++;
				if(isset($result["idbook"])){
				    $result["Error"]=0;
				    $result["Query"]=$sql;
				    $result["Count"]=count($result["idbook"]);
				    }
				else{
				    $result["Error"]=1;
				    $result["Count"]=0;
				}
			}
		}
		else{
			$result["Error"]=1;
		}
		$dbh = null;
		return $result;
	}
    function search_book_id($iddata=0){
		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");

		if ($stmt = $dbh->prepare("SELECT * FROM book WHERE idbook = ? LIMIT 1")) {
	        	$stmt->execute(array($iddata));
	        	/*comprobar que el registro existe*/
	        	if($stmt->rowCount() == 1) {
	        		return true;
	        	}
	        	else{
	        		return false;
	        	}
	        }
		$dbh = null;
	}
    function temas_query(){
		$dbh=conx("biblioteca_virtual","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");

		if ($stmt = $dbh->prepare("
				SELECT
				ExtractValue(book_data,'book/Theme/pri01/detalle') as pri01,
				ExtractValue(book_data,'book/Theme/pri02/detalle') as pri02,
				ExtractValue(book_data,'book/Theme/pri03/detalle') as pri03,
				ExtractValue(book_data,'book/Theme/pri04/detalle') as pri04,
				ExtractValue(book_data,'book/Theme/pri05/detalle') as pri05,
				ExtractValue(book_data,'book/Theme/pri06/detalle') as pri06,
				ExtractValue(book_data,'book/Theme/pri07/detalle') as pri07,
				ExtractValue(book_data,'book/Theme/pri08/detalle') as pri08,
				ExtractValue(book_data,'book/Theme/pri09/detalle') as pri09,
				ExtractValue(book_data,'book/Theme/*/secundary') as secundary FROM book
				where ( ExtractValue(book_data,'book/Theme/*/detalle') <> '' )
				")
			) {
	        /*comprobar que el registro existe*/
	    	$stmt->execute();
	        if($stmt->rowCount() > 0) {
	        	// $result=$stmt->fetch(PDO::FETCH_ASSOC); //un solo registro
	        	$result=$stmt->fetchAll();
	        	$result["Coutn"]=$stmt->rowCount();
	        	$result["error"]=100;
	        }
	        else{
	        	$result["error"]=-100;
	        }
	    }
		$dbh = null;
		return $result;
	}

?>