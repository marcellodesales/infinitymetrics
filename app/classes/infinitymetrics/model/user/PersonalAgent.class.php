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

require_once 'propel/Propel.php';
Propel::init('infinitymetrics/orm/config/om-conf.php');

require_once 'infinitymetrics/model/user/User.class.php';
require_once 'infinitymetrics/util/screenscraperapi/JNUrlBuilder.class.php';
require_once 'infinitymetrics/util/screenscraperapi/useredit/JNUserEditImpl.class.php';
require_once 'infinitymetrics/util/screenscraperapi/projecthome/JNProjectHomeImpl.class.php';
require_once 'infinitymetrics/util/screenscraperapi/mailinglist/JNMailingListsImpl.class.php';
require_once 'infinitymetrics/util/screenscraperapi/rss/JNRssImpl.class.php';
require_once 'infinitymetrics/model/user/agent/reasoning/ProjectHistoryToDatabase.class.php';
require_once 'infinitymetrics/model/user/agent/reasoning/JNRssParserSubject.class.php';
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
    private $mailingListsWSImpl;
    private $projectHomeWSImpl;
    /**
     * Constructs a new PersonalAgent for a given user. The agent will be going
     * to Java.net and try to navigate as a user
     * @param User $user is the user that the Agent will represent online.
     */
    public function  __construct(PersistentUser $user) {
        $this->user = $user;
        $this->session = new JNSessionImpl($this->user->getJnUsername(), $this->user->getJnPassword());
        $this->userEditWSImpl = new JNUserEditImpl($this->session);
        $this->mailingListsWSImpl = new JNMailinListsImpl($this->session);
        $this->projectHomeWSImpl = new JNProjectHomeImpl($this->session);
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
     * @return if the user credentials are valid on Java.net. That is, if the user provided the correct
     * Username and password for Java.net and can login
     */
    public function areUserCredentialsValidOnJN() {
        return $this->userEditWSImpl->areUserCredentialsValid();
    }
    /**
     * @return $userProfile["username"] = just the copy of the username wrapped into the profile for verification
     *                                    purposes
     *         $userProfile["fullName"] = the full name of the user (STILL BROKEN)
     *         $userProfile["email"] = the email address from the user on Java.net
     *         $userProfile["memberships"] = the list of projects the user has one of more roles. For each projec, the
     *          list of roles is also given.
     */
    public function getAuthenticatedUserProfile() {
        $userProfile["username"] = $this->getUser()->getJnUsername();
        $userProfile["fullName"] = $this->getAuthenticatedFullName();
        $userProfile["email"] = $this->getAuthenticatedEmail();
        $userProfile["memberships"] = $this->getAuthenticatedProjectsMembershipList();
        return $userProfile;
    }

    /**
     * @return string the user's email
     */
    public function getAuthenticatedEmail() {
        return $this->userEditWSImpl->getUserEmail();
    }
    /**
     * TODO: this method is BROKEN.java.net doesn't show the full name of the user,
     * as the website Tigris.org does.
     * @return String return the full name of the user
     */
    public function getAuthenticatedFullName() {
        return $this->userEditWSImpl->getUserFullName();
    }
    /**
     * Gets the memberships of the user
     * @return project[name] = {role1, role2, role3, ...} 
     */
    public function getAuthenticatedProjectsMembershipList() {
        return $this->userEditWSImpl->getUserProjectsMembershipList();
    }
    /**
     * @return mailinglists[] returns the list of all RSS channels, including private ones, for a given project.
     * mailinglist[id][description] = the description as defined on the website
     * mailinglist[id][totalMessages] = may be included if the session included calls to the number of messages.
     *
     * For discussion services, they will include the total number of messages, while for all the mailing lists, they
     * will NOT be set, thus isset(mailinglist[id][totalMessages]) == false. For this reason, the client must
     * get the total number of all different RSs channels in separate calls to
     * mailingListsWSImpl->getTotalNumberOfEmails(mailinglist[id], $projectName)
     */
    public function getListOfRssChannels($projectName) {
        return $this->mailingListsWSImpl->getCompleteListOfChannels($projectName);
    }
    /**
     * Collects all the RssData for a given project. That means:
     * 1. Get the list of all Rss Channels based on the call of getListOfRssChannels($projectName).
     * 2. Add the entire list (after filtering with some preferences);
     *
     * @param string $projectName is the java.net project name
     * @param Observer $observer an observer with regards to the Database feed of data.
     */
    public function collectRssDataFromProject($projectName, Observer $observer) {
        $events = $this->getListOfRssChannels($projectName);
//        echo "\nCollecting for project ". $projectName . " all the following events";
        print_r($events);
        if (count($events["mailingLists"]) > 0) {
            foreach ($events["mailingLists"] as $channelId => $properties) {
//                echo "\n=>channel Id " . $channelId . "/" . $projectName;
                $this->collectDataFromProjectMailingListHistory($projectName, $channelId, $properties["description"]);
            }
        }
        if (count($events["forums"]) > 0) {
            foreach ($events["forums"] as $channelId => $properties) {
//                echo "\n=>channel Id " . $channelId . "/" . $projectName;
                $this->collectRssDataFromProjectForum($projectName, $channelId, $observer);
            }
        }
    }
    /**
     * Collects the RSS Data feed from the given MAILING LIST for a given observer to process.
     * @param string $projectName is the java.net project name.
     * @param string $mailingList is the mailing list name id used in the URLs of the RSS feeds. (dev, users, issues)
     * @param Observer $observer is an instance of an observer interested in the Rss data.
     */
    private function collectRssDataFromProjectMailingList($projectName, $mailingList, Observer $observer) {
        $rssWS = new JNRssImpl($this->session);
        $url = JNUrlBuilder::makeProjectRssUrlForMailingLists($projectName, $mailingList);
        $jRssParser = new JNRssParserSubject($url, $rssWS->getRssContentsForMailingList($projectName, $mailingList));
        $jRssParser->addObserver($observer);
        $jRssParser->parseRss();
    }

    /**
     * Collects the RSS Data feed from the given MAILING LIST for a given observer to process.
     * @param string $projectName is the java.net project name.
     * @param string $mailingList is the mailing list name id used in the URLs of the RSS feeds. (dev, users, issues)
     * @param Observer $observer is an instance of an observer interested in the Rss data.
     */
    private function collectDataFromProjectMailingListHistory($projectName, $mailingList, $mailingListDesc) {
        $historyWS = new JNMailinListsImpl($this->session);
        $events = $historyWS->getCompleteMailingListHistory($mailingList, $projectName);
        if (count($events)) {
            new ProjectHistoryToDatabase($projectName, $mailingList, $mailingListDesc, $events);
        }
    }
    /**
     * Collects the RSS Data feed from the given Discussion Forum for a given observer to process.
     * @param string $projectName is the java.net project name.
     * @param int $forumId is the forum id used in the URLs of the RSS feeds. 2342, etc.
     * @param Observer $observer is an instance of an observer interested in the Rss data.
     */
    private function collectRssDataFromProjectForum($projectName, $forumId, Observer $observer) {
        $rssWS = new JNRssImpl($this->session);
        $url = JNUrlBuilder::makeProjectRssUrlForForum($projectName, $forumId);
        $jRssParser = new JNRssParserSubject($url, $rssWS->getRssContentsForForum($projectName, $forumId));
        $jRssParser->addObserver($observer);
        $jRssParser->parseRss();
    }
    /**
     * @param string $projectName is the name of the projecr
     * @return projectNames[] of all subprojects for the given projectName
     */
    public function getSubprojectsFromProject($projectName) {
        return $this->projectHomeWSImpl->getSubprojectsList($projectName);
    }
    /**
     * @param string $projectName is the name of the project
     * @return string the summary of a given Java.net project
     */
    public function getProjectSummary($projectName) {
        return $this->projectHomeWSImpl->getSummary($projectName);
    }
    /**
     * @param string $projectName is the name of the project
     * @return owners[] is the list of all project owners of a project
     */
    public function getProjectOwnersList($projectName) {
        return $this->projectHomeWSImpl->getOwnersList($projectName);
    }
}
//$u = new User();
//$u->setJnUsername("marcellosales");
//$u->setJnPassword("utn@9oad");
//$ag = new PersonalAgent($u);
//require_once 'infinitymetrics/model/user/agent/reasoning/RssToDatabaseObserver.class.php';
//$ag->collectRssDataFromProject("ppm-8", new RssToDatabaseObserver());
?>