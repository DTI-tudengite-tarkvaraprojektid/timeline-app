<?php
require_once("incldes/base.php")
class SessionManager {
    //googleda kuida teha päringuid pdo andmebaasiga
   public function signin($email, $password){
        $notice = "";
        $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
        $stmt = $mysqli->prepare("SELECT id, firstname, lastname, password FROM vpusers WHERE email=?");
        $mysqli->error;
        $stmt->bind_param("s", $email);
        $stmt->bind_result($idFromDb, $firstnameFromDb, $lastnameFromDb, $passwordFromDb);
        if($stmt->execute()){
          //kui õnnestus andmebaasist lugenine
          if($stmt->fetch()){
            //leiti selline kasutaja
            if(password_verify($password, $passwordFromDb)){
              //parool õige
              $notice = "Logisite õnnelikult sisse!";
              $_SESSION["userId"] = $idFromDb;
              $_SESSION["firstName"] = $firstnameFromDb;
              $_SESSION["lastName"] = $lastnameFromDb;
              $stmt->close();
              $mysqli->close();
              header("Location: main.php");
              exit();
              
            } else {
              $notice = "Sisestasite vale salasõna!";
            }
          } else {
            $notice = "Sellist kasutajat (" .$email .") ei leitud!";  
          }		  
        } else {
          $notice = "Sisselogimisel tekkis tehniline viga!" .$stmt->error;
        }
        $stmt->close();
        $mysqli->close();
        return $notice;
      }

}