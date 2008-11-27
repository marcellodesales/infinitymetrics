<?php
/**
 * $Id: UserSystemTest.class.php 202 2008-11-10 21:31:40Z marcellosales $
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
require_once 'infinitymetrics/model/institution/Student.class.php';
require_once 'infinitymetrics/model/institution/Institution.class.php';
require_once 'infinitymetrics/orm/PersistentUserPeer.php';
require_once 'infinitymetrics/orm/PersistentInstitutionPeer.php';
require_once 'infinitymetrics/orm/PersistentUserXInstitution.php';
require_once 'infinitymetrics/orm/PersistentUserXInstitutionPeer.php';
/**
 * Tests for the Persistence layer for the Student class.
 *
 * @author Marcello de Sales <marcello.sales@gmail.com>
 */
class StudentSystemTest extends PHPUnit_Framework_TestCase {
    
    const USERNAME = "marcellosales";
    const INST_ABBREVIATION = "SFSU";

    private $student;
    private $institution;
    private $userEnum;
    private $studentInstitution;

    public function  __construct() {
        $this->userEnum = UserTypeEnum::getInstance();
    }

    private function deleteEverything() {
        //echo "Deleting the user and institution for setting up";
        PersistentUserPeer::doDeleteAll();
        PersistentInstitutionPeer::doDeleteAll();
        PersistentUserXInstitutionPeer::doDeleteAll();
    }

    protected function setUp() {
        parent::setUp();
        $this->deleteEverything();
        //echo "Setting up new student\nObject only in MEMORY\n";
        $this->student = new Student();
        $this->student->setFirstName("Marcello");
        $this->student->setLastName("de Sales");
        $this->student->setEmail("marcello.sales@gmail.com");
        $this->student->setJnUsername(self::USERNAME);
        $this->student->setJnPassword("blabalbal");

        $this->institution = new Institution();
        $this->institution->setName("San Francisco State University");
        $this->institution->setAbbreviation(self::INST_ABBREVIATION);
        $this->institution->setCity("San Francisco");
        $this->institution->setStateProvince("California");
        $this->institution->setCountry("United States");
        $this->institution->save();

        $this->studentInstitution = new PersistentUserXInstitution();
        $this->studentInstitution->setInstitution($this->institution);
        $this->studentInstitution->setUser($this->student);
        $this->studentInstitution->setIdentification("909663916");
        $this->studentInstitution->save();
    }

    public function testCreationAndRetrival() {
        //echo "Object to be saved on DB\n";
        $this->student->save();
        $userDb = PersistentUserPeer::retrieveByEmail("marcello.sales@gmail.com");
        $this->assertNotNull($userDb, "The student should be retrieved from the persistence layer");
        $this->assertEquals($this->student->getJnUsername(), $userDb->getJnUsername(), "Persistent and transient students are different");
        $this->assertEquals($this->userEnum->STUDENT, $this->student->getType(), "Student type value was saved incorrectly");
        $this->assertTrue($this->student->isStudent(), "The isStudent() is not returning the correct type");
    }

    public function testCreationWithExistingUser() {
        $otheruser = new Student();
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
            $otheruser->setEmail("marcello.sales@gmail.com");//same username
            $otheruser->setJnUsername("otherusername");
            $otheruser->setJnPassword("blabalbal");

            $otheruser->save();
            $this->fail("The user should not be created with the same email address...");
        } catch (Exception $e) {
            $this->assertNotNull($e, "The exception is not created");
        }
    }

    public function testUserUpdateWithAndWithValidAndInvalidDataAndDelete() {
        $this->student->save();
        $otherUsername = "gurdeep";
        $otherEmail = "differentemail@email.com";
        $otheruser = new Student();
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
            $this->assertNotNull($userDb);
            $this->assertTrue($otheruser->equals($userDb), "Persistent and transient students are different");

            $otheruser->setJnUsername(self::USERNAME);
            $otheruser->save();
            $this->fail("The student should not be created with the same username as another user ".self::USERNAME);
        } catch (Exception $e) {
            $this->assertNotNull($e, "The exception is not created");
        }
        //testing the deletion of the user by username
        $crit = new Criteria();
        $crit->add(PersistentUserPeer::JN_USERNAME, $otherUsername);
        PersistentUserPeer::doDelete($crit);

        $userDb = PersistentUserPeer::retrieveByJNUsername($otherUsername);
        $this->assertNull($userDb, "The student ".$userDb." should not have been deleted from the database");
    }

    protected function tearDown() {
        //echo "Tearing down...";
        $this->deleteEverything();
    }
}
?>
