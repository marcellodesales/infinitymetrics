<?php
/**
 * $Id: UC006Test.class.php 202 2008-11-10 12:01:40Z gurdeep singh $
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
 * Tests for the Use Case UC006 - UC006: A Student registers into the system.
 *
 * Another assumption for the system tests for the user is tha the authentication will happen in another moment of
 * the execution. In this way, the PRE-CONDITION for these scenarios is tha the user has been authenticated over
 * Java.net already.
 *
 * @author Gurdeep Singh <gurdeepsingh03@gmail.com>
 */
class UC006Test extends PHPUnit_Framework_TestCase {
     const USERNAME = "gurdeep";
    const PASSWORD = "12345";
    const EMAIL = "gurdeepsingh03@gmail.com";
    const FIRSTNAME = "Gurdeep";
    const LASTNAME = "Singh";
    const SCHOOL_ID = "909765432";

    private $institution;
    private $project;
    private $userTypeEnum;
    private $oldProject;
    private $student;
    private $oldInstitution;

    public function  __construct() {
        $this->userTypeEnum = UserTypeEnum::getInstance();
    }

    private function cleanUpAll() {
        PersistentUserXProjectPeer::doDeleteAll();
        PersistentUserPeer::doDeleteAll();
        PersistentInstitutionPeer::doDeleteAll();
        PersistentProjectPeer::doDeleteAll();
        PersistentUserXInstitutionPeer::doDeleteAll();
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

        $this->oldInstitution = new Institution();
        $this->oldInstitution->setName('Punjabi University');
        $this->oldInstitution->setAbbreviation("PU");
        $this->oldInstitution->setCity('Patiala');
        $this->oldInstitution->setStateProvince('PB');
        $this->oldInstitution->setCountry('INDIA');
        $this->oldInstitution->save();


        $this->project = new PersistentProject();
        $this->project->setProjectJnName("ppm-8");
        $this->project->setSummary("Infinity Metrics");
        $this->project->save();

        $this->oldProject = new PersistentProject();
        $this->oldProject->setProjectJnName("ppm");
        $this->oldProject->setSummary("paticipation metrics");
        $this->oldProject->save();

         $this->student = UserManagementController::registerStudent(self::USERNAME,"password","email@gmail.com","firstName",
                                                             "lastName","90912345",$this->oldProject->getProjectJnName(),
                                                            $this->oldInstitution->getAbbreviation(),false );



    }
    /**
     * The test of a successful profile update when a student
     * enters the correct values.
     */
    public function testSuccessfulStudentProfileUpdate() {
        try {
            //Saving the updated student profile
            $updatedStudent = UserManagementController::updateStudentProfile(
                                    self::USERNAME,self::PASSWORD,self::EMAIL,self::FIRSTNAME,
                                    self::LASTNAME, self::SCHOOL_ID, $this->project->getProjectJnName(),
                                    $this->institution->getAbbreviation(), true);
            $this->assertNotNull($updatedStudent, "The updated student is null");
            $this->assertEquals($this->userTypeEnum->STUDENT, $updatedStudent->getType(), "The updated user is not
                                                                                               an instance of Student");

            $studentInstitution = PersistentUserXInstitutionPeer::retrieveByPk($updatedStudent->getUserId(),
                                                                               $this->institution->getInstitutionId());
            $this->assertNotNull($studentInstitution, "The user x institution relation was not created for the student.");
            $this->assertEquals(self::SCHOOL_ID, $studentInstitution->getIdentification(), "The student school
                                                                                         identification is incorrect");
            $this->assertEquals($updatedStudent->getUserId(), $studentInstitution->getUserId(), "The user id is
                                                                   incorrect on user x institution for the student");
            $this->assertEquals($this->institution->getInstitutionId(), $studentInstitution->getInstitutionId(),
                                              "The institution id is incorrect on user x institution for the student");

            $stXProjec = PersistentUserXProjectPeer::retrieveByPK($updatedStudent->getJnUsername(),
                                                                                   $this->project->getProjectJnName());
            $this->assertNotNull($stXProjec, "The relationship between student and project was not created");
            $this->assertEquals($this->project->getProjectJnName(), $stXProjec->getProjectJnName(), "The project name
                                                                                      is incorrect on user x project");
            $this->assertEquals($updatedStudent->getJnUsername(), $stXProjec->getJnUsername(), "The java.net
                                                                 username is incorrect on user x project for student");
            $this->assertTrue($stXProjec->getIsOwner() == 1, "The student is a leader, and therefore, a project owner.");

        } catch (InfinityMetricsException $ime){
            $this->fail("The successful profile update scenario failed: " . $ime);
        }

    }

    /**
     * The test of an exceptional profile update  where the student doesn't enter
     * some of the field values.
     */

   public function testMissingFieldsStudentProfileUpdate() {
        try {
            $missingNames = UserManagementController::updateStudentProfile(
                                    self::USERNAME, self::PASSWORD,self::EMAIL, "",
                                    "",self::SCHOOL_ID, $this->project->getProjectJnName(),
                                    $this->institution->getAbbreviation(), true);

            $this->fail("The exceptional profile update scenario failed with missing name");
        } catch (InfinityMetricsException $ime) {
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["newFirstName"]);
            $this->assertNotNull($errorFields["newLastName"]);
        }

        try {
            $missingEmailandOthers = UserManagementController::updateStudentProfile(
                                    "", "", "", self::FIRSTNAME,
                                    self::LASTNAME, "909663916", $this->project->getProjectJnName(),
                                    $this->institution->getAbbreviation(), true);

            $this->fail("The exceptional profile update scenario failed with missing
                            username, password email");
        } catch (InfinityMetricsException $ime) {
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["username"]);
            $this->assertNotNull($errorFields["newPassword"]);
            $this->assertNotNull($errorFields["newEmail"]);
        }

        try {
            $missingProjectName = UserManagementController::updateStudentProfile(
                                    self::USERNAME,self::PASSWORD,self::EMAIL,self::FIRSTNAME,
                                    self::LASTNAME,self::SCHOOL_ID, "",$this->institution->getAbbreviation(),"" );

            $this->fail("The exceptional student update failed for
                    missing project name, team leader and other fields");
        } catch (InfinityMetricsException $ime) {
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["newProjectName"]);
            $this->assertNotNull($errorFields["new_isLeader"]);
        }
    }
    /**
     * The test an exceptional profile update where the  username
     * does not exists or student enter wrong values for
     * institution or project.
     */
  public function testWrongFieldStudentProfileUpdate(){
       try {
           //when student does not exist.
            $nonExistentStudent = UserManagementController::updateStudentProfile(
                                    "username", "password", "email@gmail.com", "firstNameLeader",
                                    "lastNameLeader", "909663916", $this->project->getProjectJnName(),
                                    $this->institution->getAbbreviation(), true);

            $this->fail("The exceptional profile update failed for non existing student");
        } catch (InfinityMetricsException $ime) {
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["userNotFound"]);
    }

    try{
         $wrongProjectNameField = UserManagementController::updateStudentProfile(self::USERNAME,self::PASSWORD,self::EMAIL,self::FIRSTNAME,
                                                         self::LASTNAME, self::SCHOOL_ID,"ABC" ,
                                                         $this->institution->getAbbreviation(), true);
           $this->fail("The exceptional profile update failed for non existing Project of student");
       } catch (InfinityMetricsException $ime) {
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["projectNotFound"]);
       }

       try{
           $wrongInstitutionField = UserManagementController::updateStudentProfile(self::USERNAME,self::PASSWORD,self::EMAIL,self::FIRSTNAME,
                                     self::LASTNAME, self::SCHOOL_ID,$this->project->getProjectJnName() ,
                                        "ABCD", true);
           $this->fail("The exceptional profile update failed for non existing Institution of student");
       } catch (InfinityMetricsException $ime) {
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["institutionNotFound"]);
       }

  }
    protected function tearDown() {
        $this->cleanUpAll();
    }
}
?>