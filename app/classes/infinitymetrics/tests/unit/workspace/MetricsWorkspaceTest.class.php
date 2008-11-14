<?php

require_once 'PHPUnit/Framework.php';
require_once 'infinitymetrics/model/institution/Instructor.class.php';
require_once 'infinitymetrics/model/workspace/MetricsWorkspace.class.php';
require_once 'infinitymetrics/model/workspace/Project.class.php';
/**
 * Description of MetricsWorkspaceTest
 *
 * @author Marilyne Mendolla and Andres Ardila
 */

class MetricsWorkspaceTest extends PHPUnit_Framework_TestCase {

    protected $ws;

    public function setUp() {
        $this->ws = new MetricsWorkspace();
    }
    public function testConstructor() {
        $this->assertEquals(true, is_array($this->ws->getProjects()), "Projects is not an array");
        $this->assertEquals(0, count($this->ws->getProjects()), "Initialized projects array is not empty");
        $this->assertEquals("NEW", $this->ws->getState(), "Incorrect state for constructor");
    }

    public function testBuilder() {
        //$instructorName = "uName";
        //$instructor = new Instructor($instructorName);

        $project = new Project();
        $name = "pName";
        $project->setName($name);
        $summary = "pSummary";
        $project->setSummary($summary);
        $projects = array($project);
        $this->ws->addProject($project);

        $description = "WS Descr";
        $this->ws->setDescription($description);
        $title = "WS Title";
        $this->ws->setTitle($title);
        //$this->ws->builder($instructor, $description, $title, $projects);

        //$this->assertEquals($instructorName, $this->ws->getCreator()->getName(), "Name incorrect");
        $this->assertEquals($description, $this->ws->getDescription(), "Description incorrect");
        $this->assertEquals($title, $this->ws->getTitle(), "Title incorrect");
        $this->assertEquals($projects, $this->ws->getProjects(), "Projects are not equal");
    }

    public function testGetsSets() {
        unset($instructor);
        //$instructor = new Instructor("John");
        //$this->ws->setCreator($instructor);
        //$this->assertEquals($instructor, $this->ws->getCreator(), "Creator not equal");

        $description = "Metrics Tracker in PHP";
        $this->ws->setDescription($description);
        $this->assertEquals($description, $this->ws->getDescription(), "Description name is incorrect");

        $title = "Fall 2008";
        $this->ws->setTitle($title);
        $this->assertEquals($title, $this->ws->getTitle(), "Title is incorrect");

        $this->ws->makeActive();
        $this->assertEquals("ACTIVE", $this->ws->getState(), "Active state is incorrect");

        $this->ws->makeInactive();
        $this->assertEquals("INACTIVE", $this->ws->getState(), "Inactive state is incorrect");

        $this->ws->makePaused();
        $this->assertEquals("PAUSED", $this->ws->getState(), "Paused state is incorrect");

        unset($projects);
        $projects = array();

        for ($i = 0; $i < 10; $i++)
        {
            $this->ws->addProject(new Project());
        }

        $this->assertEquals(10, count($this->ws->getProjects()), "Add function incorrect");
    }
}

?>
