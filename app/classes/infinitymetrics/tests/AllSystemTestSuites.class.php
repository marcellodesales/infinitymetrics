<?php
require_once 'PHPUnit/Framework.php';
require_once 'infinitymetrics/tests/system/AllUserSystemTests.class.php';
require_once 'infinitymetrics/tests/system/AllCustomEventSystemTests.class.php';

class AllSystemTests {
    
    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit');

        $suite->addTest(AllUserComponentSystemTests::suite());
        $suite->addTest(AllCustomEventComponentSystemTests::suite());
        
        return $suite;
    }
}
?>