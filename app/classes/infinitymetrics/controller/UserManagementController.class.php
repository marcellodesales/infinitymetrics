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
     * Authenticate the user on Java.net using the username and password.
     * @param string $username is the username of the user on Java.net
     * @param string $password is the password of the user on Java.net
     * @return PersonalAgent the agent representation. It can be used to retrieve information about the user's
     * profile such as email address, full-name, if it's successfully logged-in, etc.
     */
   public static function authenticateJNUser($username, $password) {
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
        $agent = UserManagementController::makeAgentForUserCredentials($username, $password);
        return $agent;
    }
    /**
     * @param <type> $username is the java.net username of the user
     * @param <type> $password is the java.net passworld of the user
     * @return boolean whether the user has passed the correct credentials or not.
     */
    public static function areUserCredentialsValidOnJN($username, $password) {
        $agent = null;
        try {
            $agent = UserManagementController::authenticateJNUser($username, $password);
        } catch (InfinityMetricsException $ime) {
            throw $ime;
        }
        return $agent->areUserCredentialsValidOnJN();
    }
    /**
     * Builds a new PersonalAgent for a given credentials.
     * @param string $username is the username of the user on Java.net
     * @param string $password is the password of the user on Java.net
     * @return PersonalAgent is the agent to be used on the website.
     */
    private static function makeAgentForUserCredentials($username, $password) {
        $user = new User();
        $user->setJnUsername($username);
        $user->setJnPassword($password);
        $agent = new PersonalAgent($user);
        return $agent;
    }
    /**
     *  Validates the registration input form.
     * @param <type> $username
     * @param <type> $password
     * @param <type> $email
     * @param <type> $firstName
     * @param <type> $lastName
     * @param <type> $studentSchoolId
     * @param <type> $projectName
     * @param <type> $institutionAbbreviation
     * @param <type> $isLeader
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
    * @return string $institutionAbbreviation the institution abbreviation
    * @return boolean $isLeader defines if the student is a leader of the given $projectName.
    */
    public static function registerStudent($username, $password, $email,
                          $firstName,$lastName, $studentSchoolId, $projectName,
                          $institutionAbbreviation, $isLeader) {
        try {
            UserManagementController::validateStudentRegistrationForm($username, $password, $email, $firstName, $lastName, $studentSchoolId, $projectName, $institutionAbbreviation, $isLeader);
            $inst = PersistentInstitutionPeer::retrieveByAbbreviation($institutionAbbreviation);
            $proj = PersistentProjectPeer::retrieveByPK($projectName);

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
		
	    $subject = "Welcome to Infinity Metrics 'nightly build'";
 	    $body = "Hello ".$student->getFirstName().",\n\nWe'd like to welcome you to Infinity Metrics... We strive to provide you the best experience when analyzing your team(s) performance through the Infinity Metrics...\n\nYour Java.net login information was saved at Infinity Metrics database to better provide you automated services. Your Personal Agent will collect your team(s)'s data, while you play golf or go to a barbecue... However, note that your Personal Agent always needs your current Java.net login information in case you change it.\n\nPlease feel free to contact the 'Infinity Team' at any time at users@ppm8.dev.java.net.\n\nEnjoy!\n\nInfinity Metrics: Automatic Collaboration Metrics for java.net Projects\nhttp://ppm8.dev.java.net\nMailing Lists: https://ppm-8.dev.java.net/servlets/ProjectMailingListList";	
	    UserManagementController::sendEmailToUser($student, $subject, $body);
            return $student;

        } catch (Exception $e) {
            throw $e;
        }
    }
    /**
     * Sends an email to a given User, using it's first name...
     * @param User $user is the given user. 
     * @param string $subject is the subject that goes with the email
     * @param string $body is the body of the email. Note that this is for plain text email, so use "\n" for new line feed/carriege return.
     */
    public static function sendEmailToUser(User $user, $subject, $body) {
	$headers = "From: noreply@infinitymetrics.net\r\nReply-To: dev@ppm-8.dev.java.net";
	if (!mail($user->getEmail(), $subject, $body, $headers)) {
		 $error = array();
                 $error["emailServerDown"] = "The Infinity Metrics email server cannot deliever email at this time...";
        	 throw new InfinityMetricsException("There are errors in the input", $error);
	}
    }

    /**
     * Validates the registration form for instructor
     */
    public static function validateInstructorRegistrationForm($username, $password, $email,
                          $firstName,$lastName,$projectName,$institutionAbbreviation) {
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
    * @return string $institutionAbbreviation the institution abbreviation
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
            $instructor->setInstitution($inst);
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
    }

    public static function viewAccount($username) {
        $c = new Criteria();
        $c->add(PersistentUserPeer::JN_USERNAME, $username);
        return PersistentUserPeer::doSelect($c);
    }
}




?>
