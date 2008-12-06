<?php
    $isHome = !contains($_SERVER["REQUEST_URI"], "help") &&  !contains($_SERVER["REQUEST_URI"], "config") &&
              !contains($_SERVER["REQUEST_URI"], "cetracker") && !contains($_SERVER["REQUEST_URI"], "report") &&
              !contains($_SERVER["REQUEST_URI"], "user") && !contains($_SERVER["REQUEST_URI"], "workspace");
?>
<!-- Layout -->
    <div id="page-wrapper">
        <div id="page-wrapper-content">
            <div id="header">
                <div id="go-home">
<?php if ($isHome) { ?>
                  Welcome Home
<?php    } else { ?>
                  <a href="<?php echo $_SERVER["home_address"]; ?>">Go Back to Homepage</a>
<?php    } ?>
                </div>
                <div id="admin-links">
<?php if (isUserLoggedIn()) { ?>
                  <strong><a href="<?php echo $_SERVER["home_address"].'/user/logout.php' ?>">Logout</a></strong>
<?php } ?>
                </div>
                <div id="header-title">
                    <?php include 'header-title.php' ?>
                </div>
            </div>
            <div id="navigation">
                <ul>
                     <?php include 'top-navigation-menu.php'; ?>
                </ul>
                
            </div>
                
