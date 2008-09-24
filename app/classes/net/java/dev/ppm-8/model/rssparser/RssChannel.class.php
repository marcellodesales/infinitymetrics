<?php

class RssChannel {

    private $title;
    private $description;
    private $items;

    /* 
     * Creates a new CollabnetRssChannel class from the main tags from the
     * channel.
     * @param string $title is the main title of the channel
     * @param string $link is the main link of the channel
     * @param string $desc is the description of the channel. If it is the same
     * as the title.
     */
    public function __construct($title, $link, $desc) {
        $this->title = $title;
        $this->description = ($title == $desc) ? "" : $desc;
        $this->projectName = substr($link, strlen("https://"), strpos($link, ".")-strlen("https://"));
        $this->channelCategory = substr($link, strpos($link, "=")+1, strlen($link));
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
        return "https://" . $this->projectName . $this->LIST . $this->channelCategory;
    }
    
    /*
     * Returns the description tag from the rss feed.
     * <rss><channel><description>VALUE</description>...
     */
    public function getDescription() {
        return ($this->description == "") ? $this->title : $this->description;
    } 
}

?>
