<?php

require_once('PHPUnit/Framework.php');
require_once('infinitymetrics/model/report/EventCategory.class.php');

/**
 * Description of EventCategoryTest
 *
 * @author Marilyne Mendolla and Andres Ardila
 */

class EventCategoryTest extends PHPUnit_Framework_TestCase {

    protected $eventCategory;

    public function setUp() {
        $this->eventCategory = new EventCategory();
    }

    public function testMethods() {
        $this->eventCategory->commit();
        $this->assertEquals('COMMIT', $this->eventCategory->getEventCategory(), "Commit was not set");

        $this->eventCategory->document();
        $this->assertEquals('DOCUMENT', $this->eventCategory->getEventCategory(), "Document was not set");

        $this->eventCategory->forum();
        $this->assertEquals('FORUM', $this->eventCategory->getEventCategory(), "Forum was not set");

        $this->eventCategory->issue();
        $this->assertEquals('ISSUE', $this->eventCategory->getEventCategory(), "Issue was not set");

        $this->eventCategory->mailingList();
        $this->assertEquals('MAILING_LIST', $this->eventCategory->getEventCategory(), "Mailing List was not set");
    }

    public function testEquality() {
        $this->eventCategory->commit();

        $fixture = new EventCategory();
        $fixture->commit();

        $this->assertTrue($this->eventCategory == $fixture);

        $fixture->document();
        $this->assertTrue($this->eventCategory != $fixture);
    }
}
?>
