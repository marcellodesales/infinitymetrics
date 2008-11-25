o<?php
/**
 * $Id: InstructorSystemTest.class.php 202 2008-11-10 21:31:40Z marcellosales $
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
require_once 'infinitymetrics/model/institution/Instructor.class.php';
require_once 'infinitymetrics/model/institution/Institution.class.php';
require_once 'infinitymetrics/orm/PersistentUserPeer.php';
require_once 'infinitymetrics/orm/PersistentInstitutionPeer.php';
/**
 * Tests for the Instructor class.
 *
 * @author Gurdeep Singh <gurdeepsingh03@gmail.com>
 */
class InstructorSystemTest extends PHPUnit_Framework_TestCase {

    private $instructor;
    private $institution;

    const USERNAME = "gurdeep22";
    const INST_ABBREVIATION = "SFSU";

    private function deleteUser() {
        echo "Deleting the user and institution for setting up";
        $crit = new Criteria();
        $crit->add(PersistentUserPeer::JN_USERNAME, self::USERNAME);
        PersistentUserPeer::doDelete($crit);

        $crit->add(PersistentInstitutionPeer::ABBREVIATION, self::INST_ABBREVIATION);
        PersistentUserPeer::doDelete($crit);
    }

    protected function setUp() {
        parent::setUp();
        $this->deleteUser();
        echo "Setting up new instructor\nObject only in MEMORY\n";
        $this->instructor = new Instructor();
        $this->instructor->setFirstName("Gurdeep");
        $this->instructor->setLastName("Singh");
        $this->instructor->setEmail("gurdeepsingh03@gmail.com");
        $this->instructor->setJnUsername(self::USERNAME);
        $this->instructor->setJnPassword("gur22");


        $this->institution = new Institution();
        $this->institution->setName("San Francisco State University");
        $this->institution->setAbbreviation(self::INST_ABBREVIATION);
        $this->institution->setCity("San Francisco");
        $this->institution->setStateProvince("California");
        $this->institution->setCountry("United States");
        $this->institution->save();
        $this->instructor->setInstitution($this->institution);
        $this->instructor->save();
    }

    public function testCreationAndRetrival() {
        echo "Object to be saved on DB\n";
        $this->instructor->save();
        $userDb = PersistentUserPeer::retrieveByEmail("gurdeepsingh03@gmail.com");
        $this->assertNotNull($userDb);
        $this->assertTrue($this->instructor->equals($userDb), "Persistent and transient instructor are different");
    }

    public function testCreationWithExistingUser() {
	  echo "Testing Existing on...";
        $this->instructor->save();
        $otheruser = new Instructor();
        try {
            $otheruser->setFirstName("Other");
            $otheruser->setLastName("Instructor");
            $otheruser->setEmail("gurdeep@gmail.com");
            $otheruser->setJnUsername(self::USERNAME);
            $otheruser->setJnPassword("blabalbal");
            $otheruser->setInstitution($this->institution);

            $otheruser->save();
            $this->fail("The user should not be created with the same username...");
        } catch (Exception $e) {
            $this->assertNotNull($e, "The exception is not created");
        }
        try {
            $otheruser->setFirstName("Other");
            $otheruser->setLastName("Instructor");
            $otheruser->setEmail("gurdeep@gmail.com");//same email
            $otheruser->setJnUsername("otherusername");
            $otheruser->setJnPassword("blabalbal");
            $otheruser->setInstitution($this->institution);

            $otheruser->save();
            $this->fail("The user should not be created with the same email address...");
        } catch (Exception $e) {
            $this->assertNotNull($e, "The exception is not created");
        }
    }

    public function testUserUpdateWithAndWithValidAndInvalidDataAndDelete() {
        $this->instructor->save();
        $otherUsername = "gurdeep";
        $otherEmail = "differentemail@email.com";
        $otheruser = new Instructor();
        //testing the user save and update with correct and incorrect values
        try {
            $otheruser->setFirstName("Other");
            $otheruser->setLastName("Instructor");
            $otheruser->setEmail($otherEmail);
            $otheruser->setJnUsername($otherUsername);
            $otheruser->setJnPassword("blabalbal");
            $otheruser->setInstitution($this->institution);
            $otheruser->save();
            //Saves ok
            $userDb = PersistentUserPeer::retrieveByEmail($otherEmail);
            $this->assertNotNull($userDb);
            $this->assertTrue($otheruser->equals($userDb), "Persistent and transient instructor are different");

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
        echo "Tearing down...";
       // $this->instructor->delete();
    }
}
?>
