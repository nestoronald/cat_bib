<?php
    require ('../class/dbconnect.php');
    require ('../class/xajax_core/xajax.inc.php');
    require ('../class/smarty/Smarty.class.php');
    require ("../class/PasswordHash.php");
    $xajax=new xajax();
    $xajax->configure('javascript URI', 'js/');
    date_default_timezone_set('America/Lima');
    require("indexSearch.php");
    require("adminModel.php");
    session_start("igp_bib_members");
    function book_reserva(){
        $html="";
        if (isset($_SESSION["reserva"])) {
        // seleccionar los libros alamacenados en session por id
            foreach ($_SESSION["reserva"]["idbook"] as $key => $value) {
                $result=searchPublication_iddataSQL(0,"",$value);
                $xmlt = simplexml_load_string($result["book_data"][0]);
                $json = json_encode($xmlt);
                $data_array = json_decode($json,TRUE);
                $html .= "<p><span>".($key+1).") </span>".$data_array["title"]."</p>";
            }
        }
        return $html;
    }

    function manageError(){

        if(isset($_GET["e"])){

            $t_hasher = new PasswordHash(8, FALSE);
            $hash = $_GET["e"];
            $check1 = $t_hasher->CheckPassword('loginfailed', $hash);
            $check2 = $t_hasher->CheckPassword('incorrectpost', $hash);
            $check3 = $t_hasher->CheckPassword('nosession', $hash);

            if ($check1){
                return "<div class=error>Usuario / Clave no validos</div>";
            }
            if ($check2){
                return "<div class=error>No se enviaron los datos necesarios</div>";
            }
            if ($check3){
                return "<div class=error>No ha iniciado sesión</div>";
            }
        }
        else{
            return "";
        }
    }
    if (isset($_SESSION["iduser"])) {
        if (isset($_SESSION["reserva"])) {
            header("Location: ./reservation.php");exit;
        }
        else {
            header("Location: ./index.php");exit;
        }

    }
    else{
       $xajax->processRequest();
       $smarty = new Smarty;
       $smarty->assign("xajax",$xajax->printJavascript());
       $smarty->assign("reserva", book_reserva());
       $smarty->assign("msj",  manageError());
       $smarty->display('login.tpl');
    }




?>