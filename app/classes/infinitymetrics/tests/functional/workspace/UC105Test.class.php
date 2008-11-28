<?php

require_once('propel/Propel.php');
Propel::init("infinitymetrics/orm/config/om-conf.php");

require_once('PHPUnit/Framework.php');
require_once('infinitymetrics/controller/MetricsWorkspaceController.php');
require_once('infinitymetrics/controller/UserManagementController.class.php');

/**
 * Description of UC105Test
 *
 * @author Andres Ardila
 * @author Marcello de Sales (after revision 369)
 */

class UC105Test extends PHPUnit_Framework_TestCase
{
    private $ws;
    private $user;
    private $institution;

    const USERNAME = 'johntheteacher';
    const TITLE = 'New Title';
    const DESCRIPTION = 'New Description';

    private function cleanUpAll() {
        PersistentUserXProjectPeer::doDeleteAll();
        PersistentUserPeer::doDeleteAll();
        PersistentInstitutionPeer::doDeleteAll();
        PersistentProjectPeer::doDeleteAll();
        PersistentWorkspacePeer::doDeleteAll();
    }

    public function setUp() {
        parent::setUp();
        $this->cleanUpAll();

        $this->institution = new Institution();
        $this->institution->setName('Florida Atlantic University');
        $this->institution->setAbbreviation("FAU");
        $this->institution->setCity('Boca Raton');
        $this->institution->setStateProvince('FL');
        $this->institution->setCountry('USA');
        $this->institution->save();

        $this->project = new PersistentProject();
        $this->project->setProjectJnName("ppm111");
        $this->project->setSummary("Project paticipation Metrics");
        $this->project->save();

        $this->user = UserManagementController::registerInstructor(self::USERNAME, "pass",
                "johnc@institution.edu", "John", "Instructor", $this->project->getProjectJnName(), true,
                $this->institution->getAbbreviation(), "ident-fau-chair");

        $this->ws = MetricsWorkspaceController::createWorkspace($this->user->getUserId(),
                                                                $this->project->getProjectJnName(),
                                                                self::TITLE, self::DESCRIPTION);
    }

    public function testChangeWorkspaceState() {
        $newState = 'ACTIVE';
        try {
            $this->ws = MetricsWorkspaceController::changeWorkspaceState($this->ws->getWorkspaceId(), $newState);
            $this->assertNotNull($this->ws);
            $this->assertTrue($this->ws instanceof PersistentWorkspace);
            $this->assertEquals($newState, $this->ws->getState());
        
        } catch (Exception $e) {
            $this->fail('Change Workspace State failed: '.$e->getMessage());
        }
    }

    public function testExceptionEmptyParams() {
        try {
            $this->ws = MetricsWorkspaceController::changeWorkspaceState('', '');
            $this->fail('Empty parameters expect an exception');
        
        } catch (Exception $e) {
            $this->assertNotNull($e, "The exception must be not null");
        }
    }

    public function testExceptionInvalidNewState() {
        $newState = 'invalid_value';

        try {
            $this->ws = MetricsWorkspaceController::changeWorkspaceState($this->ws->getWorkspaceId(), $newState);
            $this->fail('Invalid state expects an exception');
            
        } catch (Exception $e) {
            $this->assertNotNull($e, "The exception must be not null");
        }
    }

    public function testExceptionInexistentWorkspaceId() {
        try {
            $this->ws = MetricsWorkspaceController::changeWorkspaceState('999999', 'PAUSED');
            $this->fail('Invalid state expects an exception');
            
        } catch (Exception $e) {
            $this->assertNotNull($e, "The exception must be not null");
        }
    }
}
?>
