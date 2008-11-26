<?php
require_once "infinitymetrics/util/javanet/rssparser/RssChannel.class.php";
/*
 * CollabnetRssChannel.php
 *
 * Represents the collabnet Rss channel format. It contains information
 * about the running project and channel category parsed from the main channel
 * link.
 */
class CollabnetRssChannel {

    /**
     * @var string is the RSS channel instance
     */
    private $rssChannel;
    /**
     * @var string is the URL for the RSS parsed
     */
    private $url;

    /**
     * Constructs a new CollabNetRSSChannel based on the given URL
     * @param string $url is the url of a given RSS from Java.net RSS. It can be a mailing list or forum
     */
    public function __construct($url) {
        $this->url = $url;
        $this->rssChannel = new RssChannel();
    }
    /**
     * @return RssItemBuilder a new instance of the RSS Item Builder
     */
    public function newRssItemBuilder() {
        return RssChannel::newRssItemBuilder();
    }
    /**
     * Adds an item to the collection of the RSS channel
     * @param RssItem $rssItem is an instance of the RssItem
     */
    public function addItem(RssItem $rssItem) {
        $this->rssChannel->addItem($rssItem);
    }
    public function getRssChannel() {
        return $this->rssChannel;
    }
    /**
     * @return the complete list of the RssItem[]
     */
    public function getItems() {
        $this->rssChannel->getItems();
    }
    /**
     * Sets the new title of the RSS
     * @param string $title is the title of the RSS channel
     */
    public function setTitle($title) {
      $this->rssChannel->setTitle($title);
    }
    /**
     * Sets the link of the RSS
     * @param string $link is the url to link to the rss
     */
    public function setLink($link) {
        $this->rssChannel->setLink($link);
    }
    /**
     * Sets the description of the RSS
     * @param string $description is the description of the RSS. It can be the same as the title
     */
    public function setDescription($description) {
        $this->rssChannel->setDescription($description);
    }
    /**
     * @return the java.net project name from the link tag.
     */    
    public function getProjectName() {
        return $this->rssChannel->getProjectName();
    }
    /**
     * @return the channel id based on the channel URL.
     */    
    public function getChannelId() {
        return $this->rssChannel->getChannelId();
    }
    /**
     * @return the channel category name from the link tag.
     */
    public function getChannelTitle() {
        return $this->rssChannel->getTitle();
    }
    /**
     * @return int the number of items on the RSS channel
     */
    public function getNumberOfItems() {
        return $this->rssChannel->getNumberOfItems();
    }
    /**
     * @param string $projectName the name of the project
     * @param string $mailingList the name of the mailing list
     * @return string the URL for the mailing list for a given project
     */
    public static function makeMailingListUrl($projectName, $mailingList) {
        return RssChannel::PROTOCOL . $projectName . RssChannel::DOMAIN . 
                 RssChannel::MAILING_LIST_SERVLET . $mailingList;
    }
    /**
     * @param string $projectName the name of the project
     * @param string $forumId the id of the forum
     * @return string the URL for the discussion forum for a given project
     */
    public static function makeDiscussionForumtUrl($projectName, $forumId) {
        return RssChannel::PROTOCOL . $projectName . RssChannel::DOMAIN .
                 RssChannel::FORUM_LIST_SERVLET . $forumId;
    }
}
?>
