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

    $subUseCase = "View Custom Event";
    $ws_id = $_GET['workspace_id'];
    $ce_id = $_GET['custom_event_id'];
    $crit = new Criteria();
    $crit->add(PersistentCustomEventPeer::CUSTOM_EVENT_ID, $ce_id);
    $ce = PersistentCustomEventPeer::doSelectOne($crit);

    $crit->clear();
    $crit->add(PersistentWorkspacePeer::WORKSPACE_ID, $ws_id);
    $ws = PersistentWorkspacePeer::doSelectOne($crit);
    $ws_title = $ws->getTitle();
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
    </div></div>

    <!--====  Main Body Formatting  =========================================-->

    <div id="content-wrap"><div id="content"><div class="t"><div class="b">
    <div class="l"><div class="r"><div class="bl"><div class="br">
    <div class="tl"><div class="tr"><div class="content-in">
    <div class="node-form">

        <table align="center"><tbody><tr><td>

            <!--====  Main Body  ========================================-->

            <?php
                echo "<font size='4'>Custom Event: ".$ce->getTitle().
                     "</font> - ".$ce->getDate();

                $crit->clear();
                $crit->add(PersistentCustomEventEntryPeer::CUSTOM_EVENT_ID,
                    $ce_id);
                $entries = PersistentCustomEventEntryPeer::doSelect($crit);

                echo " - <a href='editCustomEvent.php?custom_event_id=".$ce_id.
                     "&workspace_id=".$ws_id."' style='text-decoration: none;".
                     "'><input value='Edit' class='form-submit' type='button'>".
                     "</a>";

                echo " <a href='addCustomEventEntry.php?custom_event_id=".
                     $ce_id."&workspace_id=".$ws_id."' style='text-decoration:".
                     " none;'><input value='Add Entry To' class='form-submit' ".
                     "type='button'></a><br>";
                
                foreach ($entries as $entry) {
                    echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;Custom Event Entry: ".
                         $entry->getNotes()." - ".$entry->getDate();

                    echo " - <a href='deleteCustomEventEntry.php?entry_id=".
                         $entry->getEntryId()."&workspace_id=".$ws_id."' style".
                         "='text-decoration: none;'><input id='edit-delete' va".
                         "lue='Delete' class='form-submit' type='button'></a>";
                }
            ?>

            <!--====  Main Body Close  ======================================-->

        </td></tr></tbody></table>

        <hr />
        <?php
            echo "Goto: ";
            echo "<a href='index.php' style='text-decoration: none;'><input".
                 " value='CE Index' class='form-submit' type='button'></a>";
            echo "<a href='viewCustomEvents.php?workspace_id=".$ws_id."' style".
                 "='text-decoration: none;'><input value='".$ws_title."' class='".
                 "form-submit' type='button'></a>";
        ?>

    </div></div></div><br class="clear"></div></div></div></div></div></div>
    </div></div></div>

    <!--====  End of File  ==================================================-->

    <?php include 'footer.php'; ?>
</body>