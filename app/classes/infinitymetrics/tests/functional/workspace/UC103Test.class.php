<?php

require_once('PHPUnit/Framework.php');
require_once('infinitymetrics/controller/MetricsWorkspaceController.php');

/**
 * Description of UC103Test
 *
 * @author Andres Ardila
 */

class UC103Test extends PHPUnit_Framework_TestCase
{
    private $ws;

    const WS_ID = 2;
    const TITLE = "Updated Title";
    const DESCRIPTION = "Updated Description";

    public function setUp() {
        parent::setUp();

        $this->ws = PersistentWorkspacePeer::retrieveByPK(self::WS_ID);
    }

    public function testUpdateProfile() {
        try {
            $this->ws = MetricsWorkspaceController::UpdateWorkspaceProfile(
                self::WS_ID, self::TITLE, self::DESCRIPTION
            );

            $this->assertNotNull($this->ws);
            $this->assertTrue($this->ws instanceof PersistentWorkspace);
            $this->assertEquals(self::TITLE, $this->ws->getTitle());
            $this->assertEquals(self::DESCRIPTION, $this->ws->getDescription());
        }
        catch (Exception $e) {
            $this->fail('Exception occurred: '.$e->getMessage());
        }
    }

    public function testExceptionEmptyArgs() {
        try {
            $this->ws = MetricsWorkspaceController::UpdateWorkspaceProfile('', '', '');
        }
        catch (Exception $e) {
            return;
        }

        $this->fail('Expected exception calling method with no arguments', $e->getMessage());
    }

    public function testExceptionInexistentWorkspaceId() {
        try {
            $this->ws = MetricsWorkspaceController::UpdateWorkspaceProfile(
                '999999', self::TITLE, self::DESCRIPTION
            );
        }
        catch (Exception $e) {
            return;
        }

        $this->fail('Inexistent PK WorkspaceId expects an exception');
    }
}
?>
