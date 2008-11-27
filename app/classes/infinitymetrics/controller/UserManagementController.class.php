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
require_once 'infinitymetrics/controller/PersonalAgentController.class.php';
require_once 'infinitymetrics/util/SendEmail.class.php';

/*
 * @author: Marcello de Sales <marcello.sales@gmail.com>
 * @author: Gurdeep Singh <gurdeepsingh03@gmail.com>
 */
final class UserManagementController {

    const DOMAIN = "dev.java.net";
    
    /**
     * @param string $username is the java.net username of the user
     * @param string $password is the java.net passworld of the user
     * @return boolean whether the user has passed the correct credentials or not.
     */
    public static function areUserCredentialsValidOnJN($username, $password) {
        $agent = null;
        $error = array();
        if (!isset($username) || $username == "") {
            $error["username"] = "The username is empty";
        }
        if (!isset($password) || $password == "") {
            $error["password"] = "The password is empty";
        }

        if (count($error) > 0) {
            throw new InfinityMetricsException("There are errors in the input", $error);
        }
        
        try {
            $agent = PersonalAgentController::authenticateJNUser($username, $password);
        } catch (InfinityMetricsException $ime) {
            throw $ime;
        }
        return $agent->areUserCredentialsValidOnJN();
    }
    /**
     *  Validates the registration input form.
     * @param string $username
     * @param string $password
     * @param string $email
     * @param string $firstName
     * @param string $lastName
     * @param string $studentSchoolId
     * @param string $projectName
     * @param string $institutionAbbreviation
     * @param boolean $isLeader
     */
    public static function validateStudentRegistrationForm($username, $password, $email,
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
    }
   /**
    * This method implements the registration of Student UC001
    *
    * @param string $username the java.net username
    * @param string $password the java.net password
    * @param string $email the user's email
    * @param string $firstName the user's first name
    * @param string $lastName the user's last name
    * @param string $studentSchoolId the student's school identification
    * @param string $projectName the user's project name
    * @param string $institutionAbbreviation the institution abbreviation
    * @param boolean $isLeader defines if the student is a leader of the given $projectName.
    * @return Student the instance of the student for the given input. It also relates the studnet to the
    * instance of project and the institution identified by the abbreviation.
    * @throws InfinityMetricsException, PropelException, Exception depending on what goes wrong.
    */
    public static function registerStudent($username, $password, $email,
                          $firstName,$lastName, $studentSchoolId, $projectName,
                          $institutionAbbreviation, $isLeader) {
        try {
            UserManagementController::validateStudentRegistrationForm($username, $password, $email, $firstName,
                                        $lastName, $studentSchoolId, $projectName, $institutionAbbreviation, $isLeader);
            $inst = PersistentInstitutionPeer::retrieveByAbbreviation($institutionAbbreviation);
            $proj = PersistentProjectPeer::retrieveByPK($projectName);

            $student = new Student();
            $student->setFirstName($firstName);
            $student->setLastName($lastName);
            $student->setEmail($email);
            $student->setJnUsername($username);
            $student->setJnPassword($password);
            $student->setInstitutionId($inst->getInstitutionId());
            $student->save();

            $studProj = new PersistentStudentXProject();
            $studProj->setStudentschoolid($studentSchoolId);
            $studProj->setIsLeader($isLeader);
            $studProj->setProject($proj);
            $studProj->setUser($student);
            $studProj->save();
		
            $subject = "Welcome to Infinity Metrics 'nightly build'";
            $body = "Hello ".$student->getFirstName().",\n\nWe'd like to welcome you to Infinity Metrics... We strive 
                    to provide you the best experience when analyzing your team(s) performance through the Infinity
                    Metrics...\n\nYour Java.net login information was saved at Infinity Metrics database to better
                    provide you automated services. Your Personal Agent will collect your team(s)'s data, while you
                    play golf or go to a barbecue... However, note that your Personal Agent always needs your current
                    Java.net login information in case you change it.\n\nPlease feel free to contact the 'Infinity
                    Team' at any time at users@ppm8.dev.java.net.\n\nEnjoy!\n\nInfinity Metrics: Automatic
                    Collaboration Metrics for java.net Projects\nhttp://ppm8.dev.java.net\nMailing Lists:
                    https://ppm-8.dev.java.net/servlets/ProjectMailingListList";
            
            SendEmail::sendTextEmail("noreply@infinitymetrics.net", "dev@". $projectName . self::DOMAIN,
                                                                                $student->getEmail(), $subject, $body);
            return $student;

        } catch (Exception $e) {
            throw $e;
        }
    }
    /**
     * Validates the Instructor's regirstration form.
     * @param string $username
     * @param string $password
     * @param string $email
     * @param string $firstName
     * @param string $lastName
     * @param string $projectName
     * @param string $institutionAbbreviation
     * @return whether the form is valid or not.
     * @throws InfinityMetricsException if the form contains errors.
     */
    public static function validateInstructorRegistrationForm($username, $password, $email,
                          $firstName, $lastName, $parentProjectName, $institutionAbbreviation) {
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
        return true;
    }
   /**
    * This method implements the registration of Instructor UC002
    *
    * @param string $username the java.net username
    * @param string $password the java.net password
    * @param string $email the user's email
    * @param string $firstName the user's first name
    * @param string $lastName the user's last name
    * @param string $projectName the user's project name
    * @param string $institutionAbbreviation the institution abbreviation
    * @return Instructor representing the instructor instance for the given form
    */
    public static function registerInstructor($username, $password, $email,
                          $firstName,$lastName,$projectName,$institutionAbbreviation) {
        try {
            UserManagementController::validateInstructorRegistrationForm($username, $password, $email, $firstName, $lastName,$projectName, $institutionAbbreviation);
            $inst = PersistentInstitutionPeer::retrieveByAbbreviation($institutionAbbreviation);
            $proj = PersistentProjectPeer::retrieveByPK($projectName);

            $instructor = new Instructor();
            $instructor->setFirstName($firstName);
            $instructor->setLastName($lastName);
            $instructor->setEmail($email);
            $instructor->setJnUsername($username);
            $instructor->setJnPassword($password);
            $instructor->setInstitutionId($inst->getInstitutionId());
            $instructor->save();

            $instProj = new PersistentWorkpaceXProject();
            $instProj->setProject($proj);
            $instProj->save();



            $subject = "Welcome to Infinity Metrics";
 	        $body = "Hello ".$instructor->getFirstName().",\n\nWe'd like to welcome to Infinity Metrics... Know that we strive to provide the best experience when you're looking at your team(s) metrics...\n\nYour Java.net login information was saved at Infinity Metrics database to better provide you automated services. Your personal agent will collect your team's data, while you can play golf... However, note that for automatic services need your most updated Java.net information in case you change it.\n\nPlease feel free to contact the 'Infinity Team' at any time at http://ppm-8.dev.java.net.\n\nEnjoy!";
	        UserManagementController::sendEmailToUser($instructor, $subject, $body);
            return $instructor;

        } catch (Exception $e) {
            throw $e;
        }
        return $instructor;
    }
    /** this is the function to implement the login functionality for User
 *
 * @param <type> $userName
 * @param <type> $password
 * @return <type>
 */

    /**
     * Makes the user login
     * @param string $userName is the java.net username from a user
     * @param string $password is the java.net password for the a user
     * @return PersistentUser instance. 
     */
    public static function login($userName, $password) {
        $error = array();
        if (!isset($userName) || $userName == "") {
            $error["username"] = "The username is empty";
        }
        if (!isset($password) || $password == "") {
            $error["password"] = "The password is empty";
        }

        if (count($error) > 0) {
            throw new InfinityMetricsException("There are errors in the input", $error);
        }

         
       $c = new Criteria();
       $c->add(PersistentUserPeer::JN_USERNAME, $userName);
       $c->add(PersistentUserPeer::JN_PASSWORD, $password);

       return PersistentUserPeer::doSelect($c);
    }
}
?>
