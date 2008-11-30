<?php
/**
 * $Id: UC005Test.class.php 202 2008-11-10 12:01:40Z Gurdeep Singh $
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

require_once 'PHPUnit/Framework.php';
require_once 'infinitymetrics/controller/UserManagementController.class.php';
//require_once 'infinitymetrics/orm/PersistentUserPeer.php';
/**
 * Tests for the Use Case - UC005 A Instructor updates account into the system;
 *
 * @author Gurdeep Singh <gurdeepsingh03@gmail.com>
 */
class UC005Test extends PHPUnit_Framework_TestCase {

    private $instructor;
    private $project;
    private $oldProject;
    private $institution;
    private $oldInstitution;

    const USERNAME = "gurdeep22";
    const PASSWORD = "123";
    const EMAIL = "gurdeep@gmail.com";
    const FIRSTNAME = "Gurdeep";
    const LASTNAME = "Singh";
    const IS_OWNER = true;
    const IDENTIFICATION = "TEACHER101";

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

        $this->instructor = UserManagementController::registerInstructor(self::USERNAME,"PASSWORD" ,"email@gmail.com",
                                                 "firstname", "lastname",$this->oldProject->getProjectJnName(),true,
                                                  $this->oldInstitution->getAbbreviation(), "Teacher1");
    }
    /**
     * The test of a successful update when a Instructor the correct values .
     *       $password, $email,$firstName,$lastName, $studentSchoolId, $projectName,
     *          $institutionAbbreviation, $isLeader
     */
    public function testSuccessfulInstructorUpdate() {
        try {
            $updatedInstructor = UserManagementController::updateInstructorProfile(self::USERNAME,self::PASSWORD ,self::EMAIL
                                                           ,self::FIRSTNAME,self::LASTNAME,$this->project->getProjectJnName()
                                                           ,self::IS_OWNER,$this->institution->getAbbreviation(),self::IDENTIFICATION);

             $this->assertNotNull($updatedInstructor, "The updated instructor is null");
            $this->assertEquals($this->userTypeEnum->INSTRUCTOR, $updatedInstructor->getType(), "The updated user is not
                                                                                               an instance of INSTRUCTOR");

            $InstructorInstitution = PersistentUserXInstitutionPeer::retrieveByPk($updatedInstructor->getUserId(),
                                                                               $this->institution->getInstitutionId());
            $this->assertNotNull($InstructorInstitution, "The user x institution relation was not created for the instructor.");
            $this->assertEquals(self::IDENTIFICATION, $InstructorInstitution->getIdentification(), "The Instructor school
                                                                                         identification is incorrect");
            $this->assertEquals($updatedInstructor->getUserId(), $InstructorInstitution->getUserId(), "The user id is
                                                                   incorrect on user x institution for the instructor");
            $this->assertEquals($this->institution->getInstitutionId(), $InstructorInstitution->getInstitutionId(),
                                              "The institution id is incorrect on user x institution for the instructor");

            $instXProjec = PersistentUserXProjectPeer::retrieveByPK($updatedInstructor->getJnUsername(),
                                                                                   $this->project->getProjectJnName());
            $this->assertNotNull($instXProjec, "The relationship between instructor and project was not created");
            $this->assertEquals($this->project->getProjectJnName(), $instXProjec->getProjectJnName(), "The project name
                                                                                      is incorrect on user x project");
            $this->assertEquals($updatedInstructor->getJnUsername(), $instXProjec->getJnUsername(), "The java.net
                                                                 username is incorrect on user x project for instructor");
            $this->assertTrue($instXProjec->getIsOwner() == 1, "The instructor is the owner of project.");

        } catch (InfinityMetricsException $ime){
            $this->fail("The successful update of profile failed: " . $ime);
        }

    }

    /**
     * The test of an exceptional profile update  where the instructor doesn't enter
     * some of the field values.
     */

    public function testMissingFieldsInstructorProfileUpdate() {
        try {
            $missingNames = UserManagementController::updateInstructorProfile(self::USERNAME,self::PASSWORD ,self::EMAIL
                                                           ,"","",$this->project->getProjectJnName()
                                                           ,self::IS_OWNER,$this->institution->getAbbreviation(),self::IDENTIFICATION);

            $this->fail("The exceptional profile update failed with missing name");
        } catch (InfinityMetricsException $ime) {
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["newFirstName"]);
            $this->assertNotNull($errorFields["newLastName"]);
        }

        try {
            $missingEmailandOthers = UserManagementController::updateInstructorProfile("","" ,""
                                                           ,self::FIRSTNAME,self::LASTNAME,$this->project->getProjectJnName()
                                                           ,self::IS_OWNER,$this->institution->getAbbreviation(),self::IDENTIFICATION);

            $this->fail("The exceptional profile update failed with missing
                            username, password email");
        } catch (InfinityMetricsException $ime) {
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["username"]);
            $this->assertNotNull($errorFields["newPassword"]);
            $this->assertNotNull($errorFields["newEmail"]);
        }

        try {
            $missingProjectName = UserManagementController::updateInstructorProfile(self::USERNAME,self::PASSWORD ,self::EMAIL
                                                           ,self::FIRSTNAME,self::LASTNAME,""
                                                           ,"",$this->institution->getAbbreviation(),self::IDENTIFICATION);

            $this->fail("The exceptional instructor update failed for
                    missing project name, team leader and other fields");
        } catch (InfinityMetricsException $ime) {
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["newProjectName"]);
            $this->assertNotNull($errorFields["new_isOwner"]);
        }
    }
    /**
     * The test an exceptional profile update where the  username
     * does not exists or when instructor enters wrong values for
     * institution or project.
     */
    public function testWrongFieldInstructorProfileUpdate() {
        try {

            $nonExistentInstructor = UserManagementController::updateInstructorProfile("gggggg","686875","ggggg@gmail.com",
                                               "gfhgjhk ","hjhkhkj","ghgjhgj",true,$this->institution->getAbbreviation(),
                                                 "Teacher572");

            $this->fail("The exceptional profile update failed for non existing Instructor");
        } catch (InfinityMetricsException $ime) {
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["userNotFound"]);
        }

       try {

            $wrongProjectNameFieldUpdate = UserManagementController::updateInstructorProfile(self::USERNAME,self::PASSWORD ,self::EMAIL
                                                           ,self::FIRSTNAME,self::LASTNAME,"ABC"
                                                           ,self::IS_OWNER,$this->institution->getAbbreviation(),self::IDENTIFICATION);

            $this->fail("The exceptional profile update failed for non existing Project of Instructor");
        } catch (InfinityMetricsException $ime) {
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["projectNotFound"]);
        }

        try {

            $wrongInstitutionNameFieldUpdate = UserManagementController::updateInstructorProfile(self::USERNAME,self::PASSWORD ,self::EMAIL
                                                      ,self::FIRSTNAME,self::LASTNAME,$this->project->getProjectJnName()
                                                      ,self::IS_OWNER,"ABCD",self::IDENTIFICATION);

            $this->fail("The exceptional profile update failed for non existing Institution of Instructor");
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