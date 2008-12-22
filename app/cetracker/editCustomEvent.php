<!--====  Top of File  ======================================================-->

<?php
    /**
     * @author Brett <fghtikty@gmail.com>
     */
    include '../template/infinitymetrics-bootstrap.php';

    //===  Includes  =========================================================//

    require_once 'infinitymetrics/controller/CustomEventController.class.php';
    require_once 'infinitymetrics/model/workspace/Project.class.php';
    require_once 'infinitymetrics/model/InfinityMetricsException.class.php';

    //===  Initialization  ===================================================//

    $temp = false;
    $subUseCase = "Edit Custom Event";

    $ce_id =  $_GET['custom_event_id'];
    $tempevent = PersistentCustomEventPeer::retrieveByPK($ce_id);

    $ws_id = $_GET['workspace_id'];
    $c = new Criteria();
    $c->add(PersistentWorkspacePeer::WORKSPACE_ID, $ws_id);
    $ws = PersistentWorkspacePeer::doSelectOne($c);
    $ws_title = $ws->getTitle();

    //===  Action Listener  ==================================================//

    if (isset($_POST["newTitle"])) {

        $tempbool = false;
        if(isset($_POST['tog'])) {
            $tempbool = true;
        }

        try {
            $temp = CustomEventController::modifyEvent($tempevent,$tempbool,
                $_POST['newTitle']);
            $_SESSION["successMessage"] = "Data entry successful!";
        }
        catch (Exception $e) {
            $_SESSION["Data entry error."] = $e;
        }
    }
?>

<!--====  Formatting  =======================================================-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html class="js" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en">

<!--====  Header  ===========================================================-->

<head>
    <title>
        Infinity Metrics: <?php echo $subUseCase; ?>
    </title>

    <?php include 'static-js-css.php';  ?>
    <?php include 'user-signup-header-adds.php' ?>
</head>

<!--====  Top of Body  ======================================================-->

<body>
    <?php  include 'top-navigation.php';  ?>

    <table><tbody><tr>
        <td align=center width="60%">
            <b>
                <font size='4'>Now editing:
                <?php
                    echo $tempevent->getTitle()."&nbsp;&nbsp;&nbsp;&nbsp;".
                         "&nbsp;&nbsp;Status: ".$tempevent->getState();
                ?>
                </font>
            </b>
       </td><td width="60%">&nbsp;</td>
    </tr></tbody></table>
    </div></div>

    <!--====  Notifications  ================================================-->

    <?php
        if (isset($_SESSION["Data entry error."]) &&
            $_SESSION["Data entry error."] != "") {

            echo "<div class=\"messages error\">".
                $_SESSION["Data entry error."]."</div>";
            $_SESSION["Data entry error."] = "";
            unset($_SESSION["Data entry error."]);
        }
    ?>

    <?php
        if (isset($_SESSION["successMessage"]) &&
            $_SESSION["successMessage"] != "") {

            echo "<div class=\"messages ok\">".
                $_SESSION["successMessage"]."</div>";
            $_SESSION["successMessage"] = "";
            unset($_SESSION["successMessage"]);
        }
    ?>

    <!--====  Main Body Formatting  =========================================-->

    <div id="content-wrap"><div id="content"><div class="t"><div class="b">
    <div class="l"><div class="r"><div class="bl"><div class="br">
    <div class="tl"><div class="tr"><div class="content-in">
    <div class="node-form">

        <form id="createcustomevententry" autocomplete="off" method="post" 
            action="<?php echo $PHP_SELF."?custom_event_id=".
            $ce_id."&workspace_id=".$ws_id ?>">
            <table align="center"><tbody>

                <!--====  Main Body  ========================================-->

                <tr>
                    <td class="label" width="70"><label id="lnewtitle"
                        for="newTitle">New Title</label></td>
                    <td class="field"><input id="newTitle" name="newTitle"
                        type="textfield" value="" maxlength="50" type="text">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        Toggle: <input type="checkbox" name="tog">
                        &nbsp;(Check here if you want the change the status between Open and Resolved.)
                        <p>
                        <input id="edit-submit" value="OK" class="form-submit"
                            type="submit">
                        <p>
                        <input id="edit-delete" value="Cancel" 
                            class="form-submit" type="button"
                            onclick="document.location= <?php echo
                            "'viewCustomEvents.php?workspace_id=".
                            $ws_id."'" ?>">
                    </td>
                </tr>
                
                <!--====  Main Body Close  ==================================-->

            </tbody></table>
        </form>

        <hr />
        <?php
            echo "Goto: ";
            echo "<a href='index.php' style='text-decoration: none;'><input".
                 " value='CE Index' class='form-submit' type='button'></a>";
            echo "<a href='viewCustomEvents.php?workspace_id=".$ws_id."' style".
                 "='text-decoration: none;'><input value='".$ws_title."'".
                 " class='form-submit' type='button'></a>";
            echo "<a href='viewCustomEvent.php?custom_event_id=".$ce_id.
                 "&workspace_id=".$ws_id."' style='text-decoration: none;'>".
                 "<input value='".$tempevent->getTitle()."' class='".
                 "form-submit' type='button'></a>";
        ?>

    </div></div></div><br class="clear"></div></div></div></div></div></div>
    </div></div></div>

    <!--====  End of File  ==================================================-->

    <?php include 'footer.php'; ?>
</body>