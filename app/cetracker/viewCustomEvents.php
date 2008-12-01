<?php
/**
 * @author Brett
 */

include '../template/infinitymetrics-bootstrap.php';

#----->>>>>>>>>>>>> Controller Usage for ***** ------------------>>>>>>>>>>>>>>>

require_once 'infinitymetrics/controller/CustomEventController.class.php';
require_once 'infinitymetrics/model/workspace/Project.class.php';
require_once 'infinitymetrics/model/InfinityMetricsException.class.php';

#------------>>>>>>>>>>>>> Variables Initialization ------------->>>>>>>>>>>>>>>

$subUseCase = "View Custom Events";

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html class="js" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en"><head>
<title>Infinity Metrics: <?php echo $subUseCase; ?></title>

<?php include 'static-js-css.php';  ?>
<?php include 'user-signup-header-adds.php' ?>
    </head>
    <body class="<?php /*echo $enableLeftNav ? $leftNavClass : $NoLeftNavClass;*/ ?>">
<?php  include 'top-navigation.php';  ?>


</div></div> <!-- Strange unneeded /divs for PHP -->

<div id="content-wrap">
    <!--div id="inside"-->

               
        <table align="center">
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

                        echo " - ";
                        echo "<a href='";
                        echo "addCustomEventEntry.php?custom_event_id=".$evts->getCustomEventId()."&workspace_id=".$ws_id."'>";
                        echo "Add";
                        echo "</a>";

                    }
                }
            }
        ?>
        </td>
        </tr>
        </tbody>
        </table>
              

</div>

</div> <!-- Strange unneeded /div for PHP -->

<?php include 'footer.php';   ?>