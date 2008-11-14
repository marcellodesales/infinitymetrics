<?php

require_once('PHPUnit/Framework.php');
require_once('infinitymetrics/controller/MetricsWorkspaceController.php');

/**
 * Description of UC100Test
 *
 * @author Andres Ardila
 */
class UC100Test extends PHPUnit_Framework_TestCase
{
    private $ws;
    private $user;

    const USERNAME = 'johntheteacher';

    public function setUp() {
        parent::setUp();

        $criteria = new Criteria();
        $criteria->add(UserPeer::JN_USERNAME, self::USERNAME);

        $this->user = UserPeer::doSelect($criteria);

        if ($this->user == NULL )
        {
            $this->user = new User();
            $this->user->setJnUsername(self::USERNAME);
            $this->user->setJnPassword('password');
            $this->user->setFirstName('John');
            $this->user->setLastName('Instructor');
            $this->user->setEmail('johnc@institution.edu');
            $this->user->setType('INSTRUCTOR');

            $this->user->save();
        }
    }

    public function testWorkspaceCreation() {
        try {
            $title = 'New title';
            $description = 'New description';

            $this->ws = MetricsWorkspaceController::createWorkspace(
                $this->user->getUserId(), $title, $description
            );
            $this->assertNotNull($this->ws);
            $this->assertTrue($this->ws instanceof Workspace);
        }
        catch (Exception $e) {
            $this->fail('Workspace creation failed' . $e);
        }
    }
}
?>
