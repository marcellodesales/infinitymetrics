<?php
    include '../../template/infinitymetrics-bootstrap.php';

    if (!isset($_SESSION["userAgentAuthenticated"])) {
        $_SESSION["signupError"] = "Please authenticate on Java.net!";
        header('Location: signup-step1.php');
    }

#----------------------------->>>>>>>>>>>>> Controller Usage for UC404 ----------------------------->>>>>>>>>>>>>>>

    if (isset($_POST) && isset($_POST["submit"])) {
        if (isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["fullName"]) &&
            isset($_POST["institutionAbbr"]) && isset($_POST["schoolId"]) && isset($_POST["jnProject"])) {

            require_once 'infinitymetrics/controller/UserManagementController.class.php';
            require_once 'infinitymetrics/model/InfinityMetricsException.class.php';

            try {
                $firstLastName = explode(" ", $_POST["fullName"]);
                //isLeaderProject = 1|0projectname
                $isLeader = in_array("Project Owner", $_SESSION["userAgentAuthenticated"]["projects"][$_POST["jnProject"]]);
                $userAgent = UserManagementController::validateStudentRegistrationForm(
                                 $_SESSION["userAgentAuthenticated"]["jnUsername"],
                                 $_SESSION["userAgentAuthenticated"]["jnPassword"], 
                                 $_SESSION["userAgentAuthenticated"]["email"], $firstLastName[0], $firstLastName[1],
                                 $_POST["schoolId"], $_POST["jnProject"], $_POST["institutionAbbr"], $isLeader);

                $regStudent["jnUsername"] = $_SESSION["userAgentAuthenticated"]["jnUsername"];
                $regStudent["jnPassword"] = $_SESSION["userAgentAuthenticated"]["jnPassword"];
                $regStudent["email"] = $_SESSION["userAgentAuthenticated"]["email"];
                $regStudent["firstName"] = $firstLastName[0];
                $regStudent["lastName"] = $firstLastName[1];
                $regStudent["schoolId"] = $_POST["schoolId"];
                $regStudent["jnProject"] = $_POST["jnProject"];
                $regStudent["instAbbrev"] = $_POST["institutionAbbr"];
                $regStudent["isLeader"] = $isLeader;

                $_SESSION["regStudent"] = $regStudent;

                header('Location: signup-step3.php');

            } catch (InfinityMetricsException $ime) {
                $_SESSION["signupError"] = $ime;
            }
        } else {
            $_SESSION["signupError"] = "Some fields are missing";
        }
    } else {
        $_SESSION["signupError"] = "You must authenticate through Java.net to continue on step 2";
        header('Location: signup-step1.php');
    }

#----------------------------->>>>>>>>>>>>> Variables Initialization ------------------->>>>>>>>>>>>>>>

    $subUseCase = "2. Student Profile Update";
    $enableLeftNav = true;

    $breakscrum = array(
                        $_SERVER["home_address"] => "Home",
                        $_SERVER["home_address"]."/user" => "Users Registration",
                        $_SERVER["home_address"]."/user/student/signup-step1.php" => "1. Java.net Authentication",
                		$_SERVER["home_address"]."/user/student/signup-step2.php" => "2. Profile Update"
                  );
    $leftMenu = array();
    array_push($leftMenu, array("active"=>"menu-27", "url"=>"signup-step1.php", "item"=>"1. Java.net Authentication", "tip"=>"Manage your site's book outlines."));
    array_push($leftMenu, array("active"=>"menu-27 first active", "url"=>"signup-step2.php", "item"=>"2. Update Profile", "tip"=>"Update and review your profile info"));
    array_push($leftMenu, array("active"=>"menu-27", "url"=>"signup-step3.php", "item"=>"3. Confirm Registration", "tip"=>"Confirm you profile"));
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
                                <img src="../../template/images/techglobe2.jpg">
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
	  		  	<td class="label"><label id="lfullName" for="firstname">Full Name</label></td>
	  		  	<td class="field"><input style="background-color: rgb(255, 255, 160);" name="fullName" id="fullName" value="" class="textfield" maxlength="100" size="45" type="text"></td>
	  		  	<td class="status"><span id="fullNameStatus"></span></td>
	  		  </tr>
      		  <tr>
	  			<td class="label"><label id="iinstitution" for="institution">Institution</label></td>
	  			<td class="field"><select name="institutionAbbr">
