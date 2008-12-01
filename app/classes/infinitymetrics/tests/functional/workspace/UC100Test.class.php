<?php

require_once('propel/Propel.php');
Propel::init("infinitymetrics/orm/config/om-conf.php");

require_once('PHPUnit/Framework.php');
require_once('infinitymetrics/controller/MetricsWorkspaceController.class.php');
require_once('infinitymetrics/controller/UserManagementController.class.php');

/**
 * Description of UC100Test
 *
 * @author Andres Ardila
 */

class UC100Test extends PHPUnit_Framework_TestCase
{
    private $ws;
    private $user;
    private $institution;
    private $project;

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
    }

    public function testWorkspaceCreation() {
        try {
            
            $description = 'New description';

            $this->ws = MetricsWorkspaceController::createWorkspace(
                $this->user->getUserId(), $this->project->getProjectJnName(), self::TITLE, self::DESCRIPTION
            );
            $this->assertNotNull($this->ws, "The metrics workspace was not created for the user");
            $this->assertEquals($this->ws, PersistentWorkspacePeer::retrieveByPK($this->ws->getPrimaryKey()),
                                                      "The metrics transient and persistent workspaces are different");
            $this->assertTrue($this->ws instanceof PersistentWorkspace, "The workspace instance is incorrect");
        }
        catch (Exception $e) {
            $this->fail('Workspace creation failed: ' . $e->getMessage());
        }
    }

    public function testExceptionEmptyParams() {
        try {
            $this->ws = MetricsWorkspaceController::createWorkspace('', '', '', '');
            $this->fail("Workspace can't created with empty values");
        }
        catch (InfinityMetricsException $ime) {
            //$error["fieldName"] = "error message"
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields, "The errors list should be populated");
            $this->assertNotNull($errorFields["userId"], "The error field for user identification must be given");
            $this->assertNotNull($errorFields["projectName"], "The error field for project identification must be given");
            $this->assertNotNull($errorFields["title"], "The error field for title must be given");
            $this->assertNotNull($errorFields["description"], "The error field for the description must be given");
        }
    }

    public function testExceptionInexistentUserId() {
        try {
            $this->ws = MetricsWorkspaceController::createWorkspace('99999', $this->project->getProjectJnName(),
                                                                    self::TITLE, self::DESCRIPTION);

            $this->fail('The workspace was created for a non-existing user id 99999');
        } catch (InfinityMetricsException $ime) {
            //$error["fieldName"] = "error message"
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields, "The errors list should be populated");
        }
    }

    protected function tearDown() {
        $this->cleanUpAll();
    }
}
?>
