<?php
require_once 'PHPUnit/Framework.php';
require_once 'infinitymetrics/tests/unit/AllUtilityLibraryUnitTests.class.php';
require_once 'infinitymetrics/tests/unit/AllPersonalAgentUnitTests.class.php';

class AllUnitTests {
    
    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit');

        $suite->addTest(AllUtilityLibraryUnitTests::suite());
//        $suite->addTest(AllPersonalAgentComponentUnitTests::suite());

        return $suite;
    }
}
?>