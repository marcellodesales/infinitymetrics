<?php

require_once('PHPUnit/Framework.php');
require_once('infinitymetrics/controller/MetricsWorkspaceController.php');

/**
 * Description of UC102Test: View Workspace Collection
 *
 * @author Marilyne Mendolla and Andres Ardila
 */

class UC102Test extends PHPUnit_Framework_TestCase
{
    private $ws;
    private $wsCollection = array();
    private $user;

    const USERNAME = 'johntheteacher';
    const TITLE = 'New Title';
    const DESCRIPTION = 'New Description';

    public function setUp() {
        parent::setUp();

        $this->user = PersistentUserPeer::retrieveByJNUsername(self::USERNAME);

        if ($this->user == NULL )
        {
            $this->user = new PersistentUser();
            $this->user->setJnUsername(self::USERNAME);
            $this->user->setJnPassword('password');
            $this->user->setFirstName('John');
            $this->user->setLastName('Instructor');
            $this->user->setEmail('johnc@institution.edu');
            $this->user->setType('I');
            $institution = PersistentInstitutionPeer::retrieveByPK(1);
            if($institution == NULL)
            {
                $institution = new PersistentInstitution();
                $institution->setAbbreviation('FAU');
                $institution->setCity('Boca Raton');
                $institution->setCountry('USA');
                $institution->setName('Florida Atlantic University');
                $institution->setStateProvince('FL');
                $institution->save();
            }
            $this->user->setInstitutionId($institution->getInstitutionId());
            $this->user->save();
        }


    }

    public function testRetreiveWorkspaceCollection() {

        try {

            $this->wsCollection = MetricsWorkspaceController::retrieveWorkspaceCollection(
                $this->user->getUserId() );
            
            $this->assertNotNull($this->wsCollection);

            foreach ($this->wsCollection['OWN'] as $ws) {
                $this->assertTrue($ws instanceof PersistentWorkspace);
                $this->assertEquals(
                    PersistentWorkspacePeer::retrieveByPK($ws->getPrimaryKey() ),
                    $ws
                );
                
            }
            foreach ($this->wsCollection['SHARED'] as $ws) {
                $this->assertEquals(
                    PersistentWorkspacePeer::retrieveByPK($this->$ws->getPrimaryKey() ),
                    $this->ws
                );
                $this->assertTrue($this->ws instanceof PersistentWorkspace);
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
