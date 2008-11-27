<?php
/**
 * $Id: InstructorTest.class.php 202 2008-11-10 21:31:40Z Gurdeep Singh $
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
/**
 * Tests for the Instructor class.
 *
 * @author Gurdeep Singh <gurdeepsingh03@gmail.com>
 */
class InstructorTest extends PHPUnit_Framework_TestCase {

    const FIRSTNAME = "Gurdeep";
    const LASTNAME = "Singh";
    const USERNAME = "gurdeep22";
    
    private $instructor;

    protected function setUp() {
        parent::setUp();
        $this->instructor = new Instructor();
        $this->instructor->setFirstName(self::FIRSTNAME);
        $this->instructor->setLastName(self::LASTNAME);
        $this->instructor->setEmail("gurdeep22@gmail.com");
        $this->instructor->setJnUsername(self::USERNAME);
        $this->instructor->setJnPassword("blabalbal");
    }

    public function testUserComparison() {
        $a = new Instructor();
        $a->setJnUsername("marcellosales");
        $b = $this->instructor;
        $this->assertEquals(UserTypeEnum::getInstance()->INSTRUCTOR, $this->instructor->getType());
        $this->assertTrue($b === $this->instructor);
        $this->assertTrue($b !== $a);
        $this->assertFalse($a->equals($this->instructor));
        $this->assertFalse($b->equals($a));
        $this->assertTrue($a instanceof User);
        $this->assertTrue($a instanceof User);
    }
    
    protected function tearDown() {
        $this->instructor = null;
    }
}
?>
