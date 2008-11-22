<?php
/**
 * $Id: PersonalAgent.class.php 202 2008-11-02 21:31:40Z marcellosales $
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
require_once 'infinitymetrics/util/screenscraperapi/JNUrlBuilder.class.php';
require_once 'infinitymetrics/util/screenscraperapi/useredit/JNUserEditImpl.class.php';

/**
 * Basic user class for the metrics workspace. User has username, password from
 * Java.net.
 *
 * @author Marcello de Sales <marcello.sales@gmail.com> Nov 15, 2008 10:34 PST
 * @version $Id$
 */
class PersonalAgent {
    /**
     * @var User is the user in which the Personal Agent represent online.
     */
    private $user;

    private $session;
    /**
     * @var JNUserEditWSInterface The information from the UserEdit page
     * for a given Java.net User. This is used by UC001 and UC002 during the
     * user's registration.
     */
    private $userEditWSImpl;
    /**
     * Constructs a new PersonalAgent for a given user. The agent will be going
     * to Java.net and try to navigate as a user
     * @param User $user is the user that the Agent will represent online.
     */
    public function  __construct(User $user) {
        $this->user = $user;
        $this->makeJNLogin();
    }

    /**
     * Compares 2 instances of the Personal Agent class by comparing the Java.net User instance..
     * @param PersonalAgent $other is the other personal agent to be compared
     * @return boolean if the given PersonalAgent "other" is the same as the current instance.
     */
    public function equals($other) {
        if ($other instanceof PersonalAgent) {
            return $this->getUser()->equals($other->getUser());
        } else {
            return false;
        }
    }
    /**
     * @return User returns an instance of User (it can be also Student or Instructor, depending on the user).
     */
    public function getUser() {
        return $this->user;
    }
    /**
     * @return String returns the Java.net username.
     */
    public function getJnUsername() {
        return $this->user->getJnUsername();
    }
    /**
     * @return void makes the login with Java.net and keeps the session open
     * to the user.
     */
    private function makeJNLogin() {
        $this->session = new JNSessionImpl($this->user->getJnUsername(), $this->user->getJnPassword());
        $this->session->makeLogin(JNUrlBuilder::makeUserEditUrl());
    }
    /**
     * Verifies if the user has been authenticated on Java.net
     * @return boolean true if the UserEditWSImpl has been created.
     */
    private function isUserAuthenticated() {
        return isset($this->userEditWSImpl);
    }
    /**
     * Starts the UserEditWSImp to enable the UserEdit methods
     */
    private function startUserEditWSImplInstance() {
        if (!$this->isUserAuthenticated()){
            $this->userEditWSImpl = new JNUserEditImpl($this->session);
        }
    }
    /**
     * @return if the user credentials are valid on Java.net. That is, if the user provided the correct
     * Username and password for Java.net and can login
     */
    public function areUserCredentialsValidOnJN() {
        if (!$this->isUserAuthenticated()){
            $this->userEditWSImpl = new JNUserEditImpl($this->session);
        }
        return $this->userEditWSImpl->areUserCredentialsValid();
    }
    /**
     * @return string the user's email
     */
    public function getAuthenticatedEmail() {
        $this->startUserEditWSImplInstance();
        return $this->userEditWSImpl->getUserEmail();
    }
    /**
     * @return String return the full name of the user
     */
    public function getAuthenticatedFullName() {
        $this->startUserEditWSImplInstance();
        return $this->userEditWSImpl->getUserFullName();
    }
    /**
     * @return project[name] = {role1, role2, role3, ...} 
     */
    public function getAuthenticatedProjectsMembershipList() {
        $this->startUserEditWSImplInstance();
        return $this->userEditWSImpl->getUserProjectsMembershipList();
    }
}
?>