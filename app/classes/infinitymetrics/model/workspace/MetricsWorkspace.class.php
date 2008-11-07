<?php
/**
 * Description of MetricsWorkspace
 *
 * @author Andres Ardila
 */

class MetricsWorkspace
{
    private $creator;
    private $description;
    private $title;
    private $state;
    private $projects;

    public function __construct() {
        $this->state = 'NEW';
        $this->projects = array();
    }

    public function builder(User $creator, $description, $title, array $projects) {
        $this->description = $description;
        $this->title = $title;
        $this->creator = $creator;
        $this->projects = $projects;
    }

    public function getCreator() {
        return $this->creator;
    }

    public function getDescription() {
        return $this->description;
    }
    
    public function getTitle() {
        return $this->title;
    }

    public function getState() { 
        return $this->state;
    }

    public function setCreator(User $creator) {
        $this->creator = $creator;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function makeActive() {
        $this->state = 'ACTIVE';
    }

    public function makeInactive() {
        $this->state = 'INACTIVE';
    }

    public function makePaused() {
        $this->state = 'PAUSED';
    }

    public function addProject(Project $p) {
        $this->projects[] = $p;
    }
}

?>
