<?php

require_once('propel/Propel.php');
Propel::init("infinitymetrics/orm/config/om-conf.php");

require_once('PHPUnit/Framework.php');
require_once('infinitymetrics/controller/MetricsWorkspaceController.php');
require_once('infinitymetrics/model/user/User.class.php');
require_once('infinitymetrics/model/institution/Institution.class.php');

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

        if ($this->institution == NULL) {
            $this->institution = $this->user->getInstitution();
        }

        $this->ws = MetricsWorkspaceController::createWorkspace(
            $this->user->getJnUsername(),
            self::TITLE,
            self::DESCRIPTION
        );
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
                '999999', self::NEW_TITLE, self::NEW_DESCRIPTION
            );
        }
        catch (Exception $e) {
            return;
        }

        $this->fail('Inexistent PK WorkspaceId expects an exception');
    }
}
?>
