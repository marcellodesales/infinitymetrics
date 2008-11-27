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
   
    public function  __construct() {
        $this->userTypeEnum = UserTypeEnum::getInstance();
    }

    /**
     * Setting up is run ALWAYS BEFORE the execution of a test method.
     */
    protected function setUp() {
        parent::setUp();
        //detele all users;
        //delete all institutions;
        PersistentInstitutionPeer::doDeleteAll();
        PersistentUserPeer::doDeleteAll();
      
        
        $this->institution = new Institution();
        $this->institution->setName('San Francisco State University');
        $this->institution->setAbbreviation("SFSU");
        $this->institution->setCity('San Francisco');
        $this->institution->setStateProvince('CA');
        $this->institution->setCountry('USA');
        $this->institution->save();

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
     
    
    }
    /**
     * The test of a successful login when a user  exist and
     * enters the correct values.
     */

    public function testValidJNUserLogin() {
        try {
            $loggedJNUser = UserManagementController::login($this->javaNetUser->getJnUsername(),$this->javaNetUser->getJnPassword());
            $this->assertNotNull($loggedJNUser, "The Logged User is Null");
            $this->assertEquals($this->userTypeEnum->JAVANET, $this->javaNetUser->getType(), "The logged user is not a Java Net User");

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
            $loggedStudent = UserManagementController::login($this->student->getJnUsername(),$this->student->getJnPassword());
            $this->assertNotNull($loggedStudent, "The Logged student is Null");
            $this->assertEquals($this->userTypeEnum->STUDENT, $this->student->getType(), "The logged user is not a Student");

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
            $loggedInstructor = UserManagementController::login($this->instructor->getJnUsername(),$this->instructor->getJnPassword());
            $this->assertNotNull($loggedInstructor, "The Logged User is Null");
            $this->assertEquals($this->userTypeEnum->INSTRUCTOR, $this->instructor->getType(), "The logged user is not a Instructor");

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
            $invalidLoggedJNUser = UserManagementController::login("wrongUsername", "wrongPassword");
            $this->fail("The exceptional login scenario failed with wrong fields");
           // $this->assertNull($invalidLoggedJNUser, "The incorrect javaNetUser's username\password");
        } catch (Exception $e) {
            $this->assertNotNull($e);
        }
    }

     /**
     * The test of an exceptional login where the student enter wrong
     * some of the field values.
     */
     public function testWrongFieldsStudentLogin() {
        try {
            $invalidLoggedStudent = UserManagementController::login("wrongUsername", "wrongPassword");
            $this->fail("The exceptional login scenario failed with wrong fields");
        }catch (Exception $e) {
            $this->assertNotNull($e);
        }
    }

     /**
     * The test of an exceptional login where the Instructor enter wrong
     * some of the field values.
     */
     public function testWrongFieldsInstructorLogin() {
        try {
            $invalidLoggedInstructor = UserManagementController::login("wrongUsername", "wrongPassword");
            $this->fail("The exceptional login scenario failed with wrong fields");
        } catch (Exception $e) {
            $this->assertNotNull($e);
        }
    }
    /**
     * The test an exceptional login where the User doesnot enter any field.
     */
    public function testMissingFieldsJNUserLogin() {
        try {
            $missingFieldJNUserLogin = UserManagementController::login("",$this->javaNetUser->getJnPassword());
            $this->fail("The exceptional javaNetUser login scenario failed with missing username");
        } catch (Exception $e) {
            $this->assertNotNull($e);
        }

         try {
             $missingFieldJNUserLogin = UserManagementController::login($this->javaNetUser->getJnUsername(),"");
            $this->fail("The exceptional javaNetUser login scenario failed with missing password");
        } catch (Exception $e) {
            $this->assertNotNull($e);
        }

        try {
            $missingFieldJNUserLogin = UserManagementController::login("","");
            $this->fail("The exceptional javaNetUser login scenario failed with missing fields");
        } catch (Exception $e) {
            $this->assertNotNull($e);
        }
    }
    
    /**
     * The test an exceptional login where the student doesnot enter any field.
     */
    public function testMissingFieldsStudentorLogin() {
        try {
            $missingFieldStudentLogin = UserManagementController::login($this->student->getJnUsername(),"");
             $this->fail("The exceptional studentlogin scenario failed with missing passwords");
        } catch (Exception $e) {
            $this->assertNotNull($e);
        }
         try {
             $missingFieldStudentLogin = UserManagementController::login("",$this->student->getJnPassword());
             $this->fail("The exceptional student login scenario failed with missing username");
        } catch (Exception $e) {
            $this->assertNotNull($e);
        }
        try {
            $missingFieldStudentLogin = UserManagementController::login("","");
             $this->fail("The exceptional Student login scenario failed with missing fields");
        } catch (Exception $e) {
            $this->assertNotNull($e);
        }
    }
    
    /**
     * The test an exceptional login where the Instructor doesnot enter any field.
     */
    public function testMissingFieldsInstructorLogin() {
        try {
            $missingFieldInstructorLogin = UserManagementController::login($this->instructor->getJnUsername(),"");
             $this->fail("The exceptional instructor login scenario failed with missing password");
        } catch (Exception $e) {
            $this->assertNotNull($e);
        }

        try {
            $missingFieldInstructorLogin = UserManagementController::login("",$this->instructor->getJnPassword());
             $this->fail("The exceptional Instructor login scenario failed with missing username");
        } catch (Exception $e) {
            $this->assertNotNull($e);
        }

        try {
            $missingFieldInstructorLogin = UserManagementController::login("","");
             $this->fail("The exceptional Instructor login scenario failed with missing fields");
        } catch (Exception $e) {
            $this->assertNotNull($e);
        }
    }

    

    protected function tearDown() {
        echo 'Tearing down';
        PersistentUserPeer::doDeleteAll();
        PersistentInstitutionPeer::doDeleteAll();
        $this->user = null;
    }
}

?>
