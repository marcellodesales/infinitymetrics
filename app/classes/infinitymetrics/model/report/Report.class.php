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

    public function getEventChannels() {
        return $this->eventChannels;
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
