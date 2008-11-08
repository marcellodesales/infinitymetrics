<?php
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

    public function getEventsByCategory(EventCategory $category) {
        $filteredList = array();

        foreach ($this->eventChannels as $channel)
        {
            if ($channel->getCategory() == $channel->getEventCategory()) {
                array_push($filteredList, $channel);
            }
        }

        return $filteredList;
    }

    public function getEventsByDate(DateTime $startDate, DateTime $endDate) {
        $filteredChannelList = array();

        //loop through all EventChannels
        foreach ($this->eventChannels as $channel)
        {
            //make a copy of current EventChannel being looped through
            $tempEventChannel = clone $channel;
            //create an array to hold Events that match the criterion
            $tempEventList = array();
            //loop through all Events in the current EventChannel
            foreach ($tempEventChannel->getEvents as $event)
            {
                //if the current Event matches the criterion, add it to the temp array
                if ($event->getDate() >= $startDate && $event->getDate() <= $endDate) {
                    array_push($tempEventList, $event);
                }
            }
            //set the Events of the clone to those that matched the creterion
            $tempEventChannel->setEvents($tempEventList);
            //if the resulting EventChannel contains Events, add it to the return array
            if ( ! $tempEventChannel->hasNoEvents() ) {
                array_push($filteredChannelList, $tempEventChannel);
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
