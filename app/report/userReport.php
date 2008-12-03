<?php
    include '../template/infinitymetrics-bootstrap.php';

#----------------------------->>>>>>>>>>>>> Controller Usage for UC300 ----------------------------->>>>>>>>>>>>>>>
    //for debugging
    //$_GET['user_id'] = '1';

    $user = $_SESSION["loggedUser"];

    if (isset($_GET['user_id']) && $_GET['user_id'] != '' &&
        isset($_GET['project_id']) && $_GET['project_id'] != '')
    {
        require_once('infinitymetrics/controller/ReportController.class.php');

        $reportUser = PersistentUserPeer::retrieveByPK($_GET['user_id']);
        $project = PersistentProjectPeer::retrieveByPK($_GET['project_id']);

        try {
            $reportScript = ReportController::retrieveUserReport($reportUser->getUserId(), $project->getProjectJnName());
        }
        catch (InfinityMetricsException $ime) {
            $_SESSION["report_error"] = $ime;
        }
    }
    else {
        header('Location: workspaceCollection.php');
    }

#----------------------------->>>>>>>>>>>>> Variables Initialization ------------------->>>>>>>>>>>>>>>

    $subUseCase = "User Metrics Report";
    $enableLeftNav = false;

    #breadscrum[URL] = Title
    $breadcrums = array(
                        $_SERVER["home_address"] => "Home",
                        $_SERVER["home_address"]."../workspace/workspaceCollection.php" => "Workspace Collection",
                        $_SERVER["home_address"]."../workspace/viewWorkspace.php" => "View Workspace",
                        $_SERVER["home_address"].$_SERVER['PHP_SELF'] => "User Metrics Report"
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
                                <h2></h2>
                                <div class="content">
                                    <div class="item-list">
                                    
                                    </div>
                                </div>
                            </div><!-- end block-user-3 -->
                        </div><!-- end sidebar-right -->

                        <div id="content">
                            <br />
                            <div class="t"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr">
                                <div class="content-in">
                                    <h2>Metrics Report</h2>
                                    <div style="float: left; width: 250px">
                                        
                                        <h3>
                                            <?php echo $user->getFirstName().' '.$user->getLastName().
                                                            "&nbsp<a href=\"../user/profile/viewProfile.php?userId={$_GET['user_id']}\">".
                                                            "<img style=\"border: 0\" src=\"../template/icons/i16/misc/contact.png\" /></a>"
                                            ?>
                                        </h3>
                                        <h3><?php echo "in project {$_GET['project_id']}&nbsp;&nbsp;<a href=\"https://{$_GET['project_id']}.dev.java.net/\"><img style=\"border: 0\" src=\"../template/icons/i16/misc/world_link.png\" /></a>\n"; ?></h3>
                                            
                                        </div>

                                        <div style="float: right; width: 420px; border: thin groove silver; padding: 15px">

                                            <?php echo $reportScript ?>
                                            <div id="bar_chart_div"></div>

                                        </div>
                                        <div style="clear: both"></div>

                                    <br /><br />


                                </div>
                                <br class="clear" />
                            </div></div></div></div></div></div></div></div>
                        </div><!-- end content -->
                    </div><!-- end inside -->
                </div>

<?php include 'footer.php' ?>