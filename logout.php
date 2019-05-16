<?php
require_once("includes/Classes/SessionManager.php");
require_once("includes/base.php");

$logout = new SessionManager;

$logout->signOut();

header("location: index.php");


 ?>
