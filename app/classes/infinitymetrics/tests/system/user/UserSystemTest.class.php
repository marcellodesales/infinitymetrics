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
require_once 'infinitymetrics/model/user/User.class.php';
require_once 'infinitymetrics/orm/PersistentUserPeer.php';

/**
 * Tests for the User class.
 *
 * @author Marcello de Sales <marcello.sales@gmail.com>
 */
class UserTest extends PHPUnit_Framework_TestCase {

    private $user;

    const USERNAME = "marcellosales";

    protected function setUp() {
        parent::setUp();
        echo "Setting up new user\nObject only in MEMORY\n";
        $this->user = new User();
        $this->user->setFirstName("Marcello");
        $this->user->setLastName("de Sales");
        $this->user->setEmail("marcello.sales@gmail.com");
        $this->user->setJnUsername(self::USERNAME);
        $this->user->setJnPassword("blabalbal");
        $this->user->setType("STUDENT");
    }

    public function testCreationAndRetrival() {
        echo "Object to be saved on DB\n";
        $this->user->save();
        $userDb = PersistentUserPeer::retrieveByEmail("marcello.sales@gmail.com");
        $this->assertNotNull($userDb);
        $this->assertTrue($this->user->equals($userDb));
    }

    public function testUserUpdate() {
        echo "Object updated in MEMORY and on DB\n";
        $this->user->setFirstName("Gurdeep");
        $this->user->setLastName("Singh");
        $this->user->save();
        $userDb = PersistentUserPeer::retrieveByEmail("marcello.sales@gmail.com");

        $this->assertEquals("Gurdeep", $userDb->getFirstName(), "The value of the name is in incorrect");
        $this->assertEquals("Singh", $userDb->getLastName(), "The value of the name is in incorrect");
    }

    protected function tearDown() {
        echo "Tearing down...";
        $this->user->delete();
    }
}
?>
