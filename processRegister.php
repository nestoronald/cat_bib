<?php

/* Este archivo contiene el procedimiento para cambiar la clave de un usuario
 * de manera segura
 */

/* This file has the procedure to change user's password
 */

require ("../class/dbconnect.php");
require ("../class/PasswordHash.php");
require ("Security.php");

//secure_session_start(); // Our custom secure way of starting a php session.
session_start();

$t_hasher = new PasswordHash(8, FALSE);
$hash0 = $t_hasher->HashPassword("passchanged");
$hash1 = $t_hasher->HashPassword("loginfailed");
$hash2 = $t_hasher->HashPassword("incorrectpost");
$hash4 = $t_hasher->HashPassword("passnoequeals");
$hashMailConfirm = $t_hasher->HashPassword("mailconfirm");

$dbh = conx("biblioteca_virtual","wmaster","igpwmaster");

if(isset($_POST['user'], $_POST['password'],$_POST['mail'],$_POST['repassword'])) {
	$user = $_POST['user'];
	$password = $_POST['password']; // The hashed password.
    $newpassword = $_POST['repassword']; // The hashed new password.
    $email = $_POST['mail']; // The hashed new password.
    if ($password != $newpassword) {
        header('Location: ./register.php?e='.$hash4); exit;
    }

	if(login_exist($user, $email, $dbh) == false) {

                $mailHeader = 'MIME-Version: 1.0' . "\r\n";
                $mailHeader .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $mailHeader .= 'From: webmaster@app.igp.gob.pe' . "\r\n";
                $title="IGP BIBLIOTECA, confirmar registro";
                // $message = "<p>Estimado usuario ".$_SESSION['username'].",</p> <p>Para completar el registro hacer click ";
                // $message .= "<a href='http://localhost/igpshared/confirmNewPass.php?mc=$hashMailConfirm&id=".$_SESSION['user_id']."'>aqui</a></p>";
                // $message .= "<p>&oacute; escriba la siguiente direcci&oacute;n en su navegador:</p>";
                // $message .= "<p>http://app.igp.gob.pe/igpshared/confirmNewPass.php?id=".$_SESSION['user_id']."&mc=$hashMailConfirm</p>";
                NewUser($user, $email, $newpassword, $dbh);
                //echo "usuario registrado";
                if (sendUserMail($user, $password, $email, $dbh, $mailHeader, $title)) {
                    header('Location: ./register.php?e='.$hash0);
                    // echo "para confirmar registro verifique su email";
                }
                else{
                    header('Location: ./register.php?e='.$hash2);
                }

	} else {
		// Login failed
		header('Location: ./register.php?e='.$hash1);

	}
}
else {
	// The correct POST variables were not sent to this page.
	header('Location: ./register.php?error='.$hash2);
}



?>