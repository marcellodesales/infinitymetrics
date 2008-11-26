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
 *  - UC400 Retrieve All RSS Feeds
 *  - UC401
 *  - UC402
 *  - UC403
 *  - UC404 Collects user's profile
 *
 * @author: Marcello de Sales <marcello.sales@gmail.com>
 */
final class PersonalAgentController {
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
        $agent = PersonalAgentController::makeAgentForUserCredentials($username, $password);
        return $agent;
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
        return self::makeAgentForUser($user);
    }

    private static function makeAgentForUser(User $user) {
        $agent = new PersonalAgent($user);
        return $agent;
    }

    /**
     * Implements the method to collect the UC400 for the collection of RSS data.
     * @param User $user is the user which needs to log into the Java.net project
     * @param string $projectName is the project Name.
     * @param string|int $eventId is the identification of the event. It can be the name of the
     * mailing list or the name of the mailing list.
     */
    public static function collectRssData(User $user, $projectName, $eventId) {
        $agent = self::makeAgentForUser($user);
        $rssToDbObserver = new RssToDatabaseObserver();
        $intValue = (int)$eventId;
        if ((int)$eventId) {
            $agent->collectRssDataFromProjectForum($projectName, $eventId, $rssToDbObserver);
        } else {
            $agent->collectRssDataFromProjectMailingList($projectName, $eventId, $rssToDbObserver);
        }
    }

    public static function collectChildrenProjects() {

    }
}
//
//$user = new User();
//$user->setJnUsername("marcellosales");
//$user->setJnPassword("utn@9oad");
//$projectName = "ppm-8";
//$mailingList = "dev";
//
//$mailingList = "3306";
//
//PersonalAgentController::collectRssData($user, $projectName, $mailingList);


?>
