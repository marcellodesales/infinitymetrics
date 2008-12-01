<?php
 /** This is the UI implemention to view User profile UC004
  * @author Gurdeep Singh <gurdeepsingh03@gmail.com>
  */
    include '../../template/infinitymetrics-bootstrap.php';
     $subUseCase = "View Profile";
     $enableLeftNav = false;
     if (!isset($_SESSION["loggedUser"])) {
      //   header('Location: ../login.php');
    }

#----------------------------->>>>>>>>>>>>> Controller Usage for UC404 ----------------------------->>>>>>>>>>>>>>>
  require_once 'infinitymetrics/controller/UserManagementController.class.php';
  require_once 'infinitymetrics/model/InfinityMetricsException.class.php';
  require_once 'infinitymetrics/model/user/UserTypeEnum.class.php';

 if(isset($_GET["userId"]) && ($_GET["userId"]!=""))
   {
   try {
             $user_logged = UserManagementController::viewProfile($_GET["userId"]);
            //  $user_logged = UserManagementController::retrieveUser($_GET["userId"]);

       } catch (Exception $e) {
          throw $e;
      }
 }
 else {  // $_SESSION["loggedUser"]= 1076;
       $userProfile = UserManagementController::viewProfile($_SESSION["loggedUser"]);
       $user_logged = UserManagementController::retrieveUser($_SESSION["loggedUser"]);
  }
        $userId = $user_logged->getUserId();
      $c = new Criteria();
      $c->add(PersistentUserPeer::USER_ID, $userId);
      $user = PersistentUserPeer::doSelectOne($c);
//$user = PersistentUserPeer::retrieveByPK($userId);
$userName = $user->getJnUsername();
$firstName = $user->getFirstName();

$lastName = $user->getLastName();
$email = $user->getEmail();
$type = $user->getType();


if($type == UserTypeEnum::getInstance()->STUDENT || $type == UserTypeEnum::getInstance()->INSTRUCTOR){

           $cr = new Criteria();
     $cr->add(PersistentUserPeer::USER_ID, $userId);
     $inst1 = PersistentUserXInstitutionPeer::doSelectOne($cr);
           $instId = $inst1->getInstitutionId();

    $inst_user =PersistentInstitutionPeer::retrieveByPK($instId);
    $inst_abbv = $inst_user->getAbbreviation();
    $instId = $inst_user->getInstitutionId();
    $inst_name = $inst_user->getName();
    $inst_city = $inst_user->getCity();
    $inst_state =$inst_user->getStateProvince();
    $inst_country = $inst_user->getCountry();


    $instU = PersistentUserXInstitutionPeer::retrieveByPK($userId, $instId);
    $user_inst_ID = $instU->getIdentification();

}

if($type == UserTypeEnum::getInstance()->INSTRUCTOR){

    $c = new Criteria();
    $c->add(PersistentWorkspacePeer::USER_ID,$userId);
    $workspace = PersistentWorkspacePeer::doSelectOne($c);
    $wsTitle = $workspace->getTitle();
    $wsprojectName = $workspace->getProjectJnName();
    $wsDescription = $workspace->getDescription();
    $wsState = $workspace->getState();

    $cr = new Criteria();
    $cr->add(PersistentProjectPeer::PROJECT_JN_NAME,$wsprojectName);
     $proj1 = PersistentProjectPeer::doSelectOne($cr);
   // $proj1 = PersistentUserXProjectPeer::doSelectJoinUser($cr);
      $pr_name = $proj1->getProjectJnName();

    $proj = PersistentProjectPeer::retrieveByPK($pr_name);
   $projectName = $proj->getProjectJnName();
   $project_owner = $proj->getParentProjectJnName();
   $project_summary = $proj->getSummary();

}
if($type == UserTypeEnum::getInstance()->STUDENT || $type == UserTypeEnum::getInstance()->JAVANET){
$cr = new Criteria();
    $cr->add(PersistentUserXProjectPeer::JN_USERNAME,$userName);
  
   $proj1 = PersistentUserXProjectPeer::doSelectOne($cr);
      $project_jn_name = $proj1->getProjectJnName();

    $c = new Criteria();
    $c->add(PersistentProjectPeer::PROJECT_JN_NAME, $project_jn_name);
    $proj = PersistentProjectPeer::doSelectOne($c);
   $projectName = $proj->getProjectJnName();
   $project_owner = $proj->getParentProjectJnName();
   $project_summary = $proj->getSummary();
}

  


