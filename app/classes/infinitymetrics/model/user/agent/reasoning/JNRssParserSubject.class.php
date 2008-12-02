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
    
    public function __construct($rssFeedUrl) {
        $this->rssFeedUrl = $rssFeedUrl;
        $this->xmlReader = new XMLReader();
    }

    public function collectRss() {

        if (!$this->hasObserver()) {
            throw new Exception("Please add observers before collecting Rss");
        }

        $this->xmlReader->open($this->rssFeedUrl);
        
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
        $this->updateSubject($rss);
        $this->xmlReader->close();
    }
}
?>