<?php
require_once("../../../util/go4/observer/ObservableSubject.class.php");
require_once("RssToDatabaseObserver.class.php");
require_once("RssToScreenObserver.class.php");

class JavanetRssAgentReader extends ObservableSubject {

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

        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;

        $this->xmlReader->open($this->rssFeedUrl);
        $val = "";
        while ($this->xmlReader->read()) {
            #echo $this->xmlReader->name;
            $val = $this->xmlReader->name;
            
            if ($this->xmlReader->hasValue) {
                #echo ": " . $this->xmlReader->value;
            }
            #echo "<BR>";
        }
       $mtime = microtime();
       $mtime = explode(" ",$mtime);
       $mtime = $mtime[1] + $mtime[0];
       $endtime = $mtime;
       $totaltime = ($endtime - $starttime);
       echo "This page was created in ".$totaltime." seconds";
   
        $this->updateSubject("XML parsed from rss..." . $this->rssFeedUrl);
        $this->xmlReader->close();
    }
}

$rssToDbObserver = new RssToDatabaseObserver();
$rssToScreen = new RssToScreenObserver();

$jRssParser = new JavanetRssAgentReader("https://ppm.dev.java.net/servlets/MailingListRSS?listName=commits");

$jRssParser->addObserver($rssToDbObserver);
$jRssParser->addObserver($rssToScreen);

$jRssParser->collectRss();

?>