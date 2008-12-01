<?php

require_once('propel/Propel.php');
Propel::init("infinitymetrics/orm/config/om-conf.php");

require_once('PHPUnit/Framework.php');
require_once('infinitymetrics/controller/MetricsWorkspaceController.class.php');
require_once('infinitymetrics/controller/UserManagementController.class.php');
require_once('infinitymetrics/model/institution/Instructor.class.php');

/**
 * Description of UC102Test: View Workspace Collection
 *
 * @author Marilyne Mendolla
 * @author Andres Ardila
 * @author Marcello de Sales <marcello.sales@gmail.com> updates after revision 369
 */

class UC102Test extends PHPUnit_Framework_TestCase
{
    private $wsCollection = array();
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
            
        for ($i = 0; $i < 5; $i++) {
            $ws = MetricsWorkspaceController::createWorkspace($this->user->getUserId(),
                                                              $this->project->getProjectJnName(),
                                                              self::TITLE.$i, self::DESCRIPTION.$i);
        }

        for ($j = 0; $j < 5; $j++) {
            $instr = UserManagementController::registerInstructor('JNusername'.rand(), 'password',
                'user'.$j.'@domain.edu', 'TestFName'.$j, 'TestLName'.$j, $this->project->getProjectJnName(), true,
                $this->institution->getAbbreviation(), "ident-fau-chair");

            $ws = MetricsWorkspaceController::createWorkspace($instr->getUserId(), $this->project->getProjectJnName(),
                                                                 'Shared '.self::TITLE.$j,
                                                                 'Shared '.self::DESCRIPTION.$j);
            MetricsWorkspaceController::shareWorkspace($ws->getWorkspaceId(), $instr->getUserId());
        }
    }

    public function testRetreiveWorkspaceCollection() {
        try {
            $this->wsCollection = MetricsWorkspaceController::retrieveWorkspaceCollection($this->user->getUserId());
            
            $this->assertNotNull($this->wsCollection);
            $this->assertTrue(is_array($this->wsCollection));
            $this->assertArrayHasKey('OWN', $this->wsCollection);
            $this->assertArrayHasKey('SHARED', $this->wsCollection);

            foreach ($this->wsCollection['OWN'] as $ws) {
                $this->assertTrue($ws instanceof PersistentWorkspace);
                $this->assertEquals(PersistentWorkspacePeer::retrieveByPK($ws->getPrimaryKey()), $ws);
            }
            
            foreach ($this->wsCollection['SHARED'] as $ws) {
                $this->assertTrue( $ws->isSharedWithUser($this->user->getUserId()) );
                $this->assertTrue($ws instanceof PersistentWorkspace);
            }
        }
        catch (Exception $e) {
            $this->fail('Retrieve Workspace Collection failed: ' . $e->getMessage());
        }
    }

    public function testExemptionUserIDEmpty() {
        try {
            $this->wsCollection = MetricsWorkspaceController::retrieveWorkspaceCollection('');
        }
        catch (Exception $e) {
            return;
        }

        $this->fail('Empty params expects an exception');
    }

    public function testExemptionInexistentUserId() {
        try {
            $this->wsCollection = MetricsWorkspaceController::retrieveWorkspaceCollection(
                '99999');
        }
        catch (Exception $e) {
            return;
        }

        $this->fail('Inexistent UserId expects an exception');
    }
}
?>
