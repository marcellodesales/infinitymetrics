<?php
    include '../template/infinitymetrics-bootstrap.php';

#----------------------------->>>>>>>>>>>>> Controller Usage for UC100 ----------------------------->>>>>>>>>>>>>>>

    require_once ('infinitymetrics/controller/MetricsWorkspaceController.class.php');
    require_once ('infinitymetrics/controller/UserManagementController.class.php');
    $user = $_SESSION["loggedUser"];

    if (isset($_POST['parentJnProject']) &&
        isset($_POST['wsTitle']) &&
        isset($_POST['wsDescription'])
       )
    {
        try {
            $ws = MetricsWorkspaceController::createWorkspace(
                $user->getUserId(),
                $_POST['parentJnProject'],
                $_POST['wsTitle'],
                $_POST['wsDescription']
            );
        }
        catch (InfinityMetricsException $ime) {
            $_SESSION['create_ws_error'] = $ime;
        }
    }//endif POST vars are set
    else {
        
    }
#----------------------------->>>>>>>>>>>>> Variables Initialization ------------------->>>>>>>>>>>>>>>

    $subUseCase = "Create Workspace";
    $enableLeftNav = false;

    #breadscrum[URL] = Title
    $breadcrums = array(
                        $_SERVER["home_address"] => "Home",
                        $_SERVER["home_address"]."/workspace/workspaceCollection.php" => "Workspace Collection",
                        $_SERVER["home_address"].$_SERVER['PHP_SELF'] => 'Create Workspace'
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
                                <h2></h2>
                                <div class="content">
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

                            <br />

                            <div class="t"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr">
                                <div class="content-in">
                                    <h3>Create New Workspace</h3>
                                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" accept-charset="UTF-8" method="post" id="node-form">
                                        <fieldset style="padding:15px; margin:10px; border-color:silver">
                                            <table>
                                                <?php
                                                    if (isset($_SESSION['create_ws_error'])) {
                                                        echo '<div class="messages error">'.$_SESSION['create_ws_error']."</div>\n";
                                                        unset($_SESSION['create_ws_error']);
                                                    }
                                                ?>
                                                <tr>
                                                    <td>Title:</td>
                                                    <td><input type="text" name="wsTitle" size="35">&nbsp;*<small>&nbsp;required</small></td>
                                                </tr>
                                                <tr>
                                                    <td>Description:</td>
                                                    <td><input type="text" name="wsDescription" size="35"></td>
                                                </tr>
                                                <tr>
                                                    <td>Parent java.net Project:</td>
                                                    <td><input type="text" name="parentJnProject" size="35">&nbsp;*<small>&nbsp;required</small></td>
                                                </tr>
                                            </table>

                                            <br />
                                            <div style="text-align:center">
                                            <input name="submit" id="edit-delete" value="Clear" class="form-submit" type="reset" align="right">
                                            <input name="submit" id="edit-submit" value="Submit" class="form-submit" type="submit">
                                            </div>
                                        </fieldset>
                                    </form>
                                </div>
                                <br class="clear" />
                            </div></div></div></div></div></div></div></div>
                        </div><!-- end content -->
                    </div><!-- end inside -->
                </div>
                
<?php include 'footer.php' ?>
