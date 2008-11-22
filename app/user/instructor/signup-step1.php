<?php
    include 'infinitymetrics-bootstrap.php';

#----------------------------->>>>>>>>>>>>> Controller Usage for UC404 ----------------------------->>>>>>>>>>>>>>>

    if (isset($_POST["username"]) && isset($_POST["password"])) {

        require_once 'infinitymetrics/controller/UserManagementController.class.php';
        require_once 'infinitymetrics/model/InfinityMetricsException.class.php';

        try {
            $existentUser = PersistentUserPeer::retrieveByJNUsername($_POST["username"]);
            if (isset($existentUser) && $existentUser->getJnUsername() == $_POST["username"]) {
                $_SESSION["signupError"] = "User ".$_POST["username"]." already registered at Infinity Metrics";
            } else {

                $userAgent = UserManagementController::authenticateJNUser($_POST["username"], $_POST["password"]);

                if ($userAgent->areUserCredentialsValidOnJN()) {

                    $userAgentAuthenticated["email"] = $userAgent->getAuthenticatedEmail();
                    $userAgentAuthenticated["projects"] = $userAgent->getAuthenticatedProjectsMembershipList();
                    $userAgentAuthenticated["jnUsername"] = $userAgent->getUser()->getJnUsername();
                    $userAgentAuthenticated["jnPassword"] = $userAgent->getUser()->getJnPassword();
                    $_SESSION["userAgentAuthenticated"] = $userAgentAuthenticated;

                    header('Location: /user/instructor/signup-step2.php');
                } else {
                    $_SESSION["signupError"] = "Authentication failed with Java.net";
                }
            }
        } catch (InfinityMetricsException $ime) {
            $_SESSION["signupError"] = $ime;
        }
    }

#----------------------------->>>>>>>>>>>>> Variables Initialization ------------------->>>>>>>>>>>>>>>

    $subUseCase = "Instructor Registration";
    $enableLeftNav = true;

    #breadscrum[URL] = Title
    $breakscrum = array(
                        "/" => "Home",
                        "/user" => "Users Registration",
                        "/user/instructor/signup-step1.php" => "Instructor Registration"
                  );

    #leftMenu[n]["active"] - If the menu item is active or not
    #leftMenu[n]["url"] - the URL for the menu item
    #leftMenu[n]["item"] - the item of the menu
    #leftMenu[n]["tip"] - the tooltip of the URL
    $leftMenu = array();
    array_push($leftMenu, array("active"=>"menu-27 first active", "url"=>"/user/instructor/signup-step1.php", "item"=>"1. Java.net Authentication", "tip"=>"Manage your site's book outlines."));
    array_push($leftMenu, array("active"=>"menu-27", "url"=>"/user/instructor/signup-step2.php", "item"=>"2. Update Profile", "tip"=>"Update and review your profile info"));
    array_push($leftMenu, array("active"=>"menu-27", "url"=>"/user/instructor/signup-step3.php", "item"=>"3. Confirm Registration", "tip"=>"Confirm you profile"));
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html class="js" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en"><head>
<title>Infinity Metrics: <?php echo $subUseCase; ?></title>

<?php include 'static-js-css.php';  ?>

<?php include 'user-signup-header-adds.php' ?>

</head>
<body class="<?php echo $enableLeftNav ? $leftNavClass : $NoLeftNavClass; ?>">

<?php  include_once 'top-navigation.php';  ?>

                  <div id="breadcrumb" class="alone">
                    <h2 id="title">Home</h2>
                    <div class="breadcrumb">
<?php
                        $totalBreadscrum = count(array_keys($breakscrum)); $idx = 0;
                        foreach (array_keys($breakscrum) as $keyUrl) {
                              echo "<a href=\"" . $keyUrl . "\"> " . $breakscrum[$keyUrl] . "</a> ".
                                (++$idx < $totalBreadscrum ? "» " : " ");
                        }
?>
                    </div>
                  </div>

                  <div id="content-wrap">
                    <div id="inside">

                        <div id="sidebar-left">
                            <ul id="rootcandy-menu">
                                <?php
                                foreach ($leftMenu as $menuItem) {
                                    echo "<li class=\"".$menuItem["active"]."\"><a href=\"".$menuItem["url"]."\"
                                              title=\"".$menuItem["tip"]."\">".$menuItem["item"]."</a></li>";
                                }
                                ?>
                            </ul>
                        </div><!-- end sidebar-left -->

                        <div id="sidebar-right">
                          <div id="block-user-3" class="block block-user">
                              <h2>All users are welcomed</h2>
                              <div class="content" align="center">
                                <img src="/template/images/techglobe2.jpg">
                              </div>
                          </div>
                        </div>

                    </div>
                    <div id="content">
<div class="t"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr"><div class="content-in">

<div>
<div class="node-form">

      		<form id="signupform" autocomplete="off" method="post" action="<?php echo $PHP_SELF ?>">
<?php
        if (isset($_SESSION["signupError"]) && $_SESSION["signupError"] != "") {
             echo "<div class=\"messages error\">".$_SESSION["signupError"]."</div>";
             $_SESSION["signupError"] = "";
             unset($_SESSION["signupError"]);
        }
?>
	  		  <table align="center">
	  		  <tbody>
	  		  <tr>
	  			<td class="status" width="30">&nbsp;</td>
	  			<td class="label" width="20"><label id="lusername" for="username">Username</label></td>
	  			<td class="field"><input id="username" name="username" class="textfield" value="" maxlength="50" type="text"></td>
	  		  </tr>
	  		  <tr>
	  			<td class="status"></td>
	  			<td class="label"><label id="lpassword" for="password">Password</label></td>
	  			<td class="field"><input id="password" name="password" class="textfield" maxlength="50" value="" type="password"></td>
	  		  </tr>
              <tr>
                <td>&nbsp;</td>
                <td colspan="2">
                    <input id="edit-submit" value="Authenticate in Java.net" class="form-submit" type="submit">
                    <input id="edit-delete" value="Cancel Registration" class="form-submit" type="button" onclick="document.location='/user/'">
                </td>
              </tr>
	  		  </tbody></table>
        </form>
</div>
</div> <!-- End of blue box -->

    </div><br class="clear"></div></div></div></div></div></div></div></div>

<!-- End section -->


          </div>
        </div>

          
          <script src="Remember%20The%20Milk%20-%20Signup_files/ga.js"></script>

<?php include 'footer.php';   ?>