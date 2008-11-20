<?php

require_once('propel/Propel.php');
Propel::init("infinitymetrics/orm/config/om-conf.php");

require_once('PHPUnit/Framework.php');
require_once('infinitymetrics/controller/MetricsWorkspaceController.php');

/**
 * Description of UC101Test: View Workspace
 *
 * @author Marilyne Mendolla and Andres Ardila
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

    public function testViewWorkspace() {
        $this->assertEquals(
            PersistentUserPeer::retrieveByJNUsername(self::USERNAME),
            $this->user
        );
        $this->assertEquals(self::DESCRIPTION, $this->ws->getDescription());
        $this->assertEquals(self::TITLE, $this->ws->getTitle());
        $this->assertEquals('NEW', $this->ws->getState());
    }
}
?>
