<?php
/**
 * Description of Project
 *
 * @author Andres Ardila
 */

class Project
{
    private $name;
    private $summary;
    private $leader;
    private $students;
    

    public function __construct() {
        $this->students = array();
    }

    public function builder($name, $summary, Student $leader, array $students) {
        $this->name = $name;
        $this->summary = $summary;
        $this->leader = $leader;
        $this->students = $students;
        
    }

    public function getName() {
        return $this->name;
    }

    public function getSummary() {
        return $this->summary;
    }

    public function getLeader() {
        return $this->leader;
    }

    public function getStudents() {
        return $this->students;
    }
    
    public function setName($name) {
        $this->name = $name;
    }

    public function setSummary($summary) {
        $this->summary = $summary;
    }

    public function setLeader(Student $leader) {
        $this->leader = $leader;
    }

    public function setStudents(array $students) {
        $this->students = $students;
    }
}
?>
