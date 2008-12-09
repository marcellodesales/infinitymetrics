<?php
    include '../template/infinitymetrics-bootstrap.php';

#----------------------------->>>>>>>>>>>>> Controller Usage for UC101, UC302 UC303 ----------------------------->>>>>>>>>>>>>>>
    //for debugging
    //$_GET['workspace_id'] = '2';

    $user = $_SESSION["loggedUser"];

    if (isset($_GET['workspace_id']) && $_GET['workspace_id'] != '')
    {
        require_once('infinitymetrics/controller/MetricsWorkspaceController.class.php');
        require_once('infinitymetrics/controller/ReportController.class.php');

        $ws = PersistentWorkspacePeer::retrieveByPK($_GET['workspace_id']);

        if ($ws == null) {
            header('Location: workspaceCollection.php');
        }
        
        $_SESSION['isOwnWS']= $ws->isOwner($user->getUserId());
        
        try {
            $reportScript = ReportController::retrieveWorkspaceReport($ws->getWorkspaceId());
        }
        catch (InfinityMetricsException $ime) {
            $_SESSION["report_error"] = $ime;
        }

        try {
            $rankedProjects = ReportController::retrieveTopProjects($ws->getWorkspaceId());
        }
        catch (InfinityMetricsException $ime) {
            $_SESSION["project_ranking_error"] = $ime;
        }

        $stateFlag = false;

        if ($ws->getState() == 'NEW' || $ws->getState() == 'PAUSED') {
            $stateFlag = true;
        }
    }
    else {
        header('Location: workspaceCollection.php');
    }

#----------------------------->>>>>>>>>>>>> Variables Initialization ------------------->>>>>>>>>>>>>>>

    $subUseCase = "View Workspace";
    $enableLeftNav = false;

    #breadscrum[URL] = Title
    $breadcrums = array(
                        $_SERVER["home_address"] => "Home",
                        $_SERVER["home_address"]."/workspace/workspaceCollection.php" => "Workspace Collection",
                        $_SERVER["home_address"].$_SERVER['REQUEST_URI'] => $ws->getTitle()
                  );

    #leftMenu[n]["active"] - If the menu item is active or not
    #leftMenu[n]["url"] - the URL for the menu item
    #leftMenu[n]["item"] - the item of the menu
    #leftMenu[n]["tip"] - the tooltip of the URL
    #$leftMenu = array();
    #array_push($leftMenu, array("active"=>"menu-27 first active", "url"=>"signup-step1.php", "item"=>"1. Java.net Authentication", "tip"=>"Manage your site's book outlines."));
    #array_push($leftMenu, array("active"=>"menu-27", "url"=>"signup-step2.php", "item"=>"2. Update Profile", "tip"=>"Update and review your profile info"));
    #array_push($leftMenu, array("active"=>"menu-27", "url"=>"signup-step3.php", "item"=>"3. Confirm Registration", "tip"=>"Confirm you profile"));
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html class="js" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <title>Infinity Metrics: <?php echo $subUseCase ?></title>
    <?php include 'static-js-css.php' ?>
    <?php include 'user-signup-header-adds.php' ?>
