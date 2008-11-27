<?php
/**
 * $Id: UserTest.class.php 202 2008-11-10 21:31:40Z marcellosales $
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
require_once 'infinitymetrics/model/institution/Student.class.php';
/**
 * Tests for the User class.
 *
 * @author Marcello de Sales <marcello.sales@gmail.com>
 */
class StudentTest extends PHPUnit_Framework_TestCase {

    private $user;
    
    const USERNAME = "marcellosales";

    protected function setUp() {
        parent::setUp();
        $this->user = new Student();
        $this->user->setFirstName("Marcello");
        $this->user->setLastName("de Sales");
        $this->user->setEmail("marcello.sales@gmail.com");
        $this->user->setJnUsername(self::USERNAME);
        $this->user->setJnPassword("blabalbal");
    }

    public function testUserComparison() {

        $a = new Student();
        $a->setJnUsername("marcellosales");
        $b = $this->user;
        $this->assertEquals(UserTypeEnum::getInstance()->STUDENT, $this->user->getType());
        $this->assertTrue($b === $this->user);
        $this->assertTrue($b !== $a);
        $this->assertTrue($a->equals($this->user));
        $this->assertTrue($b->equals($a));
        $this->assertTrue($a instanceof User);
        $this->assertTrue($a instanceof User);
    }

    protected function tearDown() {
        $this->user = null;
    }
}
?>
