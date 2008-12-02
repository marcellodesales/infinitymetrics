<?php
/**
 * $Id: RssToDatabaseObserver.class.php 202 2008-11-02 21:31:40Z marcellosales $
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
include_once 'infinitymetrics/util/go4pattenrs/behavorial/observer/Observable.class.php';
include_once 'infinitymetrics/util/go4pattenrs/behavorial/observer/Observer.class.php';
include_once 'infinitymetrics/orm/PersistentChannel.php';
include_once 'infinitymetrics/orm/PersistentEvent.php';

/**
 * Basic user class for the metrics workspace. User has username, password from
 * Java.net.
 *
 * @author Marcello de Sales <marcello.sales@gmail.com> Nov 15, 2008 10:34 PST
 * @version $Id$
 */
class RssToDatabaseObserver implements Observer {
    /**
     * @var ArrayList with the local-temporary cache with the found fullnames  
     */
    private $usernameFullnameCache;
    /**
     * Constructs a new RssToDatabaseObjserver.
     */
    public function __construct() {
        $this->usernameFullnameCache = ArrayList::makeNewInstance(20);
    }
    /**
     * @param string $channelID is the id of the channel of the RSS.
     * @return string the type of the Rss category.
     */
    private function getRssFeedCategory($channelID) {
        switch (strtolower($channelID)) {
            case "announces" : return "MAILING_LIST";
            case "cvs"       : return "COMMITS";
            case "dev"       : return "MAILING_LIST";
            case "users"     : return "MAILING_LIST";
            case "issues"    : return "ISSUE";
            case "commits"   : return "COMMIT";
            case "documents" : return "DOCUMENTATION";
            default : return "FORUM";
        }
    }
    /**
     * @param CollabnetRssChannel $collabnetRssChannel call-back method from the observer interface. When the
     * Agent finishes crawling the RSS entry, then the update call-back method is automatically
     * called. Then it's time to save on the persistence environmnet.
     *
     * It saves all the events from the CollabnetRssChannel into the database.
     */
    public function update($collabnetRssChannel) {
        $project = PersistentProjectPeer::retrieveByPK($collabnetRssChannel->getProjectName());
        if (!isset($project)) {
            //creates a new project in case it doesn't exist... It should never happen
            //but to make sure we have data on the server.
            $project = new PersistentProject();
            $project->setProjectJnName($collabnetRssChannel->getProjectName());
            $project->setSummary("Agent created this project for the RSS feeds...");
            $project->save();
        }
        $channel = PersistentChannelPeer::retrieveByPK($collabnetRssChannel->getChannelId(),
                                                       $collabnetRssChannel->getProjectName());
        if (!isset($channel)) {
            //verifies if the channel exists. If hasn't been saved before, just create a new
            //one for the project.
            $channel = new PersistentChannel();
            $channel->setChannelId($collabnetRssChannel->getChannelId());
            $channel->setProjectJnName($collabnetRssChannel->getProjectName());
            $channel->setCategory($this->getRssFeedCategory($collabnetRssChannel->getChannelId()));
            $channel->setTitle($collabnetRssChannel->getChannelTitle());
            $channel->save();
        }

        $rssChannel = $collabnetRssChannel->getRssChannel();
        $allEvents = $rssChannel->getItems();
        $crit = new Criteria();
        foreach($allEvents as $event) {
            try {
                $crit->add(PersistentEventPeer::EVENT_ID, $event->getMessageNumber());
                $crit->add(PersistentEventPeer::PROJECT_JN_NAME, $collabnetRssChannel->getProjectName());
                $crit->add(PersistentEventPeer::CHANNEL_ID, $channel->getChannelId());
                $crit->add(PersistentEventPeer::JN_USERNAME, $event->getAuthorUsername());
                $crit->add(PersistentEventPeer::DATE, DateTimeUtil::getMySQLDate($event->getPublicationDateForMySql()));
                $crit->setDbName(PersistentEventPeer::DATABASE_NAME);
                PersistentEventPeer::doInsert($crit);
                $crit->clear();

            } catch (Exception $e) {
                //When an entry exists already. This might happen when the agent tries to save
                //a list tha has created before. This is a safe-net just to make sure.
            }
        }
    }
}
?>