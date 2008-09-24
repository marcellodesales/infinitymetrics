<?php

include_once('../../../util/go4/observer/Observer.class.php');

define('BR', '<'.'BR'.'>');

class RssToDatabaseObserver implements Observer {

    public function __construct() {
    }

    public function update($collabnetRssChannel) {
        echo "Connecting to the database...";
        echo $collabnetRssChannel;
        echo "sending rss to the database...";
    }
}
?>
