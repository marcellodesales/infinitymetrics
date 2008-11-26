<?php
/**
 * $Id: RssItem.class.php 202 2008-09-13 21:31:40Z marcellosales $
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
require_once 'infinitymetrics/util/DateTimeUtil.class.php';
/**
 * Representation of the Item from the Rss feeds from CollabNet's Java.net implementation.
 *
 * @author Marcello de Sales <marcello.sales@gmail.com> Nov 15, 2008 10:34 PST
 * @version $Id$
 *
 * <item>
 *     <title>[Issue 5750] [other]  Hudson : java.net.MalformedURLException: Unknown protocol:</title>
 *     <link>https://glassfish.dev.java.net/servlets/ReadMsg?list=issues&amp;msgNo=32302</link>
 *    <description>[Issue 5750] [other]  Hudson : java.net.MalformedURLException: Unknown protocol:</description>
 *     <pubDate>Mon, 15 Sep 2008 07:00:00 GMT</pubDate>
 *     <author>mk111283@dev.java.net</author>
 *     <guid>https://glassfish.dev.java.net/servlets/ReadMsg?list=issues&amp;msgNo=32302</guid>
 *
 *     <dc:creator>mk111283@dev.java.net</dc:creator>
 *     <dc:date>2008-09-15T07:00:00Z</dc:date>
 *   </item>
 **/
