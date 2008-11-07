<?php
require_once 'PHPUnit/Framework.php';
require_once 'infinitymetrics/model/user/User.class.php';
require_once 'infinitymetrics/model/workspace/MetricsWorkspace.class.php';
/**
 * Description of UserTestclass
 *
 * @author Marcello
 */
class UserTest extends PHPUnit_Framework_TestCase {

    private $user;

    protected function setUp() {
        parent::setUp();
        $this->user = new User("Marcello");
    }

    public function testUserCreation() {
        $this->assertEquals("Marcello", $this->user->getName(), "The value of the name is incorrect");
    }

    public function testMetricsWorkspace() {
        $desc = "Metrics descr";
        $title = "Metrics Title";
        $mw = MetricsWorkspace::builder($this->user, $desc, $title);

        $this->assertEquals($desc, $mw->getDescription(), "The value of the description is incorrect");
        $this->assertEquals($this->user, $mw->getCreator(), "The value of the user is incorrect");
        $this->assertEquals($title, $mw->getTitle(), "The value of the title is incorrect");
    }

    protected function tearDown() {
        $this->user = null;
    }
}
?>
