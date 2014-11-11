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

    function manageError(){

        $message="";
        if(isset($_GET["e"])){

                $t_hasher = new PasswordHash(8, FALSE);
                $hash = $_GET["e"];
                // $check = validar_vacio();
                $check0 = $t_hasher->CheckPassword('passchanged', $hash);
                $check1 = $t_hasher->CheckPassword('loginfailed', $hash);
                $check2 = $t_hasher->CheckPassword('incorrectpost', $hash);
                $check3 = $t_hasher->CheckPassword('nosession', $hash);
                // if ($check) {
                //     $message= "<div class='error'>Usuario no debe de estar vacio</div>";
                // }
                if ($check1){
                        $message= "<div class='error'>Usuario y/o correo,  ya están registrados</div>";
                }
                if ($check0){
                        $message= "<div class='notice'>Para completar el registro debe darle click al enlace de confirmación que se envió a su correo. </div>";
                }
                if ($check2){
                        $message= "<div class='error'>No se enviaron los datos necesarios</div>";
                }
                if ($check3){
                        $message= "<div class='error'>No ha iniciado sesión</div>";
                }
        }
        else{
                $message="";
        }

        return $message;
    }

    if (isset($_SESSION["iduser"])) {
       header("Location: ./index.php");exit;
    }
    else{
       $xajax->processRequest();
       $smarty = new Smarty;
       $smarty->assign("xajax",$xajax->printJavascript());
       $smarty->assign("msj_register",  manageError());
       $smarty->display('register.tpl');
    }




?>

