<?php

 require ("../class/dbconnect.php");
 require ("../class/PasswordHash.php");
 require ('../class/smarty/Smarty.class.php');
 require ("../class/Security.php");


 session_start("");

 $t_hasher = new PasswordHash(8, FALSE);
 $hash1 = $t_hasher->HashPassword("loginfailed");
 $hash2 = $t_hasher->HashPassword("incorrectpost");

 $dbh = conx("biblioteca_virtual","wmaster","igpwmaster");

 if (isset($_SESSION["iduser"])) {
    unset($_SESSION["iduser"]);
    unset($_SESSION["reserva"]);
    header('Location: ./index.php');exit;
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

//$smarty = new Smarty;
//$smarty->assign("msj_success",  $msj_success);
//$smarty->display('reservations.tpl');

 ?>