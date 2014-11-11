<?php

require ("../class/dbconnect.php");
require ("../class/PasswordHash.php");
require ("../class/Security.php");


$t_hasher = new PasswordHash(8, FALSE);
$hashMailConfirm = $t_hasher->HashPassword("mailconfirm");

$dbh = conx("biblioteca_virtual","wmaster","igpwmaster");

if(isset($_GET['mc'])){
    $hashMailConfirm = $_GET['mc'];
    $t_hasherMail = new PasswordHash(8, FALSE);
    $checkMail = $t_hasherMail->CheckPassword('mailconfirm', $hashMailConfirm);
    $iduser=$_GET['id'];

    if ($checkMail){
        if (updatePassword($iduser, $dbh)){
            $message ='<html><head>';
            $message.='<link rel="stylesheet" href="stylesheets/base.css"></head>';
            $message.= "<body><p>&nbsp;</p><div class='success'>La confirmaci&oacute;n de usuario se ha realizado con exito</div><body></html>";
        }
        else{
            $message ='<html><head>';
            $message.='<link rel="stylesheet" href="stylesheets/base.css"></head>';
            $message.= "<body><p>&nbsp;</p><div class='error'>Error la confirmaci&oacute;n de usuario</div><body></html>";
        }

        print $message;

    }
}

?>
