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
  require_once 'infinitymetrics/controller/MetricsWorkspaceController.class.php';
  require_once 'infinitymetrics/model/InfinityMetricsException.class.php';
  require_once 'infinitymetrics/model/user/UserTypeEnum.class.php';

 if(isset($_GET["userId"]) && ($_GET["userId"]!=""))
   {
   try {
            $user_logged = UserManagementController::retrieveUser($_GET["userId"]);

       } catch (Exception $e) {
          throw $e;
      }
 }
 else {
       $user = $_SESSION["loggedUser"];
       $user_logged = UserManagementController::viewProfile($user->getUserId());
       $user_logged = UserManagementController::retrieveUser($user->getUserId());
  }
      
$userName = $user_logged->getJnUsername();
$firstName = $user_logged->getFirstName();
$lastName = $user_logged->getLastName();
$email = $user_logged->getEmail();
$type = $user_logged->getType();
$userId = $user_logged->getUserId();


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
    $inst_country =$inst_user->getCountry();


    $instU = PersistentUserXInstitutionPeer::retrieveByPK($userId, $instId);
    $user_inst_ID = $instU->getIdentification();

}
?>



<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html class="js" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en"><head>
<title>Infinity Metrics: <?php echo $subUseCase ?></title>

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
                              <h2></h2>
                              <div class="content" align="center">
                                 <table align="left">
                                <tbody>

                                 <td>
                                 Here you can view your Profile. It displays all your personal information as you provided. <br>
                                 <b>Workspace: </b> Here all the information of workspaces you have created is displayed.<br>
                                 <b>Projects: </b> Here all the information of the projects in which you are participating is displayed.<br>
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
<?php
        if (isset($_SESSION["successMessage"]) && $_SESSION["successMessage"] != "") {
             echo "<div class=\"messages ok\">".$_SESSION["successMessage"]."</div>";
             $_SESSION["successMessage"] = "";
             unset($_SESSION["successMessage"]);
        }
?>
 <table align="left" >
      <tr><td align="justify" width="33%">
      <pre>
      <?php  
            echo " You are logged in as a"." ".$type."\n";
            echo " Name              :".$firstName." ".$lastName."\n";
            echo " UserName          :".$userName."\n"  ;
            echo " Email             :".$email."\n";
            if($type == UserTypeEnum::getInstance()->STUDENT || $type ==UserTypeEnum::getInstance()->INSTRUCTOR){
            echo " Identification ID :".$user_inst_ID."\n";
            echo " Institution name  :".$inst_name."\n";
            echo " Abbereviation     :".$inst_abbv."\n";
            echo " city              :".$inst_city."\n";
            echo " state             :".$inst_state."\n";
            echo " Country           :".$inst_country."\n\n";
      }
   
            ?>
         </pre>
            <br>
     </td>
      </tr>
      </table>

  
   <table align="right" >
      <tr><td align="justify" width="33%">
      <pre>
      <?php 
      if($type == UserTypeEnum::getInstance()->INSTRUCTOR || $type == UserTypeEnum::getInstance()->JAVANET){
            echo "    Your WorkSpace Information "."\n";
    $cri = new Criteria();
    $cri->add(PersistentWorkspacePeer::USER_ID,$userId);
    $workspaces = PersistentWorkspacePeer::doSelect($cri);
    $wsc = PersistentWorkspacePeer::doCount($cri);
    if($wsc !=0){
     foreach($workspaces as $workspace){
    $wsTitle = $workspace->getTitle();
    $wsProjectName = $workspace->getProjectJnName();
    $wsDescription = $workspace->getDescription();
    $wsState = $workspace->getState();

    $project = PersistentProjectPeer::retrieveByPK($wsProjectName);
    $projectName = $project->getProjectJnName();
    $project_summary = $project->getSummary();
    $is_owner =$user_logged->isOwnerOfProject($project);
    
            echo " Title             : ".$wsTitle ."\n";
            echo " Discription       : ".$wsDescription."\n";
            echo " Project Name      : ".$projectName."\n";
            echo " Project Summary   : ".$project_summary."\n";
            echo " State             : ".$wsState."\n";
            if($is_owner)
            echo " Owner             : YES"."\n";
            else
            echo " Owner             : NO"."\n";
            echo "\n\n";

        }
      }
   }

   if($type == UserTypeEnum::getInstance()->STUDENT || $type == UserTypeEnum::getInstance()->JAVANET){
            echo "     Your Project Information"."\n";
        $crit = new Criteria();
        $crit->add(PersistentUserXProjectPeer::JN_USERNAME,$userName);
        $projects = PersistentUserXProjectPeer::doSelect($crit);

        foreach($projects as $project){
         $project_jn_name = $project->getProjectJnName();

            $proj = PersistentProjectPeer::retrieveByPK($project_jn_name);

            $projectName = $proj->getProjectJnName();
            $project_summary = $proj->getSummary();
            $parentProject = $proj->getParentProjectJnName();
            $is_owner =$user_logged->isOwnerOfProject($proj);
            
            echo " Project Name      : ".$projectName."\n";
            echo " Project Summary   : ".$project_summary."\n";
            echo " Parent Project    : ".$parentProject."\n";
           if($is_owner)
            echo " Owner             : YES"."\n";
           else
            echo " Owner             : NO"."\n";
           echo "\n\n";
        }
    }

                     
            ?>
      </pre>
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


