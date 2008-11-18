<?php

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

    const WORKSPACE_ID = 2;

    public function setUp() {
        parent::setUp();

        $this->ws = PersistentWorkspacePeer::retrieveByPK(self::WORKSPACE_ID);
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
