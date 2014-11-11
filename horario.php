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

    session_start();


    $xajax->processRequest();
    $smarty = new Smarty;
    $smarty->assign("xajax",$xajax->printJavascript());
    $smarty->display('horario.tpl');

?>
