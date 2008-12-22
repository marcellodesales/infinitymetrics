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

    $subUseCase = "View Custom Events";
    $projectFlag = false;
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

            <!--====  Main Body  ============================================-->

            <?php
                $ws_id = $_GET['workspace_id'];
                $crit = new Criteria(PersistentWorkspacePeer::WORKSPACE_ID,
                    $ws_id);
                $ws = PersistentWorkspacePeer::doSelect($crit);

                foreach ($ws as $wwss) {
                    echo "<font size='4'>Workspace: ".$wwss->getTitle()."</font>";
                    $crit2= new Criteria(PersistentProjectPeer::PROJECT_JN_NAME,
                        $wwss->getProjectJnName());
                    $proj = PersistentProjectPeer::doSelect($crit2);

                    foreach ($proj as $pproj) {
                        echo "<br>&nbsp;&nbsp;&nbsp;Project: ".
                             $pproj->getProjectJnName();

                        if ($projectFlag) {
                            echo " - <a href='addCustomEvent.php?project_jn_na".
                                "me=".$pproj->getProjectJnName().
                                 "&parent_project_jn_name=";

                            echo $pproj->getParentProjectJnName()?
                                 $pproj->getParentProjectJnName():"null";

                            echo "&workspace_id=".$ws_id."' style='text-decoration: none;'><input value='Add To' class='form-submit' type='button'></a>";
                        }
                        else {
                            $projectFlag = true;
                        }

                        $evt = $pproj->getCustomEvents();

                        foreach ($evt as $evts) {
                            echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".
                                 "Custom Event: ".$evts->getTitle();

                            echo " - <a href='viewCustomEvent.php?custom_event".
                                 "_id=".$evts->getCustomEventId()."&workspace_".
                                 "id=".$ws_id."' style='text-decoration: none;".
                                 "'><input value='View' class='form-submit' ty".
                                 "pe='button'></a>";
                        }
                    }
                }
            ?>

            <!--====  Main Body Close  ======================================-->

        </td></tr></tbody></table>

        <hr />
        <?php
            echo "Goto: ";
            echo "<a href='index.php' style='text-decoration: none;'><input".
                 " value='CE Index' class='form-submit' type='button'></a>";
        ?>
        
    </div></div></div><br class="clear"></div></div></div></div></div></div>
    </div></div></div>

    <!--====  End of File  ==================================================-->

    <?php include 'footer.php'; ?>
</body>