<?php
    include '../template/infinitymetrics-bootstrap.php';

#----------------------------->>>>>>>>>>>>> Controller Usage for UC102 ----------------------------->>>>>>>>>>>>>>>

    require_once('infinitymetrics/controller/MetricsWorkspaceController.class.php');
    require_once('infinitymetrics/controller/UserManagementController.class.php');

    $user = $_SESSION["loggedUser"];

    try {
        $wsCollection = MetricsWorkspaceController::retrieveWorkspaceCollection($user->getUserId());
    }
    catch (Exception $e) {
        $_SESSION['retrieveWSCollectionError'] = 'Unable to retrieve the Collection of Workspaces';
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
                                <h2>All users are welcomed</h2>
                                <div class="content" align="center">
                                    <img src="../../template/images/techglobe2.jpg" alt="Globe" />
                                </div>
                            </div>
                        </div>
                    </div><!-- End "inside" -->

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
                                        echo "<h3>My Workspaces</h3>\n";
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

                                        echo "<h3>Workspaces shared with me</h3>\n";
                                        echo "<ul>\n";
                                        foreach($wsCollection['SHARED'] as $ws)
                                        {
                                            $color = getStateLabelColor($ws->getState());
                                            echo "<li>\n";
                                            echo "<a href=\"$path?type=shared&amp;workspace_id=".$ws->getWorkspaceId()."\">".$ws->getTitle()."</a>";
                                            echo " <small><b><span style=\"color:$color\">".$ws->getState()."</span></b></small>";
                                            echo "</li>\n";
                                        }
                                        echo "</ul>\n";
                                    }
                                ?>

                                <br />
                                <form action="createWorkspace.php" accept-charset="UTF-8" method="post" id="node-form">
                                    <div class="node-form">
                                        <input name="createWS" id="edit-submit" value="Create Workspace" class="form-submit" type="submit" />
                                    </div>
                                </form>
                            </div>

                        </div> <!-- End of blue box -->
                        <br class="clear" />
                    </div></div></div></div></div></div></div></div>
                </div>
            </div>


            <?php include 'footer.php';   ?>
