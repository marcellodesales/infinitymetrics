<?php
require_once 'propel/Propel.php';
Propel::init('infinitymetrics/orm/config/om-conf.php');

require_once 'infinitymetrics/model/institution/Instructor.class.php';

function isUserLoggedIn() {
    return array_key_exists("user", $_SESSION);
}

function contains($content, $str, $ignorecase=true){
    if ($ignorecase){
        $str = strtolower($str);
        $content = strtolower($content);
    }
    return strpos($content, $str) ? true : false;
}

function isUserInReservedAreas() {
    return contains($_SERVER["REQUEST_URI"], "/workspace") || contains($_SERVER["REQUEST_URI"], "/cetracker") ||
               contains($_SERVER["REQUEST_URI"], "/report");
}

session_start();
if (isUserLoggedIn()) {
   $user = $_SESSION["user"];
   $isUserInstructor = $user instanceof Instructor;
}

if (isUserInReservedAreas() && !isUserLoggedIn()) {
    header('Location: /' );
}
?>
