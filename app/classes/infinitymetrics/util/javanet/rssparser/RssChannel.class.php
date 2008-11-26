<?php
/**
 * $Id: RssChannel.class.php 202 2008-09-13 21:31:40Z marcellosales $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITYs, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the Berkeley Software Distribution (BSD).
 * For more information please see <http://ppm-8.dev.java.net>.
 */
require_once "infinitymetrics/util/javanet/rssparser/RssItemBuilder.class.php";
require_once "infinitymetrics/util/javanet/rssparser/RssItem.class.php";

/**
 * Representation of an RSS channel. Basically encapsulates the channel definition
 * of the RSS XML schema. It contains a list of RssItems (here implemented as an aggregation)
 * that are each item available.
 *
 * @author Marcello de Sales <marcello.sales@gmail.com> Sep 13, 2008 10:34 PST
 * @version $Id$
 */
class RssChannel {
    /**
     * Defines the domain information.
     * TODO: Value must be difined the environment instead through the bootstrap.
     */
    const DOMAIN = ".dev.java.net";
    /**
     * The protocol defined by the current installation. 
     * TODO: Value must be defined at the bootstrap.
     */
    const PROTOCOL = "https://";
    /**
     * The servlet value defined for the mailing list
     */
    const MAILING_LIST_SERVLET = "/servlets/MailingListRSS?listName=";
    /**
     * The servlet value defined for the forum RSS lists
     */
    const FORUM_LIST_SERVLET = "/servlets/ProjectRSS?type=message&forumID=";
    /**
     * @var is the title of the RSS channel
     */
    private $title;
    /**
     * @var string is the description of the Rss Channel
     */
    private $description;
    /**
     * It's the composition of the items of the channel being read. 
     * @var array(RssItem) is the composition of all RSS Items 
     */
    private $items;
    /**
     * @var string the is the java.net project name
     */
    private $projectName;
    /**
     * @var String is the category of the channel (basically the ID of mailing lists, or forum for others)
     */
    private $channelId;
    /**
     * Creates a new CollabnetRssChannel class from the main tags from the channel.
     * as the title.
     */
    public function __construct() {
        $this->items = array();
    }
    /**
     * @param string $link is the link or the Rss Channel. It automatically defines the
     * project name and the channel id, retrieved from the URL(link).
     */
    public function setLink($link) {
        $this->projectName = substr($link, strlen(self::PROTOCOL),
                                    strpos($link, ".")-strlen(self::PROTOCOL));
        $this->channelId = substr($link, strpos($link, "=")+1, strlen($link));
    }
    /**
     * @param string $title is the title of the RSS feed
     */
    public function setTitle($title) {
        $this->title = $title;
    }
    /**
     * @param string $description is the description of the Rss feed. If the title is the same,
     * this value WILL NOT be set.
     */
    public function setDescription($description) {
        $this->description = ($this->title == $description) ? "" : $description;
    }
    /**
     * @return RssItemBuilder factory method that returns a new RssItemBuilder
     */
    public static function newRssItemBuilder() {
        return new RssItemBuilder();
    }
    /**
     * Adds a new Item into the RssChannel instance.
     * @param RssItem $rssItem is the instance of the Rss Item.
     */
    public function addItem(RssItem $rssItem) {
        $this->items[] = $rssItem;
    }
    /**
     * @return RssItem[] is the list of the Rss Items retrieved.
     */
    public function getItems() {
        return $this->items;
    }
    /**
     * @return int returns the number of items retrieved.
     */
    public function getNumberOfItems() {
        return sizeof($this->items);
    }
    /**
     * @return string the title tag from the rss feed.
     * <rss><channel><title>VALUE</title>...
     */
    public function getTitle() {
        return $this->title;
    }
    /**
     * @return the link tag from the rss feed.
     * <rss><channel><link>VALUE</link>...
     */
    public function getLinkForMailingList() {
        return self::PROTOCOL . $this->projectName . self::DOMAIN .self::MAILING_LIST . $this->channelId;
    }
    /**
     * @return string the description tag from the rss feed.
     * <rss><channel><description>VALUE</description>...
     */
    public function getDescription() {
        return ($this->description == "") ? $this->title : $this->description;
    }
    /**
     * @return string the project name associated with the RssChannel
     */
    public function getProjectName() {
        return $this->projectName;
    }
    /**
     * @return string this is the channel identification. It can be in 2 different formats:
     * 1. a string that represents the name of the mailing list: users, dev, commits...
     * 2. an integer that is the forumID or anything else.
     */
    public function getChannelId() {
        return $this->channelId;
    }
}
?>