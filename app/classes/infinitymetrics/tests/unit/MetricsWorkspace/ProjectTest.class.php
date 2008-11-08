<?php

require_once 'PHPUnit/Framework.php';
require_once 'infinitymetrics/model/workspace/Project.class.php';
require_once 'infinitymetrics/model/institution/Student.class.php';


/**
 * Description of ProjectTestclass
 *
 * @author Marilyne Mendolla and Andres Ardila
 */

class ProjectTest extends PHPUnit_Framework_TestCase {

    protected $project;

    public function setUp() {
        $this->project = new Project();
    }

    public function testProjectBuilder() {
        $studentName = "sName";
        $leader = new Student($studentName);

        $name = "pName";
        $summary = "pSummary";
        $this->project->builder($name, $leader, $summary);

        $this->assertEquals($leader, $this->project->getLeader(), "Leader not equal");
        $this->assertEquals($name, $this->project->getName(), "Project name is incorrect");
        $this->assertEquals($summary, $this->project->getSummary(), "Summary is incorrect");
    }

    public function testGetsSets() {
        unset($leader);
        $leader = new Student("John");
        $this->project->setLeader($leader);
        $this->assertEquals($leader, $this->project->getLeader(), "Leader not equal");

        $name = "PPM-8";
        $this->project->setName($name);
        $this->assertEquals($name, $this->project->getName(), "Project name is incorrect");

        $summary = "Group 8 - Infinity Metrics";
        $this->project->setSummary($summary);
        $this->assertEquals($summary, $this->project->getSummary(), "Summary is incorrect");
    }
}
?>