<?php
            $c = new Criteria();
            $c->addAscendingOrderByColumn(PersistentInstitutionPeer::ABBREVIATION);
            $institutions = PersistentInstitutionPeer::doSelect($c);

            foreach ($institutions as $inst) {
                echo "<option value=\"".$inst->getAbbreviation()."\"\">(".$inst->getAbbreviation().") ".$inst->getName().", ".$inst->getStateProvince().", ".$inst->getCountry()."</option>";
            }
?>
                                   </select>
                </td>
	  			<td class="status"><span id="institutionStatus"></span></td>
	  		  </tr>
      		  <tr>
	  			<td class="label"><label id="lschoolId" for="schoolId">Student School Identification #</label></td>
	  			<td class="field"><input style="background-color: rgb(255, 255, 160);" id="schoolId" name="schoolId" class="textfield" value="" maxlength="30" type="text"></td>
	  			<td class="status"><span id="schoolIdStatus"></span></td>
	  		  </tr>
	  		  <tr>
	  			<td class="label">* <label id="lusername" for="username">Java.net Username</label></td>
	  			<td class="field"><?php echo $_SESSION["userAgentAuthenticated"]["jnUsername"] ?><input type="hidden" name="username" id="username" value="<?php echo $_SESSION["userAgentAuthenticated"]["jnUsername"] ?>"></td>
	  			<td class="status"><span id="usernameStatus"></span></td>
	  		  </tr>
              <tr>
	  			<td class="label">* <label id="lemail" for="email">Email Address at Java.net</label></td>
	  			<td class="field"><?php echo $_SESSION["userAgentAuthenticated"]["email"] ?><input type="hidden" name="email" id="email" value="<?php echo $_SESSION["userAgentAuthenticated"]["email"] ?>"></td>
	  			<td class="status"><span id="emailStatus"></span></td>
	  		  </tr>
      		  <tr>
	  			<td class="label"><label id="iProjectName" for="institution">I am a...</label></td>
	  			<td class="field"><select name="jnProject">
<?php
        foreach($_SESSION["userAgentAuthenticated"]["projects"] as $projectName => $rolesArray) {
                $studentMemberType = in_array("Project Owner", $rolesArray) ? "Team Leader of " : "Team Member of ";
                $roles = implode(", ", $rolesArray);
                echo "<option value=\"".$projectName."\">".$studentMemberType."(".$projectName.") as ".$roles."</option>";
        }
?>
                                   </select>
                </td>
	  			<td class="status"><span id="projectStatus"></span></td>
	  		  </tr>
	  		  <tr>
	  			<td class="label">&nbsp;</td>
	  			<td class="field" colspan="2">
	  			<div id="termswrap">
	  			<input id="terms" name="terms" type="checkbox">
	            <label id="lterms" for="terms">I have read and accept the <a onclick="window.open('/help/terms.rtm','TOS','status=yes,scrollbars=yes,resizable=yes,width=800,height=480'); return false;" href="http://www.rememberthemilk.com/help/terms.rtm">Terms of Use</a>.<span id="termsStatus"></span></label>
	            </div> <!-- /termswrap -->
	  			</td>
	  		  </tr>
	  		  <tr>
	  			<td class="label"><label id="lsignupsubmit" for="signupsubmit">&nbsp;</label></td>
	  			<td class="field" colspan="2">
                    <input name="submit" id="edit-submit" value="Continue" class="form-submit" type="submit">
                    <input name="tryAnother" id="edit-preview" value="Try another account" class="form-submit" type="button" onclick="document.location='/user/student/signup-step1.php'">
                    <input name="cancelSignup" id="edit-delete" value="Cancel Signup" class="form-submit" type="button" onclick="document.location='/user/'">
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
