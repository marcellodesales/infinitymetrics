<?php
    $showForm = true;

    if (isset($_POST['workspace_id']) && isset($_POST['jn_username_to_share_with']))
    {
        if ($_POST['jn_username_to_share_with'] != '')
        {
            include 'header-no-left-nav.php';
            require_once('propel/Propel.php');
            Propel::init("infinitymetrics/orm/config/om-conf.php");

            require_once ('infinitymetrics/controller/MetricsWorkspaceController.php');
            require_once ('infinitymetrics/model/user/User.class.php');

            //using user from session as created in infinitymetrics-bootstrap.php
            //function isUserLoggedIn() is commented out in infinitymetrics-bootstrap.php
            //assuming SESSION will return user object $user

            $username2 = $_POST['jn_username_to_share_with'];

            $user2 = PersistentUserPeer::retrieveByJNUsername($username2);

            try {
                $ws = MetricsWorkspaceController::shareWorkspace(
                        $_POST['workspace_id'],
                        $user2->getUserId()
                );
            }
            catch (Exception $e) {
                echo ($e->getMessage());
            }

            $showForm = false;
            
        }//endif POST vars are not empty strings
        else
        {

        }
    }//endif POST/GET vars are set
    elseif (!isset($_POST['shareWS'])) {
        $invalidArrivalPathFlag = true;
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
                <!--
                <h1>Current Workspaces</h1>
                <BR><BR>
                -->
                <div class="t"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr">
                    <div class="content-in">
                    <?php
                        if (isset($invalidArrivalPathFlag) && $invalidArrivalPathFlag) {
                            echo 'Please go back to the <a href="workspaceCollection.php">Workspace Collection</a> to select a workspace to be shared.';
                        }
                        elseif ($showForm)
                        {
                            echo "<h3>Share Workspace</h3>
                        <form action=\"{$_SERVER['PHP_SELF']}\" accept-charset=\"UTF-8\" method=\"post\" id=\"node-form\">
                            Please enter the Java.net username of the person with whom you wish to share this Workspace:
                                <br /><br />
                                <input type=\"text\" name=\"jn_username_to_share_with\" size=\"35\">
                            
                            <br /><br />
                            <input name=\"clear\" id=\"edit-delete\" value=\"Clear\" class=\"form-submit\" type=\"reset\">
                            <input name=\"submit\" id=\"edit-submit\" value=\"Submit\" class=\"form-submit\" type=\"submit\">
                            <input name=\"workspace_id\" id=\"workspace_id\" value=\"{$_POST['workspace_id']}\" type=\"hidden\">
                        </form>";
                        }
                        else {
                            echo "<h3>The Workspace was successfully shared with {$_POST['jn_username_to_share_with']}</h3>";
                            echo "<br />
                                <form action=\"viewWorkspace.php?type=own&workspace_id={$_POST['workspace_id']}\" accept-charset=\"UTF-8\" method=\"post\" id=\"node-form\">
                                    <input name=\"submit\" id=\"edit-submit\" value=\"Go back to Workspace\" class=\"form-submit\" type=\"submit\">
                                </form>";
                        }
                        ?>
                    </div>
                    <br class="clear">
                </div></div></div></div></div></div></div></div>
            </div>
        </div>
        <BR>
        
      </div>
<?php
    include 'footer.php';
?>