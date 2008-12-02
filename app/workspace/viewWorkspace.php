<?php
    include '../template/infinitymetrics-bootstrap.php';

#----------------------------->>>>>>>>>>>>> Controller Usage for UC101 and UC301 ----------------------------->>>>>>>>>>>>>>>
    
    $user = $_SESSION["loggedUser"];

    if (isset($_GET['workspace_id']) && $_GET['workspace_id'] != '')
    {
        require_once('infinitymetrics/controller/MetricsWorkspaceController.class.php');
        require_once('infinitymetrics/controller/ReportController.class.php');

        $ws = PersistentWorkspacePeer::retrieveByPK($_GET['workspace_id']);
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
                        $_SERVER["home_address"].$_SERVER['PHP_SELF'] => "View Workspace"
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
    <title>Infinity Metrics: <?php echo $subUseCase; ?></title>
    <?php include 'static-js-css.php';  ?>
    <?php include 'user-signup-header-adds.php' ?>
</head>
<body class="<?php echo $enableLeftNav ? $leftNavClass : $NoLeftNavClass; ?>">

    <?php  include 'top-navigation.php';  ?>

                <div id="breadcrumb" class="alone">
                    <h2 id="title">Home</h2>
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

                                <?php
                                    echo "<div class=\"content\">\n";
                                    echo "<div class=\"item-list\">\n";
                                    
                                    if ( isset($_GET['type']) &&
                                         isset($_GET['workspace_id']) &&
                                         $_GET['type'] == 'own')
                                    {
                                        $wsShares = $ws->getWorkspaceShares();
                                        

                                        
                                        if ($wsShares == NULL) {
                                            echo "<p>The workspace is not currently being shared with any other user</p>\n";
                                        }
                                        else {

                                            echo "Currently sharing this Workspace with:\n";
                                            echo "<ul>\n";
                                            foreach ($wsShares as $wss) {
                                                echo "<li>".$wss->getUser()->getJnUsername()."</li>\n";
                                            }
                                            echo "</ul>\n";
                                        }
                                        echo    "<br />\n";
                                        echo    "<form action=\"shareWorkspace.php\" accept-charset=\"UTF-8\" method=\"post\" id=\"node-form\">
                                                    <div class=\"node-form\">
                                                        <input name=\"shareWS\" id=\"edit-submit\" value=\"Share Workspace\" class=\"form-submit\" type=\"submit\" />
                                                        <input name=\"workspace_id\" id=\"workspace_id\" value=\"{$_GET['workspace_id']}\" type=\"hidden\" />
                                                    </div>
                                                </form>\n<br /><br />";
                                    }
                                    elseif (isset($_GET['type']) && $_GET['type'] == 'shared') {
                                        echo "<p>This workspace is currently being shared with you by <b>".
                                             $ws->getUser()->getJnUsername()."</b></p>\n";
                                    }
                                    
                                    echo "</div>\n</div>\n";
                                    
                                    if ($ws->getState() == 'NEW' || $ws->getState() == 'PAUSED')
                                    {
                                        include 'wsStateReminder.html';
                                    }
                                ?>

                            </div><!-- end block-user-3 -->
                        </div><!-- end sidebar-right -->

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
                                            
                                            echo "<div style=\"float: left\">";
                                            echo "<table>";
                                            echo "<tr><td><strong>Title:</strong></td><td>".$ws->getTitle()."</td></tr>\n";
                                            echo "<tr><td><strong>Description:</strong></td><td>".$ws->getDescription()."</td></tr>\n";
                                            $color = getStateColor($ws->getState());
                                            echo "<tr><td><strong>State:</strong></td><td><span style=\"font-weight: bold; color:$color\">".$ws->getState()."</span></td></tr>\n";
                                            echo "</table>\n";

                                            if (isset($_GET['type']) && $_GET['type'] == 'own')
                                            {
                                                echo '<form action="updateWorkspace.php" accept-charset="UTF-8" method="post" id="node-form">
                                                        <div class="node-form">
                                                            <input name="updateWS" id="edit-submit" value="Change Workspace Information" class="form-submit" type="submit" />
                                                        </div>
                                                      </form>';
                                            }
                                            
                                            echo "<br />";
                                            echo "<h3>Projects currently in this Workspace</h3>\n";
                                            echo "<strong>".$ws->getProjectJnName()." <small>(PARENT PROJECT)</small></strong>";
                                            echo "<ul>";
                                            
                                            foreach ($ws->getProjects() as $project)
                                            {
                                                $projectJnName = $project->getProjectJnName();
                                                echo "<li>\n";
                                                echo "<a href=\"../report/projectReport.php?project_id=$projectJnName\">$projectJnName</a>";
                                                echo "&nbsp;&nbsp;<a href=\"https://$projectJnName.dev.java.net/\"><img style=\"border: 0\" src=\"../template/icons/i16/misc/world_link.png\" /></a>\n";
                                                echo "</li>\n";
                                            }
                                            echo "</ul>";                                            
                                                                                        
                                            echo "</div>\n";
                                            
                                            echo "<div style=\"float: right; width: 420px; border: thin groove silver; padding: 15px\">";
                                            
                                            echo ReportController::retrieveWorkspaceReport($ws->getWorkspaceId());

                                            echo '<div id="bar_chart_div"></div>';
                                            echo '</div>';
                                            echo '<div style="clear:both"></div>';
                                        }
                                    ?>
                                    
                                    <br /><br />


                                </div>
                                <br class="clear" />
                            </div></div></div></div></div></div></div></div>
                        </div><!-- end content -->
                    </div><!-- end inside -->
                </div>
<?php include 'footer.php';   ?>