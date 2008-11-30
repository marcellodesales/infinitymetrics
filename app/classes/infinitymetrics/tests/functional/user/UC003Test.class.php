<?php
/**
 * $Id: UC003Test.class.php 202 2008-11-10 12:01:40Z gurdeepsingh $
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
 * Tests for the Use Case UC003 - UC003: A user logs into the system.
 *
 * @author Gurdeep Singh <gurdeepsingh03@gmail.com>
 */
class UC003Test extends PHPUnit_Framework_TestCase {

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
        $this->project->setProjectJnName("ppm111");
        $this->project->setSummary("Project paticipation Metrics");
        $this->project->save();

        $this->javaNetUser = UserManagementController::registerUser("preet", "password", "preet@gmail.com", "Preet",
                                                                    "Kaur",$this->project->getProjectJnName(),true);

        $this->student = UserManagementController::registerStudent("gurdeep22", "password", "gurdeepsingh03@gmail.com",
                                                                    "Gurdeep", "Singh", "90912345",
                                                                    $this->project->getProjectJnName(),
                                                                    $this->institution->getAbbreviation(),false );

        $this->instructor = UserManagementController::registerInstructor("marcello", "password",
                                                    "marcellosales@gmail.com", "Marcello", "sales",
                                                    $this->project->getProjectJnName(),true,
                                                    $this->institution->getAbbreviation(),"Teacher101" );
    }
    /**
     * The test of a successful login when a user  exist and
     * enters the correct values.
     */
    public function testValidJNUserLogin() {
        try {
            $loggedJNUser = UserManagementController::login($this->javaNetUser->getJnUsername(),
                                                            $this->javaNetUser->getJnPassword());
            $this->assertNotNull($loggedJNUser, "The Logged User is Null");
            $this->assertEquals($this->userTypeEnum->JAVANET, $this->javaNetUser->getType(),
                                                                          "The logged user is not a Java Net User");
        } catch (InfinityMetricsException $ime){
            $this->fail("The successful Java Net User's Login Failed " . $ime);
        }
    }
     /**
     * The test of a successful login when a student  exist and
     * enters the correct values.
     */
    public function testValidStudentLogin() {
        try {
            $loggedStudent = UserManagementController::login($this->student->getJnUsername(),
                                                             $this->student->getJnPassword());
            $this->assertNotNull($loggedStudent, "The Logged student is Null");
            $this->assertEquals($this->userTypeEnum->STUDENT, $this->student->getType(),
                                                                                 "The logged user is not a Student");
        } catch (InfinityMetricsException $ime){
            $this->fail("The successful Student Login Failed " . $ime);
        }
    }
     /**
     * The test of a successful login when a instructor  exist and
     * enters the correct values.
     */
    public function testValidInstructorLogin() {
        try {
            $loggedInstructor = UserManagementController::login($this->instructor->getJnUsername(),
                                                                $this->instructor->getJnPassword());
            $this->assertNotNull($loggedInstructor, "The Logged User is Null");
            $this->assertEquals($this->userTypeEnum->INSTRUCTOR, $this->instructor->getType(),
                                                                                "The logged user is not a Instructor");
        } catch (InfinityMetricsException $ime){
            $this->fail("The successful Instructor Login Failed " . $ime);
        }
    }
     /**
     * The test of an exceptional login where the user enter wrong
     * some of the field values.
     */
    public function testWrongFieldsJNUserLogin() {
        try {
            $invalidLoggedJNUser = UserManagementController::login("wrongUsername",$this->javaNetUser->getJnPassword());
            $this->fail("The exceptional login scenario failed with wrong fields");
           // $this->assertNull($invalidLoggedJNUser, "The incorrect javaNetUser's username\password");
        } catch (InfinityMetricsException $ime) {
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["usernameIncorrect"]);
        }

        try {
            $invalidLoggedJNUser = UserManagementController::login($this->javaNetUser->getJnUsername(), "wrongpassword");
            $this->fail("The exceptional login scenario failed with wrong fields");
           // $this->assertNull($invalidLoggedJNUser, "The incorrect javaNetUser's username\password");
        } catch (InfinityMetricsException $ime) {

            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["passwordDoesnMatch"]);
        }
    }
     /**
     * The test of an exceptional login where the student enter wrong
     * some of the field values.
     */
     public function testWrongFieldsStudentLogin() {
        try {
            $invalidLoggedStudent = UserManagementController::login("wrongUsername",$this->student->getJnPassword());
            $this->fail("The exceptional login scenario failed with wrong fields");
        } catch (InfinityMetricsException $ime) {

            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["usernameIncorrect"]);       
        }
        try {
            $invalidLoggedJNUser = UserManagementController::login($this->student->getJnUsername(),"wrongpassword");
            $this->fail("The exceptional login scenario failed with wrong fields");
           // $this->assertNull($invalidLoggedJNUser, "The incorrect javaNetUser's username\password");
        } catch (InfinityMetricsException $ime) {

            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["passwordDoesnMatch"]);
        }
    }

     /**
     * The test of an exceptional login where the Instructor enter wrong
     * some of the field values.
     */
     public function testWrongFieldsInstructorLogin() {
        try {
            $invalidLoggedInstructor = UserManagementController::login("wrongUser", $this->instructor->getJnPassword());
            $this->fail("The exceptional login scenario failed with wrong fields");
        } catch (InfinityMetricsException $ime) {

            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["usernameIncorrect"]);  
        }

        try {
            $invalidLoggedJNUser = UserManagementController::login($this->instructor->getJnUsername(), "wrongpassword");
            $this->fail("The exceptional login scenario failed with wrong fields");
           // $this->assertNull($invalidLoggedJNUser, "The incorrect javaNetUser's username\password");
        } catch (InfinityMetricsException $ime) {

            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["passwordDoesnMatch"]);
        }
    }
    /**
     * The test an exceptional login where the User doesnot enter any field.
     */
    public function testMissingFieldsJNUserLogin() {
        try {
            $missingFieldJNUserLogin = UserManagementController::login("", $this->javaNetUser->getJnPassword());
            $this->fail("The exceptional javaNetUser login scenario failed with missing username");
        } catch (InfinityMetricsException $ime) {

            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["username"]);
        }

        try {
            $missingFieldJNUserLogin = UserManagementController::login($this->javaNetUser->getJnUsername(), "");
            $this->fail("The exceptional javaNetUser login scenario failed with missing password");
        } catch (InfinityMetricsException $ime) {

            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["password"]);
        }

        try {
            $missingFieldJNUserLogin = UserManagementController::login("","");
            $this->fail("The exceptional javaNetUser login scenario failed with missing fields");
        } catch (InfinityMetricsException $ime) {

            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["username"]);
            $this->assertNotNull($errorFields["password"]);
        }
    }
    
    /**
     * The test an exceptional login where the student doesnot enter any field.
     */
    public function testMissingFieldsStudentorLogin() {
        try {
            $missingFieldStudentLogin = UserManagementController::login($this->student->getJnUsername(), "");
             $this->fail("The exceptional studentlogin scenario failed with missing passwords");
        } catch (InfinityMetricsException $ime) {

            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["password"]);
        }
         try {
             $missingFieldStudentLogin = UserManagementController::login("", $this->student->getJnPassword());
             $this->fail("The exceptional student login scenario failed with missing username");

        } catch (InfinityMetricsException $ime) {

            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["username"]);
        }

        try {
            $missingFieldStudentLogin = UserManagementController::login("","");
            $this->fail("The exceptional Student login scenario failed with missing fields");

        } catch (InfinityMetricsException $ime) {

            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["username"]);
            $this->assertNotNull($errorFields["password"]);
        }
    }
    /**
     * The test an exceptional login where the Instructor doesnot enter any field.
     */
    public function testMissingFieldsInstructorLogin() {
        try {
            $missingFieldInstructorLogin = UserManagementController::login($this->instructor->getJnUsername(), "");
             $this->fail("The exceptional instructor login scenario failed with missing password");

        } catch (InfinityMetricsException $ime) {

            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["password"]);
        }

        try {
            $missingFieldInstructorLogin = UserManagementController::login("",$this->instructor->getJnPassword());
             $this->fail("The exceptional Instructor login scenario failed with missing username");

        } catch (InfinityMetricsException $ime) {

            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["username"]);
        }

        try {
            $missingFieldInstructorLogin = UserManagementController::login("","");
             $this->fail("The exceptional Instructor login scenario failed with missing fields");
        } catch (InfinityMetricsException $ime) {

            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["username"]);
            $this->assertNotNull($errorFields["password"]);
        }
    }

    protected function tearDown() {
        $this->cleanUpAll();
    }
}
?>
