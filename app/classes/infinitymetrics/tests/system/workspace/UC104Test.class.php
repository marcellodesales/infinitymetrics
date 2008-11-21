<?php

require_once('propel/Propel.php');
Propel::init("infinitymetrics/orm/config/om-conf.php");

require_once('PHPUnit/Framework.php');
require_once('infinitymetrics/controller/MetricsWorkspaceController.php');
require_once('infinitymetrics/model/user/User.class.php');

/**
 * Description of UC104Test: Share Workspace
 *
 * @author Marilyne Mendolla and Andres Ardila
 */

class UC104Test extends PHPUnit_Framework_TestCase
{
    private $ws;
    private $user1;
    private $user2;

    const USERNAME1 = 'mmendoll';
    const USERNAME2 = 'aardila';
    const TITLE = 'New Title';
    const DESCRIPTION = 'New Description';

    public function setUp() {
        parent::setUp();

        $this->user1 = PersistentUserPeer::retrieveByJNUsername(self::USERNAME1);

        if ($this->user1 == NULL )
        {
            $this->user1 = new User();
            $this->user1->setJnUsername(self::USERNAME1);
            $this->user1->setJnPassword('password');
            $this->user1->setFirstName('marilyne');
            $this->user1->setLastName('mendolla');
            $this->user1->setEmail('mmendoll@institution.edu');
            $this->user1->setType('I');
            
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
            $this->user1->setInstitution($institution);
            $this->user1->save();
        }

        $this->user2 = PersistentUserPeer::retrieveByJNUsername(self::USERNAME2);

        if ($this->user2 == NULL )
        {
            $this->user2 = new PersistentUser();
            $this->user2->setJnUsername(self::USERNAME2);
            $this->user2->setJnPassword('password');
            $this->user2->setFirstName('andres');
            $this->user2->setLastName('ardila');
            $this->user2->setEmail('aardila@institution.edu');
            $this->user2->setType('I');

            $this->user2->setInstitution($this->user1->getInstitution());
            $this->user2->save();
        }

        $workspaces = $this->user1->getWorkspaces();

        if ($workspaces != NULL) {
            $this->ws = $workspaces[0];
        }
        else {
            $this->ws = MetricsWorkspaceController::createWorkspace(
                    $this->user1->getJnUsername(), self::TITLE, self::DESCRIPTION
            );
        }
    }

    public function testShareWorkspace() {
        try {
            PersistentWorkspaceSharePeer::doDeleteAll();
            $criteria = new Criteria();
            $criteria->add(PersistentWorkspaceSharePeer::WORKSPACE_ID, $this->ws->getWorkspaceId());
            $criteria->add(PersistentWorkspaceSharePeer::USER_ID, $this->user2->getUserId());
            
            $wss = PersistentWorkspaceSharePeer::doSelect($criteria);
            
            if ($wss == NULL) {
                MetricsWorkspaceController::shareWorkspace(
                    $this->ws->getWorkspaceId(),
                    $this->user2->getJnUsername()
                );
            }

            $wsShares = $this->user2->getWorkspaceShares();
            
            $this->assertNotNull($wsShares);
            $this->assertTrue(is_array($wsShares));
            
            foreach ($wsShares as $wss) {
                $this->assertTrue($wss instanceof PersistentWorkspaceShare);
                if ($wss->getUserId() == $this->user2->getUserId() &&
                    $wss->getWorkspaceId() == $this->ws->getWorkspaceId() ) {
                    return;
                }
            }
            $this->fail('No match found in shared workspace');
        }
        catch(Exception $e) {
            $this->fail('Share Workspace failed: ' . $e->getMessage());
        }
    }

    public function testExemptionWorkspaceIDEmpty() {
        try {
            MetricsWorkspaceController::shareWorkspace("",
                $this->user2->getJnUsername() );
        }
        catch (Exception $e) {
            return;
        }

        $this->fail('Empty params expects an exception');
    }

    public function testExemptionUserJnUsernameWithWhomToShareWorkspaceEmpty() {
        try {
            MetricsWorkspaceController::shareWorkspace($this->ws->getWorkspaceId(), "");
        }
        catch (Exception $e) {
            return;
        }

        $this->fail('Empty params expects an exception');
    }
    
    public function testExemptionInexistentWorkspaceId() {
        try {
            MetricsWorkspaceController::shareWorkspace('99999',
                $this->user2->getJnUsername() );
        }
        catch (Exception $e) {
            return;
        }

        $this->fail('Inexistent User_Jn_Username expects an exception');
    }

    public function testExemptionInexistentJnUsernameWithWhomToShareWorkspaceEmpty() {
        try {
            MetricsWorkspaceController::shareWorkspace($this->ws->getWorkspaceId(),
                'asdfgjh' );
        }
        catch (Exception $e) {
            return;
        }

        $this->fail('Inexistent Jn_Username expects an exception');
    }

    public function testExemptionWorkspaceAlreadyBeingShared() {
        try {
            PersistentWorkspaceSharePeer::doDeleteAll();
            MetricsWorkspaceController::shareWorkspace($this->ws->getWorkspaceId(),
                $this->user2->getJnUsername() );
            MetricsWorkspaceController::shareWorkspace($this->ws->getWorkspaceId(),
                $this->user2->getJnUsername() );
        }
        catch (Exception $e) {
            return;
        }

        $this->fail('Workspace Already Shared expects an exception');
    }
}

?>
