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
//require_once 'infinitymetrics/orm/PersistentUserPeer.php';

/**
 * Tests for the Use Case UC003 - UC003: A user logs into the system.
 *
 * @author Gurdeep Singh <gurdeepsingh03@gmail.com>
 */
class UC003Test extends PHPUnit_Framework_TestCase {

    private $user;

    const USERNAME = "anjelina";
    const PASSWORD = "1234";

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
     * The test of a successful login when a user  exist and
     * enters the correct values.
     */
    public function testValidUserLogin() {
        try {
            $areCreditialsOk = UserManagementController::login(
                                  self::USERNAME,self::PASSWORD);
          //  $this->assertTrue($areCreditialsOk, "The correct user's credentials failed");

        } catch (InfinityMetricsException $ime){
            $this->fail("The successful user's creditial authentication scenario failed: " . $ime);
        }
    }
    /**
     * The test of an exceptional login where the user doesn't wrong
     * some of the field values.
     */
    public function testWrongFieldsLogin() {
        try {
            $areCreditialsNOTOk = UserManagementController::login(
                                    self::PASSWORD_INCORRECT, self::PASSWORD_INCORRECT);
            $this->assertFalse($areCreditialsNOTOk, "The incorrect username\password");
            echo 'wrong';
        } catch (InfinityMetricsException $ime){
            $this->fail("The successful user's bad credentials scenario failed: " . $ime);
        }
    }
    /**
     * The test an exceptional login where the USER doesnot enter any field.
     */
    public function testMissingFieldsLogin() {
        try {
            $areCreditialsNOTOk = UserManagementController::login("","");
            $this->assertFalse($areCreditialsNOTOk, "The incorrect credentials should be invalid");
            echo 'executed';

        } catch (InfinityMetricsException $ime){
            $this->fail("The successful user's bad credentials scenario failed: " . $ime);
        }
    }

    protected function tearDown() {
        echo 'Tearing down';
        PersistentUserPeer::doDeleteAll();
        $this->user = null;
    }
}

?>
