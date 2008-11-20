<?php

require_once('propel/Propel.php');
Propel::init("infinitymetrics/orm/config/om-conf.php");

require_once('PHPUnit/Framework.php');
require_once('infinitymetrics/controller/MetricsWorkspaceController.php');

/**
 * Description of UC100Test
 *
 * @author Andres Ardila
 */

class UC100Test extends PHPUnit_Framework_TestCase
{
    private $ws;
    private $user;

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
                $institution = new Institution();
                $institution->setAbbreviation(self::ABBREVIATION);
                $institution->setCity('Boca Raton');
                $institution->setCountry('USA');
                $institution->setName('Florida Atlantic University');
                $institution->setStateProvince('FL');
                $institution->save();
            }
            else {
                $institution = $institutions[0];
            }

            $this->user->setInstitution($institution);
            $this->user->save();
        }
    }

    public function testWorkspaceCreation() {
        try {
            
            $description = 'New description';

            $this->ws = MetricsWorkspaceController::createWorkspace(
                $this->user->getJnUsername(), self::TITLE, self::DESCRIPTION
            );
            $this->assertNotNull($this->ws);
            $this->assertEquals(
                PersistentWorkspacePeer::retrieveByPK( $this->ws->getPrimaryKey() ),
                $this->ws
            );
            $this->assertTrue($this->ws instanceof PersistentWorkspace);
        }
        catch (Exception $e) {
            $this->fail('Workspace creation failed: ' . $e->getMessage());
        }
    }

    public function testExceptionEmptyParams() {
        try {
            $this->ws = MetricsWorkspaceController::createWorkspace('', '', '');
        }
        catch (Exception $e) {
            return;
        }

        $this->fail('Empty params expects an exception');
    }

    public function testExceptionInexistentUserId() {
        try {
            $this->ws = MetricsWorkspaceController::createWorkspace(
                '99999', self::TITLE, self::DESCRIPTION
            );
        }
        catch (Exception $e) {
            return;
        }

        $this->fail('Inexistent UserId expects an exception');
    }
}
?>
