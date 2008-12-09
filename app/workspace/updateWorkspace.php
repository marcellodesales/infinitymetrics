<?php
    include '../template/infinitymetrics-bootstrap.php';

#----------------------------->>>>>>>>>>>>> Controller Usage for UC103 & UC105 ----------------------------->>>>>>>>>>>>>>>
    //for debugging
    //$_GET['workspace_id'] = 2;
    
    //echo '$_GET '; print_r($_GET); echo '<br />';
    //echo '$_POST '; print_r($_POST);echo '<br />';
    //echo '$_SESSION '; print_r($_SESSION);echo '<br />';

    require_once('infinitymetrics/controller/MetricsWorkspaceController.class.php');
    require_once('infinitymetrics/controller/UserManagementController.class.php');

    $user = $_SESSION["loggedUser"];
    $showForm = false;

    if (isset($_GET['workspace_id']) && $_GET['workspace_id'] != '')
    {
        $ws = PersistentWorkspacePeer::retrieveByPK($_GET['workspace_id']);

        if ($ws == null) {
            header('Location: workspaceCollection.php');
        }

        if ( !$ws->isOwner($user->getUserId())) {
            $_SESSION['permissions_error'] = "You are not the owner of this Workspace. Only Workspace owners can update a Workspace";
        }
        else {
            $showForm = true;
        }
    }
    elseif (!isset($_POST['state_submit']) && !isset($_POST['profile_submit'])) {
        $_SESSION['invalid_arrival_path'] = 'Please go back to the <a href="workspaceCollection.php">Workspace Collection</a> to select a workspace update.';
    }

    if (isset($_POST['state_submit']) && 
        isset($_POST['workspace_id']) &&
        isset($_POST['state_select']) )
        {

        try {
            MetricsWorkspaceController::changeWorkspaceState($_POST['workspace_id'], $_POST['state_select']);

            header("Location: viewWorkspace.php?trackback=state&workspace_id={$_POST['workspace_id']}");
        }
        catch (Exception $e) {
            $_SESSION['state_error'] = $e;
            $ws = PersistentWorkspacePeer::retrieveByPK($_POST['workspace_id']);
            $showForm = true;
        }
    }
    if (isset($_POST['profile_submit']) &&
        isset($_POST['workspace_id']) &&
        isset($_POST['new_title']) &&
        isset($_POST['new_desc']) )
        {

        try {
            MetricsWorkspaceController::updateWorkspaceProfile(
                $_POST['workspace_id'], $_POST['new_title'], $_POST['new_desc']);

            header("Location: viewWorkspace.php?trackback=profile&workspace_id={$_POST['workspace_id']}");
        }
        catch (Exception $e) {
            $_SESSION['profile_error'] = $e;
            $ws = PersistentWorkspacePeer::retrieveByPK($_POST['workspace_id']);
            $showForm = true;
        }
    }

#----------------------------->>>>>>>>>>>>> Variables Initialization ------------------->>>>>>>>>>>>>>>

    $subUseCase = "Edit Workspace Details";
    $enableLeftNav = false;

    #breadscrum[URL] = Title
    $breadcrums = array(
                        $_SERVER["home_address"] => "Home",
                        $_SERVER["home_address"]."/workspace/workspaceCollection.php" => "Workspace Collection",
                        $_SERVER["home_address"]."/workspace/viewWorkspace.php".(isset($_GET['workspace_id']) ? "?workspace_id={$_GET['workspace_id']}" : (isset($_POST['workspace_id'])? "?workspace_id={$_POST['workspace_id']}" : '')) => 'View Workspace',
                        $_SERVER["home_address"].$_SERVER['PHP_SELF'] => 'Edit Workspace'
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
                        <div id="sidebar-right">
                            <div id="block-user-3" class="block block-user">
                                <br />
                                <div style="font-size:0.90em">
                                    <img src="../template/icons/i24/misc/info.png" style="float: left" alt="info_icon" />
                                    <h2>&nbsp;Workspace State</h2>
                                    <p>Keep in mind that the Personal Agent does not collect Metrics for Workspaces in the <small><strong>NEW</strong></small> and <small><strong>PAUSED</strong></small> states</p>
                                </div>

                            </div><!-- end block-user-3 -->
                        </div><!-- end sidebar-right -->

                        <div id="content">
                            <br />
                            <div class="t"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr">
                                <div class="content-in">
                                <?php

                                    if (isset($_SESSION['permissions_error'])) {
                                        echo "<div class=\"messages error\">{$_SESSION['permissions_error']}</div>";
                                    }
                                    if (isset($_SESSION['invalid_arrival_path'])) {
                                        echo "<div class=\"messages error\">{$_SESSION['invalid_arrival_path']}</div>";
                                    }
                                    elseif (isset($_SESSION['state_error'])) {
                                        echo "<div class=\"messages error\">{$_SESSION['state_error']}</div>";
                                    }
                                    elseif (isset($_SESSION['profile_error'])) {
                                        echo "<div class=\"messages error\">{$_SESSION['profile_error']}</div>";
                                    }
                                    

                                    if ($showForm) {
                                        switch($ws->getState()) {
                                            case 'NEW'      :
                                            case 'INACTIVE' :   $states = array('ACTIVE');
                                                                break;
                                            case 'ACTIVE'   :   $states = array('PAUSED', 'INACTIVE');
                                                                break;
                                            case 'PAUSED'   :   $states = array('ACTIVE', 'INACTIVE');

                                        }
                                        $curState = $ws->getState();
                                        $color = MetricsWorkspaceController::getStateColor($curState);
                                        $curTitle = $ws->getTitle();
                                        $curDesc = $ws->getDescription();

                                        include 'updateForm.php';
                                    }
                                ?>

                                        <div style="clear: both"></div>
                                    
                                    <br />

                                </div>
                                <br class="clear" />
                            </div></div></div></div></div></div></div></div>
                        </div><!-- end content -->
                    </div><!-- end inside -->
                </div>

                <?php
                    #-------->>>>>> unset all $_SESSION vars here -----------------
                    
                    if (isset($_SESSION['permissions_error'])) {
                        $_SESSION['permissions_error'] = '';
                        unset($_SESSION['permissions_error']);
                    }
                    if (isset($_SESSION['invalid_arrival_path'])) {
                        $_SESSION['invalid_arrival_path'] = '';
                        unset($_SESSION['invalid_arrival_path']);
                    }
                    if (isset($_SESSION['state_error'])) {
                        $_SESSION['state_error'] = '';
                        unset($_SESSION['state_error']);
                    }
                    if (isset($_SESSION['profile_error'])) {
                        $_SESSION['profile_error'] = '';
                        unset($_SESSION['profile_error']);
                    }

                ?>

<?php include 'footer.php' ?>