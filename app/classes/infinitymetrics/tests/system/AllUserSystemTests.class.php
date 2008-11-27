<?php
require_once 'PHPUnit/Framework.php';
require_once 'infinitymetrics/tests/system/user/StudentSystemTest.class.php';
require_once 'infinitymetrics/tests/system/user/InstructorSystemTest.class.php';
require_once 'infinitymetrics/tests/system/user/UserSystemTest.class.php';
// ...

class AllUserComponentSystemTests {
    
    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit Framework');

        $suite->addTestSuite('UserSystemTest');
        $suite->addTestSuite('StudentSystemTest');
        $suite->addTestSuite('InstructorSystemTest');

        return $suite;
    }
}
?>