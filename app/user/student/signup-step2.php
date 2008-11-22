<?php
    include 'infinitymetrics-bootstrap.php';

    if (!isset($_SESSION["userAgentAuthenticated"])) {
        $_SESSION["signupError"] = "Please authenticate on Java.net!";
        header('Location: signup-step1.php');
    }

    $subUseCase = "Student Registration";
    $enableLeftNav = true;

    $breakscrum = array(
                        "/" => "Home",
                        "/user" => "Users Registration",
                        "/user/student/signup-step1.php" => "Student Registration"
                  );
    $leftMenu = array();
    array_push($leftMenu, array("active"=>"menu-27", "url"=>"/user/student/signup-step1.php", "item"=>"Java.net Authentication", "tip"=>"Manage your site's book outlines."));
    array_push($leftMenu, array("active"=>"menu-27 first active", "url"=>"/user/student/signup-step2.php", "item"=>"Update Profile", "tip"=>"Update and review your profile info"));
    array_push($leftMenu, array("active"=>"menu-27", "url"=>"/user/student/signup-step3.php", "item"=>"Confirm Registration", "tip"=>"Confirm you profile"));
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html class="js" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en"><head>
<title>Infinity Metrics: <?php echo $subUseCase; ?></title>

<?php include 'static-js-css.php';  ?>

<?php include 'user-signup-header-adds.php' ?>

</head>
<body class="<?php echo $enableLeftNav ? $leftNavClass : $NoLeftNavClass; ?>" onload="init();">

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

        <div id="signupwrap">
      		<form id="signupform" autocomplete="off" method="post" action="https://www.rememberthemilk.com/signup/">
	  		  <table><tbody>
	  		  <tr>
	  			<td class="label">* <label id="lusername" for="username">Username</label></td>
	  			<td class="field"><input id="username" value="<?php echo $_SESSION["userAgentAuthenticated"]["jnUsername"] ?>" name="username" class="textfield" value="" maxlength="50" type="text" disabled></td>
	  			<td class="status"><span id="usernameStatus"></span></td>
	  		  </tr>
              <tr>
	  			<td class="label">* <label id="lemail" for="email">Email Address</label></td>
	  			<td class="field"><input style="background-color: rgb(255, 255, 160);" id="email" value="<?php echo $_SESSION["userAgentAuthenticated"]["email"] ?>" name="email" class="textfield" maxlength="150" type="text" disabled></td>
	  			<td class="status"><span id="emailStatus"></span></td>
	  		  </tr>
	  		  <tr>
	  		  	<td class="label"><label id="lfullName" for="firstname">Full Name</label></td>
	  		  	<td class="field"><input style="background-color: rgb(255, 255, 160);" id="fullName" value="" name="fullName" class="textfield" maxlength="100" type="text"></td>
	  		  	<td class="status"><span id="fullNameStatus"></span></td>
	  		  </tr>
      		  <tr>
	  			<td class="label"><label id="iinstitution" for="institution">Institution</label></td>
	  			<td class="field"><select name="institutionAbbr">
                                       <option value="SFSU">(SFSU) San Francisco State University, California, USA</option>
                                       <option value="FAU">(FAU) Florida Atlantic University, Florida, USA</option>
                                   </select>
                </td>
	  			<td class="status"><span id="institutionStatus"></span></td>
	  		  </tr>
      		  <tr>
	  			<td class="label"><label id="lschoolId" for="schoolId">Student School Identification</label></td>
	  			<td class="field"><input style="background-color: rgb(255, 255, 160);" id="schoolId" name="schoolId" class="textfield" value="" maxlength="30" type="text"></td>
	  			<td class="status"><span id="schoolIdStatus"></span></td>
	  		  </tr>
      		  <tr>
	  			<td class="label"><label id="iProjectName" for="institution">Select Java.net Project and Role</label></td>
	  			<td class="field"><select name="institutionAbbr">
<?php
        foreach($_SESSION["userAgentAuthenticated"]["projects"] as $projectName => $rolesArray) {
            foreach ($rolesArray as $role) {
                $studentMemberType = $role == "Project Owner" ? " as Team Leader" : " as Team Member";
                echo "<option value=\"".$projectName."\">".$projectName.$studentMemberType." / (".$role.")</option>";
            }
        }
?>
                                   </select>
                </td>
	  			<td class="status"><span id="projectStatus"></span></td>
<?php

unset($_SESSION["userAgentRegistration"]);

?>
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
	  			<td class="label"><label id="lsignupsubmit" for="signupsubmit">Signup</label></td>
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
          <script src="Remember%20The%20Milk%20-%20Signup_files/ga.js"></script>
<?php    include 'footer.php';   ?>