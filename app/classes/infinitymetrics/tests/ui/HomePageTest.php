<?php
/**
 * Description of HomePageTest
 *
 * @author Marcello
 */
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class HomePageTest extends PHPUnit_Extensions_SeleniumTestCase {

    protected function setUp() {
        $this->setBrowser('*firefox');
        $this->setBrowserUrl('http://infinitymetrics.local.net/');
    }

    public function testTitle() {
        $this->open('http://infinitymetrics.local.net/');
        $this->assertTitleEquals('Infinity Metrics');
    }
}
?>

