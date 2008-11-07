<?php
require_once 'infinitymetrics/model/institution/Institution.class.php';
require_once 'infinitymetrics/model/user/User.class.php';
/*
 * The Student class for the metrics workspace.
 *
 * @author Gurdeep Singh <gurdeepsingh03@gmail.com>
 *
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Student extends User {
     /**
     * This is the institution reference.
     * @var Institution instance of the institution class.
     */
    private $institution;
    private $studentId;

    public function  __construct($firstName) {
        parent::__construct($firstName);
    }

    public function setInstitution(Institution $institution) {
        $this->institution = $institution;
    }
/* * This is to get Student Id
 *
 */
    public function getId($studentId){
        return $this->studentId = $studentId ;
    }

    public function save() {
        //$connetion = mysql
        $newUser = "INSERT INTO ";

        $newStudent =  "INSERT INTO STUDENT VALUE ()";
        //$newUser =
    }
}
?>
