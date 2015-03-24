<?php

 require ("../class/dbconnect.php");
 require ("../class/PasswordHash.php");
 require ('../class/xajax_core/xajax.inc.php');
 require ('../class/smarty/Smarty.class.php');
 require ("Security.php");
 $xajax=new xajax();
 $xajax->configure('javascript URI', 'js/');
 date_default_timezone_set('America/Lima');


 require("indexSearch.php");
 require("adminModel.php");
 session_name("bib");
 session_start();

 // function editPass($form){
 //    $objResponse = new xajaxResponse();
 //    $msj="";
 //    // $objResponse->alert(print_r($form,true));
 //    if (isset($_SESSION["iduser"])) {
 //        $pass = $form["pass"];
 //        $newpass = $form["newpass"];
 //        $renewpass = $form["renewpass"];
 //        if (empty(trim($pass)) or empty(trim($newpass)) or empty(trim($renewpass))) {
 //            $msj = "Ningun campo puede estar vacio";
 //        }
 //        else{
 //            $pass = md5($pass);
 //            $newpass = md5($newpass);
 //            $renewpass = md5($renewpass);
 //            if ($newpass!=$renewpass) {
 //                $msj = "Las contraseñas no coinciden";
 //                $objResponse->script("$('#renewpass').foucs(); return false;");
 //            }
 //            elseif ($pass==$newpass) {
 //                $msj = "La contraseña nueva debe ser diferente a la actual";
 //            }
 //            else{
 //                if (newPassword($form)) {
 //                    $html = "<div class='exito'>La Contraseña ha sido actualizado correctamente</div>";
 //                    $objResponse->assign("modalbody","innerHTML",$html);
 //                    $objResponse->assign("modalfooter","innerHTML",$html);
 //                }
 //                else{
 //                    $msj="Verifique que la contraseña actual sea la correcta";
 //                }
 //            }
 //        }
 //    }
 //    $objResponse->assign("msj-pass","innerHTML",$msj);
 //    return $objResponse;
 // }
 // function editMyProfile_frm(){
 //    $objResponse = new xajaxResponse();
 //    $user = $_SESSION["user_id"];
 //    $result = membersQuery_01($user);
 //    if (!empty($result["data"])) {
 //            $data_array = xmlToArray($result["data"]);
 //            $result = array_merge($result,$data_array);
 //    }
 //    $html = '<input type="hidden" name="iduser" value="'.$result["id"].'">
 //            <p> <span>Nombre Completo:</span> </p>
 //            <input type="text" name="names" value="'.$result["names"].'">
 //            <p> <span>DNI:</span>  </p>
 //            <input type="text" name="dni" value="'.$result["dni"].'">
 //            <p> <span>Dirección:</span>  </p>
 //            <textarea name="dir" cols="10" >'.$result["dir"].'</textarea>
 //            <p> <span>Teléfono:</span> </p>
 //            <input type="text" name="tel" value="'.$result["tel"].'">';
 //    $objResponse->assign("frmMyProfile","style.display","block");
 //    $objResponse->assign("modalfooter_pro","style.display","block");
 //    $objResponse->assign("msj-profile","innerHTML","");
 //    $objResponse->assign("frmMyProfile","innerHTML",$html);
 //    return $objResponse;
 // }
 // function editMyprofile($form='') {
 //    $objResponse = new xajaxResponse();
 //    if (isset($_SESSION["iduser"])) {
 //        if (updateProfile($form)) {
 //            $msj = "<div class='exito'><i class='icon-ok'></i>Tus datos han sido actualizados correctamente</div>";
 //            $objResponse->assign("frmMyProfile","style.display","none");
 //            $objResponse->assign("modalfooter_pro","style.display","none");
 //            $objResponse->script("xajax_details_member()");
 //        }
 //        else{
 //            $msj="Intente mas tarde, no se pudo actualizar tus datos";
 //        }
 //    }
 //    $objResponse->assign("msj-profile","innerHTML",$msj);
 //    return $objResponse;
 // }
 // function details_member(){
 //    $objResponse = new xajaxResponse();
 //    $html = "";
 //    if($_SESSION["usertype"]==1) {
 //        $user = $_SESSION["user_id"];
 //        $result = membersIGPQuery_01($user);
 //    }
 //    elseif($_SESSION["usertype"]==2) {
 //        $user = $_SESSION["user_id"];
 //        $result = membersQuery_01($user);
 //        if (!empty($result["data"])) {
 //            $data_array = xmlToArray($result["data"]);
 //            $result = array_merge($result,$data_array);
 //        }
 //        $html = "<p> <span>Nombre Completo:</span> ".$result["names"]."</p>
 //                    <p> <span>DNI:</span>".$result["dni"]."</p>
 //                    <p> <span>Dirección:</span>".$result["dir"]."</p>
 //                    <p> <span>Teléfono:</span>".$result["tel"]."</p>";
 //    }
 //    else{
 //        // $result= "Sesión caducada";
 //        $html= "Sesión caducada";
 //    }
 //    // return $result;
 //    $objResponse->assign("email_profile","innerHTML",$result["email"]);
 //    $objResponse->assign("profileconte","innerHTML",$html);
 //    return $objResponse;
 // }

 if (isset($_SESSION["idusers"])) {
    $xajax->processRequest();
    $smarty = new Smarty;
    $smarty->assign("xajax",$xajax->printJavascript());
    // $smarty->assign("details", details_member());
    $smarty->display('tpl/about.tpl');
 }
 else{
    header('Location: ./admin.php');exit;
 }

 ?>

