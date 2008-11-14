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
        $name = "pName";
        $this->project->setName($name);
        $summary = "pSummary";
        $this->project->setSummary($summary);
        //$leader = new Student();
        //$students = array(new Student(), new Student(), new Student());
        //$this->project->builder($name, $summary, $leader, $students);

        $this->assertEquals($name, $this->project->getName(), "Project name is incorrect");
        $this->assertEquals($summary, $this->project->getSummary(), "Summary is incorrect");
        //$this->assertEquals($leader, $this->project->getLeader(), "Leader not equal");
        //$this->assertEquals($students, $this->project->getStudents(), "Leader not equal");
    }

    public function testGetsSets() {
        //$leader = new Student("John");
        //$this->project->setLeader($leader);
        //$this->assertEquals($leader, $this->project->getLeader(), "Leader not equal");

        $name = "PPM-8";
        $this->project->setName($name);
        $this->assertEquals($name, $this->project->getName(), "Project name is incorrect");

        $summary = "Group 8 - Infinity Metrics";
        $this->project->setSummary($summary);
        $this->assertEquals($summary, $this->project->getSummary(), "Summary is incorrect");

        $students = array(new Student(), new Student(), new Student());
        $this->project->setStudents($students);
        $this->assertEquals($students, $this->project->getStudents());
    }
}
?>
