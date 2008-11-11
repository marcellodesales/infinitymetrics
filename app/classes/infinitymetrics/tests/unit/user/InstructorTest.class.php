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
require_once 'PHPUnit/Framework.php';
require_once 'infinitymetrics/model/institution/Instructor.class.php';
/**
 * Tests for the Instructor class.
 *
 * @author Gurdeep Singh <gurdeepsingh03@gmail.com>
 */
class InstructorTest extends PHPUnit_Framework_TestCase {

    private $instructor;

    const FIRSTNAME = "Gurdeep";
    const LASTNAME = "Singh";
    const USERNAME = "gurdeep22";


    protected function setUp() {
        parent::setUp();
        $this->instructor = new Instructor(self::FIRSTNAME,self::LASTNAME);
       
     }
    public function testInstructorCreation() {
      
        $this->instructor->setInstitution("SFSU");
        $this->instructor->setUserName(self::USERNAME);

        $this->assertEquals(self::FIRSTNAME,$this->instructor->getFirstName(), "The value of the first name is incorrect");
        $this->assertEquals(self::LASTNAME,$this->instructor->getLastName(), "The value of the Last name is incorrect");
        $this->assertEquals("SFSU", $this->instructor->getInstitution(), "The value of the institution is incorrect");
        $this->assertEquals(self::USERNAME, $this->instructor->getUserName(), "The instructor's username is in incorrect state");

    }
    
    public function testInstructorUpdate() {
        $this->instructor->setFirstName("Gurpreet");
        $this->instructor->setLastName("Sandhu");
        $this->instructor->setInstitution("FAU");
        $this->assertEquals("Gurpreet", $this->instructor->getFirstName(), "The value of the first name is in incorrect");
        $this->assertEquals("Sandhu", $this->instructor->getLastName(), "The value of the last name is in incorrect");
        $this->assertEquals("FAU", $this->instructor->getInstitution(), "The value of the institution is incorrect");
        
    }


    protected function tearDown() {
        $this->instructor = null;
    }
}
?>
