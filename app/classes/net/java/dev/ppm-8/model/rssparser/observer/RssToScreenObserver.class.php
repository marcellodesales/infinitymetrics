<?php

include_once('../../../util/go4/observer/Observer.class.php');

define('BR', '<'.'BR'.'>');

class RssToScreenObserver implements Observer {

    public function __construct() {
    }

    public function update($collabnetRssChannel) {
        echo $collabnetRssChannel . ": sending to the screen";
    }
}
?>