<?php
    include '../../template/infinitymetrics-bootstrap.php';

    if (!isset($_SESSION["regInstructor"])) {
        $_SESSION["signupError"] = "Please authenticate on Java.net!";
        header('Location: signup-step1.php');
    }

    $regInstructor = $_SESSION["regInstructor"];

    if (isset($_POST) && isset($_POST["submit"])) {

        require_once 'infinitymetrics/controller/UserManagementController.class.php';
        require_once 'infinitymetrics/model/InfinityMetricsException.class.php';
        require_once 'infinitymetrics/controller/MetricsWorkspaceController.class.php';

        try {
            $tempUser = new PersistentUser();
            $tempUser->setJnUsername($regInstructor["jnUsername"]);
            $tempUser->setJnPassword($regInstructor["jnPassword"]);
            MetricsWorkspaceController::registerParentProject($tempUser, $regInstructor["jnProject"],
                                                             $regInstructor["jnUsername"] . "'s saved parent project");
        } catch (Exception $e) {
        }

        try {
            $userAgent = UserManagementController::registerInstructor($regInstructor["jnUsername"],
                            $regInstructor["jnPassword"], $regInstructor["email"], $regInstructor["firstName"],
                            $regInstructor["lastName"], $regInstructor["jnProject"], $regInstructor["isLeader"],
                            $regInstructor["instAbbrev"], $regInstructor["schoolId"]);
                $_SESSION["successMessage"] = "Your account was created and an email was sent to " . $regInstructor["email"];
                header('Location: ../');

        } catch (Exception $ime) {
            $_SESSION["signupError"] = $ime;
        }
    }

    $subUseCase = "Instructor Registration";
    $enableLeftNav = true;

    $breakscrum = array(
                        $_SERVER["home_address"] => "Home",
                        $_SERVER["home_address"]."/user" => "Users Registration",
                        $_SERVER["home_address"]."/user/instructor/signup-step1.php" => "1. Instructor Java.net Authentication",
                        $_SERVER["home_address"]."/user/instructor/signup-step2.php" => "2. Instructor Profile Update",
                        $_SERVER["home_address"]."/user/instructor/signup-step3.php" => "3. Instructor Profile Confirmation"
                  );
    $leftMenu = array();
    array_push($leftMenu, array("active"=>"menu-27", "url"=>"signup-step1.php", "item"=>"1. Java.net Authentication", "tip"=>"Manage your site's book outlines."));
    array_push($leftMenu, array("active"=>"menu-27", "url"=>"signup-step2.php", "item"=>"2. Update Profile", "tip"=>"Update and review your profile info"));
    array_push($leftMenu, array("active"=>"menu-27 first active", "url"=>"signup-step3.php", "item"=>"3. Confirm Registration", "tip"=>"Confirm you profile"));
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
                    <h2 id="title">Instructor Registration</h2>
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
                              <h2></h2>
                              <div class="content" align="center">
                                
                                <table align="left">
                                <tbody>
                                <td>
                                  This is the final step in Instructor registration. Here confirm your details and get registered.
                                 
                                &nbsp;</td>
                                </tbody>
                                </table>
                              </div>
                          </div>
                        </div>

                    </div>
                    <div id="content">
<div class="t"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr"><div class="content-in">

<div>
<div class="node-form">

        <div id="signupwrap">
      		<form id="signupform" autocomplete="off" method="post" action="<?php echo $PHP_SELF ?>">
<?php
        if (isset($_SESSION["signupError"]) && $_SESSION["signupError"] != "") {
             echo "<div class=\"messages error\">".$_SESSION["signupError"]."</div>";
             $_SESSION["signupError"] = "";
             unset($_SESSION["signupError"]);
        }
?>

            <table><tbody>
              <tr>
	  		  	<td class="label"><label id="lfullName" for="firstname">Type of Membership</label></td>
	  		  	<td class="field">Instructor</td>
	  		  	<td class="status"><span id="fullNameStatus"></span></td>
	  		  </tr>
              <tr>
	  		  	<td class="label"><label id="lfullName" for="firstname">Full Name</label></td>
	  		  	<td class="field"><?php echo $regInstructor["firstName"] ?> <?php echo $regInstructor["lastName"] ?></td>
	  		  	<td class="status"><span id="fullNameStatus"></span></td>
	  		  </tr>
      		  <tr>
	  			<td class="label"><label id="iinstitution" for="institution">Institution</label></td>
	  			<td class="field"><?php echo $regInstructor["instAbbrev"] ?></td>
	  			<td class="status"><span id="institutionStatus"></span></td>
	  		  </tr>
      		  <tr>
	  			<td class="label"><label id="lemail" for="email">Email Address at Java.net</label></td>
	  			<td class="field"><?php echo $regInstructor["email"] ?></td>
	  			<td class="status"><span id="emailStatus"></span></td>
	  		  </tr>
	  		  <tr>
	  			<td class="label"><label id="lusername" for="username">Java.net Username</label></td>
	  			<td class="field"><?php echo $regInstructor["jnUsername"] ?></td>
	  			<td class="status"><span id="usernameStatus"></span></td>
	  		  </tr>
      		  <tr>
	  			<td class="label"><label id="iProjectName" for="institution">Project You Participate</label></td>
	  			<td class="field"><?php echo $regInstructor["jnProject"] ?></td>
	  			<td class="status"><span id="projectStatus"></span></td>
	  		  </tr>
      		  <tr>
	  			<td class="label"><label id="iProjectName" for="institution">Role You're Playing</label></td>
	  			<td class="field"><?php echo $regInstructor["isLeader"] ? "Instructor" : "Instructor Member" ?></td>
	  			<td class="status"><span id="projectStatus"></span></td>
	  		  </tr>
      		  <tr>
	  			<td class="label"><label id="lsignupsubmit" for="signupsubmit">&nbsp;</label></td>
	  			<td class="field" colspan="2">
                    <input name="submit" id="edit-submit" value="Confirm my Account" class="form-submit" type="submit">
                    <input name="tryAnother" id="edit-preview" value="Let me fix something..." class="form-submit" type="button" onclick="history.go(-1)">
                    <input name="cancelSignup" id="edit-delete" value="Cancel Subscription" class="form-submit" type="button" onclick="document.location='/user/'">
                </td>
	  		  </tr>
	  		  </tbody></table>
                  </form>
      </div> <!-- /signupwrap -->
    <script type="text/javascript"><!--
    var emailInUse = null;
    var errorField = null;
    if (errorField !== null)
    {
        document.getElementById(errorField).focus();
    }
 --></script>

</div>
</div> <!-- End of blue box -->

    </div><br class="clear"></div></div></div></div></div></div></div></div>

<!-- End section -->


          </div>
        </div>

          </div>
<?php    include 'footer.php';   ?>
