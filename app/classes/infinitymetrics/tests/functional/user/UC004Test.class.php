<?php
/**
 * $Id: UC004Test.class.php 202 2008-11-10 12:01:40Z gurdeepsingh $
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
require_once 'infinitymetrics/orm/PersistentUserPeer.php';
require_once 'infinitymetrics/model/institution/Instructor.class.php';
require_once 'infinitymetrics/model/institution/Student.class.php';
require_once 'infinitymetrics/model/user/User.class.php';
require_once 'infinitymetrics/orm/PersistentProjectPeer.php';

/**
 * Tests for the Use Case UC004 - UC004: A user view his\her Profile Information.
 *
 * @author Gurdeep Singh <gurdeepsingh03@gmail.com>
 */
class UC004Test extends PHPUnit_Framework_TestCase {

    private $javaNetUser;
    private $student;
    private $instructor;
    private $institution;
    private $userTypeEnum;
    private $project;
    
   
    public function  __construct() {
        $this->userTypeEnum = UserTypeEnum::getInstance();
    }

    private function cleanUpAll() {
        PersistentUserXProjectPeer::doDeleteAll();
        PersistentUserXInstitutionPeer::doDeleteAll();
        PersistentUserPeer::doDeleteAll();
        PersistentInstitutionPeer::doDeleteAll();
        PersistentProjectPeer::doDeleteAll();
        PersistentWorkspacePeer::doDeleteAll();
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
        $this->project->setProjectJnName("PPM");
        $this->project->setParentProjectJnName(Null);
        $this->project->setSummary("Project participation metrics");
        $this->project->save();

        $this->javaNetUser = UserManagementController::registerUser("preet","password","preet@gmail.com","Preet",
                                                    "Kaur",$this->project->getProjectJnName(),true);

        $this->student = UserManagementController::registerStudent("gurdeep22","password","gurdeepsingh03@gmail.com","Gurdeep",
                                                             "Singh","90912345",$this->project->getProjectJnName(),
                                                            $this->institution->getAbbreviation(),false );

        $this->instructor = UserManagementController::registerInstructor("marcello", "password","marcellosales@gmail.com",
                                               "Marcello","sales",$this->project->getProjectJnName(),true,
                                                 $this->institution->getAbbreviation(),"Teacher101" );

    }
    /**
     * The test of a successful Profile information view when a user  exist and
     * enters the correct values.
     */

    public function testValidJNUserProfileView() {
        try {
            $profileJNUser = UserManagementController::viewProfile($this->javaNetUser->getUserId());
            $this->assertNotNull($profileJNUser, "The Profile of User is Null");
            $this->assertEquals($this->userTypeEnum->JAVANET, $this->javaNetUser->getType(), "The  user is not a Java Net User");
            $userXProjec = PersistentUserXProjectPeer::retrieveByPK($this->javaNetUser->getJnUsername(),
                                                                                   $this->project->getProjectJnName());
            $this->assertNotNull($userXProjec, "The relationship between java net User and project was not created");

        } catch (InfinityMetricsException $ime){
            $this->fail("The successful Java Net User's Profile view Failed " . $ime);
        }
    }

