<?php
require_once 'PHPUnit/Framework.php';
require_once 'infinitymetrics/tests/unit/util/AbstractCollectionTest.class.php';
require_once 'infinitymetrics/tests/unit/util/ArrayListTest.class.php';
require_once 'infinitymetrics/tests/unit/util/DateTimeUtilTest.class.php';

class AllUtilityLibraryUnitTests {
    
    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit Framework');

        $suite->addTestSuite('AbstractCollectionTest');
        $suite->addTestSuite('ArrayListTest');
        $suite->addTestSuite('DateTimeUtilTest');
        
        return $suite;
    }
}
?>