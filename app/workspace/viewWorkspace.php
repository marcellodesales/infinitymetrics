<?php

    if (isset($_GET['workspace_id']))
    {
        require_once('propel/Propel.php');
        Propel::init("infinitymetrics/orm/config/om-conf.php");

        require_once('PHPUnit/Framework.php');
        require_once('infinitymetrics/controller/MetricsWorkspaceController.php');

        $ws = PersistentWorkspacePeer::retrieveByPK($_GET['workspace_id']);

        $projects = $ws->getProjects();

        if ($projects == NULL)
        {
            for ($i = 0; $i < 5; $i++) {
                $project = new PersistentProject();
                $project->setProjectJnName("ppm-".rand());
                $project->setSummary("Project $i summary");
                $wxp = new PersistentWorkspaceXProject();
                $wxp->setWorkspace($ws);
                $wxp->setProject($project);
                $wxp->save();
            }
        }
    }
    else {
        header('Location: workspaceCollection.php');
    }
 
?>

<?php
    include 'header-no-left-nav.php';
?>
    <div id="content-wrap">
        <div id="inside">
            <div id="sidebar-right">
                <div id="block-user-3" class="block block-user">
                    <br />
                    <h2>Sharing Information</h2>

                    <?php
                    
                        if ( isset($_GET['type']) && 
                                isset($_GET['workspace_id']) &&
                                    $_GET['type'] == 'own')
                        {
                            $wsShares = $ws->getWorkspaceShares();
                            
                            if ($wsShares == NULL) {
                                echo "The workspace is not currently shared with any other user";
                            }
                            else {
                                echo "<div class=\"content\">\n";
                                echo "<div class=\"item-list\">\n";
                                echo "Currently sharing this Workspace with:\n";
                                echo "<ul>\n";
                                foreach ($wsShares as $wss) {
                                    echo "<li>".$wss->getUser()->getJnUsername()."</li>\n";
                                }
                                echo "</ul>\n";
                                echo "</div>\n</div>\n";
                            }
                        }
                        elseif (isset($_GET['type']) && $_GET['type'] == 'shared') {
                            echo "This workspace is currently being shared with you by <b>".
                                 $ws->getUser()->getJnUsername()."</b>\n";
                        }
                    ?>
                    
                </div>
            </div>
            <div id="content">
                <br />
                <div class="t"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr">
                    <div class="content-in">
                        <?php

                            if (isset($_GET['trackback']))
                            {
                                echo "<div style=\"margin: 20px; padding-left: 15px; border-style: solid; border-width: thin; border-color= #F0F0F0\">\n";
                                echo "<table><tr>\n";
                                echo "<td valign=\"center\"><img src=\"/template/icons/i24/status/status-ok.png\" /></td>\n";
                                echo "<td valign=\"center\"><h4>Your Workspace has been created successfully</p></h4></td>\n";
                                echo "</tr></table>\n";
                                echo "</div>";
                            }

                            if (isset($_GET['workspace_id']))
                            {
                                function getStateColor($state) {
                                    switch ($state)
                                    {
                                        case ('NEW'):       return "Blue"; break;
                                        case ('ACTIVE'):    return "Green"; break;
                                        case ('PAUSED'):    return "Orange"; break;
                                        case ('INACTIVE'):  return "Red"; break;
                                        default:            return NULL; break;
                                    }
                                }
                                
                                echo "<h2>Workspace Information</h2>\n";
                                echo "<table style=\"width: 60%\">";
                                echo "<tr><td><strong>Title:</strong></td><td>".$ws->getTitle()."</td></tr>\n";
                                echo "<tr><td><strong>Description:</strong></td><td>".$ws->getDescription()."</td></tr>\n";
                                $color = getStateColor($ws->getState());
                                echo "<tr><td><strong>State:</strong></td><td><span style=\"font-weight: bold; color:$color\">".$ws->getState()."</span></td></tr>\n";
                                echo "</table>\n";
                                echo "<h3>Projects currently in this Workspace</h3>\n";
                                echo "<ul>";

                                foreach ($ws->getProjects() as $wxp)
                                {
                                    $projectJnName = $wxp->getProject()->getProjectJnName();
                                    echo "<li><a href=\"../report/projectReport.php?project_id=$projectJnName\">$projectJnName</a></li>\n";
                                }
                                echo "</ul>";
                            }
                        ?>
                        <br /><br />
                        <form action="updateWorkspace.php" accept-charset="UTF-8" method="post" id="node-form">
                            <div class="node-form">
                                <input name="updateWS" id="edit-submit" value="Change Workspace Information" class="form-submit" type="submit">
                            </div>
                        </form>
                        <?php
                        
                            if (isset($_GET['type']) && $_GET['type'] != 'shared')
                            {
                                echo    "<form action=\"shareWorkspace.php\" accept-charset=\"UTF-8\" method=\"post\" id=\"node-form\">
                                            <div class=\"node-form\">
                                                <input name=\"shareWS\" id=\"edit-submit\" value=\"Share Workspace\" class=\"form-submit\" type=\"submit\">
                                                <input name=\"workspace_id\" id=\"workspace_id\" value=\"{$_GET['workspace_id']}\" type=\"hidden\" >
                                            </div>
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