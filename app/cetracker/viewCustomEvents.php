<?php
/**
 * @author Brett
 */

include '../template/infinitymetrics-bootstrap.php';



#----->>>>>>>>>>>>> Controller Usage for ***** ------------------>>>>>>>>>>>>>>>

/*if (isset($_POST["username"]) && isset($_POST["password"])) {

    require_once 'infinitymetrics/controller/CustomEventController.class.php';
    require_once 'infinitymetrics/model/InfinityMetricsException.class.php';

   try {  //?
        $user = UserManagementController::login($_POST["username"], $_POST["password"]);
        session_start();
        $_SESSION["loggedUser"] = $user;
        echo header('Location: ../workspace/workspaceCollection.php');
    } catch (Exception $e) {
        $_SESSION["signinError"] = $e;
    }
}*/

    require_once 'infinitymetrics/controller/CustomEventController.class.php';
    require_once 'infinitymetrics/model/workspace/Project.class.php';
    require_once 'infinitymetrics/model/InfinityMetricsException.class.php';

#------------>>>>>>>>>>>>> Variables Initialization ------------->>>>>>>>>>>>>>>



    //$subUseCase = "User Login ";
    //$enableLeftNav = true;

    #breadscrum[URL] = Title
    //$breakscrum = array(
    //                   $_SERVER["home_address"] => "Home",
    //                    $_SERVER["home_address"]."/user" => "Users Login",
    //              );

    #leftMenu[n]["active"] - If the menu item is active or not
    #leftMenu[n]["url"] - the URL for the menu item
    #leftMenu[n]["item"] - the item of the menu
    #leftMenu[n]["tip"] - the tooltip of the URL
    //$leftMenu = array();
    //array_push($leftMenu, array("active"=>"menu-27 first active", "url"=>"login.php", "item"=>"1. Java.net Authentication", "tip"=>"Manage your site's book outlines."));

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html class="js" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en"><head>
<title>Infinity Metrics: <?php echo $subUseCase; ?></title>

<?php include 'static-js-css.php';  ?>
<?php include 'user-signup-header-adds.php' ?>
    </head>
    <body class="<?php /*echo $enableLeftNav ? $leftNavClass : $NoLeftNavClass;*/ ?>">
<?php  include 'top-navigation.php';  ?>

    <!--div id="breadcrumb" class="alone">
    <h2 id="title">Home</h2>
    <div class="breadcrumb"-->

<?php
   /* $totalBreadscrum = count(array_keys($breakscrum)); $idx = 0;
    foreach (array_keys($breakscrum) as $keyUrl) {
        echo "<a href=\"" . $keyUrl . "\"> " . $breakscrum[$keyUrl] . "</a> ".
        (++$idx < $totalBreadscrum ? "» " : " ");
    }*/
?>

</div></div> <!-- Strange unneeded /divs for PHP -->

<div id="content-wrap">
    <!--div id="inside"-->
        <div id="sidebar-left">
            <!--ul id="rootcandy-menu"-->
               
                <table border="1" align="center">
                <tbody>
                <tr>
	  			<td>
                <?php

                    $ws_id = $_GET['workspace_id'];

                    $crit = new Criteria(PersistentWorkspacePeer::WORKSPACE_ID, $ws_id);
                    $ws = PersistentWorkspacePeer::doSelect($crit);
                
 
                    foreach ($ws as $wwss) {
                        echo "<br>Workspace: ";

                        
                        echo $wwss->getTitle();
                        

                        $crit2 = new Criteria(PersistentProjectPeer::PROJECT_JN_NAME, $wwss->getProjectJnName());
                        $proj = PersistentProjectPeer::doSelect($crit2);

                        foreach ($proj as $pproj) {
                            echo "<br> - - Project: ";
                            echo "<a href='";
                            echo "addCustomEvent.php?project_jn_name=".$pproj->getProjectJnName()."&parent_project_jn_name=";
                            echo $pproj->getParentProjectJnName()?$pproj->getParentProjectJnName():"null";
                            echo "&workspace_id=".$ws_id."'>";
                            echo $pproj->getSummary();
                            echo "</a>";

                            $evt = $pproj->getCustomEvents();

                            foreach ($evt as $evts) {
                                echo "<br> - - - - Custom Event: ";
                                echo $evts->getTitle();

                            }
                        }
                    }
                ?>
                </td>
                </tr>
                </tbody>
                </table>
              
            <!--/ul-->
        </div><!-- end sidebar-left -->

        <!--div id="sidebar-right">
            <div id="block-user-3" class="block block-user">
            <h2>All users are welcomed</h2>
                <div class="content" align="center">
                    <img src="../../template/images/techglobe2.jpg">
                </div>
            </div>
        </div-->

    <!--/div-->
    <!--div id="content">
        <!--div class="t">
            <div class="b">
                <div class="l">
                    <div class="r">
                        <div class="bl">
                            <div class="br">
                                <div class="tl">
                                    <div class="tr">
                                        <div class="content-in">
                                            <div>
                                                <div class="node-form">
                                                    <form id="signinform" autocomplete="off" method="post" action="<!--?php echo $PHP_SELF ?>"-->
                                                         <?php /*
                                                            if (isset($_SESSION["signinError"]) && $_SESSION["signinError"] != "") {
                                                                echo "<div class=\"messages error\">".$_SESSION["signinError"]."</div>";
                                                                $_SESSION["signinError"] = "";
                                                                unset($_SESSION["signinError"]);
                                                            } */
                                                        ?>
                                                        <!-- <table align="center">
                                                            <tbody>
                                                                <tr>
                                                                    <td class="status" width="30">&nbsp;</td>
                                                                    <td class="label" width="20"><label id="lusername" for="username">Username</label></td>
                                                                    <td class="field"><input id="username" name="username" class="textfield" value="" maxlength="50" type="text"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="status"></td>
                                                                    <td class="label"><label id="lpassword" for="password">Password</label></td>
                                                                    <td class="field"><input id="password" name="password" class="textfield" maxlength="50" value="" type="password"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>&nbsp;</td>
                                                                    <td colspan="2">
                                                                        <input id="edit-submit" value="Login" class="form-submit" type="submit">
                                                                        <input id="edit-delete" value="Cancel" class="form-submit" type="button" onclick="document.location='..'">
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table> -->
                                                    <!--/form>
                                                </div>
                                            </div> <!-- End of blue box --> 
                                        <!-- /div -->
                                        <!-- br class="clear">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- End section -->
    <!--/div-->
</div>

</div> <!-- Strange unneeded /div for PHP -->

<?php include 'footer.php';   ?>