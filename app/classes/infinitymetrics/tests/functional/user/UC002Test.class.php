<?php
/**
 * $Id: UC002est.class.php 202 2008-11-10 12:01:40Z Gurdeep Singh $
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
 * Tests for the Use Case UC002 : A Instructor registers into the system.
 *
 * Another assumption for the system tests for the user is tha the authentication will happen in another moment of
 * the execution. In this way, the PRE-CONDITION for these scenarios is tha the user has been authenticated over
 * Java.net already.
 *
 * @author Gurdeep singh <gurdeepsingh03@gmail.com>
 */
class UC002Test extends PHPUnit_Framework_TestCase {

    const USERNAME = "Gurdeep";
    const PASSWORD = "password";
    const EMAIL = "gurdeepsingh03@gmail.com";
    const FIRSTNAME = "Gurdeep";
    const LASTNAME = "Singh";
    const SCHOOL_IDENTIFICATION = "TEACHER101";
    
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
        $this->institution->setName('Punjabi University');
        $this->institution->setAbbreviation("PU");
        $this->institution->setCity('Patiala');
        $this->institution->setStateProvince('PB');
        $this->institution->setCountry('INDIA');
        $this->institution->save();

        $this->project = new PersistentProject();
        $this->project->setProjectJnName("ppm111");
        $this->project->setSummary("Project paticipation Metrics");
        $this->project->save();
    }
    /**
     * The test of a successful registration when a instructor doesn't exist and
     * enters the correct values.
     */
    public function testSuccessfulInstructorRegistration() {
        try {
            //Saving the Instructor
            $createdInstructor = UserManagementController::registerInstructor(self::USERNAME, self::PASSWORD, 
                self::EMAIL, self::FIRSTNAME, self::LASTNAME, $this->project->getProjectJnName(), true,
                $this->institution->getAbbreviation(), self::SCHOOL_IDENTIFICATION);

            $this->assertNotNull($createdInstructor, "The registered student is null");
            $this->assertEquals($this->userTypeEnum->INSTRUCTOR, $createdInstructor->getType(), "The registered user is
                                                                                       not an instance of Instructor");
            $instructorInstitution = PersistentUserXInstitutionPeer::retrieveByPk($createdInstructor->getUserId(),
                                                                               $this->institution->getInstitutionId());
            $this->assertNotNull($instructorInstitution, "The user x institution relation was not created for
                                                                                                      the instructor");
            $this->assertEquals(self::SCHOOL_IDENTIFICATION, $instructorInstitution->getIdentification(), 
                                                           "The instructor school identification should NOT be given");
            $this->assertEquals($createdInstructor->getUserId(), $instructorInstitution->getUserId(), "The user id is
                                                                                     incorrect on user x institution");
            $this->assertEquals($this->institution->getInstitutionId(), $instructorInstitution->getInstitutionId(),
                                                               "The institution id is incorrect on user x institution");

            $stXProjec = PersistentUserXProjectPeer::retrieveByPK($createdInstructor->getJnUsername(),
                                                                                   $this->project->getProjectJnName());
            $this->assertNotNull($stXProjec, "The relationship between instructor and project was not created");
            $this->assertEquals($this->project->getProjectJnName(), $stXProjec->getProjectJnName(), "The project name
                                                                   is incorrect on user x project for the instructor");
            $this->assertEquals($createdInstructor->getJnUsername(), $stXProjec->getJnUsername(), "The java.net 
                                                           usernameis incorrect on user x project for the instructor");
            $this->assertTrue($stXProjec->getIsOwner() == 1, "The instructor is a leader, and therefore, a project owner.");

        } catch (InfinityMetricsException $ime){
            $this->fail("The successful login scenario failed: " . $ime);
        }
    }
    /**
     * The test of an exceptional registration where the instructor doesn't enter
     * some of the field values.
     */
    public function testMissingFieldsStudentRegistration() {
        try {
            $createdInstructor = UserManagementController::registerInstructor("", "",
                self::EMAIL, self::FIRSTNAME, self::LASTNAME, $this->project->getProjectJnName(), true,
                $this->institution->getAbbreviation(), "");
            $this->fail("The exceptional login scenario failed with missing name");
            
        } catch (InfinityMetricsException $ime) {
            //$error["fieldName"] = "error message"
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["username"]);
            $this->assertNotNull($errorFields["password"]);
            $this->assertNotNull($errorFields["institutionIdentification"]);
        }

        try {
            $missingEmailandOthers = UserManagementController::registerInstructor(self::USERNAME, self::PASSWORD,
                self::EMAIL, self::FIRSTNAME, self::LASTNAME, "", "", "", self::SCHOOL_IDENTIFICATION);
            
            $this->fail("The exceptional login scenario failed with missing username, password email");
            
        } catch (InfinityMetricsException $ime) {
            //$error["fieldName"] = "error message"
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["projectName"]);
            $this->assertNotNull($errorFields["isOwner"]);
            $this->assertNotNull($errorFields["institutionAbbreviation"]);
        }

        try {
            $createdInstructor = UserManagementController::registerInstructor(self::USERNAME, self::PASSWORD,
                self::EMAIL, "", "", $this->project->getProjectJnName(), true,
                $this->institution->getAbbreviation(), self::SCHOOL_IDENTIFICATION);

            $this->fail("The exceptional registration scenario failed for
                    missing project name, and other fields");
        } catch (InfinityMetricsException $ime) {
            //$error["fieldName"] = "error message"
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["firstName"]);
            $this->assertNotNull($errorFields["lastName"]);
        }
    }
    /**
     * The test an exceptional registration where the instructor enteres an
     * existing username.
     */
    public function testRegisterExistingInstructorRegistration() {
        try {
            //registering the instructor
            $createdInstructor = UserManagementController::registerInstructor(self::USERNAME, self::PASSWORD,
                self::EMAIL, self::FIRSTNAME, self::LASTNAME, $this->project->getProjectJnName(), true,
                $this->institution->getAbbreviation(), self::SCHOOL_IDENTIFICATION);
            
            //registering the instructor once again. This time it throws an exception
            $createdInstructor = UserManagementController::registerInstructor(self::USERNAME, self::PASSWORD,
                self::EMAIL, self::FIRSTNAME, self::LASTNAME, $this->project->getProjectJnName(), true,
                $this->institution->getAbbreviation(), self::SCHOOL_IDENTIFICATION);
            
            $this->fail("The exceptional registration failed for existing instructor");
        } catch (Exception $e) {
            $this->assertNotNull($e);
        }
    }

    protected function tearDown() {
        $this->cleanUpAll();
    }
}
?>
