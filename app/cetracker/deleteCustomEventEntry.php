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

    $subUseCase = "Remove Custom Event Entry";
    $entry_id = $_GET['entry_id'];

    $crit = new Criteria();
    $crit->add(PersistentCustomEventEntryPeer::ENTRY_ID, $entry_id);
    $entry = PersistentCustomEventEntryPeer::doSelectOne($crit);

    $ce_id = $entry->getCustomEventId();
    $crit->clear();
    $crit->add(PersistentCustomEventPeer::CUSTOM_EVENT_ID, $ce_id);
    $ce = PersistentCustomEventPeer::doSelectOne($crit);
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
    <?php include 'top-navigation.php';  ?>

    <table><tbody><tr>
        <td align=center width="30%">
                    <b><?php echo $ce->getTitle(); ?></b>
       </td><td width="60%">&nbsp;</td>
    </tr></tbody></table>
    </div></div>

    <!--====  Main Body Formatting  =========================================-->

    <div id="content-wrap"><div id="content"><div class="t"><div class="b">
    <div class="l"><div class="r"><div class="bl"><div class="br">
    <div class="tl"><div class="tr"><div class="content-in">
    <div class="node-form">

        <form id="removecustomevententry" autocomplete="off" method="post" 
            action="<?php echo $PHP_SELF."?custom_event_id=".
            $ce_id."&workspace_id=".$_GET['workspace_id'] ?>">
            
            <table align="center"><tbody><tr><td>

                <!--====  Main Body  ================================-->

                <?php
                    echo "<b>Click the entry to confirm removal.</b><p>";

                    if (isset($_GET['delete_entry_id'])) {
                        CustomEventController::removeEntry(
                            $_GET['delete_entry_id']);

                        $_SESSION["successMessage"]="Entry removal successful!";
                        echo "<div class=\"messages ok\">".
                            $_SESSION["successMessage"]."</div>";
                        $_SESSION["successMessage"] = "";
                        unset($_SESSION["successMessage"]);
                    }

                    echo $ce->getTitle()."<br>";

                    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='deleteCus".
                         "tomEventEntry.php".
                         "?entry_id=".$entry_id."&workspace_id=".
                         $_GET['workspace_id']."&delete_entry_id=".
                         $entry_id."'>".$entry->getNotes().
                         "</a><br>";
                ?>
                
                <!--====  Main Body Close  ==================================-->
                
            </td></tr></tbody></table>
        </form>

    </div></div></div><br class="clear"></div></div></div></div></div></div>
    </div></div></div>

    <!--====  End of File  ==================================================-->

    <?php include 'footer.php'; ?>
</body>

