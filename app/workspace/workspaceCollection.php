<?php
    include 'header-no-left-nav.php';
?>


<?php

require_once('propel/Propel.php');
Propel::init("infinitymetrics/orm/config/om-conf.php");

require_once('PHPUnit/Framework.php');
require_once('infinitymetrics/controller/MetricsWorkspaceController.php');
require_once('infinitymetrics/model/user/User.class.php');

$jnUsername = 'johntheteacher';
$title = "New Title ";
$description = "New Description ";

$user = PersistentUserPeer::retrieveByJNUsername($jnUsername);

if ($user == NULL )
{
    $user = new User();
    $user->setJnUsername($jnUsername);
    $user->setJnPassword('password');
    $user->setFirstName('John');
    $user->setLastName('Instructor');
    $user->setEmail('johnc@institution.edu');
    $user->setType('I');

    $criteria = new Criteria();
    $criteria->add(PersistentInstitutionPeer::ABBREVIATION, 'FAU');

    $institutions = PersistentInstitutionPeer::doSelect($criteria);

    if($institutions == NULL)
    {
        $institution = new Institution();
        $institution->setAbbreviation('FAU');
        $institution->setCity('Boca Raton');
        $institution->setCountry('USA');
        $institution->setName('Florida Atlantic University');
        $institution->setStateProvince('FL');
        $institution->save();
    }
    else {
        $institution = $institutions[0];
    }

    $user->setInstitution($institution);
    $user->save();
}

if (!isset($institution)) {
    $institution = $user->getInstitution();
}

if ($user->getWorkspaces() == NULL) {
    for ($i = 0; $i < 5; $i++) {
        $ws = MetricsWorkspaceController::createWorkspace(
            $user->getJnUsername(),
            $title.$i,
            $description.$i
        );

        if ($i == 0 || $i == 1) {
            $ws->setState('ACTIVE');
        }
        elseif ($i == 2) {
            $ws->setState('PAUSED');
        }
        $ws->save();
    }

    for ($j = 0; $j < 5; $j++) {
        $sharingUser = new User();
        $sharingUser->setJnUsername('JNusername'.rand());
        $sharingUser->setJnPassword('password');
        $sharingUser->setFirstName('TestFName'.$j);
        $sharingUser->setLastName('TestLName'.$j);
        $sharingUser->setEmail('user'.$j.'@domain.edu');
        $sharingUser->setType('I');
        $sharingUser->setInstitution($institution);
        $sharingUser->save();

        $ws = MetricsWorkspaceController::createWorkspace(
            $sharingUser->getJnUsername(),
            'Shared '.$title.$j,
            'Shared '.$description.$j
        );

        MetricsWorkspaceController::shareWorkspace(
            $ws->getWorkspaceId(),
            $user->getJnUsername()
        );

        if ($j < 4) {
            $ws->setState('ACTIVE');
        }

        $ws->save();
    }
}
?>
    <div id="content-wrap">
        <div id="inside">
            <div id="sidebar-right">
                <div id="block-user-3" class="block block-user">
                    <h2>Who's doing metrics</h2>

                    <div class="content">
                    There are currently <em>2 users</em> and <em>0 guests</em> online.
                        <div class="item-list">
                            <h3>Online users</h3>
                            <ul>
                                <li class="first">demo</li>
                                <li class="last">demo</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div id="content">

                <BR>
                <h1>Current Workspaces</h1>
                <BR>
                

                <div class="t"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr">
                    <div class="content-in">
                        <?php
                        
                            $wsCollection = MetricsWorkspaceController::retrieveWorkspaceCollection($user->getJnUsername());

                            $path = "viewWorkspace.php";

                            echo "<h3>My Workspaces</h3>\n";
                            echo "<ul>\n";
                            foreach($wsCollection['OWN'] as $ws)
                            {
                                switch ($ws->getState())
                                {
                                    case ('NEW'):       $color = "Blue"; break;
                                    case ('ACTIVE'):    $color = "Green"; break;
                                    case ('PAUSED'):    $color = "Orange"; break;
                                    case ('INACTIVE'):  $color = "Red"; break;
                                }
                                echo "<li>\n";
                                echo "<a href=\"$path?type=own&workspace_id=".$ws->getWorkspaceId()."\">".$ws->getTitle()."</a>";
                                echo " <small><b><span style=\"color:$color\">".$ws->getState()."</span></b></small>";
                                echo "</li>\n";
                            }
                            echo "</ul>\n";
                            
                            echo "<h3>Workspaces shared with me</h3>";
                            echo "<ul>\n";
                            foreach($wsCollection['SHARED'] as $ws)
                            {
                                switch ($ws->getState())
                                {
                                    case ('NEW'):       $color = "Blue"; break;
                                    case ('ACTIVE'):    $color = "Green"; break;
                                    case ('PAUSED'):    $color = "Orange"; break;
                                    case ('INACTIVE'):  $color = "Red"; break;
                                }
                                echo "<li>\n";
                                echo "<a href=\"$path?type=shared&workspace_id=".$ws->getWorkspaceId()."\">".$ws->getTitle()."</a>";
                                echo " <small><b><span style=\"color:$color\">".$ws->getState()."</span></b></small>";
                                echo "</li>\n";
                            }
                            echo "</ul>\n";
                        
                        ?>
                        <br />
                        <form action="createWorkspace.php" accept-charset="UTF-8" method="post" id="node-form">
                            <div class="node-form">
                                <input name="createWS" id="edit-submit" value="Create Workspace" class="form-submit" type="submit">
                            </div>
                        </form>

                    </div>
                    <br class="clear">
                </div></div></div></div></div></div></div></div>
            </div>
        </div>
        <BR>
      </div>
<?php
    include '../template/footer.php';
?>