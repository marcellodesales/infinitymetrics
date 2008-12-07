<?php

    include '../template/infinitymetrics-bootstrap.php';

#----------------------------->>>>>>>>>>>>> Controller Usage for UC102 ----------------------------->>>>>>>>>>>>>>>

    require_once('infinitymetrics/controller/MetricsWorkspaceController.class.php');
    require_once('infinitymetrics/controller/UserManagementController.class.php');
    require_once('infinitymetrics/controller/ReportController.class.php');

    //$_SESSION["loggedUser"] = PersistentUserPeer::retrieveByJNUsername('aardila');

    $user = $_SESSION["loggedUser"];

    try {
        $wsCollection = MetricsWorkspaceController::retrieveWorkspaceCollection($user->getUserId());
        
        if (count($wsCollection['OWN']) == 0) {
            $_SESSION['noOwnWorkspaces'] = "No Workspaces found";
        }
        
        if (count($wsCollection['SHARED']) == 0) {
            $_SESSION['noSharedWorkspaces'] = "No Shared Workspaces found";
        }

        $criteria = new Criteria();
        $criteria->add(PersistentUserXProjectPeer::JN_USERNAME, $user->getJnUsername());
        $criteria->add(PersistentUserXProjectPeer::IS_OWNER, 1);

        $uXps = PersistentUserXProjectPeer::doSelect($criteria);
        
        foreach ($uXps as $uXp)
        {
            $criteria->clear();
            $criteria->add(PersistentWorkspacePeer::USER_ID, $user->getUserId());
            $criteria->add(PersistentWorkspacePeer::PROJECT_JN_NAME, $uXp->getProjectJnName());

            if (PersistentWorkspacePeer::doCount($criteria) == 0) {
                $_SESSION['available_user_x_projects'][] = $uXp;
            }
        }

        $stateFlag = false;

        foreach ($wsCollection as $type => $workspaces)
        {
            foreach ($workspaces as $ws)
            {
                if ($ws->getState() == 'NEW' || $ws->getState() == 'PAUSED') {
                    $stateFlag = true;
                    break;
                }
            }
        }
    }
    catch (InfinityMetricsException $ime) {
        $_SESSION['retrieveWSCollectionError'] = $ime;
    }

    try {
        $reportScript = ReportController::retrieveWorkspaceCollectionReport($user->getUserId());
    }
    catch (InfinityMetricsException $ime) {
        $_SESSION['report_error'] = $ime;
    }

