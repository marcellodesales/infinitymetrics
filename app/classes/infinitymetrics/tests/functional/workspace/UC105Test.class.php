<?php

require_once('PHPUnit/Framework.php');
require_once('infinitymetrics/controller/MetricsWorkspaceController.php');
require_once('infinitymetrics/model/InfinityMetricsException.class.php');

/**
 * Description of UC101class
 *
 * @author Andres Ardila
 */
class UC105Test extends PHPUnit_Framework_TestCase
{
    private $ws;

    const WORKSPACE_ID = 2;

    public function setUp() {
        parent::setUp();

        $this->ws = WorkspacePeer::retrieveByPK(self::WORKSPACE_ID);
    }

    public function testChangeWorkspaceState() {
        $newState = 'ACTIVE';

        $this->ws = MetricsWorkspaceController::changeWorkspaceState(
            $this->ws->getWorkspaceId(), $newState
        );
        $this->assertNotNull($this->ws);
        $this->assertTrue($this->ws instanceof Workspace);
        $this->assertEquals($newState, $this->ws->getState());
    }

    public function testInvalidSParams() {
        try {
            $this->ws = MetricsWorkspaceController::changeWorkspaceState('', '');
        }
        catch (InvalidArgumentException $e) {
            return;
        }
        
        $this->fail('Workspace state update failed' . $e);
    }

    public function testInvalidNewState() {
        $newState = 'invalid_value';

        try {
            $this->ws = MetricsWorkspaceController::changeWorkspaceState(
                $this->ws->getWorkspaceId(), $newState
            );
        }
        catch (InvalidArgumentException $e) {
            $this->fail('Workspace state update failed' . $e);
        }
    }
}
?>
