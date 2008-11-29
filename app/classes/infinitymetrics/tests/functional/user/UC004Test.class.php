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
    private $parentProject;
    private $childProject;
    private $workspace;
    private $student_project;
    private $instructor_project;
    private $student_inst;
    private $instructor_inst;

    public function  __construct() {
        $this->userTypeEnum = UserTypeEnum::getInstance();
    }

    /**
     * Setting up is run ALWAYS BEFORE the execution of a test method.
     */
    protected function setUp() {
        parent::setUp();
        PersistentInstitutionPeer::doDeleteAll();
        PersistentUserPeer::doDeleteAll();
        PersistentProjectPeer::doDeleteAll();
        PersistentWorkspacePeer::doDeleteAll();
        PersistentUserXInstitutionPeer::doDeleteAll();
        PersistentUserXProjectPeer::doDeleteAll();


        //detele all users;
        //delete all institutions;

        $this->institution = new Institution();
        $this->institution->setName('San Francisco State University');
        $this->institution->setAbbreviation("SFSU");
        $this->institution->setCity('San Francisco');
        $this->institution->setStateProvince('CA');
        $this->institution->setCountry('USA');
        $this->institution->save();

        $this->parentProject = new PersistentProject();
        $this->parentProject->setProjectJnName("PPM");
        $this->parentProject->setParentProjectJnName(Null);
        $this->parentProject->setSummary("Project participation metrics");
        $this->parentProject->save();

        $this->childProject = new PersistentProject();
        $this->childProject->setProjectJnName("ppm-8");
        $this->childProject->setParentProjectJnName($this->parentProject->getProjectJnName());
        $this->childProject->setSummary("Infinity metrics");
        $this->childProject->save();

        $this->javaNetUser = new User();
        $this->javaNetUser->setJnUsername("preet");
        $this->javaNetUser->setJnPassword("1234");
        $this->javaNetUser->setFirstName("Preet");
        $this->javaNetUser->setLastName("kaur");
        $this->javaNetUser->setEmail("preet@gmail.com");
        $this->javaNetUser->setType("JAVANET");
        $this->javaNetUser->save();

        $this->student = new User();
        $this->student->setJnUsername("gurdeep22");
        $this->student->setJnPassword("12345");
        $this->student->setEmail("gurdeepsingh03@gmail.com");
        $this->student->setFirstName("Gurdeep");
        $this->student->setLastName("Singh");
        $this->student->setType("STUDENT");
        $this->student->save();
     
        $this->instructor = new User();
        $this->instructor->setJnUsername("marcello");
        $this->instructor->setJnPassword("12345");
        $this->instructor->setEmail("marcellosales@gmail.com");
        $this->instructor->setFirstName("Marcello");
        $this->instructor->setLastName("Sales");
        $this->instructor->setType("INSTRUCTOR");
        $this->instructor->save();

        $this->student_project = new PersistentUserXProject();
        $this->student_project->setProjectJnName($this->childProject->getProjectJnName());
        $this->student_project->setJnUsername($this->student->getJnUsername());
        $this->student_project->setIsOwner("0");
        $this->student_project->save();

        $this->instructor_project = new PersistentUserXProject();
        $this->instructor_project->setProjectJnName($this->parentProject->getProjectJnName());
        $this->instructor_project->setJnUsername($this->instructor->getJnUsername());
        $this->instructor_project->setIsOwner("1");
        $this->instructor_project->save();

        $this->workspace = new PersistentWorkspace();
        $this->workspace->setTitle("My Workspace");
        $this->workspace->setProjectJnName($this->parentProject->getProjectJnName());
        $this->workspace->setState("NEW");
        $this->workspace->setDescription("This is the workspace for PPM");
        $this->workspace->setUserId($this->instructor->getUserId());
        $this->workspace->save();

        $this->student_inst = new PersistentUserXInstitution();
        $this->student_inst->setUserId($this->student->getUserId());
        $this->student_inst->setInstitutionId($this->institution->getInstitutionId());
        $this->student_inst->setIdentification("90912345");
        $this->student_inst->save();

        $this->instructor_inst = new PersistentUserXInstitution();
        $this->instructor_inst->setUserId($this->instructor->getUserId());
        $this->instructor_inst->setInstitutionId($this->institution->getInstitutionId());
        $this->instructor_inst->setIdentification("Teacher001");
        $this->instructor_inst->save();





    }
    /**
     * The test of a successful Profile information view when a user  exist and
     * enters the correct values.
     */

    public function testValidJNUserProfileView() {
        try {
            $profileJNUser = UserManagementController::viewProfile($this->javaNetUser->getUserId());
            $this->assertNotNull($profileJNUser, "The Profile of User is Null");
            $this->assertEquals($this->userTypeEnum->JAVANET, $this->javaNetUser->getType(), "The logged user is not a Java Net User");


        } catch (InfinityMetricsException $ime){
            $this->fail("The successful Java Net User's Login Failed " . $ime);
        }
    }

     /**
     * The test of a successful Profile view when a student  exist and
     * enters the correct values.
     */
    public function testValidStudentProfileView() {
        try {
            $profileStudent = UserManagementController::viewProfile($this->student->getUserId());
            $this->assertNotNull($profileStudent, "The Logged student is Null");
            $this->assertEquals($this->userTypeEnum->STUDENT, $this->student->getType(), "The logged user is not a Student");
            $studentInstitution = PersistentUserXInstitutionPeer::retrieveByPk($this->student->getUserId(),
                                                                               $this->institution->getInstitutionId());
            $this->assertNotNull($studentInstitution,"The user x institution relation was not created for
                                                                                                      the student");
            $studXProjec = PersistentUserXProjectPeer::retrieveByPK($this->student->getJnUsername(),
                                                                                   $this->childProject->getProjectJnName());
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
            $this->assertNotNull($profileInstructor, "The Logged User is Null");
            $this->assertEquals($this->userTypeEnum->INSTRUCTOR, $this->instructor->getType(), "The logged user is not a Instructor");
            $instructorInstitution = PersistentUserXInstitutionPeer::retrieveByPk($this->instructor->getUserId(),
                                                                               $this->institution->getInstitutionId());
            $this->assertNotNull($instructorInstitution,"The user x institution relation was not created for
                                                                                                      the instructor");
            $instXProjec = PersistentUserXProjectPeer::retrieveByPK($this->instructor->getJnUsername(),
                                                                                   $this->parentProject->getProjectJnName());
            $this->assertNotNull($instXProjec, "The relationship between instructor and project was not created");
          
        } catch (InfinityMetricsException $ime){
            $this->fail("The successful Instructor Login Failed " . $ime);
        }
    }

     /**
     * The test of an exceptional Profile view where the user enter wrong
     * some of the field values.
     */
    public function testWrongFieldsJNUserProfileView() {
        try {
            $invalidJNUserProfileView = UserManagementController::viewProfile("wrong_Id");
            $this->fail("The exceptional login scenario failed with wrong fields");
           // $this->assertNull($invalidLoggedJNUser, "The incorrect javaNetUser's username\password");
        } catch (Exception $e) {
            $this->assertNotNull($e);
        }
    }

     /**
     * The test of an exceptional Profile where the student enter wrong
     * some of the field values.
     */
     public function testWrongFieldsStudentProfileView() {
        try {
            $invalidStudentProfileView = UserManagementController::viewProfile("wrong_Id");
            $this->fail("The exceptional login scenario failed with wrong fields");
        }catch (Exception $e) {
            $this->assertNotNull($e);
        }
    }

     /**
     * The test of an exceptional Profile View where the Instructor enter wrong
     * some of the field values.
     */
     public function testWrongFieldsInstructorProfileView() {
        try {
            $invalidInstructorProfileView = UserManagementController::viewProfile("wrong_Id");
            $this->fail("The exceptional login scenario failed with wrong fields");
        } catch (Exception $e) {
            $this->assertNotNull($e);
        }
    }
    /**
     * The test an exceptional Profile View where the User doesnot enter any field.
     */
    public function testMissingFieldsJNUserProfileView() {
       try {
            $missingFieldJNUserProfileView = UserManagementController::viewProfile("");
            $this->fail("The exceptional javaNetUser login scenario failed with missing fields");
        } catch (Exception $e) {
            $this->assertNotNull($e);
        }
    }

    /**
     * The test an exceptional Profile View where the student doesnot enter any field.
     */
    public function testMissingFieldsStudentProfileview() {
        try {
            $missingFieldStudentProfileView = UserManagementController::viewProfile("");
             $this->fail("The exceptional Student login scenario failed with missing fields");
        } catch (Exception $e) {
            $this->assertNotNull($e);
        }
    }

    /**
     * The test an exceptional Profile View where the Instructor doesnot enter any field.
     */
    public function testMissingFieldsInstructorProfileView() {
        try {
            $missingFieldInstructorLogin = UserManagementController::viewProfile("");
             $this->fail("The exceptional Instructor login scenario failed with missing fields");
        } catch (Exception $e) {
            $this->assertNotNull($e);
        }
    }



    protected function tearDown() {
        echo 'Tearing down';
        PersistentUserPeer::doDeleteAll();
        PersistentInstitutionPeer::doDeleteAll();
        PersistentProjectPeer::doDeleteAll();
        PersistentWorkspacePeer::doDeleteAll();
        PersistentUserXInstitutionPeer::doDeleteAll();
        PersistentUserXProjectPeer::doDeleteAll();
        $this->user = null;
    }
}

?>
