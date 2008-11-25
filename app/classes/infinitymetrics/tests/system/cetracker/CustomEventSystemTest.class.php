<?php
/**
 * $Id: CustomEventSystemTest.class.php 202 2008-11-10 21:31:40Z
 * fisher_brett $
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
require_once 'infinitymetrics/model/cetracker/CustomEvent.class.php';
require_once 'infinitymetrics/model/cetracker/CustomEventEntry.class.php';
require_once 'infinitymetrics/model/workspace/Project.class.php';
require_once 'infinitymetrics/orm/PersistentCustomEventPeer.php';

/**
 * Tests for the CustomEvent class.
 *
 * @author Brett Fisher <fghtikty@gmail.com>
 */
class CustomEventSystemTest extends PHPUnit_Framework_TestCase {

    private $customEvent;
    private $criteria;
    private $results;
    private $project;

    const TITLE = "Brett's coolness factor.";
    const PROJECT_JN_NAME = "Brett's fanclub.";

    private function deleteCustomEventAndProject() {
        //echo "Deleting the custom event for setting up.\n";
        $criteria = new Criteria();
        $criteria->add(PersistentCustomEventPeer::TITLE, self::TITLE);
        PersistentCustomEventPeer::doDelete($criteria);

        $criteria->clear();
        $criteria->add(PersistentProjectPeer::PROJECT_JN_NAME,
            self::PROJECT_JN_NAME);
        PersistentCustomEventPeer::doDelete($criteria);
    }

    protected function setUp() {
        parent::setUp();
        $this->deleteCustomEventAndProject();
        //echo "Setting up new custom event and project.\n";
        //echo "Object only in MEMORY.\n";
        $this->customEvent = new CustomEvent(self::TITLE);
        $this->customEvent->setProjectJnName(self::PROJECT_JN_NAME);
        $this->project = new Project();
        $this->project->setProjectJnName(self::PROJECT_JN_NAME);
        $this->project->save();
    }

    public function testCreationAndRetrival() {
        //echo "Objects to be saved on DB.\n";
        $this->project->save();
        $this->customEvent->save();

        $criteria = new Criteria();
        $criteria->add(PersistentCustomEventPeer::TITLE,
            self::TITLE);

        $results = PersistentCustomEventPeer::doSelectOne($criteria);

        $this->assertNotNull($results, "The results are null from the persistent database");
        $this->assertTrue($this->customEvent->equals($results),
            "Persistent and transient custom events are different.");
    }

    public function testCreationWithExistingCustomEvent() {
        $otherCustomEvent = new CustomEvent(self::TITLE);

        try {
            $otherCustomEvent->save();
            $this->fail("The custom event should not be created with the same title...");
        } catch (Exception $e) {
            $this->assertNotNull($e, "The exception is not created.");
        }
        try {
            $otherCustomEvent->setTitle("Brett is superb!");
            $otherCustomEvent->save();
            $this->fail("The custom event should not be created with the same title...");
        } catch (Exception $e) {
            $this->assertNotNull($e, "The exception is not created.");
        }
    }

    public function testCustomEventEntries() {
        $tempArray;

        $this->customEvent->setTitle("Brett is almighty!");

        $i=1;
        while($i<=5) {
            $this->customEvent->addCustomEventEntry(new
                CustomEventEntry("Test ".$i));
            $i++;
        }
        $this->customEvent->save();

        $criteria = new Criteria();
        $criteria->add(PersistentCustomEventPeer::TITLE, "Brett is almighty!");
        $results = PersistentCustomEventPeer::doSelect($criteria);

        $i=1;
        foreach($results as $event) {
            foreach($event->getCustomEventEntrys() as $entry) {
                $this->assertEquals($entry->getNotes(), "Test ".$i,
                "Persistent and transient custom event entries are different.".
                "\n");
                $i++;
            }
        }
    }

    public function testCustomEventUpdateWithInvalidData() {
        $this->customEvent->save();
        $otherTitle = "Brett is incredible!";
        $otherCustomEvent = new CustomEvent($otherTitle);

        // Testing the custom event  save and update
        //   with correct and incorrect values.
        try {
            $otherCustomEvent->save();
            // Saves ok.

            $this->criteria = new Criteria();
            $criteria->add(PersistentCustomEventPeer::TITLE, $otherTitle);
            $results =
                PersistentCustomEventPeer::doSelect($this->criteria);

            $this->assertNotNull($results);
            $this->assertTrue($otherTitle->equals($results),
                "Persistent and transient custom event entries are different.".
                "\n");

            $otherCustomEvent->setTitle("");
            $otherCustomEvent->save();
            $this->fail("The other custom event should not be created "
                ."with empty title.\n");
        } catch (Exception $e) {
            $this->assertNotNull($e, "The exception is not created.\n");
        }

        // Testing the deletion of the custom event by title.
        $criteria = new Criteria();
        $criteria->add(PersistentCustomEventPeer::TITLE, $otherTitle);
        PersistentCustomEventPeer::doDelete($criteria);

        $results = PersistentCustomEventPeer::doSelectOne($criteria);
        $this->assertNull($results, "The custom event ".$results.
            " should not have been deleted from the database.\n");
    }

    protected function tearDown() {
        //echo "Tearing down...\n";
        $this->customEvent->delete();
    }
}
?>
