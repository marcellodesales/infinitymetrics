<?php

require_once('propel/Propel.php');
Propel::init("infinitymetrics/orm/config/om-conf.php");

require_once('PHPUnit/Framework.php');
require_once('infinitymetrics/controller/MetricsWorkspaceController.class.php');
require_once('infinitymetrics/controller/UserManagementController.class.php');

/**
 * Description of UC103Test
 *
 * @author Andres Ardila
 */

class UC103Test extends PHPUnit_Framework_TestCase
{
    private $ws;
    private $user;
    private $institution;

    const USERNAME = 'johntheteacher';
    const ABBREVIATION = 'FAU';
    const TITLE = 'New Title';
    const DESCRIPTION = 'New Description';
    const NEW_TITLE = "Updated Title";
    const NEW_DESCRIPTION = "Updated Description";

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

    public function testUpdateProfile() {
        try {
            $this->ws = MetricsWorkspaceController::UpdateWorkspaceProfile(
                $this->ws->getWorkspaceId(), self::NEW_TITLE, self::NEW_DESCRIPTION
            );

            $this->assertNotNull($this->ws);
            $this->assertTrue($this->ws instanceof PersistentWorkspace);
            $this->assertEquals(self::NEW_TITLE, $this->ws->getTitle());
            $this->assertEquals(self::NEW_DESCRIPTION, $this->ws->getDescription());

        } catch (Exception $e) {
            $this->fail('Exception occurred: '.$e->getMessage());
        }
    }

    public function testExceptionEmptyArgs() {
        try {
            $this->ws = MetricsWorkspaceController::UpdateWorkspaceProfile('', '', '');
            $this->fail('Empty parameters expect an exception');
            
        } catch (Exception $e) {
            $this->assertNotNull($e, "The exception must be not null");
        }
    }

    public function testExceptionInexistentWorkspaceId() {
        try {
            $this->ws = MetricsWorkspaceController::UpdateWorkspaceProfile('999999', self::NEW_TITLE,
                                                                           self::NEW_DESCRIPTION);
            $this->fail('Empty parameters expect an exception');
        } catch (Exception $e) {
            $this->assertNotNull($e, "The exception must be not null");
        }
    }
}
?>
