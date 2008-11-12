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
        $this->assertEquals($eventChannels, $this->report->getEventChannels(), "Event list is not equal");
    }

    public function testGetsSets() {
        $project = new Project();
        $this->report->setProject($project);

        $eventChannels = array_fill(0, 10, new EventChannel());
        $this->report->setEventChannels($eventChannels);

        $this->assertEquals($project, $this->report->getProject(), "Project is not equal");
        $this->assertEquals($eventChannels, $this->report->getEventChannels(), "Event list is not equal");
    }

    public function testAddEventChannel() {
        $eventChannel = new EventChannel();
        $this->report->addEventChannel($eventChannel);

        $this->assertEquals(true, in_array($eventChannel, $this->report->getEventChannels()));
    }

    public function testFilterByCategory() {
        $filterCategory = new EventCategory();
        $filterCategory->document();

        $this->report->setEventChannels( array() );

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

        for ($j = 0; $j < 10; $j++)
        {
            $eventChannel = new EventChannel();
            $eventChannel->setCategory($filterCategory);
            $this->report->addEventChannel($eventChannel);
        }

        $this->assertEquals(10, count($this->report->filterByCategory($filterCategory)), "Filter returned incorrect number of Event Channels");
    }

    public function testFilterByDate() {
        $this->report->setEventChannels(array());
        $controlEventChannels = array();

        $startDate = new DateTime('2008-05-01');
        $endDate = new DateTime('2008-08-31');

        for ($i = 0; $i < 10; $i++)
        {
            $eventChannel = new EventChannel();
            $controlChannel = new EventChannel();

            for ($j = 0; $j < 25; $j++)
            {
                $event = new Event();
                $mo = rand()%12 + 1;
                $day = rand()%28 + 1;
                $dateStr = '2008-'.$mo.'-'.$day;
                $date = new DateTime($dateStr);
                $event->setDate($date);

                $eventChannel->addEvent($event);
                if ($date >= $startDate && $date <= $endDate) {
                    $controlChannel->addEvent($event);
                }
            }

            $this->report->addEventChannel($eventChannel);
            $controlEventChannels[] = $controlChannel;
        }
        
        $this->assertEquals($controlEventChannels,
                            $this->report->filterByDate($startDate, $endDate),
                            "Arrays of EventChannels are not equal");
    }

    public function testFilterByUser() {
        $this->report->setEventChannels(array());
        $controlEventChannels = array();

        $controlUser = new User("control", "control", "control");

        for ($i = 0; $i < 10; $i++)
        {
            $eventChannel = new EventChannel();
            $controlChannel = new EventChannel();

            for ($j = 0; $j < 25; $j++)
            {
                $event = new Event();
                $mo = rand()%12 + 1;
                $day = rand()%28 + 1;
                $dateStr = '2008-'.$mo.'-'.$day;
                $date = new DateTime($dateStr);
                if ($i == 5 || $i == 10 || $i ==15) {
                    $user = clone $controlUser;
                }
                else {
                    $user = new User("notTheControl", "notTheControl", "NotTheControl");
                }
                $event->builder($user, $date);
                $eventChannel->addEvent($event);
            }

            $this->report->addEventChannel($eventChannel);
            $controlEventChannels[] = $controlChannel;
        }

        $this->assertEquals($controlEventChannels,
                            $this->report->filterByUser($controlUser),
                            "Arrays of EventChannels are not equal");
    }
}
?>
