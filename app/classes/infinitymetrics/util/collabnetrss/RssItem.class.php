<?php
/* 
 * <item>
      <title>[Issue 5750] [other]  Hudson : java.net.MalformedURLException: Unknown protocol:</title>
      <link>https://glassfish.dev.java.net/servlets/ReadMsg?list=issues&amp;msgNo=32302</link>
      <description>[Issue 5750] [other]  Hudson : java.net.MalformedURLException: Unknown protocol:</description>
      <pubDate>Mon, 15 Sep 2008 07:00:00 GMT</pubDate>
      <author>mk111283@dev.java.net</author>
      <guid>https://glassfish.dev.java.net/servlets/ReadMsg?list=issues&amp;msgNo=32302</guid>

      <dc:creator>mk111283@dev.java.net</dc:creator>
      <dc:date>2008-09-15T07:00:00Z</dc:date>
    </item>
 */
class RssItem {

    public static $DOMAIN = "dev.java.net";
    
    public static $LINK_DOMAIN_SERVLET = "/servlets/ReadMsg?list=";
    /*
     * The title of the item.
     */
    private $title;
    /*
     * The description of the item. If the same as the title, will be empty 
     */
    private $description;
    /*
     * https://glassfish.dev.java.net/servlets/ReadMsg?list=issues&amp;msgNo=32302
     * The link will only hold the msgNo as the primary object. It also applies
     * to the guid that has the same address as the link.
     */
    private $messageNumber;
    /*
     * Mon, 15 Sep 2008 07:00:00 GMT
     */
    private $pubDate;
    /*
     * mk111283@dev.java.net Username. The username will be the only kept. The
     * output must be decorated on the get method, or have overloaded getters;
     */
    private $authorUsername;
    /*
     * mk111283@dev.java.net
     */
    private $creatorEmail;
    /*
     * 2008-09-15T07:00:00Z
     */
    private $isoDate;

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setMessageNumber($messageNumber) {
        $this->messageNumber = $messageNumber;
    }

    public function setPublicationDate($pubDate) {
        $this->pubDate = $pubDate;
    }

    public function setAuthorUsername($username) {
        $this->authorUsername = $username;
    }

    public function setCreatorEmail($email) {
        $this->creatorEmail = $email;
    }

    public function setIsoDate($isoDate) {
        $this->isoDate = $date;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return ($this->description == "") ? $this->title : $this->description;
    }
    
    public function getLink($projectName, $channelCategory) {
        return  "https://" . $projectName . CollabnetRssItem::$DOMAIN . $this->LINK_DOMAIN_SERVLET . $channelCategory . "&amp;msgNo=" . $this->messageNumber;
    }

    public function getGuid($projectName, $channelCategory) {
        return $this->getLink($projectName, $channelCategory);
    }

    public function getPublicationDate() {
        return $this->pubDate;
    }

    public function getPublicationDateForMySql() {
        return $this->getMySQLDate(strtotime($this->pubDate));
    }

    public function getAuthorUsername() {
        return $this->author;
    }

    public function getAuthorEmail($domain) {
        return $this->author . "@" . $domain;
    }
    
    public function __toString() {
        return $this->messageNumber . "\t" .
               $this->getTitle() . "\t" .
               $this->getDescription() . "\t" .
               $this->getPublicationDateForMySql() . "\t" .
               $this->authorUsername;
    }
    
    /*
     * Mon, 15 Sep 2008 07:00:00 GMT This is buggy... not returning the correct
     * valeu...
     * 2008-09-15T07:00:00Z -> int value -> Original: 1221462000
     *  Mon, 15 Sep 2008 00:00:00 PDT the time was 7 and came as 0
     * http://us.php.net/date
     *
     * 
      echo "2008-09-15T07:00:00Z<BR>";
      echo "Original: ".strtotime("2008-09-15T07:00:00Z");
      echo "<BR>".date("D, j M Y H:i:s T", strtotime("Mon, 15 Sep 2008 07:00:00 GMT"))
     * 
     */
    public function getPublicationDateHumanReadable() {
        return date("D, j M Y G:i:s T", $this->pubDate);
    }

    private function getMySQLDate($dateInt) {
        return date ("Y-m-d H:i:s", $dateInt);
    }
}
?>
