<?php

include_once('infinitymetrics/util/go4pattenrs/behavorial/observer/Observable.class.php');

define('BR', '<'.'BR'.'>');

class RssToDatabaseObserver implements Observer {

    public function __construct() {
    }

    public function update($collabnetRssChannel) {
        echo "<BR><BR>Connecting to the database...";
        echo $collabnetRssChannel->getNumberOfItems() . " ITEMS";
        echo "sending rss to the database...<BR><BR>";
    }
}
?>
