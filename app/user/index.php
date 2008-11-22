<?php
    include 'infinitymetrics-bootstrap.php';

    $subUseCase = "Users Registration";
    $enableLeftNav = false;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html class="js" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en"><head>
<title>Infinity Metrics: <?php echo $subUseCase; ?></title>

<?php include 'static-js-css.php';  ?>

<body class="<?php echo $enableLeftNav ? $leftNavClass : $NoLeftNavClass ;?>">

<?php  include_once 'top-navigation.php';  ?>

                  <div id="breadcrumb" class="alone">
                    <h2 id="title">Home</h2>
                    <div class="breadcrumb">
                        <a href="http://infinitymetrics.local.net/">Home</a> »
                        <a href="http://infinitymetrics.local.net/user/registration.php">Users Registration</a>
                    </div>
                  </div>

                  <div id="content-wrap">
                    <div id="inside">

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
<?php
        if (isset($_SESSION["successMessage"]) && $_SESSION["successMessage"] != "") {
             echo "<div class=\"messages ok\">".$_SESSION["successMessage"]."</div>";
             $_SESSION["successMessage"] = "";
             unset($_SESSION["successMessage"]);
        }
?>
<table align="center">
<tr><td width="33%" align="center">
<input name="op" id="edit-submit" value="Register as Student" class="form-submit" type="button" onclick="document.location='/user/student/signup-step1.php'">
</td>
<td width="33%" align="center">
<input name="op" id="edit-submit" value="Register as Instructor" class="form-submit" type="button" onclick="document.location='/user/instructor/signup-step1.php'">
</td>
<td width="33%" align="center">
<input name="op" id="edit-submit" value="Register as Java.net User" class="form-submit" type="button" onclick="document.location='/user/signup-step1.php'">
</td>
</table>


</div>
</div> <!-- End of blue box -->

    </div><br class="clear"></div></div></div></div></div></div></div></div>

<!-- End section -->

          </div>
        </div>

          </div>

<?php    include 'footer.php';   ?>