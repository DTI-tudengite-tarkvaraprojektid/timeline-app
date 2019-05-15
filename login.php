<?php require_once("includes/header.php");
require_once("includes/base.php");

$email = "";
$emailError = "";
$passwordError = "";
$notice = "";

if(isset($_POST["submit"])){
	if (isset($_POST["email"]) and !empty($_POST["email"])){
	  $email = $_POST["email"];
    } else {
	  $emailError = "Palun sisesta kasutajatunnusena e-posti aadress!";
    }
  
    if (!isset($_POST["password"]) or strlen($_POST["password"]) < 8){
	  $passwordError = "Palun sisesta parool, vähemalt 8 märki!";
    }
  
  if(empty($emailError) and empty($passwordError)){
	 $notice = signin($email, $_POST["password"]);
     } else {
	  $notice = "Ei saa sisse logida!";
  }

  }

?>

<div class="container mt-3">
    <h3>Logi sisse! </h3>
    <form class="mt-3">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Sisesta email">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Sisesta parool">
        </div>
        <button type="submit" class="btn btn-primary">Logi sisse</button>
    </form>
</div>
<?php require_once("includes/footer.php"); ?>