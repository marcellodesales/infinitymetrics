<?php
require_once "RssChannel.class.php";
/*
 * CollabnetRssChannel.php
 *
 * Represents the collabnet Rss channel format. It contains information
 * about the running project and channel category parsed from the main channel
 * link.
 */
class CollabnetRssChannel {

    private $rssChannel;
    private $url;

    public function __construct($url) {
        $this->url = $url;
        $this->rssChannel = new RssChannel();
    }

    public function newRssItemBuilder() {
        return RssChannel::newRssItemBuilder();
    }

    public function addItem(RssItem $rssItem) {
        $this->rssChannel->addItem($rssItem);
    }

    public function getItems() {
        $this->rssChannel->getItems();
    }

    public function setTitle($title) {
      $this->rssChannel->setTitle($title);
    }

    public function setLink($link) {
        $this->rssChannel->setLink($link);
    }

    public function setDescription($description) {
        $this->rssChannel->setDescription($description);
    }
    /*
     * Returns the project name from the link tag.
     */    
    public function getProjectName() {
        return $this->rssChannel->getProjectName();
    }

    /*
     * Returns the channel category name from the link tag.
     */    
    public function getChannelCategory() {
        return $this->rssChannel->getChannelCategory();
    }

    public function getNumberOfItems() {
        return $this->rssChannel->getNumberOfItems();
    }
    
    public static function getUrl($projectName, $mailingList) {
        return RssChannel::$PROTOCOL . $projectName . RssChannel::$DOMAIN . 
                 RssChannel::$LIST . $mailingList;
    }
}
?>
