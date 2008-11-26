<?php
/**
 * $Id: Observable.class.php 144 2008-09-24 02:59:40Z marcellosales $
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
 
require_once('infinitymetrics/util/javanet/rssparser/RssItem.class.php');
require_once('infinitymetrics/util/go4pattenrs/creational/AbstractBuilder.class.php');

/**
 * The RssItemBuilder is a builder for an RSSItem builder based on RSS 2.0
 * definition. The build method builds the instance of the RssItem class.
 * 
 * @author Marcello de Sales <marcello.sales@gmail.com>
 * @version $Id$
 * @see net.java.dev.ppm-8.util.go4.AbstractBuilder
 */
class RssItemBuilder extends AbstractBuilder {

    /**
     * It's the internal instance of RssItem. 
     * @var RssItem instance
     */
    private $rssItem = NULL;
    /**
     * The REQUIRED title of the rss item. 
     * @var string
     */    
    private $title;
    /**
     * The REQUIRED link of the rss item. 
     * @var string
     */
    private $link;
    /**
     * The OPTIONAL description of the rss. 
     * @var string
     */
    private $description;
    /**
     * The publication date in the ISO format. 
     * @var date
     */
    private $pubDate;
    /**
     * It's the internal instance of RssItem. 
     * @var RssItem instance
     */
    private $author;
    /**
     * It's the internal instance of RssItem. 
     * @var RssItem instance
     */
    private $guid;
    /**
     * It's the internal instance of RssItem. 
     * @var RssItem instance
     */
    private $creator;
    /**
     * It's the internal instance of RssItem. 
     * @var RssItem instance
     */
    private $date;
    /**
     * Constructs a new builder and sets up {@link $rssItem}
     */
    public function __construct() {
        $this->rssItem = new RssItem();
    }
    /**
     * Sets the value of the title tag
     * @param string $title
     * @return the instance of this builder.
     */
    public function title($title) {
        $this->title = $title;
        return $this;
    }
    /**
     * Sets the value of the link tag
     * @param string $link
     * @return the instance of this builder.
     */
    public function link($link) {
        $this->link = $link;
        return $this;
    }
    /**
     * Sets the value of the description tag
     * @param string $desc
     * @return the instance of this builder.
     */
    public function description($desc) {
        $this->description = $desc;
        return $this;
    }
    /**
     * Sets the value of the publicationDate tag
     * @param string $pubDate
     * @return the instance of this builder.
     */
    public function publicationDate($pubDate) {
        $this->pubDate = $pubDate;
        return $this;
    }
    /**
     * Sets the value of the author tag
     * @param string $link
     * @return the instance of this builder.
     */
    public function author($author) {
        $this->author = $author;
        return $this;
    }
    /**
     * Sets the value of the guid tag
     * @param string $guid
     * @return the instance of this builder.
     */
    public function guid($guid) {
        $this->guid = $guid;
        return $this;
    }
    /**
     * Sets the value of the creator tag
     * @param string $creator
     * @return the instance of this builder.
     */
    public function creator($creator) {
        $this->creator = $creator;
        return $this;
    }
    /**
     * Sets the value of the date tag
     * @param string $date
     * @return the instance of this builder.
     */
    public function date($date) {
        $this->date = $date;
        return $this;
    }

    /**
     * Builds an instance of the RssItem based on the given values for the
     * builder.
     * @return an instance for RssItem.
     * @throws Exception - if the following properties were not given: title,
     * link, creator and publicationDate.
     */   
    public function build() {
        if ($this->title == NULL || $this->title == "") {
            throw new Exception("Title not be provided to the rss item");
        } 
        $this->rssItem->setTitle($this->title);
        $this->rssItem->setDescription($this->title == $this->description ? "" : $this->description);
        if ($this->link == NULL || $this->link == "") {
            throw new Exception("Link not provided to the rss item");
        }
        $this->rssItem->setMessageNumber(substr($this->link, strrpos($this->link, "=")+1, strlen($this->link)-strrpos($this->link, "=")+1));
        if ($this->creator == NULL || $this->creator == "") {
            throw new Exception("Creator not provided to the rss item");
        }
        $onlyUsername = !strpos(" ",$this->author) ? true : false;
        $containsEmail = strpos("@",$this->author) ? true : false;
        if ($onlyUsername && !$containsEmail) {
            //If the real name is given, add the flag to the user.
            $this->rssItem->setAuthorUsername($this->author);
        } else
        if ($containsEmail) {
            //although creator and creator are always the same, we will be saving
            //the email address... 
            $this->rssItem->setCreatorEmail($this->creator);
            $this->rssItem->setAuthorUsername($this->extractUsernameFromEmail($this->author));
        } else
        if (!$onlyUsername) {
            $this->rssItem->setRealName($this->author);
        }

        if ($this->pubDate == NULL || $this->pubDate == "") {
            throw new Exception("Publication date not provided to the rss item");
        }
        $this->rssItem->setPublicationDate($this->pubDate);
        $this->rssItem->setIsoDate($this->date);
        return $this->rssItem;
    }
    
    /**
     * Returns extracted username from a given email address
     * @param string $emailAddress is a given email address. The string must
     * contain the character @.
     * @return string.
     */
    private function extractUsernameFromEmail($emailAddress) {
        return substr($emailAddress, 0, strpos($emailAddress, "@"));
    }
}

//      $title = "[Issue 5750] [other]  Hudson : java.net.MalformedURLException: Unknown protocol:";
//      $link = "https://glassfish.dev.java.net/servlets/ReadMsg?list=issues&amp;msgNo=32302";
//      $description = "[Issue 5750] [other]  Hudson : java.net.MalformedURLException: Unknown protocol:";
//      $pubDate = "Mon, 15 Sep 2008 07:00:00 GMT";
//      $author = "mk111283@dev.java.net";
//      $guid = "https://glassfish.dev.java.net/servlets/ReadMsg?list=issues&amp;msgNo=32302";
//      $creator = "mk111283@dev.java.net";
//      $isoDate = "2008-09-15T07:00:00Z";
//
//$builder = new RssItemBuilder();
//$builder->title($title)->link($link)->description($description)->publicationDate($pubDate)->author($author)->guid($guid)->creator($creator)->date($isoDate);
//$rssItem = $builder->build();
//echo $rssItem;
//
//$pos = (strrpos("mar", "x"));
//$s = $pos == NULL;
//$bb = $pos == "";
//echo "<BR>--->$s ---- $bb";
?>
