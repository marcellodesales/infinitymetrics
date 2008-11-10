<?php

require_once('PHPUnit/Framework.php');
require_once('infinitymetrics/model/report/Report.class.php');

/**
 * Description of ReportTest
 *
 * @author Andres Ardila
 */
class ReportTest extends PHPUnit_Framework_TestCase {

    protected $report;

    public function setUp() {
        $this->report = new Report();
    }

    public function testBuilder() {
        $project = new Project();
        $eventChannels = array_fill(0, 10, new EventChannel());

        $this->report->builder($project, $eventChannels);

        $this->assertEquals($project, $this->report->getProject(), "Project is not equal");
        $this->assertEquals($eventChannels, $this->report->getEventList(), "Event list is not equal");
    }

    public function testGetsSets() {
        $project = new Project();
        $this->report->setProject($project);

        $eventChannels = array_fill(0, 10, new EventChannel());
        $this->report->setEvenChannels($eventChannels);

        $this->assertEquals($project, $this->report->getProject(), "Project is not equal");
        $this->assertEquals($eventChannels, $this->report->getEventList(), "Event list is not equal");
    }

    public function testAddEventChannel() {
        $eventChannel = new EventChannel();
        $this->report->addEventChannel($eventChannel);

        $this->assertEquals(true, in_array($eventChannel, $this->report->getEventList()));
    }

    public function testFilterByCategory() {
        $filterCategory = new EventCategory();
        $filterCategory->document();

        $this->report->setEvenChannels( array() );

        for ($i = 0; $i < 20; $i++)
        {
            $eventChannel = new EventChannel();
            $category = new EventCategory();
            $category->commit();
            $eventChannel->setCategory($category);
            $this->report->addEventChannel($eventChannel);
            $category->forum();
            $this->report->addEventChannel($eventChannel);
        }

        $this->assertEquals(0, count($this->report->filterByCategory($filterCategory)), "Filter returned incorrect number of Event Channels");
    }

    public function testFilterByDate() {
        
    }

}
?>
