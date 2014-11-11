<?php
	/**************************************************
	Funcion que consulta la BD sobre los tipos de publicaciones
	para una categoria
	***************************************************/
	
	function comboAreaSQL($idDpto=0,$idRegion=0){
		/*utf8_encode se usa para evitar los errores de caracteres invalidos ejm acentos, etc
		y que salga este error uncaught exception: [object Object]*/
	
            $i=0;
            $area_description="";
		$dbh=conx("DB_ITS","wmaster","igpwmaster");
                $dbh->query("SET NAMES 'utf8'");
		foreach($dbh->query("select * from area where area_enable=1") as $row) {
		//foreach($dbh->query("select * from area where idarea not in(8) and area_enable=1") as $row) {
			$area_description[$i]= $row["area_description"];
			$idarea[$i]= $row["idarea"];
			$i++;
		}
		$dbh = null;

		if (is_array($area_description) and is_array($idarea)){
			$qresult[0]=$area_description;
			$qresult[1]=$idarea;

		}
		else{
			$qresult[0]=array("Error");
			$qresult[1]=array("Error");
		}

		return $qresult;
	}
	
	
	
	function searchSubcategorySQL($idcategory,$in_notin="",$rango="",$idarea=0){
	
	    if($in_notin!=""){
	        $and= " and idsubcategory $in_notin ($rango) ";
	    }
	    else{
	        $and="";
	    }
            
            
            switch ($idarea) {
                case 11:
                    $and= " and idsubcategory=7 ";//idsubcategory=7 son los informes trimestrales de la categoria Información Interna(idcategory=4)
                break;                
                case 12:
                    $and= " and idsubcategory=7 ";//idsubcategory=7 son los informes trimestrales de la categoria Información Interna(idcategory=4)
                break;
                case 13:
                    $and= " and idsubcategory=7 ";//idsubcategory=7 son los informes trimestrales de la categoria Información Interna(idcategory=4)
                break;
                case 14:
                    $and= " and idsubcategory=7 ";//idsubcategory=7 son los informes trimestrales de la categoria Información Interna(idcategory=4)
                break;

            }
            
	    
		$dbh=conx("DB_ITS","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");
                
	        if($idarea==8){
	            $sql = "SELECT * FROM subcategory WHERE idcategory=$idcategory and subcategory_enable=1";
	        }
	        elseif($idarea==5){
	        	$sql = "SELECT * FROM subcategory WHERE idcategory=$idcategory and subcategory_enable=1";
	        }/*
	        elseif($idarea==12){//idsubcategory=7 son los informes trimestrales de la categoria Información Interna(idcategory=4)
	        	$sql = "SELECT * FROM subcategory WHERE idcategory=$idcategory and idsubcategory=7 and subcategory_enable=1";
	        }  */              
	        else{
	            $sql = "SELECT * FROM subcategory WHERE idcategory=$idcategory and subcategory_enable=1 $and";
	        }
	        
	    $i=0;
	    if($dbh->query($sql)){
	        foreach($dbh->query($sql) as $row) {
	            $result["idsubcategory"][$i]= $row["idsubcategory"];
	            $result["subcategory_description"][$i]= $row["subcategory_description"];
	            $i++;
	        }
	
	        if(isset($result["idsubcategory"])){
	            $result["Count"]=count($result["idsubcategory"]);
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

	/**************************************************
	Funcion que consulta la BD sobre los tipos de publicaciones
	para una categoria
	***************************************************/
	
	function comboCategorySQL($idarea=0){
	/*utf8_encode se usa para evitar los errores de caracteres invalidos ejm acentos, etc
	y que salga este error uncaught exception: [object Object]*/
                $and="";
                if($idarea==12){
                    $and=" where idcategory=4 and category_enable=1";
                }
                elseif($idarea==8){
                    $and=" where idcategory=3 and category_enable=0";
                }
                else{
                    $and=" where category_enable=1";
                }
                   
            
            
		$i=0;
		$category_description="";
		$dbh=conx("DB_ITS","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");
		foreach($dbh->query("select * from category ".$and) as $row) {
			$category_description[$i]= $row["category_description"];
			$idcategory[$i]= $row["idcategory"];
			$i++;
		}
		$dbh = null;
	
		if (is_array($category_description) and is_array($idcategory)){
			$qresult[0]=$category_description;
			$qresult[1]=$idcategory;

		}
		else{
			$qresult[0]=array("Error");
			$qresult[1]=array("Error");
		}

		return $qresult;
	}
		

	function comboRegionSQL($idRegion=0){

	/*utf8_encode se usa para evitar los errores de caracteres invalidos ejm acentos, etc
	y que salga este error uncaught exception: [object Object]*/

            $i=0;
            $reference_description="";
		$dbh=conx("DB_ITS","wmaster","igpwmaster");
                $dbh->query("SET NAMES 'utf8'");
		foreach($dbh->query("select * from region") as $row) {
			$region_description[$i]= $row["region_description"];
			$idregion[$i]= $row["idregion"];
			$i++;
		}
		$dbh = null;

		if (is_array($region_description) and is_array($idregion)){
			$qresult[0]=$region_description;
			$qresult[1]=$idregion;

		}
		else{
			$qresult[0]=array("Error");
			$qresult[1]=array("Error");
		}

		return $qresult;
	}

	
	/**************************************************
	Funcion que consulta la BD sobre los tipos de publicaciones
	para una categoria
	***************************************************/

	function comboDepartamentoSQL($idDpto=0,$idRegion=0){
		/*utf8_encode se usa para evitar los errores de caracteres invalidos ejm acentos, etc
		y que salga este error uncaught exception: [object Object]*/
		$where="";
		if($idRegion!=0){
		    $where="where idregion='$idRegion'";
		}

            $i=0;
            $reference_description="";
		$dbh=conx("DB_ITS","wmaster","igpwmaster");
                $dbh->query("SET NAMES 'utf8'");
		foreach($dbh->query("select * from department $where") as $row) {
			$department_description[$i]= $row["department_description"];
			$iddepartment[$i]= $row["iddepartment"];
			$i++;
		}
		$dbh = null;

		if (is_array($department_description) and is_array($iddepartment)){
			$qresult[0]=$department_description;
			$qresult[1]=$iddepartment;

		}
		else{
			$qresult[0]=array("Error");
			$qresult[1]=array("Error");
		}

		return $qresult;
	}


	/**************************************************

	***************************************************/

	function comboReferenciaSQL($idarea,$idsubcategory=0,$codReferencia=0){

		//$idarea=isset($_SESSION["idarea"])?$_SESSION["idarea"]:0;
		
		if($idsubcategory!=0){
                    if($idarea<>9){
                        $where="where idsubcategory='$idsubcategory' and idarea=$idarea and reference_enable=1 ORDER BY reference_description ASC";
                    }
                    else{
                        $where="where idsubcategory='$idsubcategory' and reference_enable=1 ORDER BY reference_description ASC";
                    }
		}

		$i=0;
		$reference_description="";
		$dbh=conx("DB_ITS","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");
		
		$sql="select * from reference $where";

	    if($dbh->query($sql)){
	        foreach($dbh->query($sql) as $row) {
	            $result["idreference"][$i]= $row["idreference"];
	            $result["reference_description"][$i]= $row["reference_description"];
	            $i++;
	        }
                if(isset($result["idreference"])){
	            $result["Count"]=count($result["idreference"]);
                    $result["Error"]=0;
	        }
	        else{
	            $result["Count"]=0;                    
                    $result["Error"]=1;
	        }		        
	
	    }
	    else{
	        $result["Error"]=1;
	    }		
		
	    $dbh = null;
	    $result["Query"]=$sql;
	
	    return $result;		

	}

	
	function searchAutorSQL($idauthor){
		$dbh=conx("DB_ITS","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");	
		$sql = "SELECT * FROM author WHERE idauthor=$idauthor";
	    $i=0;
	    
	    if($dbh->query($sql)){
	        foreach($dbh->query($sql) as $row) {
	            $result["idauthor"][$i]= $row["idauthor"];
	            $result["author_surname"][$i]= $row["author_surname"];
	            $result["author_first_name"][$i]= $row["author_first_name"];
	            $result["author_second_name"][$i]= $row["author_second_name"];
	            $i++;
	        }
	
	        if(isset($result["idauthor"])){
	            $result["Count"]=count($result["idauthor"]);
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
	
	
	function searchAreaSQL($idarea){
		$dbh=conx("DB_ITS","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");	
		$sql = "SELECT * FROM area WHERE idarea=$idarea";
	    $i=0;
	    
	    if($dbh->query($sql)){
	        foreach($dbh->query($sql) as $row) {
	            $result["idarea"][$i]= $row["idarea"];
	            $result["area_description"][$i]= $row["area_description"];
	            $i++;
	        }
	
	        if(isset($result["idauthor"])){
	            $result["Count"]=count($result["idauthor"]);
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

	
	/**************************************************

	***************************************************/

	function comboReferenciaAutorSQL($idsubcategory){
		$i=0;
		$dbh=conx("DB_ITS","wmaster","igpwmaster");
		$dbh->query("SET NAMES 'utf8'");
		
		$sql="SELECT idreference,reference_description FROM reference WHERE idsubcategory=$idsubcategory  group BY reference_description";

	    if($dbh->query($sql)){
	        foreach($dbh->query($sql) as $row) {
                    $result["idreference"][$i]= $row["idreference"];
	            $result["reference_description"][$i]= $row["reference_description"];
	            $i++;
	        }
		    if(isset($result["idreference"])){
	            $result["Count"]=count($result["idreference"]);
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
	
	
	
	
?>