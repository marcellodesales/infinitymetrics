<?php

require_once 'infinitymetrics/model/institution/Instructor.class.php';
require_once 'infinitymetrics/model/workspace/Project.class.php';

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

    public function builder(Instructor $creator, $description, $title, array $projects) {
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

    public function getProjects() {
        return $this->projects;
    }

    public function setCreator(Instructor $creator) {
        $this->creator = $creator;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setProjects(array $projects) {
        $this->projects = $projects;
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

    public function addProject(Project $project) {
        $this->projects[] = $project;
    }
}

?>
