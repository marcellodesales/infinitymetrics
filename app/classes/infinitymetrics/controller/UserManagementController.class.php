
<?php
/**
 * $Id: UserManagementController.class.php 202 2008-11-10 12:01:40Z Gurdeep Singh $
 * ,Marcello Sales $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITYs, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the Berkeley Software Distribution (BSD).
 * For more information please see <http://ppm-8.dev.java.net>.
 */

require_once 'infinitymetrics/model/InfinityMetricsException.class.php';
require_once 'infinitymetrics/model/institution/Student.class.php';
require_once 'infinitymetrics/model/institution/Instructor.class.php';
require_once 'infinitymetrics/model/institution/Institution.class.php';
require_once 'infinitymetrics/model/user/User.class.php';


/*
 * @author: Marcello de Sales <ddslkd>
 * @author: Gurdeep Singh <gurdeepsingh03@gmail.com>
 */
final class UserManagementController {

   /** this function implements the registration of Student
    *
    * @param <type> $username
    * @param <type> $password
    * @param <type> $email
    * @param <type> $firstName
    * @param <type> $lastName
    * @param <type> $institution
    * @param <type> $studentSchoolId
    * @param <type> $teamLeader
    * @param <type> $projectName
    * @return <type>
    */
    public static function registerStudent($username, $password, $email, $firstName,$lastName, $institution, $studentSchoolId, $teamLeader,
          $projectName) {

          $error = array();
          if ($username == "") {
              $error["username"] = "The username is empty";
          }
          if ($password == "") {
              $error["password"] = "The password is empty";
          }
          if ($firstName == "") {
              $error["firstName"] = "The firstName is empty";
          }
          if ($lastName == "") {
              $error["lastName"] = "The lastName is empty";
          }
          if ($email == "") {
              $error["email"] = "The email is empty";
          }
          if ($studentSchoolId == "") {
              $error["studentSchoolId"] = "The student school Id is empty";
          }
          if ($projectName == "") {
              $error["projectName"] = "The projectName is empty";
          }
          if ($institution == "") {
              $error["institution"] = "The institution is empty";
          }
          if ($teamLeader == "") {
              $error["teamleader"] = "The teamLeader is empty";
          }

        if (count($error) > 0) {
            throw new InfinityMetricsException("There are errors in the input", $error);
        }

        $student = new Student($firstName,$lastName);
        $student->setInstitution($institution);

        $student->setStudentId($studentSchoolId);


       //$orm->save($student);
        //$student->save();

        return $student;
    }
/** this function is to implement the registration of Instructor
 *
 * @param <type> $userName
 * @param <type> $password
 * @param <type> $email
 * @param <type> $firstName
 * @param <type> $lastName
 * @param <type> $institution
 * @param <type> $projectName
 * @return <type>
 */
 public static function registerinstructor($userName, $password, $email, $firstName,$lastName,$institution,$projectName) {

          $error = array();
          if ($userName == "") {
              $error["userName"] = "The username is empty";
          }
          if ($password == "") {
              $error["password"] = "The password is empty";
          }
          if ($firstName == "") {
              $error["firstName"] = "The firstName is empty";
          }
          if ($lastName == "") {
              $error["lastName"] = "The lastName is empty";
          }
          if ($projectName == "") {
              $error["projectName"] = "The projectName is empty";
          }
          if ($institution == "") {
              $error["institution"] = "The institution is empty";
          }
          if ($email == "") {
              $error["email"] = "The email is empty";
          }

        if (count($error) > 0) {
            throw new InfinityMetricsException("There are errors in the input", $error);
        }
       
        $instructor = new Instructor($firstName,$lastName);
        $instructor->setInstitution($institution);
        $instructor->setEmail($email);
        $instructor->setProjectName($projectName);
        $instructor->setUserName($userName);
        $instructor->setPassword($password);
        

        return $instructor;
    }


}







?>




