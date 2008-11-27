<?php
require_once 'PHPUnit/Framework.php';
require_once 'infinitymetrics/util/DateTimeUtil.class.php';
/**
 * Tests for the ArrayList class.
 *
 * @author Marcello de Sales (marcello.sales@gmail.com) Nov 09, 2008 22:34 PST
 */
class DateTimeUtilTest extends PHPUnit_Framework_TestCase {

    public function testHumanReadableDateTimeToMySQLDateFormat() {
//        date_default_timezone_set('PST');
        $this->assertEquals("2008-11-20", DateTimeUtil::getMySQLDate("Thu, 20 Nov 2008 10:42:35 GMT"),
                    "Time should be the same on the same time timezone (PST compared to GMT)");
        $this->assertEquals("2008-11-19", DateTimeUtil::getMySQLDate("Thu, 20 Nov 2008 07:42:35 GMT"),
                    "Timezone differences should decrease by one day (PST compared to GMT)");
    }

    public function testIsoDateTimeToMySQLDateFormat() {
        $this->assertEquals("2008-11-25", DateTimeUtil::getMySQLDate("2008-11-25T19:00:00Z"),
                    "Time should be the same on the same time timezone (PST compared to GMT)");
        $this->assertEquals("2008-11-24", DateTimeUtil::getMySQLDate("2008-11-25T01:00:00Z"),
                    "Timezone differences should decrease by one day (PST compared to GMT)");
    }

    public function testHumanReadableDateTimeToMySQLDateTimeFormat() {
//        date_default_timezone_set('PST');
        $this->assertEquals("2008-11-20 11:42:35", DateTimeUtil::getMySQLDateAndTime("Thu, 20 Nov 2008 19:42:35 GMT"),
                    "Time should be the same on the same time timezone (PST compared to GMT)");

        $this->assertEquals("2008-11-19 23:42:35", DateTimeUtil::getMySQLDateAndTime("Thu, 20 Nov 2008 07:42:35 GMT"),
                    "Timezone differences should decrease by one day (PST compared to GMT)");
    }

    public function testIsoDateTimeToMySQLDateTimeFormat() {
        $this->assertEquals("2008-11-25 11:00:00", DateTimeUtil::getMySQLDateAndTime("2008-11-25T19:00:00Z"),
                    "Time should be the same on the same time timezone (PST compared to GMT)");
        $this->assertEquals("2008-11-24 17:00:00", DateTimeUtil::getMySQLDateAndTime("2008-11-25T01:00:00Z"),
                    "Timezone differences should decrease by one day (PST compared to GMT)");
    }
}
?>