</head>
<body class="<?php echo $enableLeftNav ? $leftNavClass : $NoLeftNavClass ?>">

    <?php include 'top-navigation.php' ?>

                <div id="breadcrumb" class="alone">
                    <h2 id="title">&nbsp;</h2>
                    <div class="breadcrumb">
                        <?php
                            $totalBreadcrums = count(array_keys($breadcrums));
                            $idx = 0;
                            foreach (array_keys($breadcrums) as $keyUrl)
                            {
                                echo "<a href=\"$keyUrl\">{$breadcrums[$keyUrl]}</a> ".
                                    (++$idx < $totalBreadcrums ? "» " : " ");
                            }
                        ?>
                    </div>
                </div>

                <div id="content-wrap">
                    <div id="inside">
                        <div id="sidebar-right">
                            <div id="block-user-3" class="block block-user">
                                <br />
                                <h2>Sharing Information</h2>
                                <div class="content">
                                    
                                    <?php
                                        if ( isset($_SESSION['isOwnWS']) &&
                                             isset($_GET['workspace_id']) &&
                                             $_SESSION['isOwnWS'] == true)
                                        {
                                            $wsShares = $ws->getWorkspaceShares();

                                            if ($wsShares == NULL) {
                                                echo "<p>The workspace is not currently being shared with any other user</p>\n";
                                            }
                                            else {
                                                echo "<div class=\"item-list\">";
                                                echo "Currently sharing this Workspace with:\n";
                                                echo "<ul>\n";
                                                foreach ($wsShares as $wss) {
                                                    echo '<li><a href="../user/profile/viewProfile.php?userId='.$wss->getUser()->getUserId().'">'.
                                                         $wss->getUser()->getFirstName()." ".$wss->getUser()->getLastName().'</a>'.
                                                         "&nbsp;<img style=\"border: 0\" src=\"../template/icons/i16/misc/contact.png\" />\n";
                                                }
                                                echo "</ul>\n</div>";
                                            }
                                            echo    "<br />\n";
                                            echo    "<form action=\"shareWorkspace.php?workspace_id=".$ws->getWorkspaceId()."\" accept-charset=\"UTF-8\" method=\"post\" id=\"node-form\">
                                                        <div class=\"node-form\">
                                                            <input name=\"shareWS\" id=\"edit-submit\" value=\"Share Workspace\" class=\"form-submit\" type=\"submit\" />
                                                        </div>
                                                    </form>\n<br />";
                                        }
                                        elseif (isset($_SESSION['isOwnWS']) && $_SESSION['isOwnWS'] == false) {
                                            echo '<p>This workspace is currently being shared with you by <strong>'.
                                                 '<a href="../user/profile/viewProfile.php?userId='.$ws->getUser()->getUserId().'">'.
                                                 $ws->getUser()->getFirstName()." ".$ws->getUser()->getLastName().'</a>'.
                                                 '&nbsp;<img style="border: 0" src="../template/icons/i16/misc/contact.png" />'.
                                                 "</strong></p>\n<br />\n";
                                        }
                                    ?>


                                   <?php
                                        if ($stateFlag) {
                                            include 'wsStateReminder.html';
                                        }
                                    ?>
                                    
                                    <div class="item-list">
                                        <h2>
                                            Projects in this Workspace:<br />
                                            <small>(Ranked by contribution)</small>
                                        </h2>
                                        
                                        <?php
                                            if (!isset($_SESSION['project_ranking_error'])) {
                                            echo "<h4>".$ws->getProjectJnName()." <small>(PARENT PROJECT)</small></h4>";
                                            echo "<ol>\n";
                                            foreach ($rankedProjects as $projectJnName => $total) {
                                                echo "<li>\n".
                                                     "<a href=\"../report/projectReport.php?project_id=$projectJnName\">$projectJnName</a>".
                                                     "&nbsp;&nbsp;<a href=\"https://$projectJnName.dev.java.net/\">".
                                                     "<img style=\"border: 0\" src=\"../template/icons/i16/misc/world_link.png\" alt=\"link_icon\" /></a>\n".
                                                     "</li>";
                                                }
                                            }
                                            echo "</ol>\n";
                                        ?>
                                        
                                    </div>
                                </div>

                            </div><!-- end block-user-3 -->
                        </div><!-- end sidebar-right -->

                        <div id="content">
                            <br />
                            <div class="t"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr">
                                <div class="content-in">
                                    <div>
                                    <?php

                                        if (isset($_GET['trackback']) && $_GET['trackback'] != '')
                                        {
                                            if ($_GET['trackback'] == 'new') {
                                                echo "<div style=\"margin: 20px; padding-left: 15px; border-style: solid; border-width: thin; border-color= #F0F0F0\">\n";
                                                echo "<table><tr>\n";
                                                echo "<td valign=\"center\"><img src=\"/template/icons/i24/status/status-ok.png\" /></td>\n";
                                                echo "<td valign=\"center\"><h4>Your Workspace has been created successfully</p></h4></td>\n";
                                                echo "</tr></table>\n";
                                                echo "</div>";
                                            }
                                            elseif ($_GET['trackback'] == 'state') {
                                                echo "<div class=\"messages ok\"><img src=\"../template/icons/i24/status/status-ok.png alt=\"success\" />The <strong>state<strong> of your Workspace has been updated successfully!</div>";
                                            }
                                            elseif ($_GET['trackback'] == 'profile') {
                                                echo "<div class=\"messages ok\"><img src=\"../template/icons/i24/status/status-ok.png alt=\"success\" />Your <strong>Workspace Profile</strong> has been updated successfully!</div>";
                                            }
                                        }

                                        if (isset($_GET['workspace_id']))
                                        {
                                            echo $reportScript."\n";
                                            echo "<h2>Workspace Information</h2>\n";
                                            
                                            echo '<div style="float: left; width: 300px">';
                                            $color = MetricsWorkspaceController::getStateColor($ws->getState());
                                            echo "<div style=\"border: thin groove silver; padding: 15px\">\n";
                                            echo '<table>';
                                            echo "<tr><td><strong>State:</strong></td><td><span style=\"font-weight: bold; color:$color\">".$ws->getState()."</span></td></tr>\n";
                                            echo "<tr><td><strong>Title:</strong></td><td>".$ws->getTitle()."</td></tr>\n";
                                            echo "<tr><td colspan=\"2\"><strong>Description:</strong></td></tr>\n";
                                            echo "<tr><td colspan=\"2\">\n";
                                            echo ($ws->getDescription() == '' ? '<span style="color: gray"> [ Empty ]' : '<span style="font-size: 0.9em">'.$ws->getDescription()); echo "</span></td></tr>\n";
                                            echo "</table><br />\n";

                                            if (isset($_SESSION['isOwnWS']) && $_SESSION['isOwnWS'] == true)
                                            {
                                                echo '<form action="updateWorkspace.php?workspace_id='.$ws->getWorkspaceId().'" accept-charset="UTF-8" method="post" id="node-form">
                                                        <div class="node-form">
                                                            <input name="updateWS" id="edit-submit" value="Edit Workspace Information" class="form-submit" type="submit" />
                                                        </div>
                                                      </form><br />';
                                            }
                                            echo "</div>";
                                            echo "<div style=\"border: thin groove silver; padding: 10px; margin-top: 10px\">";
                                            echo "<div id=\"ws_pie_chart_div\"></div>\n<br />\n";
                                            echo "<div id=\"cat_pie_chart_div\"></div>\n";
                                            echo "</div>\n</div>\n";
                                            
                                            echo "<div style=\"float: left; width: 420px; border: thin groove silver; padding: 15px; margin-left: 10px\">";
                                            
                                            if (isset($_SESSION['report_error']) && $_SESSION['report_error'] != '') {
                                                echo "<div class=\"messages error\">{$_SESSION['report_error']}</div>";
                                                $_SESSION['report_error'] = '';
                                                
                                            }
                                            else {
                                                echo "<div id=\"col_chart_div\"></div><br />\n";
                                                echo "<div id=\"table_chart_div\"></div>\n";
                                            }
                                            
                                            echo '</div>';
                                            echo '<div style="clear:both"></div>';
                                            echo "";
                                        }
                                    ?>
                                    
                                    <br /><br />

                                    </div>
                                </div>
                                <br class="clear" />
                            </div></div></div></div></div></div></div></div>
                        </div><!-- end content -->
                    </div><!-- end inside -->
                </div>
                <?php
                if (isset($_SESSION['isOwnWS'])) {
                    $_SESSION['isOwnWS'] = '';
                    unset($_SESSION['isOwnWS']);
                }
                if (isset($_SESSION['report_error'])) {
                    $_SESSION['report_error'] = '';
                    unset($_SESSION['report_error']);
                }
                ?>
<?php include 'footer.php' ?>