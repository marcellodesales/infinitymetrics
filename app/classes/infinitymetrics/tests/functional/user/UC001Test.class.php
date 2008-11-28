<?php
/**
 * $Id: UC001Test.class.php 202 2008-11-10 12:01:40Z marcellosales $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITYs, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the Berkeley Software Distribution (BSD).
 * For more information please see <http://ppm-8.dev.java.net>.
 */

require_once 'propel/Propel.php';
Propel::init('infinitymetrics/orm/config/om-conf.php');

require_once 'PHPUnit/Framework.php';
require_once 'infinitymetrics/controller/UserManagementController.class.php';
require_once 'infinitymetrics/model/user/UserTypeEnum.class.php';
/**
 * Tests for the Use Case UC001 - UC001: A Student registers into the system.
 *
 * Another assumption for the system tests for the user is tha the authentication will happen in another moment of
 * the execution. In this way, the PRE-CONDITION for these scenarios is tha the user has been authenticated over
 * Java.net already.
 *
 * @author Marcello de Sales <marcello.sales@gmail.com>
 */
class UC001Test extends PHPUnit_Framework_TestCase {

    private $institution;
    private $project;
    private $userTypeEnum;

    public function  __construct() {
        $this->userTypeEnum = UserTypeEnum::getInstance();
    }

    private function cleanUpAll() {
        PersistentUserXProjectPeer::doDeleteAll();
        PersistentUserPeer::doDeleteAll();
        PersistentInstitutionPeer::doDeleteAll();
        PersistentProjectPeer::doDeleteAll();
    }

