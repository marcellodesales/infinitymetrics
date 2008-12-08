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
    $ce_id = $_GET['custom_event_id'];

    $crit = new Criteria();
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

    <table align=center>
        <tbody align=center>
            <tr align=center>
                <td align=center>
                    <b>
                    Click an entry to remove it.
                    </b><p><b>
                    <?php echo $ce->getTitle(); ?>
                    </b>
                </td>
                <td></td><td></td>
            </tr>
        </tbody>
    </table>
    </div></div>

    <!--====  Main Body Formatting  =========================================-->

    <div class="t"><div class="b"><div class="l"><div class="r"><div class="bl">
    <div class="br"><div class="tl"><div class="tr"><div class="content-in">

    <div id="content-wrap">
        <form id="removecustomevententry" autocomplete="off" method="post" 
            action="<?php echo $PHP_SELF."?custom_event_id=".
            $_GET['custom_event_id']."&workspace_id=".$_GET['workspace_id'] ?>">
            
            <table align="center"><tbody><tr><td>

                <!--====  Main Body  ================================-->

                <?php
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
                    $ce_id = $_GET['custom_event_id'];

                    $crit2 = new Criteria();
                    $crit2->add(PersistentCustomEventEntryPeer::CUSTOM_EVENT_ID,
                        $ce_id);
                    $entries = PersistentCustomEventEntryPeer::doSelect($crit2);

                    foreach ($entries as $entry) {
                        echo " - - <a href='deleteCustomEventEntry.php".
                             "?custom_event_id=".$ce_id."&workspace_id=".
                             $_GET['workspace_id']."&delete_entry_id=".
                             $entry->getEntryID()."'>".$entry->getNotes().
                             "</a><br>";
                    }
                ?>
                
                <!--====  Main Body Close  ==================================-->
                
            </td></tr></tbody></table>
        </form>
    </div>

    </div></div></div></div></div></div></div></div></div>

    <!--====  End of File  ==================================================-->

    </div><?php include 'footer.php'; ?>
</body>

