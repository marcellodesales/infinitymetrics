<?php
require_once 'infinitymetrics/model/user/UserTypeEnum.class.php';

    $liWorkspace = contains($_SERVER["REQUEST_URI"], "workspace") ||
                   contains($_SERVER["REQUEST_URI"], "report") ? "<li id=\"current\">" : "<li>";
    $liCeTracker = contains($_SERVER["REQUEST_URI"], "cetracker") ? "<li id=\"current\">" : "<li>";
    $liUser      = contains($_SERVER["REQUEST_URI"], "user") ? "<li id=\"current\">" : "<li>";
    $liHelp      = contains($_SERVER["REQUEST_URI"], "help") ? "<li id=\"current\">" : "<li>";
    $liHome      = !contains($_SERVER["REQUEST_URI"], "help") &&
                   !contains($_SERVER["REQUEST_URI"], "cetracker") && 
                   !contains($_SERVER["REQUEST_URI"], "report") &&
                   !contains($_SERVER["REQUEST_URI"], "workspace") &&
                   !contains($_SERVER["REQUEST_URI"], "user") ? "<li id=\"current\">" : "<li>";
?>
                        <?php echo $liHome; ?><a href="<?php echo $_SERVER["home_address"] ?>">
                                <img src="<?php echo $_SERVER["home_address"] ?>/template/edit_files/home.png" alt="" title="" width="32" height="32" style="border: 0" />
                                <br />Home</a>
                        </li>
<?php
if (isUserLoggedIn()) {
?>
                        <?php echo $liWorkspace; ?><a href="<?php echo $_SERVER["home_address"] ?>/workspace/workspaceCollection.php">
                                <img src="<?php echo $_SERVER["home_address"]; ?>/template/edit_files/admin-reports.png" alt="" title="" width="32" height="32" style="border: 0" />
                                <br />Workspace</a>
                        </li>
<?php
    $user = $_SESSION["loggedUser"];
    if ($user->getType() == UserTypeEnum::getInstance()->INSTRUCTOR) { ?>
                        <?php echo $liCeTracker; ?><a href="<?php echo $_SERVER["home_address"]; ?>/cetracker/index.php">
                                <img src="<?php echo $_SERVER["home_address"]; ?>/template/edit_files/node-add.png" alt="" title="" width="32" height="32" style="border: 0" />
                                <br />Custom Events</a>
                        </li>

<?php }
}
?>
                        <?php echo $liUser; ?><a href="<?php echo $_SERVER["home_address"] ?>/user">
                                <img src="<?php echo $_SERVER["home_address"]; ?>/template/edit_files/admin-user.png" alt="" title="" width="32" height="32" style="border: 0" />
                                <br />Users</a>
                        </li>
                        <?php echo $liHelp ?><a href="<?php echo $_SERVER["home_address"] ?>/help">
                                <img src="<?php echo $_SERVER["home_address"]; ?>/template/edit_files/admin-help.png" alt="" title="" width="32" height="32" style="border: 0" />
                                <br />Help</a>
                        </li>
