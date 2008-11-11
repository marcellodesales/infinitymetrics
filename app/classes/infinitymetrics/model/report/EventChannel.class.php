<?php

require_once('infinitymetrics/model/workspace/Project.class.php');
require_once('infinitymetrics/model/report/Event.class.php');
require_once('infinitymetrics/model/report/EventCategory.class.php');

/**
 * Description of EventChannel
 *
 * @author Andres Ardila
 */

class EventChannel
{
    private $description;
    private $name;
    private $project;
    private $events;
    private $category;
    
    public function __construct() {
        $this->events = array();
    }

    public function builder($description,
                            $name,
                            Project $project,
                            array $events,
                            EventCategory $category)
    {
        $this->description = $description;
        $this->name = $name;
        $this->project = $project;
        $this->events = $events;
        $this->category = $category;
    }

    public function getCategory() {
        return $this->category;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getName() {
        return $this->name;
    }

    public function getProject() {
        return $this->project;
    }

    public function getEvents() {
        return $this->events;
    }

    public function getEventsByDate(DateTime $startDate, DateTime $endDate) {
        $filteredEventList = array();
        
        foreach ($this->events as $event)
        {
            if ($event->getDateObject() >= $startDate &&
                $event->getDateObject() <= $endDate)
            {
                array_push($filteredEventList, $event);
            }
        }
        
        return $filteredEventList;
    }

    public function getEventsByUser(User $user) {
        $filteredEventList = array();

        foreach ($this->events as $event)
        {
            if ($event->getUser() == $user) {
                array_push($filteredEventList, $event);
            }
        }

        return $filteredEventList;
    }

    public function setCategory(EventCategory $category) {
        $this->category = $category;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setProject(Project $project) {
        $this->project = $project;
    }

    public function setEvents(array $events) {
        $this->events = $events;
    }

    public function addEvent(Event $event) {
        $this->events[] = $event;
    }

    public function hasNoEvents() {
        if ( count($this->events )) {
            return false;
        }
        else {
            return true;
        }
    }
}
?>
