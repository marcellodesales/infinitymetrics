<?php
require_once 'PHPUnit/Framework.php';
require_once 'infinitymetrics/util/ArrayList.class.php';
/**
 * Tests for the ArrayList class.
 *
 * @author Marcello de Sales (marcello.sales@gmail.com) Nov 09, 2008 22:34 PST
 */
class DateUtiltTest extends PHPUnit_Framework_TestCase {

    public function testPublicationDateHumanReadable() {
        date_default_timezone_set('GMT');

        $a = "Tue, 25 Nov 2008 11:00:00 GMT";
        $b = DateTimeUtil::getPublicationDateHumanReadable($a);
        $a = "2008-11-25T19:00:00Z";
        $b = DateTimeUtil::getMySQLDateAndTime($a);
    }

    public function testMySQLDateTimeFormat() {
        date_default_timezone_set('GMT');
        
        $a = "Tue, 25 Nov 2008 11:00:00 GMT";
        $b = DateTimeUtil::getPublicationDateHumanReadable($a);
        $a = "2008-11-25T19:00:00Z";
        $b = DateTimeUtil::getMySQLDateAndTime($a);
    }

    public function testMySQLDateFormat() {
        date_default_timezone_set('GMT');
        
        $a = "Tue, 25 Nov 2008 11:00:00 GMT";
        $b = DateTimeUtil::getPublication($a);
        $a = "2008-11-25T19:00:00Z";
        $b = DateTimeUtil::getMySQLDate($a);
    }

}
?>
