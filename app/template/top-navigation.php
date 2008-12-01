
<!-- Layout -->
    <div id="page-wrapper">
        <div id="page-wrapper-content">
            <div id="header">
                <div id="go-home">
                  <a href="<?php echo $_SERVER["home_address"]; ?>">Go Back to Homepage</a>
                </div>
                <div id="admin-links">
                  <a href="<?php echo $_SERVER["home_address"]; ?>" class="user-name"><strong>(DEMO-TEMPLATE)</strong></a> |
                  <a href="<?php echo $_SERVER["home_address"]; ?>">Logout</a>
                </div>
                <div id="header-title">
                    <?php include 'header-title.php' ?>
                </div>
            </div>
            <div id="navigation">
                <ul>
                     <?php include 'top-navigation-menu.php'; ?>
                </ul>
                <img src="/static/images/infinity.png" width="32" height="32" alt="rootcandy test" id="logo" />
            </div>
                
