<?php

ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '/home/group8/pear/PEAR');
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '/home/group8/infinitymetrics/app/classes');
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '/home/group8/infinitymetrics/app/template');

$_SERVER["home_address"] = "http://infinitymetrics.local.net";

$leftNavClass = "sidebars admin-reports-dblog admin-reports admin tableHeader-processed";
$NoLeftNavClass = "sidebar-right node-2-edit node-2 node";

function isUserLoggedIn() {
    return isset($_SESSION) && array_key_exists("loggedUser", $_SESSION);
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

session_cache_limiter('private');
session_cache_expire(180); // 2 hours
session_start();

if (isUserInReservedAreas()) {
   if (!isUserLoggedIn() && strpos($_SERVER["REQUEST_URI"],"/user/login.php")) {
       
        $_SESSION["signinError"] = "Invalid session to visit a reserved page";
        header("Location: " . $_SERVER["home_address"] . "/user/login.php");
   }
   //$user = $_SESSION["user"];
   //TODO: NEEDS to verify if the user is the owner of ANY project associated with ANY workspace
   //That means this user is a java.net project owner and will have a workspace, or this user is
   //an instructor. Both types of users have workspaces.
   //$user->hasWorkspace() should be the method created on the PersistenUser class 
   //(TO BE PATCHED with the correct implementation).
   //$userHasWorkspace = $user->isInstructor();
}
?>
