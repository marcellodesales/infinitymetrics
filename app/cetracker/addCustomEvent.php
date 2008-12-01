<?php
/**
 * @author Brett
 */

include '../template/infinitymetrics-bootstrap.php';

$temp = false;

#----->>>>>>>>>>>>> Controller Usage for ***** ------------------>>>>>>>>>>>>>>>

require_once 'infinitymetrics/controller/CustomEventController.class.php';
require_once 'infinitymetrics/model/workspace/Project.class.php';
require_once 'infinitymetrics/model/InfinityMetricsException.class.php';

if (isset($_POST["notes"]) && isset($_POST["title"])) {

    $temp = CustomEventController::createEvent($_POST['notes'],$_POST['title'],$_GET['project_jn_name'],$_GET['parent_project_jn_name']);
}

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

    <form id="createcustomevent" autocomplete="off" method="post" action="<?php echo $PHP_SELF."?project_jn_name=".$_GET['project_jn_name']."&parent_project_jn_name=".$_GET['parent_project_jn_name'] ?>">
  
        <table align="center">
            <tbody>
                <tr>
                    <td class="status" width="30">&nbsp;</td>
                    <td class="label" width="20"><label id="ltitle" for="title">Title</label></td>
                    <td class="field"><input id="title" name="title" class="textfield" value="" maxlength="50" type="text"></td>
	  		    </tr>
                <tr>
                    <td class="status" width="30">&nbsp;</td>
                    <td class="label" width="20"><label id="lnotes" for="notes">Notes</label></td>
                    <td class="field"><input id="notes" name="notes" class="textfield" value="" maxlength="50" type="text"></td>
	  		    </tr>
                <tr>
                    <td colspan="2">
                        <input id="edit-submit" value="OK" class="form-submit" type="submit">
                        <p>
                        <a href= "<?php echo 'viewCustomEvents.php?workspace_id='.$_GET['workspace_id'] ?>" ><input id="edit-delete" value="Cancel" class="form-submit" type="button" ></a>
                    </td>
                </tr>
	  		</tbody>
        </table>


    </form>
    <?php echo $temp?"Successfully added custom event to the project ".$_GET['project_jn_name']."!":""; ?>

</div>

</div> <!-- Strange unneeded /div for PHP -->

<?php include 'footer.php';   ?>