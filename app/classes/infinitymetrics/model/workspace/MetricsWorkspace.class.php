<?php

require_once 'infinitymetrics/orm/PersistentWorkspace.php';
require_once 'infinitymetrics/model/workspace/Project.class.php';

/**
 * Description of MetricsWorkspace
 *
 * @author Andres Ardila
 */

class MetricsWorkspace extends PersistentWorkspace
{
    private $projects;

    public function __construct() {
        parent::__construct();
        $this->projects = array();
    }

    public function builder($creatorUserId, $description, $title) {
        $this->setUserId($creatorUserId);
        $this->setDescription($description);
        $this->setTitle($title);
    }

    public function getProjects() {
        return $this->projects;
    }

    public function setProjects(array $projects) {
        $this->projects = $projects;
    }

    public function makeActive() {
        $this->setState('ACTIVE');
    }

    public function makeInactive() {
        $this->setState('INACTIVE');
    }

    public function makePaused() {
        $this->setState('PAUSED');
    }

    public function addProject(Project $project) {
        $this->projects[] = $project;
    }
}

?>
