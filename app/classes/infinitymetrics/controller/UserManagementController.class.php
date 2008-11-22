
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

require_once 'propel/Propel.php';
Propel::init('infinitymetrics/orm/config/om-conf.php');

require_once 'infinitymetrics/model/InfinityMetricsException.class.php';
require_once 'infinitymetrics/model/institution/Student.class.php';
require_once 'infinitymetrics/model/user/User.class.php';
require_once 'infinitymetrics/model/user/PersonalAgent.class.php';
require_once 'infinitymetrics/model/institution/Instructor.class.php';
require_once 'infinitymetrics/model/institution/Institution.class.php';

/*
 * @author: Marcello de Sales <ddslkd>
 * @author: Gurdeep Singh <gurdeepsingh03@gmail.com>
 */
final class UserManagementController {

    /**
     * @param <type> $username is the java.net username of the user
     * @param <type> $password is the java.net passworld of the user
     * @return boolean whether the user has passed the correct credentials or not.
     */
    public static function areUserCredentialsValidOnJN($username, $password) {
        $agent = UserManagementController::makeAgentForUserCredentials($username, $password);
        return $agent->areUserCredentialsValidOnJN();
    }
    /**
     * Builds a new PersonalAgent for a given credentials.
     * @param string $username is the username of the user on Java.net
     * @param string $password is the password of the user on Java.net
     * @return PersonalAgent is the agent to be used on the website.
     */
    public static function makeAgentForUserCredentials($username, $password) {
        $user = new User();
        $user->setJnUsername($username);
        $user->setJnPassword($password);
        $agent = new PersonalAgent($user);
        return $agent;
    }
    /**
     * Authenticate the user on Java.net using the username and password.
     * @param string $username is the username of the user on Java.net
     * @param string $password is the password of the user on Java.net
     * @return PersonalAgent the agent representation. It can be used to retrieve information about the user's
     * profile such as email address, full-name, if it's successfully logged-in, etc.
     */
    public static function authenticateJNUser($username, $password) {
        $agent = UserManagementController::makeAgentForUserCredentials($username, $password);
        return $agent;
    }
   /**
    * This method implements the registration of Student
    *
    * @param string $username
    * @param string $password
    * @param string $email
    * @param string $firstName
    * @param string $lastName
    * @param string $studentSchoolId
    * @param string $projectName
    * @return string $institutionAbbreviation
    */
    public static function registerStudent($username, $password, $email, 
                          $firstName,$lastName, $studentSchoolId, $projectName, 
                          $institutionAbbreviation, $isLeader) {
        $error = array();
        if (!isset($username) || $username == "") {
            $error["username"] = "The username is empty";
        }
        if (!isset($password) || $password == "") {
            $error["password"] = "The password is empty";
        }
        if (!isset($firstName) || $firstName == "") {
            $error["firstName"] = "The firstName is empty";
        }
        if (!isset($lastName) || $lastName == "") {
            $error["lastName"] = "The lastName is empty";
        }
        if (!isset($email) || $email == "") {
            $error["email"] = "The email is empty";
        }
        if (!isset($studentSchoolId) || $studentSchoolId == "") {
            $error["studentSchoolId"] = "The student school Id is empty";
        }
        if (!isset($projectName) || $projectName == "") {
            $error["projectName"] = "The java.net project name is empty";
        }
        if (!isset($institutionAbbreviation) || $institutionAbbreviation == "") {
            $error["institution"] = "The institution is empty";
        }
        if (!isset($isLeader)) {
            $error["isLeader"] = "The information if the the student is a leader is not given";
        }

        if (count($error) > 0) {
            throw new InfinityMetricsException("There are errors in the input", $error);
        }
        
        try {
            $inst = PersistentBaseInstitutionPeer::retrieveByAbbreviation($institutionAbbreviation);
            $proj = PersistentBaseProjectPeer::retrieveByPK($projectName);

            $student = new Student();
            $student->setFirstName($firstName);
            $student->setLastName($lastName);
            $student->setEmail($email);
            $student->setJnUsername($username);
            $student->setJnPassword($password);
            $student->setInstitution($inst);
            $student->save();

            $studProj = new PersistentStudentXProject();
            $studProj->setStudentschoolid($studentSchoolId);
            $studProj->setIsLeader($isLeader);
            $studProj->setProject($proj);
            $studProj->setUser($student);
            $studProj->save();

        } catch (Exception $e) {
            $error["save_student"] = $e->getMessage();
            throw new InfinityMetricsException("An error occurred while saving creating the student account.", $error);
        }

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
     public static function registerInstructor($username, $password, $email, $firstName,$lastName,
                                                     $institutionAbbreviation,$projectName) {

          $error = array();
        if (!isset($username) || $username == "") {
            $error["username"] = "The username is empty";
        }
        if (!isset($password) || $password == "") {
            $error["password"] = "The password is empty";
        }
        if (!isset($firstName) || $firstName == "") {
            $error["firstName"] = "The firstName is empty";
        }
        if (!isset($lastName) || $lastName == "") {
            $error["lastName"] = "The lastName is empty";
        }
        if (!isset($email) || $email == "") {
            $error["email"] = "The email is empty";
        }

        if (!isset($projectName) || $projectName == "") {
            $error["projectName"] = "The java.net project name is empty";
        }
        if (!isset($institutionAbbreviation) || $institutionAbbreviation == "") {
            $error["institution"] = "The institution is empty";
        }


        if (count($error) > 0) {
            throw new InfinityMetricsException("There are errors in the input", $error);
        }

        try {
            $inst = PersistentBaseInstitutionPeer::doSelectOne($institutionAbbreviation);
            $proj = PersistentBaseProjectPeer::retrieveByPK($projectName);

            $instructor = new Instructor();
            $instructor->setFirstName($firstName);
            $instructor->setLastName($lastName);
            $instructor->setEmail($email);
            $instructor->setJnUsername($username);
            $instructor->setJnPassword($password);
            $instructor->setInstitution($inst);
            $instructor->save();

            $instProj = new PersistentWorkpaceXProject();
            $instProj->setProject($proj);
            $instProj->save();
            
        } catch (Exception $e) {
            $error["save_instructor"] = $e->getMessage();
            throw new InfinityMetricsException("An error occurred while saving creating the instructor account.", $error);
        }

        return $instructor;
    }

  
    public static function viewAccount($userName,$password){
         $user = new User();
        $link= mysql_connect('localhost','root','1234');
         $db = mysql_select_db('infinitymetricsm303',$link);
         if (!$db) {
           die ('Can\'t use infinitymetricsm303 : ' . mysql_error());
          }
          else {
         $sql = "SELECT * FROM user WHERE jn_username = $userName AND jn_password = $password";
         if($result = mysql_query($sql)){
           if($view = mysql_fetch_row($result))
            echo($view);
           else
           echo 'SQL ERROR -----'.mysql_eror() ;
           }
           mysql_close();
           
           }
          
        
    }

    
}
?>