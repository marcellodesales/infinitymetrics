<?php
/**
 * $Id: UserManagementController.class.php 202 2008-10-21 12:01:40Z Marcello Sales
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
require_once 'infinitymetrics/model/user/User.class.php';
require_once 'infinitymetrics/model/user/PersonalAgent.class.php';
require_once 'infinitymetrics/model/user/agent/reasoning/JNRssParserSubject.class.php';
require_once 'infinitymetrics/model/user/agent/reasoning/RssToDatabaseObserver.class.php';

/**
 * Controller for the Personal Agent. It will include the functions defined in the following
 * Use Cases:
 *  - UC400 Retrieve All RSS Feeds from all channels listed by UC403
 *  - UC401 Collects the names of the subprojects for a given project
 *  - UC402 Verify user identity through Java.net
 *  - UC403 Collects the events list (all channels such as mailing lists and discussion forums)
 *  - UC404 Collects user's profile
 *  - UC405 Collects the project RSS history from the Mailinglist history.
 *
 * @author: Marcello de Sales <marcello.sales@gmail.com>
 */
final class PersonalAgentController {
    /**
     * Builds a new PersonalAgent for a given credentials.
     * @param string $username is the username of the user on Java.net
     * @param string $password is the password of the user on Java.net
     * @return PersonalAgent is the agent to be used on the website.
     */
    private static function makeAgentForUsernamePassoword($username, $password) {
        $user = new User();
        $user->setJnUsername($username);
        $user->setJnPassword($password);
        return self::makeAgentForUser($user);
    }
    /**
     * Generates an agent for a given user
     * @param PersistentUser $user is the persistent user to be used
     * @return PersonalAgent is the personal agent representing the user
     */
    private static function makeAgentForUser(PersistentUser $user) {
        $agent = new PersonalAgent($user);
        return $agent;
    }
    /**
     * Implements the method to collect the UC400 for the collection of RSS data.
     *
     * @param User $user is the user which needs to log into the Java.net project
     * @param string $projectName is the project Name.
     * @param string|int $eventId is the identification of the event. It can be the name of the
     * mailing list or the name of the mailing list.
     */
    public static function collectRssData(PersistentUser $user, $projectName) {
        $agent = self::makeAgentForUser($user);
        $rssToDbObserver = new RssToDatabaseObserver();
        $agent->collectRssDataFromProject($projectName, $rssToDbObserver);
    }
    /**
     * This method is the implementation of UC401
     *
     * @param PersistentUser $user is the user that will visit the pages
     * @param string $parentProjectName the name of the parent project, which contains a list of subprojects.
     * @return subprojects[] the list of all children project of a given parent project. If the project doesn't
     * have any subproject, the list has size 0.
     *         subproject[name] = the java.net project name
     *         subproject[title] = the title given in the main project page.
     */
    public static function collectChildrenProjects($user, $parentProjectName) {
        $agent = self::makeAgentForUser($user);
        return $agent->getSubprojectsFromProject($parentProjectName);
    }
    /**
     * Implementation of the UC402 - User authentication
     *
     * Authenticate the user on Java.net using the username and password.
     * @param string $username is the username of the user on Java.net
     * @param string $password is the password of the user on Java.net
     * @return PersonalAgent the agent representation. It can be used to retrieve information about the user's
     * profile such as email address, full-name, if it's successfully logged-in, etc.
     */
    public static function authenticateJavanetUser($username, $password) {
        $error = array();
        if (!isset($username) || $username == "") {
            $error["username"] = "The username is empty";
        }
        if (!isset($password) || $password == "") {
            $error["password"] = "The password is empty";
        }
        if (count($error) > 0) {
            throw new InfinityMetricsException("Personal Agent: There are errors in the input", $error);
        }
        return PersonalAgentController::makeAgentForUsernamePassoword($username, $password);
    }
    /**
     * Implementation of the UC403 to collect the events list
     *
     * @param PersistentUser is the user that will collect the data
     * @param $projectName is the project name to retrieve the list
     * @return rssEvents[] the list of all the events
     *        $rssEvents["mailingLists"]
     *        $rssEvents["forums"]
     */
    public static function collectProjectEventsList(PersistentUser $user, $projectName) {
        $agent = self::makeAgentForUser($user);
        if (!$this->agent->areUserCredentialsValidOnJN()) {
            throw new InfinityMetricsException("Can't collect Events list: credentials are invalid");
        }
        return $agent->getListOfRssChannels();
    }
    /**
     * This method implementes UC404 to retrieve the User's profile information from Java.net
     *
     * @param PersistentUser $user is the user in which the profile will be retrieved
     * @return $userProfile[username] = the realname maching the PersistentUser->getJnUsername()
     *         $userProfile[fullName] = the full name of the user. It might be empty since Java.net doesn't provide it
     *         $userProfile[email] = the authenticated email address from the user
     *         $userProfile[memberships] = list of the projects associated with a list of all their memberhips
     */
    public static function collectUserProfileInformation(PersistentUser $user) {
        $agent = self::makeAgentForUser($user);
        return $agent->getAuthenticatedUserProfile();
    }
}
?>
