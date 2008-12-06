<?php
    include 'template/infinitymetrics-bootstrap.php';

    $enableLeftNav = false;

    #breadscrum[URL] = Title
    $breakscrum = array(
                        $_SERVER["home_address"] => "Home"
                  );
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html class="js" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en"><head>
<title>∞Metrics: Automatic Collaboration Metrics for Java.net Projects</title>

<?php include 'static-js-css.php';  ?>

<?php include 'user-signup-header-adds.php' ?>

<?php
require_once 'infinitymetrics/controller/ReportController.class.php';
?>
</head>
<body class="<?php echo $enableLeftNav ? $leftNavClass : $NoLeftNavClass; ?>">

<?php  include 'top-navigation.php';  ?>

                  <div id="breadcrumb" class="alone">
                    <h2 id="title">Fast Login</h2>
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

                        <div id="sidebar-right">

      		<div class="node-form">
            <form id="signinform" autocomplete="off" method="post" action="user/login.php">
<?php
        if (isset($_SESSION["signinError"]) && $_SESSION["signinError"] != "") {
             echo "<div class=\"messages error\">".$_SESSION["signinError"]."</div>";
             $_SESSION["signinError"] = "";
             unset($_SESSION["signinError"]);
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
                <td colspan="2" align="right">
                    <input id="edit-submit" value="Login" class="form-submit" type="submit"><BR>
                </td>
              </tr>
	  		  </tbody></table>
        </form>
        </div>
                    </div>
        <div id="content">

          <div class="t"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr"><div class="content-in">

            <table>
                <tr>
                    <td><img src="../../template/images/techglobe2.jpg"></td>
                    <td align="center"><h2>Welcome to Infinity Metrics</h2></td>
                    <td>
                Wanna know what's really going on in a project? What if you could track the performance of multiple
                Java.net projects on the same environment without creating boring spreadsheets? <b>Infinity Metrics</b>
                aggregates data from Java.net and provide reports for all types of users, specially those from the
                academia teaching Global Software Engineering.
                    </td>
                    <td align="right"><input name="op" id="edit-preview" value="Get Started now!" class="form-submit" type="button" onclick="document.location='user/index.php'"></td>
                </tr>
            </table>

            </div><br class="clear"></div></div></div></div></div></div></div></div>

<!-- End section -->

            <div id="chart_div"></div>

          </div>
        </div>

          </div>

<?php include 'footer.php';   ?>