<?php
    include '../template/infinitymetrics-bootstrap.php';

    $enableLeftNav = false;

    #breadscrum[URL] = Title
    $breakscrum = array(
                        $_SERVER["home_address"] => "Home",
                        $_SERVER["home_address"]."/help" => "Help",
                        $_SERVER["home_address"]."/help/" => "Introduction",
                  );
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html class="js" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en"><head>
<title>∞Metrics: Automatic Collaboration Metrics for Java.net Projects</title>

<?php include 'static-js-css.php';  ?>

<?php include 'user-signup-header-adds.php' ?>

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
<div class="help">

            <p>
                <strong><em>Infinity Metrics</em></strong> is a distributed collaboration Software
                Engineering project between students from San Francisco State University
                (<a href="http://www.sfsu.edu">SFSU</a>) and Florida Atlantic University
                (<a href="http://www.fau.edu">FAU</a>). Our project corresponds to <strong>Group 8</strong>
                of the Project Participation Metrics project, or
                <a href="https://ppm.dev.java.net">PPM</a>. The project aims to create
                a web-based application that will enable instructors teaching
                Software Engineering courses with distributed global development
                teams using the <a href="http://www.java.net">java.net</a> platform
                to collect and track student participation. To read more about
                our project please visit its <a href="https://ppm-8.dev.java.net">java.net project space</a>.
            </p>
            <p>
                Infinity Metrics aggregates data from Java.net and provide reports for all types of users.
                In addition to that, we provide customized visibility for instructors and students participation metrics
                for such class environment mainly via the projects' syndicated RSS feeds on java.net.
                The application allows a single instructor or set of instructors in different locations
                (i.e. concurrent Software Engineering courses taking place in different
                universities) to automatically collect participation data for a set of java.net projects and report on its
                members’ contribution to the project at all stages of development.
            <p>
                We aim to provide an intuitive, easy-to-use application that requires little initial set-up
                and will automatically generate the metrics categories available for the given projects,
                whilst allowing instructors to retain full control to modify these settings
                at any point throughout the semester.
            </p>
            <p>
                To get started using the application, please visit the <a href="/user/">registration area</a>.
            </p>


</div>              <h2>Help topics</h2><p>Help is available on the following items:</p><div class="clear-block"><div class="help-items"><ul><li><a href="/help/workspace">Workspace</a></li><li><a href="/help/reports">Reports</a></li></ul></div></div>

    </div><br class="clear"></div></div></div></div></div></div></div></div>

<!-- End section -->

          </div>
        </div>

          </div>

<?php include 'footer.php';   ?>