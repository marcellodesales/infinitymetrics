<?php
    $liWorkspace = contains($_SERVER["REQUEST_URI"], "workspace") ? "<li id=\"current\">" : "<li>";
    $liReports = contains($_SERVER["REQUEST_URI"], "report") ? "<li id=\"current\">" : "<li>";
    $liCeTracker = contains($_SERVER["REQUEST_URI"], "cetracker") ? "<li id=\"current\">" : "<li>";
    $liUser = contains($_SERVER["REQUEST_URI"], "user") ? "<li id=\"current\">" : "<li>";
    $liConfig = contains($_SERVER["REQUEST_URI"], "config") ? "<li id=\"current\">" : "<li>";
    $liHelp = contains($_SERVER["REQUEST_URI"], "help") ? "<li id=\"current\">" : "<li>";
    $liHome = !contains($_SERVER["REQUEST_URI"], "help") &&  !contains($_SERVER["REQUEST_URI"], "config") &&
                !contains($_SERVER["REQUEST_URI"], "cetracker") && !contains($_SERVER["REQUEST_URI"], "report") &&
                !contains($_SERVER["REQUEST_URI"], "user") && !contains($_SERVER["REQUEST_URI"], "workspace")
                ? "<li id=\"current\">" : "<li>";
?>
                        <?php echo $liHome; ?><a href="/">
                                <img src="/template/edit_files/home.png" alt="" title="" width="32" height="32" border="0">
                                <br>Home</a>
                        </li>
<?php
if (isUserLoggedIn()) {
?>
                        <?php echo $liWorkspace; ?><a href="/workspace/workspaceCollection.php">
                                <img src="/template/edit_files/logo.png" alt="" title="" width="32" height="32" border="0">
                                <br>Workspace</a>
                        </li>
                        <?php echo $liReports; ?><a href="/report">
                                <img src="/template/edit_files/admin-reports.png" alt="" title="" width="32" height="32" border="0">
                                <br>Reports</a>
                        </li>
                        <?php echo $liCeTracker; ?><a href="/cetracker">
                                <img src="/template/edit_files/node-add.png" alt="" title="" width="32" height="32" border="0">
                                <br>Custom Events</a>
                        </li>
<?php } ?>
                        <?php echo $liUser; ?><a href="/user">
                                <img src="/template/edit_files/admin-user.png" alt="" title="" width="32" height="32" border="0">
                                <br>Users</a>
                        </li>
<?php if (isUserLoggedIn()) { ?>
                        <?php echo $liConfig ?><a href="/config">
                                <img src="/template/edit_files/admin-settings.png" alt="" title="" width="32" height="32" border="0">
                                <br>Configuration</a>
                        </li>
<?php } ?>
                        <?php echo $liHelp ?><a href="/help">
                                <img src="/template/edit_files/admin-help.png" alt="" title="" width="32" height="32" border="0">
                                <br>Help</a>
                        </li>
