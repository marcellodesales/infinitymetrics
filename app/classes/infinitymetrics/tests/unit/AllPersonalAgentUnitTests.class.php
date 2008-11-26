<?php
require_once 'PHPUnit/Framework.php';
require_once 'infinitymetrics/tests/unit/user/agent/PersonalAgentTest.class.php';
require_once 'infinitymetrics/tests/unit/user/agent/FullnameJNUsernameInMemoryCacheTest.class.php';
// ...

class AllPersonalAgentComponentUnitTests {
    
    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit Framework');

        $suite->addTestSuite('FullnameJNUsernameInMemoryCacheTest');
        $suite->addTestSuite('PersonalAgentTest');

        return $suite;
    }
}
?>