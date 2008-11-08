<?php

require_once ('infinitymetrics/model/workspace/Project.class.php');
require_once('infinitymetrics/model/report/Event.class.php');
require_once('infinitymetrics/model/report/EventCategory.class.php');

/**
 * Description of EventChannelclass
 *
 * @author Andres Ardila
 */

class EventChannel
{
    private $category;
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
                            Event $events,
                            EventCategory $category)
    {
        $this->description = $description;
        $this->name = $name;
        $this->project = $project;
        $this->events = $events;
    }

    public function getCategory() {
        return $this->category->getEventCategory();
    }

    public function getDescription() {
        return $this->category;
    }

    public function getName() {
        return $this->category;
    }

    public function getProject() {
        return $this->category;
    }

    public function getEvents() {
        return $this->events;
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
        $this->project = $events;
    }

    public function addEvent(Event $e) {
        $this->events[] = $e;
    }

    public function hasNoEvents() {
        if ( count($this->events )) {
            return true;
        }
        else {
            return false;
        }
    }
}
?>