#----------------------------->>>>>>>>>>>>> Variables Initialization ------------------->>>>>>>>>>>>>>>

    $subUseCase = "Workspace Collection";
    $enableLeftNav = false;

    #breadscrum[URL] = Title
    $breadcrums = array(
                        $_SERVER["home_address"] => "Home",
                        $_SERVER["home_address"].$_SERVER['PHP_SELF'] => "Workspace Collection"
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
                        <div id="sidebar-right" align="center">
                            <div id="block-user-3" class="block block-user">
                                <br />
                                <div class="content">
                                <?php
                                    if(isset($_SESSION['available_user_x_projects']))
                                        {
                                            echo "<div class=\"item-list\">\n";
                                            echo "<img src=\"../template/icons/i24/misc/info.png\" align=\"left\" alt=\"info_icon\" />\n";
                                            echo "<h2>&nbsp;You have new projects</h2>\n";
                                            echo "<p>We found the following Projects for which you have not created a Workpsace:</p>\n";
                                            echo "<ul>\n";
                                            foreach ($_SESSION['available_user_x_projects'] as $uXp) {
                                                echo "<li><a href=\"./createWorkspace.php?project_jn_name=".$uXp->getProjectJnName()."\">".$uXp->getProjectJnName()."</a></li>";
                                            }
                                            echo "</ul><br />\n";
                                            echo "</div>\n";

                                            $_SESSION['available_user_x_projects'] = '';
                                            unset($_SESSION['available_user_x_projects']);
                                        }

                                        if ($stateFlag) {
                                            include 'wsStateReminder.html';
                                        }
                                    ?>
                                </div>
                            </div><!-- end block-user-3 -->
                        </div><!-- end sidebar-right -->

                    <div id="content">

                        <br />
                        
                        <div class="t"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr">
                            <div class="content-in">
                                <?php

                                    if (isset($_SESSION['retrieveWSCollectionError'])) {
                                        echo "<div class=\"messages error\">{$_SESSION['retrieveWSCollectionError']}</div>\n";
                                        $_SESSION['retrieveWSCollectionError'] = '';
                                        unset($_SESSION['retrieveWSCollectionError']);
                                    }
                                    else {
                                        function getStateLabelColor($state) {
                                            switch ($state)
                                            {
                                                case ('NEW'):       return "Blue"; break;
                                                case ('ACTIVE'):    return "Green"; break;
                                                case ('PAUSED'):    return "Orange"; break;
                                                case ('INACTIVE'):  return "Red"; break;
                                            }
                                        }
                                        echo "<h2>Current Workspaces</h2><br />";
                                        echo "<div style=\"float: left\">";
                                        echo "<h3>My Workspaces</h3>\n";
                                        if (isset($_SESSION['noOwnWorkspaces']))
                                        {
                                            echo "<span style=\"color: gray\">".$_SESSION['noOwnWorkspaces']."</span><br /><br />\n";
                                            $_SESSION['noOwnWorkspaces'] = '';
                                            unset($_SESSION['noOwnWorkspaces']);
                                        }
                                        else {
                                            echo "<ul>\n";
                                            foreach($wsCollection['OWN'] as $ws)
                                            {
                                                $color = getStateLabelColor($ws->getState());
                                                echo "<li>\n";
                                                echo "<a href=\"viewWorkspace.php?type=own&amp;workspace_id=".$ws->getWorkspaceId()."\">".$ws->getTitle()."</a>";
                                                echo " <small><b><span style=\"color:$color\">".$ws->getState()."</span></b></small>";
                                                echo "</li>\n";
                                            }
                                            echo "</ul>\n";
                                        }
                                        echo '<br />';

                                        echo "<h3>Workspaces shared with me</h3>\n";

                                        if (isset($_SESSION['noSharedWorkspaces']))
                                        {
                                            echo "<span style=\"color: gray\">".$_SESSION['noSharedWorkspaces']."</span><br /><br />\n";
                                            $_SESSION['noSharedWorkspaces'] = '';
                                            unset($_SESSION['noSharedWorkspaces']);
                                        }
                                        else {
                                            echo "<ul>\n";
                                            foreach($wsCollection['SHARED'] as $ws)
                                            {
                                                $color = getStateLabelColor($ws->getState());
                                                echo "<li>\n";
                                                echo "<a href=\"viewWorkspace.php?type=shared&amp;workspace_id=".$ws->getWorkspaceId()."\">".$ws->getTitle()."</a>";
                                                echo " <small><b><span style=\"color:$color\">".$ws->getState()."</span></b></small>";
                                                echo "</li>\n";
                                            }
                                            echo "</ul>\n";
                                        }
                                        echo '<br />';
                                        echo '<form action="createWorkspace.php" accept-charset="UTF-8" method="post" id="node-form">
                                                <div class="node-form">
                                                    <input name="createWS" id="edit-submit" value="Create New Workspace" class="form-submit" type="submit" />
                                                </div>
                                              </form>';
                                                        
                                        echo "</div>";

                                        echo "<div style=\"float: right; width: 420px; border: thin groove silver; padding: 15px\">";
                                        
                                        if (isset($_SESSION['report_error']) && $_SESSION['report_error'] != '')
                                        {
                                            echo "<div class=\"message error\">{$_SESSION['report_error']}</div>";
                                            $_SESSION['report_error'] = '';
                                            unset($_SESSION['report_error']);
                                        }
                                        else {
                                            echo $reportScript;
                                            echo "<div id=\"bar_chart_div\"></div>";
                                        }

                                        echo "</div>";
                                        
                                        echo "<div style=\"clear: both\"></div>\n";
                                    }
                                ?>
                                
                                <br />

                            </div><!-- end content-in -->
                        </div> <!-- End of blue box -->
                        <br class="clear" />
                    </div></div></div></div></div></div></div></div>
                </div>
            </div>

<?php include 'footer.php' ?>