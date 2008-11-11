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
require_once 'PHPUnit/Framework.php';
require_once 'infinitymetrics/controller/UserManagementController.class.php';
/**
 * Tests for the Use Case - UC002 A instructor registers into the system;
 *
 * @author Gurdeep Singh <gurdeepsingh03@gmail.com>
 */
class UC002Test extends PHPUnit_Framework_TestCase {

    private $instructor;

    const USERNAME = "Gurdeep";
    const PASSWORD = "password";
    const EMAIL = "gurdeepsingh03@gmail.com";
    const FIRSTNAME = "Gurdeep";
    const LASTNAME = "Singh";
    const INSTITUTION = "SFSU";
    const PROJECT_NAME = "PPM8";

    /**
     * Setting up is run ALWAYS BEFORE the execution of a test method.
     */
    protected function setUp() {
        parent::setUp();
    }
    /**
     * The test of a successful registration when a instructor doesn't exist and
     * enters the correct values.
     */
    public function testSuccessfulInstructorRegistration() {
        try {
            $createdInstructor = UserManagementController::registerInstructor(
                self::USERNAME, self::PASSWORD, self::EMAIL, self::FIRSTNAME,
                self::LASTNAME, self::INSTITUTION, self::PROJECT_NAME);
            $this->assertNotNull($createdInstructor, "The registered instructor is null");
            $this->assertTrue($createdInstructor instanceof Instructor, "The registered instructor is null");

        } catch (InfinityMetricsException $ime){
            $this->fail("The successful login scenario failed: " . $ime);
        }
    }
    /**
     * The test of an exceptional registration where the instructor doesn't enter
     * some of the field values.
     */
    public function testMissingFieldsInstructorRegistration() {
        try {
            $missingNames = UserManagementController::registerInstructor(
                "", "", self::EMAIL, self::FIRSTNAME,
                self::LASTNAME, self::INSTITUTION,self::PROJECT_NAME);

            $this->fail("The exceptional login scenario failed with missing name");
        } catch (InfinityMetricsException $ime) {
            //$error["fieldName"] = "error message"
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["userName"]);
            $this->assertNotNull($errorFields["password"]);
        }

        try {
            $missingEmailandOthers = UserManagementController::registerInstructor(
                "", "", "", self::FIRSTNAME,
                self::LASTNAME, self::INSTITUTION,self::PROJECT_NAME);

            $this->fail("The exceptional login scenario failed with missing
                            username, password and email");
        } catch (InfinityMetricsException $ime) {
            //$error["fieldName"] = "error message"
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["userName"]);
            $this->assertNotNull($errorFields["password"]);
            $this->assertNotNull($errorFields["email"]);
        }

        try {
            $createdInstructor = UserManagementController::registerInstructor(
                "", "", self::EMAIL, self::FIRSTNAME,
                self::LASTNAME, self::INSTITUTION, "");

            $this->fail("The exceptional registration scenario failed for
                    missing project name,  and other fields");
        } catch (InfinityMetricsException $ime) {
            //$error["fieldName"] = "error message"
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["userName"]);
            $this->assertNotNull($errorFields["password"]);
            $this->assertNotNull($errorFields["projectName"]);
        }
    }
    /**
     * The test an exceptional registration where the instructor enteres an
     * existing username.
     */
    public function testRegisterExistingInstructorRegistration() {
        try {
            $createdInstructor = UserManagementController::registerInstructor(
                self::USERNAME, self::PASSWORD, self::EMAIL, self::FIRSTNAME,
                self::LASTNAME, self::INSTITUTION, self::PROJECT_NAME);
            $this->fail("The exceptional registration failed for existing instructor");
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