    /**
     * Setting up is run ALWAYS BEFORE the execution of a test method.
     */
    protected function setUp() {
        parent::setUp();
        $this->cleanUpAll();
        
        $this->institution = new Institution();
        $this->institution->setName('San Francisco State University');
        $this->institution->setAbbreviation("SFSU");
        $this->institution->setCity('San Francisco');
        $this->institution->setStateProvince('CA');
        $this->institution->setCountry('USA');
        $this->institution->save();

        $this->project = new PersistentProject();
        $this->project->setProjectJnName("ppm-8");
        $this->project->setSummary("Infinity Metrics");
        $this->project->save();
    }
    /**
     * The test of a successful registration when a student doesn't exist and
     * enters the correct values.
     */
    public function testSuccessfulStudentRegistration() {
        try {
            //Saving the student leader
            $createdStudent = UserManagementController::registerStudent(
                                    "username2", "password", "email@gmail.com", "firstNameLeader",
                                    "lastNameLeader", "909663916", $this->project->getProjectJnName(),
                                    $this->institution->getAbbreviation(), true);
            $this->assertNotNull($createdStudent, "The registered student is null");
            $this->assertEquals($this->userTypeEnum->STUDENT, $createdStudent->getType(), "The registered user is not
                                                                                               an instance of Student");

            $studentInstitution = PersistentUserXInstitutionPeer::retrieveByPk($createdStudent->getUserId(),
                                                                               $this->institution->getInstitutionId());
            $this->assertNotNull($studentInstitution, "The user x institution relation was not created for the student.");
            $this->assertEquals("909663916", $studentInstitution->getIdentification(), "The student school 
                                                                                         identification is incorrect");
            $this->assertEquals($createdStudent->getUserId(), $studentInstitution->getUserId(), "The user id is 
                                                                   incorrect on user x institution for the student");
            $this->assertEquals($this->institution->getInstitutionId(), $studentInstitution->getInstitutionId(), 
                                              "The institution id is incorrect on user x institution for the student");

            $stXProjec = PersistentUserXProjectPeer::retrieveByPK($createdStudent->getJnUsername(),
                                                                                   $this->project->getProjectJnName());
            $this->assertNotNull($stXProjec, "The relationship between student and project was not created");
            $this->assertEquals($this->project->getProjectJnName(), $stXProjec->getProjectJnName(), "The project name 
                                                                                      is incorrect on user x project");
            $this->assertEquals($createdStudent->getJnUsername(), $stXProjec->getJnUsername(), "The java.net 
                                                                 username is incorrect on user x project for student");
            $this->assertTrue($stXProjec->getIsOwner() == 1, "The student is a leader, and therefore, a project owner.");

        } catch (InfinityMetricsException $ime){
            $this->fail("The successful login scenario failed: " . $ime);
        }

        try {
            //Saving a regular student 
            $createdStudent = UserManagementController::registerStudent(
                                    "username", "password", "email2@gmail.com", "firstNameNOTLeader",
                                    "lastName", "888888888", $this->project->getProjectJnName(),
                                    $this->institution->getAbbreviation(), false);
            $this->assertNotNull($createdStudent, "The registered student is null");
            $this->assertEquals($this->userTypeEnum->STUDENT, $createdStudent->getType(), "The registered user is not
                                                                                               an instance of Student");

            $studentInstitution = PersistentUserXInstitutionPeer::retrieveByPk($createdStudent->getUserId(),
                                                                               $this->institution->getInstitutionId());
            $this->assertNotNull($studentInstitution, "The student x institution relation was not created.");
            $this->assertEquals("888888888", $studentInstitution->getIdentification(), "The student school
                                                                                         identification is incorrect");
            $this->assertEquals($createdStudent->getUserId(), $studentInstitution->getUserId(), "The user id is
                                                                                     incorrect on user x institution");
            $this->assertEquals($this->institution->getInstitutionId(), $studentInstitution->getInstitutionId(),
                                                               "The institution id is incorrect on user x institution");

            $stXProjec = PersistentUserXProjectPeer::retrieveByPK($createdStudent->getJnUsername(),
                                                                                   $this->project->getProjectJnName());
            $this->assertNotNull($stXProjec, "The relationship between student and project was not created");
            $this->assertEquals($this->project->getProjectJnName(), $stXProjec->getProjectJnName(), "The project name
                                                                                      is incorrect on user x project");
            $this->assertEquals($createdStudent->getJnUsername(), $stXProjec->getJnUsername(), "The user id is incorrect
                                                                                                   on user x project");
            $this->assertTrue($stXProjec->getIsOwner() == 0, "The student is NOT a leader, and therefore, NOT a project
                                                                                                              owner.");

        } catch (InfinityMetricsException $ime){
            $this->fail("The successful login scenario failed: " . $ime);
        }
    }
    /**
     * The test of an exceptional registration where the student doesn't enter
     * some of the field values.
     */
    public function testMissingFieldsStudentRegistration() {
        try {
            $missingNames = UserManagementController::registerStudent(
                                    "username2", "password", "email@gmail.com", "",
                                    "", "909663916", $this->project->getProjectJnName(),
                                    $this->institution->getAbbreviation(), true);

            $this->fail("The exceptional login scenario failed with missing name");
        } catch (InfinityMetricsException $ime) {
            //$error["fieldName"] = "error message"
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["firstName"]);
            $this->assertNotNull($errorFields["lastName"]);
        }

        try {
            $missingEmailandOthers = UserManagementController::registerStudent(
                                    "", "", "", "ssss",
                                    "sssss", "909663916", $this->project->getProjectJnName(),
                                    $this->institution->getAbbreviation(), true);

            $this->fail("The exceptional login scenario failed with missing
                            username, password email");
        } catch (InfinityMetricsException $ime) {
            //$error["fieldName"] = "error message"
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["username"]);
            $this->assertNotNull($errorFields["password"]);
            $this->assertNotNull($errorFields["email"]);
        }

        try {
            $createdStudent = UserManagementController::registerStudent(
                                    "username2", "password", "email@gmail.com", "333",
                                    "34", "909663916", "",
                                    $this->institution->getAbbreviation(), 0);

            $this->fail("The exceptional registration scenario failed for
                    missing project name, team leader and other fields");
        } catch (InfinityMetricsException $ime) {
            //$error["fieldName"] = "error message"
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["projectName"]);
        }
    }
    /**
     * The test an exceptional registration where the student enteres an
     * existing username.
     */
    public function testRegisterExistingStudentLeaderRegistration() {
        try {
            //Saving the student leader
            $createdStudent = UserManagementController::registerStudent(
                                    "username2", "password", "email@gmail.com", "firstNameLeader",
                                    "lastNameLeader", "909663916", $this->project->getProjectJnName(),
                                    $this->institution->getAbbreviation(), true);

            //Saving the student leader once again... this time it throws the exception
            $createdStudent = UserManagementController::registerStudent(
                                    "username2", "password", "email@gmail.com", "firstNameLeader",
                                    "lastNameLeader", "909663916", $this->project->getProjectJnName(),
                                    $this->institution->getAbbreviation(), true);

            $this->fail("The exceptional registration failed for existing student");
        } catch (Exception $ime) {
            $this->assertNotNull($ime);
        }
    }

    protected function tearDown() {
        $this->cleanUpAll();
    }
}
?>