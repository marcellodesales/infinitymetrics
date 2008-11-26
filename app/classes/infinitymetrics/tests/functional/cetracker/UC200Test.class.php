<?php
/**
 * $Id: UC200Test.class.php 202 2008-11-10 12:01:40Z PST Brett Fisher $
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
require_once 'infinitymetrics/controller/CustomEventController.class.php';
/**
 * Tests for the Use Case UC202: The instructor wishes to add an entry for a
 * given Custom Event.
 *
 * @author Brett Fisher <fghtikty@gmail.com>
 */
class UC202Test extends PHPUnit_Framework_TestCase {

    private $project;
    private $event;
    private $entry;
    private $eventId;

    /**
     * Setting up is run ALWAYS BEFORE the execution of a test method.
     */
    protected function setUp() {
        parent::setUp();
        PersistentProjectPeer::doDeleteAll();
        PersistentCustomEventPeer::doDeleteAll();
        PersistentCustomEventEntryPeer::doDeleteAll();

        $this->project = new Project();
        $this->project->setProjectJnName("ppm-8");
        $this->project->setSummary("Infinity Metrics");

        $this->event = new CustomEvent("Brett's coolness factor.");
        $this->event->setProjectJnName("ppm-8");
        $this->project->addCustomEvent($this->event);

        $this->event->save();
        $this->eventId = $this->event->getCustomEventId();

        $this->entry = new CustomEventEntry("Is off the charts!");
        $this->entry->setCustomEventId($this->eventId);
        $this->event->addCustomEventEntry($this->entry);

        $this->project->save();
    }
    /**
     * The test of a successful registration when an entry doesn't exist and
     * enters the correct values.
     */
    public function testSuccessfulEventCreation() {
        try {
            //Saving the entry
            $createdEvent = CustomEventController::createEvent(
                "Is unbelieveably high!", "Brett's wow factor.",
                $this->project->getProjectJnName());
            $this->assertNotNull($createdEvent,
                "The custom event is null.\n");
            $this->assertTrue($createdEvent instanceof CustomEvent,
                "The custom event is not an instance of CustomEvent.\n");
            $this->assertEquals($this->project->getProjectJnName(),
                $createdEvent->getProjectJnName(), "The relationship between ".
                "Project and the Custom Event is wrong.\n");
        } catch (InfinityMetricsException $ime){
            $this->fail("The Entry registration failed: ".$ime);
        }
    }
    /**
     * The test of an exceptional creation where the instructor doesn't
     * enter some of the field values.
     */
    public function testMissingFieldsEntryCreation() {
        try {
            CustomEventController::createEvent("", "Brett's wow factor.",
                $this->project->getProjectJnName());
            $this->fail("The exceptional event creation failed with missing ".
                "notes.\n");
        } catch (InfinityMetricsException $ime) {
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["notes"]);
        }
        try {
            CustomEventController::createEvent("Is unbelieveably high!", "",
                $this->project->getProjectJnName());
            $this->fail("The exceptional event creation failed with missing ".
                "title.\n");
        } catch (InfinityMetricsException $ime) {
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["title"]);
        }
        try {
            CustomEventController::createEvent("Is unbelieveably high!",
                "Brett's wow factor.", null);
            $this->fail("The exceptional event creation failed with missing ".
                "project jn name.\n");
        } catch (InfinityMetricsException $ime) {
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["project_jn_name"]);
        }
    }
    /**
     * The test an exceptional creation where the instructor enters an
     * existing entry.
     */
    public function testCreateExistingEntry() {
        try {
            CustomEventController::createEvent(
                "Is unbelieveably high!", "Brett's wow factor.",
                $this->project->getProjectJnName());

            CustomEventController::createEvent(
                "Is unbelieveably high!", "Brett's wow factor.",
                $this->project->getProjectJnName());

            $this->fail("The exceptional creation failed for existing ".
                "event.\n");
        } catch (Exception $ime) {
            $this->assertNotNull($ime);
        }
    }

    protected function tearDown() {
        PersistentCustomEventEntryPeer::doDeleteAll();
        PersistentCustomEventPeer::doDeleteAll();
        PersistentProjectPeer::doDeleteAll();
    }
}
?>