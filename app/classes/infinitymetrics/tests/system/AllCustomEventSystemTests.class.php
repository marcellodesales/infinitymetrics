<?php
require_once 'PHPUnit/Framework.php';
require_once 'infinitymetrics/tests/system/cetracker/CustomEventSystemTest.class.php';
// ...

class AllCustomEventComponentSystemTests {
    
    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit Framework');

        $suite->addTestSuite('CustomEventSystemTest');

        return $suite;
    }
}
?>