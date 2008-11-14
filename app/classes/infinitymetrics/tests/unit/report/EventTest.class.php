<?php
require_once('PHPUnit/Framework.php');
require_once('infinitymetrics/model/report/Event.class.php');
require_once('infinitymetrics/model/user/User.class.php');


/**
 * Description of EventTestclass
 *
 * @author Andres Ardila and Marilyne Mendolla
 */
class EventTest extends PHPUnit_Framework_TestCase {

   protected $event;

   public function setUp() {
       $this->event = new Event();
   }

   public function builderTest() {
       $user = new User("Andres", "Ardila", "aardila");
       $date = new DateTime("2008-11-08");

       $this->event->builder($user, $date);
       $this->assertEquals($user, $this->event->getUser(), "User is not equal");
       $this->assertEquals($date, $this->event->getDateObject(), "Date is not equal");

   }

   public function testGetsSets() {

       $user = new User("Marilyne", "Mendolla", "mmendoll");
       $this->event->setUser($user);
       $this->assertEquals($user, $this->event->getUser(), "User is not equal");

       $date = new DateTime("2009-01-25");
       $this->event->setDate($date);
       $this->assertEquals($date, $this->event->getDateObject(), "Date is not equal");

       $this->assertEquals($date->format("Y-m-d"), $this->event->getDateString(), "Date string is not equal");

   }
}
?>
