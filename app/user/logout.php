<?php
require_once '../template/infinitymetrics-bootstrap.php';

$user =  $_SESSION["loggedUser"];
$userFirstName = $user->getFirstName();

$_SESSION["loggedUser"] = null;
unset($_SESSION["loggedUser"]);
if(isset($_COOKIE[session_name()])){
   setcookie(session_name(),'', time()-48000,'/');
}
session_destroy();

session_start();
$_SESSION["successMessage"] = "Thanks ".$userFirstName." for using ∞Metrics... You've been logged out... See you later...";

header("Location: " . $_SERVER["home_address"]);
?>
