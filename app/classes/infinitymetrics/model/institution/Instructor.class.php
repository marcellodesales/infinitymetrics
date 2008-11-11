<?php
/**
 * $Id: Instructor.class.php 202 2008-11-10 12:01:40Z Gurdeep Singh $
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
require_once 'infinitymetrics/model/user/User.class.php';
require_once 'infinitymetrics/model/institution/Institution.class.php';
/**
 * The instructorinfinitymetrics class for the metrics workspace.
 * @author Gurdeep Singh <gurdeepsingh03@gmail.com>
 */
class Instructor extends User {

    /**
     * This is the institution reference.
     * @var Institution instance of the institution class.
     */
    private $institution;
    
    /**
     * It's the first name of the instructor.
     * @var string the reference to the name
     */
    public $firstName;

    /**
     * It's the instructor's last name.
     * @var string the instance of the last name
     */
    public $lastName;

    /**
     * It's the instructor's email address.
     * @var string the instance of the email.
     */
    private $email;

    /**
     * It's the instructor's Project name.
     * @var string the instance of th Project name
     */
    private $projectName;

    /**
     * It's the instructor's username.
     * @var string the instance of th username
     */
    //private $userName;

    public function __construct($firstName,$lastName) {
        parent::__construct($firstName, $lastName);
       
    }

    public function getInstitution() {
       return $this->institution ;
    }
    public function setInstitution($institution) {
        $this->institution = $institution;
    }
    public function getEmail(){
        return $this->email;
    }
    public function setEmail($email){
        $this->email = $email;
    }
   public function getProjectName(){
        return $this->$projectName;
   }
   public function setProjectName($projectName){
         $this->projectName = $projectName;
   }
   public function getFirstName() {
       return $this->firstName ;
    }
    public function setFirstName($firstName) {
        $this->firstName = $firstName ;
    }
    public function getLastName() {
       return $this->lastName ;
    }
    public function setLastName($lastName) {
        $this->lastName = $lastName ;
    }
    /*public function getUserName() {
       return $this->userName ;
    }
    public function setUserName($userName) {
        $this->userName = $userName ;
    }
     *
     */
}
   
?>
