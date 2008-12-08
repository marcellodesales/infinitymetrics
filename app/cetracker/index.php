<!--====  Top of File  ======================================================-->

<?php
    /**
     * @author Brett <fghtikty@gmail.com>
     */
    include '../template/infinitymetrics-bootstrap.php';

    //===  Includes  =========================================================//

    require_once 'infinitymetrics/controller/CustomEventController.class.php';
    require_once 'infinitymetrics/controller/MetricsWorkspaceController.php';
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
    </div></div>

    <!--====  Main Body Formatting  =========================================-->

    <div class="t"><div class="b"><div class="l"><div class="r"><div class="bl">
    <div class="br"><div class="tl"><div class="tr"><div class="content-in">

    <div id="content-wrap">
        <table align="center"><tbody><tr><td>

            <!--====  Main Body  ========================================-->

            <?php
                $ws = MetricsWorkspaceController::
                    retrieveWorkspaceCollection(321/*$user_id*/);

                 echo "Your own workspaces:<br>";
                 foreach ($ws['OWN'] as $wwss) {
                     echo " - <a href='viewCustomEvents.php".
                         "?workspace_id=".$wwss->getWorkspaceId().
                         "'>".$wwss->getTitle()."</a>";
                 }


                 echo "<p>Your shared workspaces:<br>";
                 foreach ($ws['SHARED'] as $wwss) {
                     echo " - <a href='viewCustomEvents.php".
                         "?workspace_id=".$wwss->getWorkspaceId().
                         "'>".$wwss->getTitle()."</a>";
                 }
            ?>

            <!--====  Main Body Close  ======================================-->

        </td></tr></tbody></table>
    </div>

    </div></div></div></div></div></div></div></div></div>

    <!--====  End of File  ==================================================-->

    </div><?php include 'footer.php'; ?>
</body>