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
    $subUseCase = "Add Custom Event Entry";
    $ce_id = $_GET['custom_event_id'];

    //===  Action Listener  ==================================================//

    if (isset($_POST["notes"])) {

        try {
            $temp = CustomEventController::createEntry($_POST['notes'],
                $ce_id);
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
        <td align=center width="30%">
            <b><?php
                $crit = new Criteria();
                $crit->add(PersistentCustomEventPeer::CUSTOM_EVENT_ID, $ce_id);
                $ce = PersistentCustomEventPeer::doSelectOne($crit);
                echo $ce->getTitle()
            ?></b>
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
            $ce_id."&workspace_id=".$_GET['workspace_id'] ?>">

            <table align="center"><tbody>

                <!--====  Main Body  ========================================-->
                <tr>
                    <td class="status" width="30">&nbsp;</td>
                    <td class="label" width="20"><label id="lnotes" for="notes">
                        Notes</label></td>
                    <td class="field"><input id="notes" name="notes" 
                        class="textfield" value="" maxlength="50" type="text">
                        </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input id="edit-submit" value="OK" class="form-submit"
                            type="submit">
                        <p>
                        <input id="edit-delete" value="Cancel" 
                            class="form-submit" type="button"
                            onclick="document.location= <?php echo
                            "'viewCustomEvents.php?workspace_id=".
                            $_GET['workspace_id']."'" ?>">
                    </td>
                </tr>

                <!--====  Main Body Close  ==================================-->

            </tbody></table>
        </form>

    </div></div></div><br class="clear"></div></div></div></div></div></div>
    </div></div></div>

    <!--====  End of File  ==================================================-->

    <?php include 'footer.php';   ?>
</body>