     /**
     * The test of a successful Profile view when a student  exist and
     * enters the correct values.
     */
    public function testValidStudentProfileView() {
        try {
            $profileStudent = UserManagementController::viewProfile($this->student->getUserId());
            $this->assertNotNull($profileStudent, "The profile student is Null");
            $this->assertEquals($this->userTypeEnum->STUDENT, $this->student->getType(), "The  user is not a Student");
            $studentInstitution = PersistentUserXInstitutionPeer::retrieveByPk($this->student->getUserId(),
                                                                               $this->institution->getInstitutionId());
            $this->assertNotNull($studentInstitution,"The user x institution relation was not created for
                                                                                                      the student");
            $studXProjec = PersistentUserXProjectPeer::retrieveByPK($this->student->getJnUsername(),
                                                                                   $this->project->getProjectJnName());
            $this->assertNotNull($studXProjec, "The relationship between student and project was not created");
        } catch (InfinityMetricsException $ime){
            $this->fail("The successful Student profile view Failed " . $ime);
        }
    }

     /**
     * The test of a successful Profile view when a instructor  exist and
     * enters the correct values.
     */
    public function testValidInstructorProfileView() {
        try {
            $profileInstructor = UserManagementController::viewProfile($this->instructor->getUserId());
            $this->assertNotNull($profileInstructor, "The profile of instructor is Null");
            $this->assertEquals($this->userTypeEnum->INSTRUCTOR, $this->instructor->getType(), "The user is not a Instructor");
            $instructorInstitution = PersistentUserXInstitutionPeer::retrieveByPk($this->instructor->getUserId(),
                                                                               $this->institution->getInstitutionId());
            $this->assertNotNull($instructorInstitution,"The user x institution relation was not created for
                                                                                                      the instructor");
            $instXProjec = PersistentUserXProjectPeer::retrieveByPK($this->instructor->getJnUsername(),
                                                                                   $this->project->getProjectJnName());
            $this->assertNotNull($instXProjec, "The relationship between instructor and project was not created");
          
        } catch (InfinityMetricsException $ime){
            $this->fail("The successful Instructor view profile  Failed " . $ime);
        }
    }

     /**
     * The test of an exceptional Profile view where the user enter wrong
     * some of the field values.
     */
    public function testWrongFieldsJNUserProfileView() {
        try {
            $invalidJNUserProfileView = UserManagementController::viewProfile("wrong_Id");
            $this->fail("The exceptional view profile information scenario failed with wrong fields");
           }catch (InfinityMetricsException $ime) {
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["userNotFound"]);
         }
    }

     /**
     * The test of an exceptional Profile where the student enter wrong
     * some of the field values.
     */
     public function testWrongFieldsStudentProfileView() {
        try {
            $invalidStudentProfileView = UserManagementController::viewProfile("wrong_Id");
            $this->fail("The exceptional view profile scenario failed with wrong fields");
        }catch (InfinityMetricsException $ime) {
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["userNotFound"]);
         }
    }

     /**
     * The test of an exceptional Profile View where the Instructor enter wrong
     * some of the field values.
     */
     public function testWrongFieldsInstructorProfileView() {
        try {
            $invalidInstructorProfileView = UserManagementController::viewProfile("wrong_Id");
            $this->fail("The exceptional view profile scenario failed with wrong fields");
        } catch (InfinityMetricsException $ime) {
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["userNotFound"]);
         }
    }
    /**
     * The test an exceptional Profile View where the User doesnot enter any field.
     */
    public function testMissingFieldsJNUserProfileView() {
       try {
            $missingFieldJNUserProfileView = UserManagementController::viewProfile("");
            $this->fail("The exceptional javaNetUser view profile scenario failed with missing fields");
        } catch (InfinityMetricsException $ime) {
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["user_id"]);
        }
    }

    /**
     * The test an exceptional Profile View where the student doesnot enter any field.
     */
    public function testMissingFieldsStudentProfileview() {
        try {
            $missingFieldStudentProfileView = UserManagementController::viewProfile("");
             $this->fail("The exceptional Student view profile scenario failed with missing fields");
        } catch (InfinityMetricsException $ime) {
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["user_id"]);
         }
    }

    /**
     * The test an exceptional Profile View where the Instructor doesnot enter any field.
     */
    public function testMissingFieldsInstructorProfileView() {
        try {
            $missingFieldInstructorLogin = UserManagementController::viewProfile("");
             $this->fail("The exceptional Instructor view profile scenario failed with missing fields");
        } catch (InfinityMetricsException $ime) {
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["user_id"]);
        }
    }



    protected function tearDown() {
        $this->cleanUpAll();
    }
}

?>
