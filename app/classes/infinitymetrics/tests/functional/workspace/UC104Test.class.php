<?php

require_once('propel/Propel.php');
Propel::init("infinitymetrics/orm/config/om-conf.php");

require_once('PHPUnit/Framework.php');
require_once('infinitymetrics/controller/MetricsWorkspaceController.php');
require_once('infinitymetrics/controller/UserManagementController.class.php');

/**
 * Description of UC104Test: Share Workspace
 *
 * @author Marilyne Mendolla and Andres Ardila
 */

class UC104Test extends PHPUnit_Framework_TestCase
{
    const USERNAME1 = 'mmendoll';
    const USERNAME2 = 'aardila';
    const TITLE = 'New Title';
    const DESCRIPTION = 'New Description';

    private $ws;
    private $user1;
    private $user2;
    private $institution;
    private $project;

    private function cleanUpAll() {
        PersistentUserXProjectPeer::doDeleteAll();
        PersistentUserPeer::doDeleteAll();
        PersistentInstitutionPeer::doDeleteAll();
        PersistentProjectPeer::doDeleteAll();
        PersistentWorkspacePeer::doDeleteAll();
        PersistentWorkspaceSharePeer::doDeleteAll();
    }

    public function setUp() {
        parent::setUp();
        $this->cleanUpAll();

        $this->institution = PersistentInstitutionPeer::retrieveByAbbreviation("FAU");
        if($this->institution == NULL)
        {
            $this->institution = new PersistentInstitution();
            $this->institution->setAbbreviation('FAU');
            $this->institution->setCity('Boca Raton');
            $this->institution->setCountry('USA');
            $this->institution->setName('Florida Atlantic University');
            $this->institution->setStateProvince('FL');
            $this->institution->save();
        }

        $this->project = new PersistentProject();
        $this->project->setProjectJnName("ppm111");
        $this->project->setSummary("Project paticipation Metrics");
        $this->project->save();

        $this->user1 = UserManagementController::registerInstructor(self::USERNAME1, "password",
                "mmendoll@institution.edu", "marilyne", "mendolla", $this->project->getProjectJnName(), true,
                $this->institution->getAbbreviation(), "school-id-edu");

        $this->user2 = UserManagementController::registerInstructor(self::USERNAME2, "password",
                "aardila@institution.edu", "andres", "ardila", $this->project->getProjectJnName(), true,
                $this->institution->getAbbreviation(), "school-id-edu");

        //create the workspace for user 1 to be share with user 2
        $this->ws = MetricsWorkspaceController::createWorkspace($this->user1->getUserId(),
                                                                $this->project->getProjectJnName(),
                                                                self::TITLE, self::DESCRIPTION);
    }

    public function testShareWorkspace() {
        try {
            $wss = MetricsWorkspaceController::shareWorkspace($this->ws->getWorkspaceId(),
                                                              $this->user2->getUserId());
            $this->assertNotNull($this->ws->getWorkspaceId(), $wss->getWorkspaceId(), "The share of the metrics
                                                                    workspace incorrect with the workspace id.");
            $this->assertEquals($this->user2->getUserId(), $wss->getUserId(), "The share of the metrics workspace
                                                                    id is incorrect with the user id.");
        }  catch(Exception $e) {
            $this->fail('Share Workspace failed: ' . $e->getMessage());
        }
    }

    public function testExemptionWorkspaceIDEmpty() {
        try {
            MetricsWorkspaceController::shareWorkspace("", $this->user2->getJnUsername() );
            $this->fail("Workspace shared with non-existing user");

        } catch (Exception $e) {
            $this->assertNotNull($e, "The exception must be not null");
        }
    }

    public function testExemptionUserJnUsernameWithWhomToShareWorkspaceEmpty() {
        try {
            MetricsWorkspaceController::shareWorkspace($this->ws->getWorkspaceId(), "");
            $this->fail("Workspace shared with non-existing user");
        
        } catch (Exception $e) {
            $this->assertNotNull($e, "The exception must be not null");
        }
    }
    
    public function testExemptionInexistentWorkspaceId() {
        try {
            MetricsWorkspaceController::shareWorkspace('99999', $this->user2->getJnUsername() );
            $this->fail("Workspace shared with non-existing workspace id 99999");
            
        } catch (Exception $e) {
            $this->assertNotNull($e, "The exception must be not null");
        }
    }

    public function testExemptionInexistentJnUsernameWithWhomToShareWorkspaceEmpty() {
        try {
            MetricsWorkspaceController::shareWorkspace($this->ws->getWorkspaceId(), 'asdfgjh' );
            $this->fail("Workspace shared with non-existing user asdfg");

        } catch (Exception $e) {
            $this->assertNotNull($e, "The exception must be not null");
        }
    }

    public function testExemptionWorkspaceAlreadyBeingShared() {
        try {
            MetricsWorkspaceController::shareWorkspace($this->ws->getWorkspaceId(), $this->user2->getJnUsername() );
            MetricsWorkspaceController::shareWorkspace($this->ws->getWorkspaceId(), $this->user2->getJnUsername() );
            $this->fail("Workspace can't be shared twice for the same user");
        
        } catch (Exception $e) {
            $this->assertNotNull($e, "The exception must be not null");
        }
    }
}

?>
