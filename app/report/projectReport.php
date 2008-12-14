<?php
    include '../template/infinitymetrics-bootstrap.php';

#----------------------------->>>>>>>>>>>>> Controller Usage for UC301 ----------------------------->>>>>>>>>>>>>>>
    //for debugging
    //$_GET['project_id']='PPM-1';

    $user = $_SESSION["loggedUser"];

    if (isset($_GET['project_id']) && $_GET['project_id'] != '')
    {
        require_once('infinitymetrics/controller/ReportController.class.php');

        $project = PersistentProjectPeer::retrieveByPK($_GET['project_id']);

        if ($project == null) {
            header('Location: workspaceCollection.php');
        }

        try {
            $reportScript = ReportController::retrieveProjectReport($project->getProjectJnName());
        }
        catch (InfinityMetricsException $ime) {
            $_SESSION['report_error'] = $ime;
        }

        $eventAuthors = $project->getDistinctEventAuthors();

        $registeredAuthors = array();

        foreach ($eventAuthors as $key => $assocArray)
        {
            $jnUsername = $assocArray['JN_USERNAME'];
            $dbUser = PersistentUserPeer::retrieveByJNUsername($jnUsername);

            if ($dbUser != null) {
                $registeredAuthors[$jnUsername] = $dbUser;
            }
            else {
                $registeredAuthors[$jnUsername] = $jnUsername;
            }
        }
    }
    else {
        header('Location: workspaceCollection.php');
    }


#----------------------------->>>>>>>>>>>>> Variables Initialization ------------------->>>>>>>>>>>>>>>

    $subUseCase = "Project Report";
    $enableLeftNav = false;

    #breadscrum[URL] = Title
    $breadcrums = array(
                        $_SERVER["home_address"] => "Home",
                        $_SERVER["home_address"]."/workspace/workspaceCollection.php" => "Workspace Collection",
                        $_SERVER["home_address"]."/workspace/viewWorkspace.php" => "View Workspace",
                        $_SERVER["home_address"].$_SERVER['PHP_SELF'] => "Project Report"
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
                                    
                                    <div class="content">
                                        <div class="item-list">
                                            <h2>Users with Metrics in this project:</h2>
                                            <ul>
                                            <?php
                                                foreach ($registeredAuthors as $jnUsername => $userObj)
                                                {
                                                    if ($userObj instanceof PersistentUser) {
                                                        $u = new PersistentUser();
                                                        echo "<li><a href=\"./userReport.php?user_id=".$userObj->getUserId()."&project_id=".$_GET['project_id']."\">";
                                                                
                                                        if ($userObj->isOwnerOfProject($project)) {
                                                            echo "<strong>".$userObj->getFirstName()." ".$userObj->getLastName()."</a></strong>";
                                                        }
                                                        else {
                                                            echo $userObj->getFirstName()." ".$userObj->getLastName()."</a>";
                                                        }
                                                        echo "&nbsp;<a href=\"../user/profile/viewProfile.php?userId=".$userObj->getUserId()."\">".
                                                                "<img style=\"border: 0\" src=\"../template/icons/i16/misc/contact.png\" /></a>";
                                                        echo "</li>";
                                                    }
                                                    else {
                                                        echo "<li>$jnUsername</li>\n";
                                                    }
                                                }
                                            ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div><!-- end block-user-3 -->
                            </div><!-- end sidebar-right -->

                            <div id="content">
                                <br />
                                <div class="t"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr">
                                    <div class="content-in">
                                        <h2>Project Metrics Report</h2>
                                        <div style="float: left; width: 300px">
                                            <div style="border: thin groove silver; padding: 15px">
                                                <h3><?php echo "{$_GET['project_id']}&nbsp;&nbsp;<a href=\"https://{$_GET['project_id']}.dev.java.net/\"><img style=\"border: 0\" src=\"../template/icons/i16/misc/world_link.png\" /></a>\n"; ?></h3>
                                                <br />
                                                <h4>Project Summary:</h4>
                                            
                                                <?php
                                                    echo $reportScript;

                                                    echo "<p>";
                                                    if ($project->getSummary() == '') {
                                                        echo "<span style=\"color: gray\">[Empty]</span>";
                                                    }
                                                    else {
                                                        echo $project->getSummary();
                                                    }
                                                    echo "</p>\n";
                                                ?>
                                                </div>
                                                <div style="border: thin groove silver; padding: 10px; margin-top: 10px">
                                                    <div id="ws_pie_chart_div"></div><br />
                                                    <div id="cat_pie_chart_div"></div>
                                                </div>
                                            </div>
                                        
                                            <div style="float: left; width: 420px; border: thin groove silver; padding: 15px; margin-left: 10px">
                                                <div id="bar_chart_div"></div>
                                                <div id="table_chart_div"></div>
                                            </div>
                                        </div>
                                        
                                        <div style="clear: both"></div>

                                        <br /><br />
                                    </div>
                                    <br class="clear" />
                                </div></div></div></div></div></div></div></div>
                            </div>
                        </div>
                        <br />
                      </div>
                      
<?php include '../template/footer.php'; ?>