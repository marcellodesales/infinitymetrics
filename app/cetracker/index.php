<!--====  Top of File  ======================================================-->

<?php
    /**
     * @author Brett <fghtikty@gmail.com>
     */
    include '../template/infinitymetrics-bootstrap.php';

    //===  Includes  =========================================================//

    require_once 'infinitymetrics/controller/CustomEventController.class.php';
    require_once 'infinitymetrics/controller/MetricsWorkspaceController.class.'.
                 'php';
    require_once 'infinitymetrics/model/workspace/Project.class.php';
    require_once 'infinitymetrics/model/InfinityMetricsException.class.php';

    //===  Initialization  ===================================================//

    $subUseCase = "Index";
    $user = $_SESSION["loggedUser"];
    $user_id = $user->getUserId();
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

    <!--====  Main Body Formatting  =========================================-->

    <div id="content-wrap"><div id="content"><div class="t"><div class="b">
    <div class="l"><div class="r"><div class="bl"><div class="br">
    <div class="tl"><div class="tr"><div class="content-in">
    <div class="node-form">

        <table align="center"><tbody><tr><td>

            <!--====  Main Body  ============================================-->

            <?php
                $ws = MetricsWorkspaceController::
                    retrieveWorkspaceCollection($user_id);

                 echo "<font size='4'>Welcome ".$user->getJnUsername().
                      ", you are at the Custom Event Index.</font><br>Please ".
                      "select the workspace you wish to work on.<p><br><p>";

                 echo "Your own workspaces:";
                 echo "</td></tr></table><table>";
   
                 foreach ($ws['OWN'] as $workspace) {
                     echo "<tr><td width='8%'></td><td><a href='viewCustomEven".
                          "ts.php?workspace_id=".$workspace->getWorkspaceId().
                          "'>".$workspace->getTitle()."</a></td></tr>";
                 }

                 echo "</td></tr></table><table><tr height='20'></tr><tr><td>";
                 echo "Your shared workspaces:";
                 echo "</td></tr></table><table>";

                 foreach ($ws['SHARED'] as $workspace) {
                     echo "<tr><td width='8%'></td><td><a href='viewCustomEven".
                          "ts.php?workspace_id=".$workspace->getWorkspaceId().
                          "'>".$workspace->getTitle()."</a></td></tr>";
                 }
            ?>

            <!--====  Main Body Close  ======================================-->

        </tbody></table>

    </div></div></div><br class="clear"></div></div></div></div></div></div>
    </div></div></div>

    <!--====  End of File  ==================================================-->

    <?php include 'footer.php'; ?>
</body>