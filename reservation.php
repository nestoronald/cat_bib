<?php

 require ("../class/dbconnect.php");
 require ("../class/PasswordHash.php");
 require ("../class/Security.php");
 require ('../class/xajax_core/xajax.inc.php');
 require ('../class/smarty/Smarty.class.php');
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
                $result=searchPublication_iddataSQL($value);
                $xmlt = simplexml_load_string($result["book_data"][0]);
                $json = json_encode($xmlt);
                $data_array = json_decode($json,TRUE);
                $html .= "<p><span>".($key+1).") </span>".$data_array["title"]."</p>";
            }
        }
        else{
            $html ="<p>Usted no tiene ninguna reserva</p>";
        }

        return $html;
 }
 function lista_reserva(){
        $html="";
        // if (isset($_SESSION["reserva"])) {
        // // seleccionar los libros alamacenados en session por id
        //     foreach ($_SESSION["reserva"]["idbook"] as $key => $value) {
        //         $result=searchPublication_iddataSQL(0,"",$value);
        //         $xmlt = simplexml_load_string($result["book_data"][0]);
        //         $json = json_encode($xmlt);
        //         $data_array = json_decode($json,TRUE);
        //         $html .= "<p><span>".$key.") </span>".$data_array["title"]."</p>";
        //     }
        // }
        // else{
        //     $html ="<p>Usted no tiene ninguna reserva</p>";
        // }
        $result=loanBookMembersQuery("",$_SESSION["user_id"]);
        if (isset($result)) {
            if ($result["Count"]>0) {
                for ($i=0; $i < $result["Count"]; $i++) {
                    $html .="<div class='list_block row'>";
                    if (isset($result["book_data"][$i])) {
                        $html .= book_xml_html($result["book_data"][$i]);
                    }
                    $html .= "<p><i class='icon-calendar'></i><span class='label-1'>Fecha: </span>".$result["fx_reserve"][$i]."</p> ";
                    $state = $result["state"][$i]==1 ? "<span class='text-success'>Reservado</span>":($result["state"][$i]==2 ? "<span class='text-error'>Prestado</span>":"<span class='text-info'>Devuelto</span>");
                    $html .= "<p class='res'><i class='icon-info-sign'></i><span class='label-1'>Estado: </span>".$state."</p> ";
                    $html .="</div>";
                }

            }
        }

        return $html;
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
        $title="";
        // if (isset($xmlt->title)) {
        //     $title.= "<p class='ssss'>".(string)$xmlt->title."</p>";
        // }
        if (isset($data_array["title"])) {
            $title .= "<p class='res'><i class='icon-book'></i> ".$data_array["title"]."</p>";
        }

        $html = $title;
        return $html;
}


 if (isset($_SESSION["iduser"])) {
    $xajax->processRequest();
    $smarty = new Smarty;
    $smarty->assign("xajax",$xajax->printJavascript());
    $smarty->assign("reservation", book_reserva());
    $smarty->assign("listReservation", lista_reserva());
    $smarty->display('reservations.tpl');
 }
 else{
    header('Location: ./index.php');exit;

    // if(isset($_POST['user'], $_POST['p'])) {
    //    $user = $_POST['user'];
    //    $password = $_POST['p']; // The hashed password.
    //    if(login($user, $password, $dbh) == true) {
    //        // Login success
    //        //$msj_success = "Acceso correcto";
    //        $_SESSION["iduser"] = 1;
    //        $_SESSION["username"] = $user;
    //        header('Location: ./index.php');

    //    } else {
    //        // Login failed
    //        header('Location: ./login.php?e='.$hash1);

    //    }
    // } else {
    //    // The correct POST variables were not sent to this page.
    //    header('Location: ./login.php?error='.$hash2);

    // }
 }


 ?>