<?php
    include '../template/infinitymetrics-bootstrap.php';

    $subUseCase1 = "Users Login";
    $subUseCase = "Users Registration";
    $enableLeftNav = false;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html class="js" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en"><head>
<title>Infinity Metrics: <?php echo $subUseCase1 . " " .$subUseCase; ?></title>

<?php include '../template/static-js-css.php';  ?>

<body class="<?php echo $enableLeftNav ? $leftNavClass : $NoLeftNavClass ;?>">

<?php  include '../template/top-navigation.php';  ?>

                  <div id="breadcrumb" class="alone">
                    <h2 id="title">Home</h2>
                    <div class="breadcrumb">
                        <a href="<?php echo $_SERVER['home_address']; ?>">Home</a> »
                        <a href="<php echo $_SERVER['home_address'] ?>/user">Users</a>
                    </div>
                  </div>

                  <div id="content-wrap">
                    <div id="inside">

                        <div id="sidebar-right">
                          <div id="block-user-3" class="block block-user">
                          <tr><td width="33%" align="left">
                                  </td>
                              <h2>All users are welcomed</h2>
                              <div class="content" align="center">
                                <img src="../template/images/techglobe2.jpg">
                              </div>
                          </div>
                        </div>
                    </div>
                    <div id="content">
<div class="t"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr"><div class="content-in">

<div>
<div class="node-form">
<?php
        if (isset($_SESSION["successMessage"]) && $_SESSION["successMessage"] != "") {
             echo "<div class=\"messages ok\">".$_SESSION["successMessage"]."</div>";
             $_SESSION["successMessage"] = "";
             unset($_SESSION["successMessage"]);
        }
?>
<table align="center">
<tr><td width="33%" align="center">

Wanna know more about your performance in your Global Software Engineering class?
<input name="op" id="edit-preview" value="Register as a Student" class="form-submit" type="button" onclick="document.location='student/signup-step1.php'">
to verify your participation metrics on a given project and compare yourself with other peers.

</td>
<td width="33%" align="center">

You are teaching Global Software Engineering using Java.net as the main development platform? 
<input name="op" id="edit-preview" value="Register as an Instructor" class="form-submit" type="button" onclick="document.location='instructor/signup-step1.php'">
and track the collaboration and participation among the students in all subprojects of a given term. 
</td>
<td width="33%" align="center">

Having headache to measure the contributions and the team collaboration on a Java.net project?
<input name="op" id="edit-preview" value="Register as a Java.net User" class="form-submit" type="button" onclick="document.location='signup-step1.php'">
to take full control from your team or a set of subjects in Java.net.
</td>
</table>


</div>
</div> <!-- End of blue box -->

    </div><br class="clear"></div></div></div></div></div></div></div></div>

<!-- End section -->

          </div>
        </div>

          </div>

<?php    include '../template/footer.php';   ?>
