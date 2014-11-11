<?php

 require ("../class/dbconnect.php");
 require ("../class/PasswordHash.php");
 require ('../class/smarty/Smarty.class.php');
 require ("../class/Security.php");
 require("indexSearchSQL.php");
 // session_name("igp_bib_members");
 session_start();

 $t_hasher = new PasswordHash(8, FALSE);
 $hash1 = $t_hasher->HashPassword("loginfailed");
 $hash2 = $t_hasher->HashPassword("incorrectpost");

 if (isset($_SESSION["iduser"])) {
    header('Location: ./index.php');exit;
 }
 else{

    if(isset($_POST['user'], $_POST['password'])) {
       $user = $_POST['user'];
       $password = $_POST['password']; // The hashed password.
       if(membersIGPQuery($user, $password) != -100) {
           // Login success
           //$msj_success = "Acceso correcto";
           $result = membersIGPQuery($user, $password);
           $_SESSION["user_id"] = $result["idempleado"];
           $_SESSION["iduser"] = 1;
           $_SESSION["username"] = $user;
           $_SESSION["usertype"] = 1;
           if (isset($_SESSION["reserva"])) {
               header("Location: ./reservation.php");exit;
           }
           else {
               header("Location: ./index.php");exit;
           }

       }
       elseif(membersQuery($user,$password) != -100) {
          $result = membersQuery($user,$password);
          $_SESSION["user_id"] = $result["id"];
          $_SESSION["iduser"] = 1;
          $_SESSION["username"] = $user;
          $_SESSION["usertype"] = 2;
          if (isset($_SESSION["reserva"])) {
              header("Location: ./reservation.php");exit;
          }
          else {
              header("Location: ./index.php");exit;
          }

       }
       else{
          header('Location: ./login.php?e='.$hash1);
       }
    } else {
       // The correct POST variables were not sent to this page.
       header('Location: ./login.php?error='.$hash2);

    }
 }


 ?>