?>



<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html class="js" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en"><head>
<title>Infinity Metrics: <?php echo "Profile View" ?></title>

<?php include '../../template/static-js-css.php';  ?>

<body class="<?php echo $enableLeftNav ? $leftNavClass : $NoLeftNavClass ;?>">

<?php  include '../../template/top-navigation.php';  ?>

                  <div id="breadcrumb" class="alone">
                    <h2 id="title">Profile</h2>
                    <div class="breadcrumb">
                        <a href="<?php echo $_SERVER["home_address"]; ?>">Profile</a> »

                    </div>
                  </div>

                  <div id="content-wrap">
                    <div id="inside">

                        <div id="sidebar-right">
                          <div id="block-user-3" class="block block-user">
                              <h2>You are welcome</h2>
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
<?php
        if (isset($_SESSION["successMessage"]) && $_SESSION["successMessage"] != "") {
             echo "<div class=\"messages ok\">".$_SESSION["successMessage"]."</div>";
             $_SESSION["successMessage"] = "";
             unset($_SESSION["successMessage"]);
        }
?>
<table align="center" >
<tr><td align="justify" width="33%">
 <h1> <?php  echo " You are logged in as a          "." ".$type;?></h1><br><br><br>
      <?php  echo "Name           :".$firstName." ".$lastName;?> <br><br>
      <?php  echo "UserName         :".$userName  ;?><br><br>
      <?php  echo "Email         :".$email;?><br><br>
      
</td>
</tr>
</table>

<table align="center" >
      <tr><td align="justify" width="33%">
      <?php  echo "Project         :".$projectName ;?><br><br>
      
      <?php echo "Project summary      :".$project_summary;?><br><br>
      <?php  if($project_owner==0)
            echo "You are Owner for this project"?><br><br>
       </td>
      </tr>
      </table>

 <table align="center" >
      <tr><td align="justify" width="33%">
      <?php  if($type == UserTypeEnum::getInstance()->STUDENT || $type ==UserTypeEnum::getInstance()->INSTRUCTOR)
            echo "Identification ID      :".$user_inst_ID;?><br><br>
      <?php  echo "institution name         :".$inst_name;?><br><br>
      <?php  echo "Abbereviation   :".$inst_abbv;?><br><br>
      <?php  echo "city      :".$inst_city;?><br><br>
      <?php  echo "state      :".$inst_state;?><br><br>
      <?php  echo "Country      :".$inst_country;?><br><br>
     </td>
      </tr>
      </table>


   <table align="center" >
      <tr><td align="justify" width="33%">
      <?php  if($type ==UserTypeEnum::getInstance()->INSTRUCTOR)
            echo "WorkSpace Title      :".$wsTitle;?><br><br>
      <?php   if($type ==UserTypeEnum::getInstance()->INSTRUCTOR)
            echo "Project Name         :".$wsprojectName;?><br><br>
      <?php  if($type ==UserTypeEnum::getInstance()->INSTRUCTOR)
            echo "Descripion   :".$wsDescription;?><br><br>
      <?php  if($type ==UserTypeEnum::getInstance()->INSTRUCTOR)
            echo "State:".$wsState;?><br><br>

       </td>
      </tr>
      </table>
 <tr>
 <td width="33%" align="justify">
<input name="op" id="edit-submit" value="Update Profile" class="form-submit" type="button" onclick="document.location='updateProfile.php'">
</td>
</tr>


</div>
</div>
</div> <!-- End of blue box -->

    </div><br class="clear"></div></div></div></div></div></div></div></div>

<!-- End section -->

          </div>
        </div>
        </div>



<?php    include '../../template/footer.php';   ?>