class RssItem {
    const PROTOCOL = "https://";
    /**
     * @var string this is the definition of a domain.
     * TODO: this value should be created by the bootstrap as an environment variable.
     */
    const DOMAIN = "dev.java.net";
    /**
     * @var string the mailing list servlet to make one able to view the Rss item.
     */
    const LINK_MAILING_LIST_SERVLET = "/servlets/ReadMsg?list={_MAILING_LIST_ID_}&amp;msgNo={_EVENT_ID_}";
    /**
     * @var string the forum view servlet to view the item of a discussion forum. It must be  parsed
     * to get correct value for the forum ID and the message ID.
     */
    const LINK_FORUM_MESSAGE_SERVLET = "/servlets/ProjectForumMessageView?forumID={_FORUM_ID_}&messageID={_EVENT_ID_}";
    /**
     * @var string The title of the item.
     */
    private $title;
    /**
     * @var string the description of the item. If the same as the title, will be empty
     */
    private $description;
    /**
     * @var int https://glassfish.dev.java.net/servlets/ReadMsg?list=issues&amp;msgNo=32302
     * The link will only hold the msgNo as the primary object. It also applies
     * to the guid that has the same address as the link.
     */
    private $messageNumber;
    /**
     * @var string this is the complete date information Mon, 15 Sep 2008 07:00:00 GMT
     */
    private $pubDate;
    /**
     * @var string mk111283@dev.java.net Username. The username will be the only kept. The
     * output must be decorated on the get method, or have overloaded getters;
     */
    private $authorUsername;
    /**
     * @var string is the email address of the creator. It can be just like with the Java.net
     * domain mk111283@dev.java.net
     */
    private $creatorEmail;
    /**
     * @var string is the iso data format 2008-09-15T07:00:00Z
     */
    private $isoDate;
    /**
     * @var string if the username or email is not given, the real name is used instead. The
     * value must be then converted by the one used by the username.
     */
    private $realName;
    /**
     * Sets the RSS Item title
     * @param string $title is the title of the RSS
     */
    public function setTitle($title) {
        $this->title = $title;
    }
    /**
     * Sets the real name of the user. If it contains the real name, the method containsRealName
     * should be used to verify this fact instead of using null values for the username.
     * @param string $realName is the real name of the user.
     */
    public function setRealName($realName) {
        $this->realName = $realName;
    }
    /**
     * @return string the real name of th author of the post
     */
    public function getAuthorRealName() {
        return $this->realName;
    }
    /**
     * @return boolean verifies if the RssItem contains the real name of the user instead of the
     * username or email address.
     */
    public function containsRealName() {
        return isset($this->realName) && $this->realName != "" && !isset($this->authorUsername)
                     && !isset($this->creatorEmail);
    }
    /**
     *  Sets the description of the Rss item
     * @param string $description is the long description
     */
    public function setDescription($description) {
        $this->description = $description;
    }
    /**
     * Sets the message number. 
     * @param int $messageNumber it is the id of the message to be opened online.
     */
    public function setMessageNumber($messageNumber) {
        $this->messageNumber = $messageNumber;
    }
    /**
     * Sets the publication date
     * @param string $pubDate is the date on any format (ISO or Human-readable)
     */
    public function setPublicationDate($pubDate) {
        $this->pubDate = $pubDate;
    }
    /**
     * Sets the author java.net username
     * @param string $username the username of the user on Java.net
     */
    public function setAuthorUsername($username) {
        $this->authorUsername = $username;
    }
    /**
     * Sets the creator email address in case it is given
     * @param string $email is the java.net email address used on the RSS.
     */
    public function setCreatorEmail($email) {
        $this->creatorEmail = $email;
    }
    /**
     * Sets the ISO date for the item.
     * @param string $isoDate is the iso date for the item
     */
    public function setIsoDate($isoDate) {
        $this->isoDate = $isoDate;
    }
    /**
     * @return int this is the identification number of the message on collabNet.
     * https://glassfish.dev.java.net/servlets/ReadMsg?list=issues&amp;msgNo=32302
     * The link will only hold the msgNo as the primary object. It also applies
     * to the guid that has the same address as the link.
     */
    public function getMessageNumber() {
        return $this->messageNumber;
    }
    /**
     * @return string returns the title of the RSS item.
     */
    public function getTitle() {
        return $this->title;
    }
    /**
     * @return string the description of the Item. It can return the value as the title in case
     * the values were the same on the RssItem.
     */
    public function getDescription() {
        return ($this->description == "") ? $this->title : $this->description;
    }
    /**
     * Gets the link for the RssItem
     * @param <type> $projectName
     * @param <type> $channelCategory
     * @return <type>
     */
    public function getLink($projectName, $channelId, $isForum) {
        if ($isForum) {
            $forumUrl = str_replace("{_FORUM_ID_}", $channelId, self::LINK_FORUM_MESSAGE_SERVLET);
            $forumUrl = str_replace("{_EVENT_ID_}", $this->messageNumber, $forumUrl);
            return  self::PROTOCOL.$projectName.self::DOMAIN.$forumUrl;
        } else {
            $mailingUrl = str_replace("{_MAILING_LIST_ID_}", $channelId, self::LINK_FORUM_MESSAGE_SERVLET);
            $mailingUrl = str_replace("{_EVENT_ID_}", $this->messageNumber, $mailingUrl);
            return  self::PROTOCOL.$projectName.self::DOMAIN.$mailingUrl;
        }
    }
    /**
     * Gets the GUID from the RSS
     * @param string $projectName
     * @param string $channelCategory
     * @return string the string for the GUID
     */
    public function getGuid($projectName, $channelId, $isForum) {
        return $this->getLink($projectName, $channelId, $isForum);
    }
    /**
     * @return string the publication date.
     */
    public function getPublicationDate() {
        return $this->pubDate;
    }
    /**
     * @return string the publication date in the MySQL format
     */
    public function getPublicationDateForMySql() {
        return DateTimeUtil::getMySQLDate($this->pubDate);
    }
    /**
     * @return string the author Java.net username
     */
    public function getAuthorUsername() {
        return $this->authorUsername;
    }
    /**
     * @return string the domain of the creator in the current domain. Basically it uses the username
     * captured by the creator or username.
     */
    public function getAuthorEmail() {
        return $this->author . "@" . self::DOMAIN;
    }
    /**
     * @return string representation of an item in the string
     */
    public function __toString() {
        return $this->messageNumber . "\t" .
               $this->getTitle() . "\t" .
               $this->getDescription() . "\t" .
               $this->getPublicationDateForMySql() . "\t" .
               $this->authorUsername;
    }
}
?>
