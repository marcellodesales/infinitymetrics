<?php

class ProjectHistoryToDatabase {

    private $events;
    private $projectName;
    private $channelId;
    private $channelDescription;

    public function ProjectHistoryToDatabase($projectName, $channelName, $channelDescription, $events) {
        $this->events = $events;
        $this->channelId = $channelName;
        $this->projectName = $projectName;
        $this->channelDescription = $channelDescription;
        $this->saveEventsToDatabase();
    }

    /**
     * @param string $channelID is the id of the channel of the RSS.
     * @return string the type of the Rss category.
     */
    private function getRssFeedCategory($channelID) {
        switch (strtolower($channelID)) {
            case "announce" : return "MAILING_LIST";
            case "cvs"       : return "COMMITS";
            case "dev"       : return "MAILING_LIST";
            case "users"     : return "MAILING_LIST";
            case "issues"    : return "ISSUE";
            case "commits"   : return "COMMIT";
            case "documents" : return "DOCUMENTATION";
            default : return (int)$channelID ? "FORUM" : "MAILING_LIST";
        }
    }

    private function saveEventsToDatabase() {
        $channelId = $this->channelId;
        if (!isset($channelId) || $channelId == null || $channelId == "") {
            //if the agent givens incorrect values, then just don't do anything...
            return;
        }
        if (count($this->events) == 0) {
            return;
        }
        $project = PersistentProjectPeer::retrieveByPK($this->projectName);
        if (!isset($project)) {
            //TODO: REMOVE THIS ONCE THE PROJECTS ARE BEIN COLLECTED AUTOMATICALLY
            //creates a new project in case it doesn't exist... It should never happen
            //but to make sure we have data on the server.
            $project = new PersistentProject();
            $project->setProjectJnName($collabnetRssChannel->getProjectName());
            $project->setSummary("Agent created for the event " . $channelId);
            $project->save();
        }
        $channel = PersistentChannelPeer::retrieveByPK($this->channelId, $this->projectName);
        if (!isset($channel)) {
            //verifies if the channel exists. If hasn't been saved before, just create a new
            //one for the project.
            $channel = new PersistentChannel();
            $channel->setChannelId($this->channelId);
            $channel->setProjectJnName($this->projectName);
            $channel->setCategory($this->getRssFeedCategory($this->channelId));
            $channel->setTitle(str_replace("'", " ", substr($this->channelDescription, 0,
                                   strlen($this->channelDescription) > 255 ? 254 : strlen($this->channelDescription))));
            $channel->save();
        }

        $crit = new Criteria();
        foreach($this->events as $authorName => $events) {
            echo "\nStoring ".count($events)." events for ".$channelId. " on project ".$this->projectName;
            foreach($events as $event) {
                try {
                    $crit->add(PersistentEventPeer::EVENT_ID, $event["id"]);
                    $crit->add(PersistentEventPeer::PROJECT_JN_NAME, $this->projectName);
                    $crit->add(PersistentEventPeer::CHANNEL_ID, $channel->getChannelId());
                    if (strpos($authorName, " ")) {
                        $cache = FullnameJNUsernameInMemoryCache::getInstance();
                        $possibleUsernameMatch = $cache->getUsernameFromFullname($authorName);
                        if (isset($possibleUsernameMatch)) {
                            $crit->add(PersistentEventPeer::JN_USERNAME, $possibleUsernameMatch);
                        } else {
                            $crit->add(PersistentEventPeer::JN_USERNAME, $authorName);
                        }
                    } else {
                        $crit->add(PersistentEventPeer::JN_USERNAME, $authorName);
                    }
                    $crit->add(PersistentEventPeer::DATE, $event["date"]);
                    $crit->setDbName(PersistentEventPeer::DATABASE_NAME);
                    PersistentEventPeer::doInsert($crit);
                    $crit->clear();

                } catch (Exception $e) {
                    //When an entry exists already. This might happen when the agent tries to save
                    //a list tha has created before. This is a safe-net just to make sure.
                    echo $e;
                }
            }
        }
    }
}
?>
