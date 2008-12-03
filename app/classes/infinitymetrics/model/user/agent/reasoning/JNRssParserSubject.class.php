<?php
/**
 * $Id: JNRssParserSubject.class.php 202 2008-11-10 12:01:40Z Marcello de Sales
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
require_once("infinitymetrics/model/user/agent/reasoning/RssToDatabaseObserver.class.php");

require_once("infinitymetrics/util/go4pattenrs/behavorial/observer/ObservableSubject.class.php");
require_once("infinitymetrics/util/javanet/rssparser/CollabnetRssChannel.class.php");

class JNRssParserSubject extends ObservableSubject {

    private $xmlReader;
    
    private $rssFeedUrl;

    private $rssInstance;
    
    public function __construct($rssFeedUrl, $rssInstance) {
        $this->rssFeedUrl = $rssFeedUrl;
        $this->rssInstance = $rssInstance;
        $this->xmlReader = new XMLReader();
    }

    public function parseRss() {

        if (!$this->hasObserver()) {
            throw new Exception("Please add observers before collecting Rss");
        }
        if (isset($this->rssInstance)) {
            $this->xmlReader->XML($this->rssInstance);
        } else {
            $this->xmlReader->open($this->rssFeedUrl);
        }
        $rss = new CollabnetRssChannel($this->rssFeedUrl);
        $numItems = 0;
        $currentTag = "";
        $currentValue = "";
        $reader = $this->xmlReader;
        while ($reader->read()) {
            $currentTag = $reader->localName;
            switch ($reader->nodeType) {
            case (XMLREADER::ELEMENT):
                if ($reader->localName == "title") {
                    $reader->read();
                    $rss->setTitle($reader->value);
                } else
                if ($reader->localName == "link") {
                    $reader->read();
                    $rss->setLink($this->xmlReader->value);
                } else
                if ($reader->localName == "description") {
                    $reader->read();
                    $rss->setDescription($reader->value);
                } else 
                if ($reader->localName == "item") {
                    $builder = $rss->newRssItemBuilder();
                    while ($reader->read()) {
                        $currentTag = $reader->localName;
                        if ($reader->nodeType == XMLREADER::ELEMENT) {
                            if ($reader->localName == "title") {
                                $reader->read();
                                if ($reader->value == "No Messages in Mailing List") {
                                    break 3;
                                }
                                $builder->title($reader->value);
                            } else
                            if ($reader->localName == "link") {
                                $reader->read();
                                $builder->link($reader->value);
                            } else
                            if ($reader->localName == "description") {
                                $reader->read();
                                $builder->description($reader->value);
                            } else
                            if ($reader->localName == "pubDate") {
                                $reader->read();
                                $builder->publicationDate($reader->value);
                            } else
                            if ($reader->localName == "author") {
                                $reader->read();
                                $builder->author($reader->value);
                            } else
                            if ($reader->localName == "guid") {
                                $reader->read();
                                $builder->guid($reader->value);
                            } else
                            if ($reader->localName == "creator") {
                                $reader->read();
                                $builder->creator($reader->value);
                            } else
                            if ($reader->localName == "date") {
                                $reader->read();
                                $builder->date($reader->value);
                                
                                $rssItem = $builder->build();
                                $rss->addItem($rssItem);
                                
                                $builder = $rss->newRssItemBuilder();
                            }
                        }
                    }
                }
            }
        }
        $this->xmlReader->close();
        $this->updateSubject($rss);        
    }
}
//$rssSource = "<?xml version=\"1.0\" encoding=\"UTF-8\"
//<rss xmlns:taxo=\"http://purl.org/rss/1.0/modules/taxonomy/\" xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\" xmlns:dc=\"http://purl.org/dc/elements/1.1/\" version=\"2.0\">
//  <channel>
//    <title>Message List in RSS Format for Project ppm-8, Mailing List process</title>
//    <link>https://ppm-8.dev.java.net/servlets/BrowseList?list=process</link>
//    <description>Message List in RSS Format for Project ppm-8, Mailing List process</description>
//    <item>
//      <title>No Messages in Mailing List</title>
//
//      <link>https://ppm-8.dev.java.net/servlets/BrowseList?list=process</link>
//      <description>No Messages in Mailing List process</description>
//      <guid>https://ppm-8.dev.java.net/servlets/BrowseList?list=process</guid>
//    </item>
//  </channel>
//</rss>";

//$rss = new JNRssParserSubject("https://ppm-8.dev.java.net/servlets/MailingListRSS?listName=process");
//$rss = new JNRssParserSubject($rssSource);
//require_once 'infinitymetrics/model/user/agent/reasoning/RssToDatabaseObserver.class.php';
//$rss->addObserver(new RssToDatabaseObserver());
//$rss->collectRss();
?>