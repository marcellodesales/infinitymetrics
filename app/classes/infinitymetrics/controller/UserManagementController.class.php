<?php

require_once 'infinitymetrics/model/InfinityMetricsException.class.php';
require_once 'infinitymetrics/model/institution/Student.class.php';

/*
 * @author: Marcello de Sales <ddslkd>
 */
final class UserManagementController {

    public static function registerStudent($username, $password, $email, $realName, $institution, $studentId, $teamLeader,
          $projectName) {

          $error = array();
          if ($username == "") {
              $error["username"] = "The username is empty";
          } 
          if ($password == "") {
              $error["password"] = "The password is empty";
          } 
          if ($realName == "") {
              $error["realName"] = "The realName is empty";
          }

        if (count($error) > 0) {
            throw new InfinityMetricsException("There are errors in the input", $error);
        }

        $student = new Student($realName);
        $student->setInstitution($institution);
        //$orm->save($student);
        //$student->save();

        return $student;
    }
}

?>
