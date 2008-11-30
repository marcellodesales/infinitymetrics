<?php
/**
 * $Id: UserManagementController.class.php 202 2008-11-10 12:01:40Z
 * Gurdeep Singh, Marcello de Sales
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
require_once 'infinitymetrics/controller/MetricsWorkspaceController.php';

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
                          $firstName, $lastName, $studentSchoolId, $projectName,
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
            if ($inst == null) {
                $errors = array();
                $errors["institutionNotFound"] = "The institution referred by " . $institutionAbbreviation .
                                                  " doesn't exist";
                throw new InfinityMetricsException("Can't register Student", $errors);
            }

            $proj = PersistentProjectPeer::retrieveByPK($projectName);
            if ($proj == null) {
                $errors = array();
                $errors["projectNotFound"] = "The project referred by " . $projectName . " doesn't exist";
                throw new InfinityMetricsException("Can't register Student", $errors);
            }

            $student = new Student();
            $student->setFirstName($firstName);
            $student->setLastName($lastName);
            $student->setEmail($email);
            $student->setJnUsername($username);
            $student->setJnPassword($password);
            $student->save();

            self::makeInstitutionUserRelations($student, $inst, $studentSchoolId, $proj, $isLeader);

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
            
            //SendEmail::sendTextEmail("noreply@infinitymetrics.net", "dev@". $projectName . self::DOMAIN,
              //                                                                  $student->getEmail(), $subject, $body);
            return $student;

        } catch (Exception $e) {
            throw $e;
        }
    }

    private static function makeInstitutionUserRelations(PersistentUser $newUser, PersistentInstitution
                                                         $existingInstitution, $institutionIdentification,
                                                         PersistentProject $existingProject, $isProjectOwner) {
        try {
            $studentInstitution = new PersistentUserXInstitution();
            $studentInstitution->setInstitution($existingInstitution);
            $studentInstitution->setUser($newUser);
            $studentInstitution->setIdentification($institutionIdentification);
            $studentInstitution->save();

            $userProject = new PersistentUserXProject();
            $userProject->setUser($newUser);
            $userProject->setProject($existingProject);
            $userProject->setIsOwner($isProjectOwner);
            $userProject->save();

            //TODO: collect the names of all children project if you are project owner

        } catch (Exception $e) {
            $errors = array();
            $errors["institutionRelations"] = $e->getMessage();
            throw new InfinityMetricsException("Error saving user's institution relations", $errors);
        }
    }
    /**
     *  Validates the registration input form.
     * @param string $username
     * @param string $password
     * @param string $email
     * @param string $firstName
     * @param string $lastName
     * @param string $projectName
     * @param boolean $isProjectOwner
     */
    private static function validateUserRegistrationForm($username, $password, $email, $firstName, $lastName,
                                                            $projectName, $isProjectOwner) {
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
        if (!isset($isProjectOwner)) {
            $error["isLeader"] = "The information if the the student is a leader is not given";
        }

        if (count($error) > 0) {
            throw new InfinityMetricsException("There are errors in the input", $error);
        }
    }
   /**
    * This method implements the registration for User 
    *
    * @param string $username the java.net username
    * @param string $password the java.net password
    * @param string $email the user's email
    * @param string $firstName the user's first name
    * @param string $lastName the user's last name
    * @param string $projectName the user's project name
    * @param boolean $isLeader defines if the student is a leader of the given $projectName.
    * @return User the instance of the user for the given input. It also relates the studnet to the
    * instance of project and the institution identified by the abbreviation.
    * @throws InfinityMetricsException, PropelException, Exception depending on what goes wrong.
    */
    public static function registerUser($username, $password, $email, $firstName, $lastName, $projectName,
                                                                                                    $isProjectOwner) {
        try {
            UserManagementController::validateUserRegistrationForm($username, $password, $email, $firstName,
                                                                      $lastName, $projectName, $isProjectOwner);

            $proj = PersistentProjectPeer::retrieveByPK($projectName);

            $user = new User();
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setEmail($email);
            $user->setJnUsername($username);
            $user->setJnPassword($password);
            $user->save();

            $userProject = new PersistentUserXProject();
            $userProject->setUser($user);
            $userProject->setProjectJnName($proj->getProjectJnName());
            $userProject->setIsOwner($isProjectOwner);
            $userProject->save();

            //TODO: Metrics workspace collect the names of all children project if you are IS PROJECt OWNER owner

            $subject = "Welcome to Infinity Metrics 'nightly build'";
            $body = "Hello ".$user->getFirstName().",\n\nWe'd like to welcome you to Infinity Metrics... We strive
                    to provide you the best experience when analyzing your team(s) performance through the Infinity
                    Metrics...\n\nYour Java.net login information was saved at Infinity Metrics database to better
                    provide you automated services. Your Personal Agent will collect your team(s)'s data, while you
                    play golf or go to a barbecue... However, note that your Personal Agent always needs your current
                    Java.net login information in case you change it.\n\nPlease feel free to contact the 'Infinity
                    Team' at any time at users@ppm8.dev.java.net.\n\nEnjoy!\n\nInfinity Metrics: Automatic
                    Collaboration Metrics for java.net Projects\nhttp://ppm8.dev.java.net\nMailing Lists:
                    https://ppm-8.dev.java.net/servlets/ProjectMailingListList";

            //SendEmail::sendTextEmail("noreply@infinitymetrics.net", "dev@". $projectName . self::DOMAIN,
              //                                                                  $student->getEmail(), $subject, $body);
            return $user;

        } catch (Exception $e) {
            $errors = array();
            $errors["userRegistration"] = $e->getMessage();
            throw new InfinityMetricsException("Error registering the user", $errors);
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
    public static function validateInstructorRegistrationForm($username, $password, $email, $firstName,
                                   $lastName, $projectName, $isOwner, $institutionAbbreviation, $institutionIdent) {
        $error = array();
        if (!isset($username) || $username == "") {
            $error["username"] = "The username is empty";
        }
        if (!isset($password) || $password == "") {
            $error["password"] = "The password is empty";
        }
        if (!isset($email) || $email == "") {
            $error["email"] = "The email is empty";
        }
        if (!isset($firstName) || $firstName == "") {
            $error["firstName"] = "The firstName is empty";
        }
        if (!isset($lastName) || $lastName == "") {
            $error["lastName"] = "The lastName is empty";
        }
        if (!isset($projectName) || $projectName == "") {
            $error["projectName"] = "The project name is empty";
        }
        if (!isset($isOwner) || $isOwner == "") {
            $error["isOwner"] = "The definition of isOwner is empty";
        }
        if (!isset($institutionAbbreviation) || $institutionAbbreviation == "") {
            $error["institutionAbbreviation"] = "The institution abbreviation is empty";
        }
        if (!isset($institutionIdent) || $institutionIdent == "") {
            $error["institutionIdentification"] = "The institution identification is empty";
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
    public static function registerInstructor($username, $password, $email, $firstName, $lastName, $projectName,
                                                $isOwner, $institutionAbbreviation, $schoolIdentification) {
        try {
            UserManagementController::validateInstructorRegistrationForm($username, $password, $email, $firstName,
                                   $lastName, $projectName, $isOwner, $institutionAbbreviation, $schoolIdentification);

            $inst = PersistentInstitutionPeer::retrieveByAbbreviation($institutionAbbreviation);
            $proj = PersistentProjectPeer::retrieveByPK($projectName);

            $instructor = new Instructor();
            $instructor->setFirstName($firstName);
            $instructor->setLastName($lastName);
            $instructor->setEmail($email);
            $instructor->setJnUsername($username);
            $instructor->setJnPassword($password);
            $instructor->save();

            self::makeInstitutionUserRelations($instructor, $inst, $schoolIdentification, $proj, $isOwner);
            
            $subject = "Welcome to Infinity Metrics";
 	        $body = "Hello ".$instructor->getFirstName().",\n\nWe'd like to welcome to Infinity Metrics... Know that 
                     we strive to provide the best experience when you're looking at your team(s) metrics...\n\nYour
                     Java.net login information was saved at Infinity Metrics database to better provide you automated
                     services. Your personal agent will collect your team's data, while you can play golf... However,
                     note that for automatic services need your most updated Java.net information in case you change
                     it.\n\nPlease feel free to contact the 'Infinity Team' at any time at http://ppm-8.dev.java.net.
                     \n\nEnjoy!";
	        //UserManagementController::sendEmailToUser($instructor, $subject, $body);
            return $instructor;

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Makes the user login for a given user. This is the implementation of UC003
     * @param string $userName is the java.net username from a user
     * @param string $password is the java.net password for the a user
     * @return PersistentUser instance.
     */
    public static function login($userName, $password) {

        $error = array();
        
        if (!isset($userName) || $userName == "")  {
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
        $user = PersistentUserPeer::doSelect($c);
        if ($user == null) {
            $error["usernameIncorrect"] = "The user '".$userName."' is not registered";
            throw new InfinityMetricsException("Login failed", $error);
        }
        
        $c->add(PersistentUserPeer::JN_PASSWORD, $password);
        $user = PersistentUserPeer::doSelect($c);
        if ($user == null) {
            $error["passwordDoesnMatch"] = "The password for the '".$userName."' doesn't match";
            throw new InfinityMetricsException("Login fail", $error);
        }
        return $user;
    }

   /**
     * This method implements part of the UC004 to retrieve User
     * @param string $user_id is the workspace identification or any other and other possible persistence problems.
     * @throws InfinityMetricsException if the given user_id is empty. Also if it refers to a non-existing user.
     * @return PersistentUser the instance of the user for the given user_id
     */
    public static function retrieveUser($user_id) {
        if (!isset($user_id)|| $user_id == "") {
            $errors = array();
            $errors["user_id"] = "The user id must be provided";
            throw new InfinityMetricsException("Can't retrieve user", $errors);
        }

        $user = PersistentUserPeer::retrieveByPK($user_id);
        if ($user == NULL) {
            $errors = array();
            $errors["userNotFound"] = "The user referred by " . $user_id . " doesn't exist";
            throw new InfinityMetricsException("Can't retrieve user", $errors);
        }
        return $user;
    }
    
   

    /**
     *
     * @param <type> $user_id is the id of the user.
     * @return <type> PersistentUser is the instance of the user.
     */
    public static function viewProfile($user_id){
         $errors = array();
         if (!isset($user_id)|| $user_id == "") {
            $errors["user_id"] = "The user id must be provided";
            throw new InfinityMetricsException("Can't retrieve user", $errors);
        }

        $c = new Criteria();
        $c->add(PersistentUserPeer::USER_ID,$user_id);
        $profile = PersistentUserPeer::doSelect($c);
        if ($profile == NULL) {
            $errors = array();
            $errors["userNotFound"] = "The user referred by " . $user_id . " doesn't exist";
            throw new InfinityMetricsException("Can't retrieve user", $errors);
        }
        return $profile;
        
    }

    /**
     * retrieveUserByUserName retrieve user by username .
     * @param <type> $username
     * @return <type>
     */
    public static function retrieveUserByUserName($username) {
        if (!isset($username)|| $username == "") {
            $errors = array();
            $errors["username"] = "The user id must be provided";
            throw new InfinityMetricsException("Can't retrieve user", $errors);
        }

        $user = PersistentUserPeer::retrieveByJNUsername($username);
        if ($user == NULL) {
            $errors = array();
            $errors["userNotFound"] = "The user referred by " . $username . " doesn't exist";
            throw new InfinityMetricsException("Can't retrieve user", $errors);
        }
        return $user;
    }

    /** This is the implemention of UC005
     * this function validates the values .
     *
     * @param <type> $username is the exixting username
     * @param <type> $newPassword new password of instructor to be updated
     * @param <type> $newEmail  new Email of instructor to be updated
     * @param <type> $newFirstName new first name of instructor to be updated
     * @param <type> $newLastName  new last name of instructor to be updated
     * @param <type> $newProjectName  new project of instructor to be updated
     * @param <type> $new_isOwner  new value of ownership of instructor to be updated
     * @param <type> $newInstitutionAbbreviation  new institution of instructor to be updated
     * @param <type> $newInstitutionIdent  new School identification of instructor to be updated
     * @return <type>
     */
 public static function validateInstructorprofileUpdate($username, $newPassword, $newEmail, $newFirstName,
                                   $newLastName, $newProjectName, $new_isOwner, $newInstitutionAbbreviation, $newInstitutionIdent) {
        $error = array();
        if (!isset($username) || $username == "") {
            $error["username"] = "The username is empty";
        }
        if (!isset($newPassword) || $newPassword == "") {
            $error["newPassword"] = "The password is empty";
        }
        if (!isset($newEmail) || $newEmail == "") {
            $error["newEmail"] = "The email is empty";
        }
        if (!isset($newFirstName) || $newFirstName == "") {
            $error["newFirstName"] = "The firstName is empty";
        }
        if (!isset($newLastName) || $newLastName == "") {
            $error["newLastName"] = "The lastName is empty";
        }
        if (!isset($newProjectName) || $newProjectName == "") {
            $error["newProjectName"] = "The project name is empty";
        }
        if (!isset($new_isOwner) || $new_isOwner == "") {
            $error["new_isOwner"] = "The definition of isOwner is empty";
        }
        if (!isset($newInstitutionAbbreviation) || $newInstitutionAbbreviation == "") {
            $error["newInstitutionAbbreviation"] = "The institution abbreviation is empty";
        }
        if (!isset($newInstitutionIdent) || $newInstitutionIdent == "") {
            $error["newInstitutionIdentification"] = "The institution identification is empty";
        }

        if (count($error) > 0) {
            throw new InfinityMetricsException("There are errors in the input", $error);
        }
        return true;
    }

    /** Implemention of UC005
     * this function saves the updated values into system.
     * 
     * @param <type> $username is the exixting username
     * @param <type> $newPassword new password of instructor to be updated
     * @param <type> $newEmail  new Email of instructor to be updated
     * @param <type> $newFirstName new first name of instructor to be updated
     * @param <type> $newLastName  new last name of instructor to be updated
     * @param <type> $newProjectName  new project of instructor to be updated
     * @param <type> $new_isOwner  new value of ownership of instructor to be updated
     * @param <type> $newInstitutionAbbreviation  new institution of instructor to be updated
     * @param <type> $newInstitutionIdent  new School identification of instructor to be updated
     *
     */

     public static function updateInstructorProfile($username, $newPassword, $newEmail, $newFirstName, $newLastName, $newProjectName,
                                                $new_isOwner, $newInstitutionAbbreviation, $newInstitutionIdent) {

         self::validateInstructorprofileUpdate($username, $newPassword, $newEmail, $newFirstName, $newLastName,
                $newProjectName, $new_isOwner, $newInstitutionAbbreviation, $newInstitutionIdent);

         $newInstructor = self::retrieveUserByUserName($username);
          if($newInstructor == null){
              $errors = array();
              $errors["userNotFound"]= " the user reffered by " . $username. "doesn't exist";
              throw new InfinityMetricsException("can't update profile",$errors);
          }

            $inst = PersistentInstitutionPeer::retrieveByAbbreviation($newInstitutionAbbreviation);
            if ($inst == null) {
                $errors = array();
                $errors["institutionNotFound"] = "The institution referred by " . $newInstitutionAbbreviation .
                                                  " doesn't exist";
                throw new InfinityMetricsException("Can't update Profile", $errors);
            }

            $proj = PersistentProjectPeer::retrieveByPK($newProjectName);
            if ($proj == null) {
                $errors = array();
                $errors["projectNotFound"] = "The project referred by " . $newProjectName . " doesn't exist";
                throw new InfinityMetricsException("Can't update Student", $errors);
            }

        try{
            $newInstructor->setFirstName($newFirstName);
            $newInstructor->setLastName($newLastName);
            $newInstructor->setEmail($newEmail);
            $newInstructor->setJnPassword($newPassword);
            $newInstructor->save();

            self::makeInstitutionUserRelations($newInstructor, $inst, $newInstitutionIdent, $proj, $new_isOwner);

            $subject = "Infinity Metrics : Your Profile has been updated";
 	        $body = "Hello ".$newInstructor->getFirstName().",\n\nThis is the confirmation Email . Your Profile Information
                     has been successfully updated.\n\nPlease feel free to contact the 'Infinity Team' at any time at http://ppm-8.dev.java.net.
                     \n\nEnjoy!";
	        //SendEmail::sendTextEmail("noreply@infinitymetrics.net", "dev@". $projectName . self::DOMAIN,
              //                                                                  $newInstructor->getEmail(), $subject, $body);
            return $newInstructor;

        } catch (Exception $e) {
            throw $e;
        }
    }

    /** Implementation of UC006
     * This function validates the values entered .
     * @param <type> $username   is existing username .
     * @param <type> $newPassword is new value of password to be updated.
     * @param <type> $newEmail   is new Email to be updated.
     * @param <type> $newFirstName is new first name to be updated.
     * @param <type> $newLastName  is new last name to be updated.
     * @param <type> $newStudentSchoolId  new  schood Id  to be updated.
     * @param <type> $newProjectName  new project name to be updated.
     * @param <type> $newInstitutionAbbreviation  new Institution to be updated.
     * @param <type> $new_isLeader
     */
    public static function validateStudentProfileUpdate($username, $newPassword, $newEmail,
                          $newFirstName, $newLastName, $newStudentSchoolId, $newProjectName,
                          $newInstitutionAbbreviation, $new_isLeader) {
        $error = array();
        if (!isset($username) || $username == "") {
            $error["username"] = "The username is empty";
        }
        if (!isset($newPassword) || $newPassword == "") {
            $error["newPassword"] = "The password is empty";
        }
        if (!isset($newFirstName) || $newFirstName == "") {
            $error["newFirstName"] = "The firstName is empty";
        }
        if (!isset($newLastName) || $newLastName == "") {
            $error["newLastName"] = "The lastName is empty";
        }
        if (!isset($newEmail) || $newEmail == "") {
            $error["newEmail"] = "The email is empty";
        }
        if (!isset($newStudentSchoolId) || $newStudentSchoolId == "") {
            $error["newStudentSchoolId"] = "The student school Id is empty";
        }
        if (!isset($newProjectName) || $newProjectName == "") {
            $error["newProjectName"] = "The java.net project name is empty";
        }
        if (!isset($newInstitutionAbbreviation) || $newInstitutionAbbreviation == "") {
            $error["newInstitution"] = "The institution is empty";
        }
        if (!isset($new_isLeader) || $new_isLeader == "") {
            $error["new_isLeader"] = "The information if the the student is a leader is not given";
        }

        if (count($error) > 0) {
            throw new InfinityMetricsException("Can't Update Student Profile", $error);
        }
    }
     /**
      Implementation of UC006
     * This function updates the values entered in the system .
     * @param <type> $username   is existing username .
     * @param <type> $newPassword is new value of password to be updated.
     * @param <type> $newEmail   is new Email to be updated.
     * @param <type> $newFirstName is new first name to be updated.
     * @param <type> $newLastName  is new last name to be updated.
     * @param <type> $newStudentSchoolId  new  schood Id  to be updated.
     * @param <type> $newProjectName  new project name to be updated.
     * @param <type> $newInstitutionAbbreviation  new Institution to be updated.
     * @param <type> $new_isLeader
      */
    public static function updateStudentProfile($username, $newPassword, $newEmail,
                          $newFirstName, $newLastName, $newStudentSchoolId, $newProjectName,
                          $newInstitutionAbbreviation, $new_isLeader){

        self::validateStudentProfileUpdate($username, $newPassword, $newEmail, $newFirstName, $newLastName,
                                   $newStudentSchoolId, $newProjectName, $newInstitutionAbbreviation, $new_isLeader);

        $newStudent = self::retrieveUserByUserName($username);
           if($newStudent == null){
              $errors = array();
              $errors["userNotFound"]= " the user reffered by " . $username. "doesn't exist";
              throw new InfinityMetricsException("can't update profile",$errors);
          }



         $inst = PersistentInstitutionPeer::retrieveByAbbreviation($newInstitutionAbbreviation);
            if ($inst == null) {
                $errors = array();
                $errors["institutionNotFound"] = "The institution referred by " . $newInstitutionAbbreviation .
                                                  " doesn't exist";
                throw new InfinityMetricsException("Can't update Profile", $errors);
            }

            $proj = PersistentProjectPeer::retrieveByPK($newProjectName);
            if ($proj == null) {
                $errors = array();
                $errors["projectNotFound"] = "The project referred by " . $newProjectName . " doesn't exist";
                throw new InfinityMetricsException("Can't update Student", $errors);
            }

        try {
            $newStudent->setJnPassword($newPassword);
            $newStudent->setEmail($newEmail);
            $newStudent->setFirstName($newFirstName);
            $newStudent->setLastName($newLastName);
            $newStudent->save();

            self::makeInstitutionUserRelations($newStudent, $inst, $newStudentSchoolId, $proj, $new_isLeader);

            $subject = "Welcome to Infinity Metrics 'nightly build'";
            $body = "Hello ".$newStudent->getFirstName().",\n\nThis is the confirmation Email. Your profile information
                     at Infinity Metricshas been sucessfuly updated.\n\nPlease feel free to contact the 'Infinity
                    Team' at any time at users@ppm8.dev.java.net.\n\nEnjoy!\n\nInfinity Metrics: Automatic
                    Collaboration Metrics for java.net Projects\nhttp://ppm8.dev.java.net\nMailing Lists:
                    https://ppm-8.dev.java.net/servlets/ProjectMailingListList";

            //SendEmail::sendTextEmail("noreply@infinitymetrics.net", "dev@". $projectName . self::DOMAIN,
              //                                                                  $newStudent->getEmail(), $subject, $body);
            return $newStudent;



        } catch (Exception $e) {
            throw $e;
        }
         }




   
}
?>