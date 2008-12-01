<?php

require_once('propel/Propel.php');
Propel::init("infinitymetrics/orm/config/om-conf.php");

require_once('PHPUnit/Framework.php');
require_once('infinitymetrics/controller/MetricsWorkspaceController.class.php');
require_once('infinitymetrics/controller/UserManagementController.class.php');

/**
 * Description of UC101Test: View Workspace
 *
 * @author Marilyne Mendolla
 * @author Andres Ardila
 * @author Marcello de Sales (after revision 369)
 */

class UC101Test extends PHPUnit_Framework_TestCase
{
    private $ws;
    private $user;
    private $institution;

    const USERNAME = 'johntheteacher';
    const TITLE = 'New Title';
    const DESCRIPTION = 'New Description';
    const ABBREVIATION = 'FAU';

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

    public function testViewExistingWorkspace() {
        $ws = MetricsWorkspaceController::retrieveWorkspace($this->ws->getWorkspaceId());
        $this->assertNotNull($ws, "The workspace existing workspace with id " . $this->ws->getWorkspaceId() . " was not
                                                                retrieved");
    }

    public function testViewInexistingWorkspace() {
        try {
            $ws = MetricsWorkspaceController::retrieveWorkspace('');
            $this->fail("There can't be any workspace with the identification empty");

        } catch (Exception $e) {
            $this->assertNotNull($e, "The exception must be not null");
        }
    }
    
    protected function tearDown() {
        $this->cleanUpAll();
    }
}
?>
