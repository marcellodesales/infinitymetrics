<?php

require_once 'infinitymetrics/model/workspace/Project.class.php';
require_once('infinitymetrics/model/report/EventChannel.class.php');

/**
 * Description of Report
 *
 * @author Andres Ardila
 */

class Report
{

    private $project;
    private $eventChannels;

    public function __construct() {
        $this->eventChannels = array();
    }

    public function builder(Project $project, array $eventChannels) {
        $this->project = $project;
        $this->eventChannels = $eventChannels;
    }

    public function getProject() {
        return $this->project;
    }

    public function getEventList() {
        return $this->eventChannels;
    }

    public function filterByCategory(EventCategory $category) {
        $filteredList = array();

        foreach ($this->eventChannels as $channel)
        {
            if ($channel->getCategory() == $category) {
                array_push($filteredList, $channel);
            }
        }

        return $filteredList;
    }

    public function filterByDate(DateTime $startDate, DateTime $endDate) {
        $filteredChannelList = array();

        //loop through all EventChannels
        foreach ($this->eventChannels as $channel)
        {
            $filteredEventList = $channel->getEventsByDate($startDate, $endDate);
            if (count($filteredEventList)) {
                    array_push($filteredChannelList, $filteredEventList);
            }
        }

        return $filteredChannelList;
    }

    public function setProject(Project $project) {
        $this->project = $project;
    }

    public function setEvenChannels(array $eventChannels) {
        $this->eventChannels = $eventChannels;
    }

    public function addEventChannel(EventChannel $eventChannel) {
        $this->eventChannels[] = $eventChannel;
    }
}
?>
