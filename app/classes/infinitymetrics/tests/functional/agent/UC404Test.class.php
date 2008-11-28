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
require_once 'infinitymetrics/orm/PersistentUserPeer.php';

/**
 * Tests for the Use Case UC001 - UC001: A Student registers into the system.
 *
 * @author Marcello de Sales <marcello.sales@gmail.com>
 */
class UC404Test extends PHPUnit_Framework_TestCase {

    private $student;

    const USERNAME_CORRECT = "marcellosales";
    const PASSWORD_CORRECT = "utn@9oad";

    const USERNAME_INCORRECT = "wrongUsername";
    const PASSWORD_INCORRECT = "wrongpassword";

    const USERNAME_MISSING = "";
    const PASSWORD_MISSING = "";

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
    public function testValidUserAuthentication() {
        try {
            $areCreditialsOk = UserManagementController::areUserCredentialsValidOnJN(
                                  self::USERNAME_CORRECT,self::PASSWORD_CORRECT);
            $this->assertTrue($areCreditialsOk, "The correct user's credentials failed");

        } catch (InfinityMetricsException $ime){
            $this->fail("The successful user's creditial authentication scenario failed: " . $ime);
        }
    }
    /**
     * The test of an exceptional registration where the student doesn't enter
     * some of the field values.
     */
    public function testMissingFieldsAuthentication() {
        try {
            $areCreditialsNOTOk = UserManagementController::areUserCredentialsValidOnJN(
                                    self::PASSWORD_INCORRECT, self::PASSWORD_INCORRECT);
            $this->assertFalse($areCreditialsNOTOk, "The incorrect credentials should be invalid");

        } catch (InfinityMetricsException $ime){
            $this->fail("The successful user's bad credentials scenario failed: " . $ime);
        }
    }
    /**
     * The test an exceptional registration where the student enteres an
     * existing username.
     */
    public function testWrongFieldsAuthentication() {
        try {
            $areCreditialsNOTOk = UserManagementController::areUserCredentialsValidOnJN(
                                        self::USERNAME_INCORRECT, self::PASSWORD_INCORRECT);
            $this->assertFalse($areCreditialsNOTOk, "The incorrect credentials should be invalid");

        } catch (InfinityMetricsException $ime){
            $this->fail("The successful user's bad credentials scenario failed: " . $ime);
        }
    }

    protected function tearDown() {
        $this->user = null;
    }
}
?>