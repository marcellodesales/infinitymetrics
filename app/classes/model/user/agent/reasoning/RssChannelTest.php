<?php

require_once 'CollabnetRssReader.class.php';
require_once 'PHPUnit/Framework.php';

class RssChannelTest extends PHPUnit_Framework_TestCase {
    
    public function testRssChannelCreation() {
        $title = "Message List in RSS Format for Project glassfish,..";
        $link = "https://glassfish.dev.java.net/servlets/BrowseList?list=issues";
        $desc = "Message List in RSS Format for Project glassfish, Mailing List";
        $rssChannelFixture = CollabnetRssChannel::Builder($title, $link, $desc);

        $this->assertEquals($title, $rssChannelFixture->getTitle());
        $this->assertEquals($link, $rssChannelFixture->getLink());
        $this->assertEquals($desc, $rssChannelFixture->getDescription());
        $this->assertEquals("glassfish", $rssChannelFixture->getProjectName());
        $this->assertEquals("issues", $rssChannelFixture->getChannelCategory());
    }
}

?>
