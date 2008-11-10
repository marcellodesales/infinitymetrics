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
require_once 'PHPUnit/Framework.php';
require_once 'infinitymetrics/controller/UserManagementController.class.php';
/**
 * Tests for the Use Case UC001 - UC001: A Student registers into the system.
 *
 * @author Marcello de Sales <marcello.sales@gmail.com>
 */
class UC001Test extends PHPUnit_Framework_TestCase {

    private $student;

    const USERNAME = "marcellosales";
    const PASSWORD = "password";

    /**
     * Setting up is run ALWAYS BEFORE the execution of a test method.
     */
    protected function setUp() {
        parent::setUp();
    }
    /**
     * The test of a successful registration when a student doesn't exist and
     * enters the correct values.
     */
    public function testSuccessfulStudentRegistration() {
        try {
            $createdStudent = UserManagementController::registerStudent(
                self::USERNAME, self::PASSWORD, self::EMAIL, self::FIRSTNAME,
                self::LASTNAME, self::INSTITUTION, self::STUDENT_ID,
                self::TEAM_LEADER, self::PROJECT_NAME);
            $this->assertNotNull($createdStudent, "The registered student is null");
            $this->assertTrue($createdStudent instanceof Student, "The registered student is null");

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
                "", "", self::EMAIL, self::FIRSTNAME,
                self::LASTNAME, self::INSTITUTION, self::STUDENT_ID,
                self::TEAM_LEADER, self::PROJECT_NAME);

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
                "", "", "", self::FIRSTNAME,
                self::LASTNAME, self::INSTITUTION, self::STUDENT_ID,
                self::TEAM_LEADER, self::PROJECT_NAME);

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
                "", "", self::EMAIL, self::FIRSTNAME,
                self::LASTNAME, self::INSTITUTION, self::STUDENT_ID,
                "", "");

            $this->fail("The exceptional registration scenario failed for
                    missing project name, team leader and other fields");
        } catch (InfinityMetricsException $ime) {
            //$error["fieldName"] = "error message"
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["teamLeader"]);
            $this->assertNotNull($errorFields["projectName"]);
        }
    }
    /**
     * The test an exceptional registration where the student enteres an
     * existing username.
     */
    public function testRegisterExistingStudentRegistration() {
        try {
            $createdStudent = UserManagementController::registerStudent(
                self::USERNAME, self::PASSWORD, self::EMAIL, self::FIRSTNAME,
                self::LASTNAME, self::INSTITUTION, self::STUDENT_ID,
                self::TEAM_LEADER, self::PROJECT_NAME);
            $this->fail("The exceptional registration failed for existing student");
        } catch (InfinityMetricsException $ime) {
            //$error["fieldName"] = "error message"
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["userExists"]);
        }
    }

    protected function tearDown() {
        $this->user = null;
    }
}
?>