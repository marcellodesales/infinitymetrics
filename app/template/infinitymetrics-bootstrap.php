<?php

ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '/home/group8/pear/PEAR');
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '/home/group8/infinitymetrics/app/classes');
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '/home/group8/infinitymetrics/app/template');

$home_address = "http://hci.cs.sfsu.edu/~group8/nightly";

require_once 'propel/Propel.php';
Propel::init('infinitymetrics/orm/config/om-conf.php');

$leftNavClass = "sidebars admin-reports-dblog admin-reports admin tableHeader-processed";
$NoLeftNavClass = "sidebar-right node-2-edit node-2 node";

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
