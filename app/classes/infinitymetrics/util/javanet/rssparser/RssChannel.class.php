<?php
require_once "RssItemBuilder.class.php";

require_once "RssItem.class.php";

class RssChannel {

    private $title;
    private $description;
    private $items;
    private $projectName;
    private $channelCategory;
    
    public static $DOMAIN = ".dev.java.net";
    public static $PROTOCOL = "https://";
    public static $LIST = "/servlets/MailingListRSS?listName=";

    /* 
     * Creates a new CollabnetRssChannel class from the main tags from the
     * channel.
     * as the title.
     */
    public function __construct() {
        $this->items = array();
    }
    
    public function setLink($link) {
        $this->projectName = substr($link, strlen(self::$PROTOCOL),
                                    strpos($link, ".")-strlen(self::$PROTOCOL));
        $this->channelCategory = substr($link, strpos($link, "=")+1, strlen($link));
    }
    
    public function setTitle($title) {
        $this->title = $title;
    }
    
    public function setDescription($description) {
        $this->description = ($title == $description) ? "" : $description;        
    }

    public static function newRssItemBuilder() {
        return new RssItemBuilder();
    }

    public function addItem(RssItem $rssItem) {
        $this->items[] = $rssItem;
    }

    public function getItems() {
        return $this->items;
    }

    public function getNumberOfItems() {
        return sizeof($this->items);
    }
    /*
     * Returns the title tag from the rss feed.
     * <rss><channel><title>VALUE</title>...
     */
    public function getTitle() {
        return $this->title;
    }

    /*
     * Returns the link tag from the rss feed.
     * <rss><channel><link>VALUE</link>...
     */
    public function getLink() {
        return self::$protocol . $this->projectName . self::DOMAIN .
                  $this->LIST . $this->channelCategory;
    }
    
    /*
     * Returns the description tag from the rss feed.
     * <rss><channel><description>VALUE</description>...
     */
    public function getDescription() {
        return ($this->description == "") ? $this->title : $this->description;
    }

    public function getProjectName() {
        return $this->projectName;
    }

    public function getChannelCategory() {
        return $this->channelCategory;
    }
}
?>