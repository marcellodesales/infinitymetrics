<?php
    include '../template/infinitymetrics-bootstrap.php';

#----------------------------->>>>>>>>>>>>> Controller Usage for UC104 ----------------------------->>>>>>>>>>>>>>>
    //for debugging
    //$_GET['workspace_id'] = 2;
    //$_POST['workspace_id'] = 2;
    //$_POST['jn_username_to_share_with'] = 'user999';
    //$_POST['submit'] = 'Submit';
    //echo '$_GET '; print_r($_GET); echo '<br />';
    //echo '$_POST '; print_r($_POST);echo '<br />';
    //echo '$_SESSION '; print_r($_SESSION);echo '<br />';

    require_once('infinitymetrics/controller/MetricsWorkspaceController.class.php');
    require_once('infinitymetrics/controller/UserManagementController.class.php');

    $user = $_SESSION["loggedUser"];
    
    if (isset($_GET['workspace_id']) && $_GET['workspace_id'] != '') {
        $ws = PersistentWorkspacePeer::retrieveByPK($_GET['workspace_id']);

        if ($ws == null) {
            header('Location: workspaceCollection.php');
        }
        if ( !$ws->isOwner($user->getUserId())) {
            $_SESSION['permissions_error'] = "You are not the owner of this Workspace. Only Workspace owners can share their Workspaces with others";
        }
        else {
            $showForm = true;
        }
    }
    elseif (!isset($_POST['submit'])) {
        $_SESSION['invalid_arrival_path'] = 'Please go back to the <a href="workspaceCollection.php">Workspace Collection</a> to select a workspace to be shared.';
    }

    if (isset($_POST['workspace_id']) &&
        isset($_POST['jn_username_to_share_with']) )
    {
        try {
            $userToShareWith = UserManagementController::retrieveUserByUserName($_POST['jn_username_to_share_with']);

            $ws = MetricsWorkspaceController::shareWorkspace(
                    $_POST['workspace_id'],
                    $userToShareWith->getUserId()
            );

            $showForm = false;
            $_SESSION['success'] = "The Workspace was successfully shared with <strong>".$userToShareWith->getJnUsername()."</strong>";
        }
        catch (Exception $e) {
            $showForm = true;
            $_SESSION['ws_sharing error'] = $e;
        } 
    }

#----------------------------->>>>>>>>>>>>> Variables Initialization ------------------->>>>>>>>>>>>>>>

    $subUseCase = "Share Workspace";
    $enableLeftNav = false;

    #breadscrum[URL] = Title
    $breadcrums = array(
                        $_SERVER["home_address"] => "Home",
                        $_SERVER["home_address"]."/workspace/workspaceCollection.php" => "Workspace Collection",
                        $_SERVER["home_address"]."/workspace/viewWorkspace.php".(isset($_GET['workspace_id']) ? "?type=own&workspace_id={$_GET['workspace_id']}" : (isset($_POST['workspace_id'])? "?type=own&workspace_id={$_POST['workspace_id']}" : '')) => 'View Workspace',
                        $_SERVER["home_address"].$_SERVER['PHP_SELF'] => 'Share Workspace'
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
                                <h2></h2>

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
                                    elseif (isset($_SESSION['userToShareWith_error'])) {
                                        echo "<div class=\"messages error\">{$_SESSION['userToShareWith_error']}</div>";
                                    }
                                    elseif (isset($_SESSION['ws_sharing error'])) {
                                        echo "<div class=\"messages error\">{$_SESSION['ws_sharing error']}</div>";
                                    }
                                    elseif (isset($_SESSION['success'])) {
                                        echo "<div class=\"messages ok\">{$_SESSION['success']}</div>";
                                        echo "<br />
                                            <form action=\"viewWorkspace.php?type=own&workspace_id={$_POST['workspace_id']}\" accept-charset=\"UTF-8\" method=\"post\" id=\"node-form\">
                                                <input name=\"submit\" id=\"edit-submit\" value=\"Go back to Workspace\" class=\"form-submit\" type=\"submit\">
                                            </form>";
                                    }
                                    
                                    if (isset($showForm) && $showForm == true)
                                    {
                                        echo    "<h3>Share Workspace</h3>
                                                <form action=\"{$_SERVER['PHP_SELF']}\" accept-charset=\"UTF-8\" method=\"post\" id=\"node-form\">
                                                    Please enter the Java.net username of the person with whom you wish to share this Workspace:
                                                        <br /><br />
                                                        <input type=\"text\" name=\"jn_username_to_share_with\" size=\"35\">

                                                    <br /><br />
                                                    <input name=\"clear\" id=\"edit-delete\" value=\"Clear\" class=\"form-submit\" type=\"reset\">
                                                    <input name=\"submit\" id=\"edit-submit\" value=\"Submit\" class=\"form-submit\" type=\"submit\">
                                                    <input name=\"workspace_id\" id=\"workspace_id\" value=\"".(isset($_GET['workspace_id']) ? $_GET['workspace_id'] : $_POST['workspace_id'])."\" type=\"hidden\">
                                                </form>";
                                    }
                                    
                                    ?>

                                    <br /><br />

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
                    if (isset($_SESSION['userToShareWith_error'])) {
                        $_SESSION['userToShareWith_error'] = '';
                        unset($_SESSION['userToShareWith_error']);
                    }
                    if (isset($_SESSION['ws_sharing error'])) {
                        $_SESSION['ws_sharing error'] = '';
                        unset($_SESSION['ws_sharing error']);
                    }
                ?>

<?php include 'footer.php' ?>