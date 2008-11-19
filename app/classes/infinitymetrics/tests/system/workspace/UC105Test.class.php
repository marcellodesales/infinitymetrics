<?php

require_once('propel/Propel.php');
Propel::init("infinitymetrics/orm/config/om-conf.php");

require_once('PHPUnit/Framework.php');
require_once('infinitymetrics/controller/MetricsWorkspaceController.php');

/**
 * Description of UC105Test
 *
 * @author Andres Ardila
 */

class UC105Test extends PHPUnit_Framework_TestCase
{
    private $ws;
    private $user;

    const USERNAME = 'johntheteacher';
    const TITLE = 'New Title';
    const DESCRIPTION = 'New Description';

    public function setUp() {
        parent::setUp();

        $this->user = PersistentUserPeer::retrieveByJNUsername(self::USERNAME);

        if ($this->user == NULL )
        {
            $this->user = new User();
            $this->user->setJnUsername(self::USERNAME);
            $this->user->setJnPassword('password');
            $this->user->setFirstName('John');
            $this->user->setLastName('Instructor');
            $this->user->setEmail('johnc@institution.edu');
            $this->user->setType('I');

            $institution = PersistentInstitutionPeer::retrieveByPK(1);

            if($institution == NULL)
            {
                $institution = new Institution();
                $institution->setAbbreviation('FAU');
                $institution->setCity('Boca Raton');
                $institution->setCountry('USA');
                $institution->setName('Florida Atlantic University');
                $institution->setStateProvince('FL');
                $institution->save();
            }

            $this->user->setInstitution($institution);
            $this->user->save();
        }

        $workspaces = $this->user->getWorkspaces();

        if ($workspaces != NULL) {
            $this->ws = $workspaces[0];
        }
        else {
            $this->ws = MetricsWorkspaceController::createWorkspace(
                    $this->user1->getUserId(), $title, $description
            );
        }
    }

    public function testChangeWorkspaceState() {
        $newState = 'ACTIVE';

        try {
            $this->ws = MetricsWorkspaceController::changeWorkspaceState(
                $this->ws->getWorkspaceId(), $newState
            );
            $this->assertNotNull($this->ws);
            $this->assertTrue($this->ws instanceof PersistentWorkspace);
            $this->assertEquals($newState, $this->ws->getState());
        }
        catch (Exception $e) {
            $this->fail('Change Workspace State failed: '.$e->getMessage());
        }
    }

    public function testExceptionEmptyParams() {
        try {
            $this->ws = MetricsWorkspaceController::changeWorkspaceState('', '');
        }
        catch (Exception $e) {
            return;
        }
        
        $this->fail('Empty parameters expect an exception');
    }

    public function testExceptionInvalidNewState() {
        $newState = 'invalid_value';

        try {
            $this->ws = MetricsWorkspaceController::changeWorkspaceState(
                $this->ws->getWorkspaceId(), $newState
            );
        }
        catch (Exception $e) {
            return;
        }

        $this->fail('Invalid state expects an exception');
    }

    public function testExceptionInexistentWorkspaceId() {
        try {
            $this->ws = MetricsWorkspaceController::changeWorkspaceState(
                '999999', 'PAUSED'
            );
        }
        catch (Exception $e) {
            return;
        }

        $this->fail('Inexistent PK WorkspaceID expects an exception');
    }
}
?>
