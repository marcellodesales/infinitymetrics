<?php

    if (isset($_POST['wsTitle']) && isset($_POST['wsDescription']))
    {
        if ($_POST['wsTitle'] != '' && $_POST['wsDescription'] != '')
        {
            require_once('propel/Propel.php');
            Propel::init("infinitymetrics/orm/config/om-conf.php");

            require_once ('infinitymetrics/controller/MetricsWorkspaceController.php');
            require_once ('infinitymetrics/model/user/User.class.php');

            $jnUsername = 'johntheteacher';

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
            }//endif ($user == NULL)
            try {
                $ws = MetricsWorkspaceController::createWorkspace(
                        $user->getJnUsername(),
                        $_POST['wsTitle'],
                        $_POST['wsDescription']
                );
            }
            catch (Exception $e) {
                echo ($e->getMessage());
            }

            if ($ws != NULL) {
                header( 'Location: viewWorkspace.php?trackback=new&workspace_id='.$ws->getWorkspaceId() );
            }
        }//endif POST vars are not empty strings
        else
        {

        }
    }//endif POST vars are set

?>


<?php
    include '../template/header-no-left-nav.php';
?>
    <div id="content-wrap">
        <div id="inside">
            <div id="sidebar-right">
                <div id="block-user-3" class="block block-user">
                    <br />
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
                
                <br />
                
                <div class="t"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr">
                    <div class="content-in">
                        <h3>Create New Workspace</h3>
                        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" accept-charset="UTF-8" method="post" id="node-form">
                            <fieldset style="padding:15px; margin:10px; border-color:silver">
                                <table style="width: 85%">
                                    <tr>
                                        <td>Title:</td>
                                        <td><input type="text" name="wsTitle" size="35"></td>
                                    </tr>
                                    <tr>
                                        <td>Description:</td>
                                        <td><input type="text" name="wsDescription" size="35"></td>
                                    </tr>
                                    <tr>
                                        <td>Parent java.net Project:</td>
                                        <td><input type="text" name="parentJnProject" size="35"></td>
                                    </tr>
                                </table>
                                
                                <br />
                                <div style="text-align:center">
                                <input name="submit" id="edit-delete" value="Clear" class="form-submit" type="reset" align="right">
                                <input name="submit" id="edit-submit" value="Submit" class="form-submit" type="submit">
                                </div>
                            </fieldset>
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