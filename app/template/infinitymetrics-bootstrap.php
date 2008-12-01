<?php

ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '/home/group8/pear/PEAR');
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '/home/group8/infinitymetrics/app/classes');
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '/home/group8/infinitymetrics/app/template');

$_SERVER["home_address"] = "http://infinitymetrics.local.net";

require_once 'propel/Propel.php';
Propel::init('infinitymetrics/orm/config/om-conf.php');

$leftNavClass = "sidebars admin-reports-dblog admin-reports admin tableHeader-processed";
$NoLeftNavClass = "sidebar-right node-2-edit node-2 node";

function isUserLoggedIn() {
    return true;
    //return array_key_exists("loggedUser", $_SESSION);
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

////ini_set("session.cache_expire","180"); // default is 180, which is 3 hours...
ini_set("session.gc_maxlifetime","3600"); // default is 1440, which is only 24 minutes
session_save_path("c:/ppm8-dev/sessions");
session_start();

if (!isUserLoggedIn()) {
   // header("Location: " . $_SERVER["home_address"] . "?error=Session Has Expired");
   //$user = $_SESSION["user"];
   //TODO: NEEDS to verify if the user is the owner of ANY project associated with ANY workspace
   //That means this user is a java.net project owner and will have a workspace, or this user is
   //an instructor. Both types of users have workspaces.
   //$user->hasWorkspace() should be the method created on the PersistenUser class 
   //(TO BE PATCHED with the correct implementation).
   //$userHasWorkspace = $user->isInstructor();
}

if (isUserInReservedAreas() && !isUserLoggedIn()) {
    header('Location: /' );
}
?>
