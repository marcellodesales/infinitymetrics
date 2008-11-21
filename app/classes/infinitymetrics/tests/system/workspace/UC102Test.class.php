<?php

require_once('propel/Propel.php');
Propel::init("infinitymetrics/orm/config/om-conf.php");

require_once('PHPUnit/Framework.php');
require_once('infinitymetrics/controller/MetricsWorkspaceController.php');
require_once('infinitymetrics/model/user/User.class.php');

/**
 * Description of UC102Test: View Workspace Collection
 *
 * @author Marilyne Mendolla and Andres Ardila
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

    public function setUp() {
        parent::setUp();

        $this->user = PersistentUserPeer::retrieveByJNUsername(self::USERNAME);

        if ($this->user == NULL )
        {
            $this->user = new User();
            $this->user->setJnUsername(self::USERNAME);
            $this->user->setJnPassword('password');
            $this->user->setFirstName('John');
            $this->user->setLastName('Instructor');
            $this->user->setEmail('johnc@institution.edu');
            $this->user->setType('I');

            $criteria = new Criteria();
            $criteria->add(PersistentInstitutionPeer::ABBREVIATION, self::ABBREVIATION);

            $institutions = PersistentInstitutionPeer::doSelect($criteria);

            if($institutions == NULL)
            {
                $this->institution = new Institution();
                $this->institution->setAbbreviation(self::ABBREVIATION);
                $this->institution->setCity('Boca Raton');
                $this->institution->setCountry('USA');
                $this->institution->setName('Florida Atlantic University');
                $this->institution->setStateProvince('FL');
                $this->institution->save();
            }
            else {
                $this->institution = $institutions[0];
            }

            $this->user->setInstitution($this->institution);
            $this->user->save();
        }

        if ($this->institution == NULL) {
            $this->institution = $this->user->getInstitution();
        }
        
        for ($i = 0; $i < 5; $i++) {
            $ws = MetricsWorkspaceController::createWorkspace(
                $this->user->getJnUsername(),
                self::TITLE.$i,
                self::DESCRIPTION.$i
            );
        }

        for ($j = 0; $j < 5; $j++) {
            $user = new User();
            $user->setJnUsername('JNusername'.rand());
            $user->setJnPassword('password');
            $user->setFirstName('TestFName'.$j);
            $user->setLastName('TestLName'.$j);
            $user->setEmail('user'.$j.'@domain.edu');
            $user->setType('I');
            $user->setInstitution($this->institution);
            $user->save();

            $ws = MetricsWorkspaceController::createWorkspace(
                $user->getJnUsername(),
                'Shared '.self::TITLE.$j,
                'Shared '.self::DESCRIPTION.$j
            );
            
            MetricsWorkspaceController::shareWorkspace(
                $ws->getWorkspaceId(),
                $this->user->getJnUsername()
            );
        }
    }

    public function testRetreiveWorkspaceCollection() {
        try {
            $this->wsCollection = MetricsWorkspaceController::retrieveWorkspaceCollection(
                $this->user->getJnUsername() );
            
            $this->assertNotNull($this->wsCollection);
            $this->assertTrue(is_array($this->wsCollection));
            $this->assertArrayHasKey('OWN', $this->wsCollection);
            $this->assertArrayHasKey('SHARED', $this->wsCollection);

            foreach ($this->wsCollection['OWN'] as $ws) {
                $this->assertTrue($ws instanceof PersistentWorkspace);
                $this->assertEquals(
                    PersistentWorkspacePeer::retrieveByPK($ws->getPrimaryKey()),
                    $ws
                );    
            }
            
            foreach ($this->wsCollection['SHARED'] as $ws) {
                $this->assertTrue(
                    $ws->isSharedWithUser(
                        $ws->getWorkspaceId(), $this->user->getUserId())
                );
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
