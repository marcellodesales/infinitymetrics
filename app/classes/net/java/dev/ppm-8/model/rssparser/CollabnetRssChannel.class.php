<?php

/*
 * CollabnetRssChannel.php
 *
 * Represents the collabnet Rss channel format. It contains information
 * about the running project and channel category parsed from the main channel
 * link.
 */
class CollabnetRssChannel {

    private $projectName;
    private $channelCategory;

    private static $LIST = ".dev.java.net/servlets/BrowseList?list=";

    /*
     * Returns the project name from the link tag.
     */    
    public function getProjectName() {
        return $this->projectName;
    }

    /*
     * Returns the channel category name from the link tag.
     */    
    public function getChannelCategory() {
        return $this->channelCategory;
    }
}

$rss = new CollabnetRssChannel("Message List in RSS Format for Project glassfish, Mailing List issues", 
                              "https://glassfish.dev.java.net/servlets/BrowseList?list=issues",
                              "Message List in RSS Format for Project glassfish, Mailing List issues");

echo $rss->getTitle() . "<BR>";
echo $rss->getLink() . "<BR>";
echo $rss->getDescription() . "<BR>";
echo $rss->getProjectName() . "<BR>";
echo $rss->getChannelCategory() . "<BR>";

?>
