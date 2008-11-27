<?php
require_once 'PHPUnit/Framework.php';
require_once 'infinitymetrics/tests/unit/user/InstructorTest.class.php';
require_once 'infinitymetrics/tests/unit/user/StudentTest.class.php';
require_once 'infinitymetrics/tests/unit/user/UserTest.class.php';

class AllUserUnitTests {
    
    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit Framework');

        $suite->addTestSuite('InstructorTest');
        $suite->addTestSuite('StudentTest');
        $suite->addTestSuite('UserTest');
        
        return $suite;
    }
}
?>