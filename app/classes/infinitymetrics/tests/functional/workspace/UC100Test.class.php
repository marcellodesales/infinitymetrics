<?php

require_once('PHPUnit/Framework.php');
require_once('infinitymetrics/controller/MetricsWorkspaceController.php');
require_once('infinitymetrics/model/InfinityMetricsException.class.php');

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

         $users = UserPeer::doSelect($criteria);

         $this->user = $users[0];

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

            $instructor = new Instructors();
            $instructor->setPrimaryKey($this->user->getPrimaryKey());
            $instructor->setInstitutionId(1);
            $instructor->save();
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
            $this->assertEquals(
                WorkspacePeer::retrieveByPK( $this->ws->getPrimaryKey() ),
                $this->ws
            );
            $this->assertTrue($this->ws instanceof Workspace);
        }
        catch (Exception $e) {
            $this->fail('Workspace creation failed' . $e);
        }
    }
}
?>
