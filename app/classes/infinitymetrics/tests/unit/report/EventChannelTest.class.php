<?php

require_once('PHPUnit/Framework.php');
require_once('infinitymetrics/model/report/EventChannel.class.php');

/**
 * Description of EventChannel
 *
 * @author Andres Ardila
 */

class EventChannelTest extends PHPUnit_Framework_TestCase {
    
    protected $eventChannel;
    
    public function setUp() {
        $this->eventChannel = new EventChannel();
    }
    
    public function testBuilder() {
        $description = "Test Channel Description";
        $name = "Test Channel name";
        
        $events = array();
        for ($i = 0; $i < 10; $i++) {
            array_push($events, new Event());
        }
        
        $category = new EventCategory();
        $category->commit();
        
        $this->eventChannel->builder($description, $name, $events, $category);
        
        $this->assertEquals($description, $this->eventChannel->getDescription(), "Description is not equal");
        $this->assertEquals($name, $this->eventChannel->getName(), "Name is not equal");
        $this->assertEquals($events, $this->eventChannel->getEvents(), "Events is not equal");
        $this->assertEquals($category, $this->eventChannel->getCategory(), "Category is not equal");
    }

    public function testGetsSets() {
        $description = "Descr";
        $this->eventChannel->setDescription($description);
        $this->assertEquals($description, $this->eventChannel->getDescription(), "Description is not equal");

        $name = "Name";
        $this->eventChannel->setName($name);
        $this->assertEquals($name, $this->eventChannel->getName(), "Name is not equal");

        $events = array();
        for ($i = 0; $i < 20; $i++) {
            array_push($events, new Event());
        }
        $this->eventChannel->setEvents($events);
        $this->assertEquals($events, $this->eventChannel->getEvents(), "Events is not equal");

        $category = new EventCategory();
        $category->document();
        $this->eventChannel->setCategory($category);
        $this->assertEquals($category, $this->eventChannel->getCategory(), "Category is not equal");
    }
    
    public function testAddEvent() {
        for ($i = 0; $i < 10; $i++)
        {
            $event = new Event();
            $this->eventChannel->addEvent($event);
        }

        $this->assertEquals(10, count($this->eventChannel->getEvents()), "Count of Events is not equal");
    }

    public function testGetEventsByDate() {
        $this->eventChannel->setEvents( array() );
        $matchingEvents = array();

        $startDate = new DateTime('2008-09-01');
        $endDate = new DateTime('2008-11-30');
        
        for ($i = 0; $i < 100; $i++)
        {
            $event = new Event();
            $mo  = rand()%12 + 1;
            $day = rand()%28 + 1;
            $dateStr = '2008-'.$mo.'-'.$day;
            $date = new DateTime($dateStr);
            $event->setDate($date);

            $this->eventChannel->addEvent($event);
            if ($date >= $startDate && $date <= $endDate ) {
                $matchingEvents[] = $event;
            }
        }

        $this->assertEquals(
            $matchingEvents,
            $this->eventChannel->getEventsByDate($startDate, $endDate),
            "Array of Events is not equal");
    }

    public function testGetEventsByUser() {
    }

    public function testHasNoEvents() {
        $this->eventChannel->setEvents(array());
        $this->assertTrue($this->eventChannel->hasNoEvents(), "Events should be empty");

        $this->eventChannel->addEvent(new Event());
        $this->assertFalse($this->eventChannel->hasNoEvents(), "Events should not be empty");
    }

    public function testEquality() {
        $this->eventChannel->setEvents(array());
        $key = new EventChannel();

        for ($i = 0; $i < 10; $i++)
        {
            $event = new Event();
            $mo  = rand()%12 + 1;
            $day = rand()%28 + 1;
            $dateStr = '2008-'.$mo.'-'.$day;
            $date = new DateTime($dateStr);
            $event->setDate($date);

            $this->eventChannel->addEvent($event);
            $key->addEvent($event);
        }

        $this->assertTrue($this->eventChannel == $key);

        $key->addEvent(new Event());

        $this->assertFalse($this->eventChannel == $key);
    }
}
?>
