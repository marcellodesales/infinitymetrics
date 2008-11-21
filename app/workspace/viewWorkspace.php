<?php
    include 'header-no-left-nav.php';
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
                <div class="t"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr">
                    <div class="content-in">
                        <?php

                            require_once('propel/Propel.php');
                            Propel::init("infinitymetrics/orm/config/om-conf.php");

                            require_once('PHPUnit/Framework.php');
                            require_once('infinitymetrics/controller/MetricsWorkspaceController.php');

                            if (isset($_GET['trackback']))
                            {
                                echo '<table><tr><td>';
                                echo '<img src="icons/i24/status/status-ok.png" /></td>';
                                echo "<td><h3>Congratulations!</h3>";
                                echo "<p>Your Workspace has been created successfully</p></td></tr></table>";
                                echo '<br />';
                            }

                            if (isset($_GET['workspace_id']))
                            {
                                $ws = MetricsWorkspaceController::retrieveWorkspace($_GET['workspace_id']);

                                echo "<h2>Workspace Information</h2>\n";
                                echo "<h4>Title:</h4>".$ws->getTitle()."<br />\n";
                                echo "<h4>Description:</h4>".$ws->getDescription()."<br />\n";
                                switch ($ws->getState())
                                {
                                    case ('NEW'):       $color = "Blue"; break;
                                    case ('ACTIVE'):    $color = "Green"; break;
                                    case ('PAUSED'):    $color = "Orange"; break;
                                    case ('INACTIVE'):  $color = "Red"; break;
                                }
                                echo "<h4>State:</h4><span style=\"color:$color\">".$ws->getState()."</span>\n";
                            }
                        ?>
                        <br /><br />
                        <form action="updateWorkspace.php" accept-charset="UTF-8" method="post" id="node-form">
                            <div class="node-form">
                                <input name="updateWS" id="edit-submit" value="Change Workspace Information" class="form-submit" type="submit">
                            </div>
                        </form>
                        <form action="shareWorkspace.php" accept-charset="UTF-8" method="post" id="node-form">
                            <div class="node-form">
                                <input name="shareWS" id="edit-submit" value="Share Workspace" class="form-submit" type="submit">
                                <input name="workspace_id" id="workspace_id" value="<?php echo $_GET['workspace_id'] ?>" type="hidden" >
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
    include 'footer.php';
?>