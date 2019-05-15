
<?php
require_once("includes/base.php");


class SessionManager {
  public function signin($email, $password){
    global $db;
    $notice = "";
    $stmt = $db->prepare("SELECT id, password FROM users WHERE email=?");
    $stmt->execute([$email]);
    if ($row = $stmt->fetch()) {
      //leiti selline kasutaja
      if (password_verify($password, $row['password'])){
        $notice = "Logisite õnnelikult sisse!";
        $_SESSION["user"] = $row['id'];
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
}
