<?php
include 'header-no-left-nav.php';

require_once('propel/Propel.php');
Propel::init("infinitymetrics/orm/config/om-conf.php");

require_once('infinitymetrics/controller/MetricsWorkspaceController.php');
require_once('infinitymetrics/model/user/User.class.php');


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

                            //using user from session as created in infinitymetrics-bootstrap.php
                            //function isUserLoggedIn() is commented out in infinitymetrics-bootstrap.php
                            //assuming SESSION will return user object

                            $wsCollection = MetricsWorkspaceController::retrieveWorkspaceCollection($user->getUserId());

                            $path = "viewWorkspace.php";

                            echo "<h3>My Workspaces</h3>\n";
                            echo "<ul>\n";
                            foreach($wsCollection['OWN'] as $ws)
                            {
                                $color = getStateColor($ws->getState());
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
                                $color = getStateColor($ws->getState());
                                echo "<li>\n";
                                echo "<a href=\"$path?type=shared&workspace_id=".$ws->getWorkspaceId()."\">".$ws->getTitle()."</a>";
                                echo " <small><b><span style=\"color:$color\">".$ws->getState()."</span></b></small>";
                                echo "</li>\n";
                            }
                            echo "</ul>\n";
  
                        ?>
                        <br />
                        <div style = "float: right;">
                        <form action="createWorkspace.php" accept-charset="UTF-8" method="post" id="node-form">
                            <div class="node-form">
                                <input name="createWS" id="edit-submit" value="Create Workspace" class="form-submit" type="submit">
                            </div>
                        </form>
                        </div>

                    </div>
                    <br class="clear">
                </div></div></div></div></div></div></div></div>
            </div>
        </div>
        <BR>
      </div>
<?php include 'footer.php';   ?>