<?php
/**
 * $Id: WorkspaceStateTest.class.php 202 2008-11-10 21:31:40Z marcellosales $
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
require_once 'infinitymetrics/model/workspace/WorkspaceState.class.php';

/**
 * Tests for the the workspace state class. It tests all the enumaration
 * properties.
 *
 * @author Marcello de Sales (marcello.sales@gmail.com) Nov 10, 2008 2:45 PST
 */
class WorkspaceStateTest extends PHPUnit_Framework_TestCase {

    private $subject;

    protected function setUp() {
        parent::setUp();
        $this->subject = WorkspaceState::getInstance();
    }

    public function testCollectionCreation() {
        //asserting that any instance acquired from the getInstance() is the same
        $anotherWorkspace = WorkspaceState::getInstance();
        $anotherWorkspace2 = WorkspaceState::getInstance();
        $this->assertSame($this->subject, $anotherWorkspace);
        $this->assertSame($this->subject, $anotherWorkspace2);
        $this->assertSame($anotherWorkspace, $anotherWorkspace2);
        //asserting that the values also are the same
        $this->assertEquals($this->subject->NEW, WorkspaceState::getInstance()->NEW);
        $this->assertEquals($this->subject->ACTIVE, WorkspaceState::getInstance()->ACTIVE);
        $this->assertEquals($this->subject->PAUSED, WorkspaceState::getInstance()->PAUSED);
        $this->assertEquals($this->subject->INACTIVE, WorkspaceState::getInstance()->INACTIVE);
    }

    public function testTraverseValuesAndSwitch() {
        $ws = WorkspaceState::getInstance();
        //testing the switch version
        foreach($ws->values() as $val) {
            switch ($val) {
                case $ws->NEW:
                    $this->assertEquals(WorkspaceState::getInstance()->NEW, $ws->NEW);
                    break;
                case $ws->ACTIVE:
                    $this->assertEquals(WorkspaceState::getInstance()->ACTIVE, $ws->ACTIVE);
                    break;
                case $ws->INACTIVE:
                    $this->assertEquals(WorkspaceState::getInstance()->INACTIVE, $ws->INACTIVE);
                    break;
                case $ws->PAUSED:
                    $this->assertEquals(WorkspaceState::getInstance()->PAUSED, $ws->PAUSED);
                    break;
                default:
                    $this->fail("Unknown value of enum " . $val);
                    break;
            }
        }
    }

    protected function tearDown() {
        $this->subject = null;
    }
}
?>