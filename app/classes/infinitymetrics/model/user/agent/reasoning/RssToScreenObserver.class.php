<?php

include_once('infinitymetrics/util/go4pattenrs/behavorial/observer/Observable.class.php');

define('BR', '<'.'BR'.'>');

class RssToScreenObserver implements Observer {

    public function __construct() {
    }

    public function update($collabnetRssChannel) {
        echo $collabnetRssChannel->getChannelCategory() . ": sending to the screen";
    }
}
?>