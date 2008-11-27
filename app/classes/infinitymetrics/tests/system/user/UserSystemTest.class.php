<?php
/**
 * $Id: UserSystemTest.class.php 202 2008-11-26 14:45:40Z marcellosales $
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
require_once 'infinitymetrics/model/user/UserTypeEnum.class.php';
require_once 'infinitymetrics/model/user/User.class.php';
require_once 'infinitymetrics/orm/PersistentUserPeer.php';
/**
 * Tests for the Persistence layer for the User class.
 *
 * @author Marcello de Sales <marcello.sales@gmail.com>
 */
class UserSystemTest extends PHPUnit_Framework_TestCase {

    const USERNAME = "marcellosales";
    const INST_ABBREVIATION = "SFSU";

    private $user;
    private $userEnum;

    public function  __construct() {
        $this->userEnum = UserTypeEnum::getInstance();
    }

    private function deleteEverything() {
        //echo "Deleting the user and institution for setting up\n";
        $crit = new Criteria();
        $crit->add(PersistentUserPeer::JN_USERNAME, self::USERNAME);
        PersistentUserPeer::doDelete($crit);

        $crit = new Criteria();
        $crit->add(PersistentUserPeer::JN_USERNAME, "otherusername");
        PersistentUserPeer::doDelete($crit);
    }

    protected function setUp() {
        parent::setUp();
        $this->deleteEverything();
        //echo "Setting up new student\nObject only in MEMORY\n";
        $this->user = new User();
        $this->user->setFirstName("Marcello");
        $this->user->setLastName("de Sales");
        $this->user->setEmail("marcello.sales@gmail.com");
        $this->user->setJnUsername(self::USERNAME);
        $this->user->setJnPassword("blabalbal");
    }

    public function testCreationAndRetrival() {
//        echo "Object to be saved on DB\n";
        $this->user->save();
        $userDb = PersistentUserPeer::retrieveByEmail("marcello.sales@gmail.com");
        $this->assertNotNull($userDb, "Persistent user is null");
        $this->assertTrue($this->user->equals($userDb), "Persistent and transient users are different");
        $this->assertEquals($this->userEnum->JAVANET, $this->user->getType(), "User type value was saved incorrectly");
        $this->assertTrue($this->user->isRegularJnUser(), "The isRegularJnUser() is not returning the correct type");
    }

    public function testCreationWithExistingUser() {
        $otheruser = new User();
        try {
            $otheruser->setFirstName("Other");
            $otheruser->setLastName("Student");
            $otheruser->setEmail("marcello.sales@gmail.com");
            $otheruser->setJnUsername(self::USERNAME);
            $otheruser->setJnPassword("blabalbal");
            
            $otheruser->save();
            $this->fail("The user should not be created with the same username...");
        } catch (Exception $e) {
            $this->assertNotNull($e, "The exception is not created");
        }
        try {
            $otheruser->setFirstName("Other");
            $otheruser->setLastName("Student");
            $otheruser->setEmail("marcello.sales@gmail.com");//same email
            $otheruser->setJnUsername("otherusername");
            $otheruser->setJnPassword("blabalbal");
            
            $otheruser->save();
            $this->fail("The user should not be created with the same email address...");
        } catch (Exception $e) {
            $this->assertNotNull($e, "The exception is not created");
        }
    }

    public function testUserUpdateWithAndWithValidAndInvalidDataAndDelete() {
        $this->user->save();
        $otherUsername = "otherusername";
        $otherEmail = "differentemail@email.com";
        $otheruser = new User();
        //testing the user save and update with correct and incorrect values
        try {
            $otheruser->setFirstName("Other");
            $otheruser->setLastName("Student");
            $otheruser->setEmail($otherEmail);
            $otheruser->setJnUsername($otherUsername);
            $otheruser->setJnPassword("blabalbal");
            $otheruser->save();
            //Saves ok
            $userDb = PersistentUserPeer::retrieveByEmail($otherEmail);
            $this->assertNotNull($userDb, "The persistent object is null");
            $this->assertTrue($otheruser->equals($userDb), "Persistent and transient users are different");

            $otheruser->setJnUsername(self::USERNAME);
            $otheruser->save();
            $this->fail("The otheruser should not be created with the same username as another user ".self::USERNAME);
        } catch (Exception $e) {
            $this->assertNotNull($e, "The exception is not created");
        }
        //testing the deletion of the user by username
        $crit = new Criteria();
        $crit->add(PersistentUserPeer::JN_USERNAME, $otherUsername);
        PersistentUserPeer::doDelete($crit);

        $userDb = PersistentUserPeer::retrieveByJNUsername($otherUsername);
        $this->assertNull($userDb, "The user ".$userDb." should not have been deleted from the database");
    }

    protected function tearDown() {
        //echo "Tearing down...\n";
        $this->deleteEverything();
    }
}
?>
