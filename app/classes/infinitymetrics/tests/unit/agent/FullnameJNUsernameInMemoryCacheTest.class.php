<?php
/**
 * $Id: FullnameJNUsernameInMemoryCacheTest.class.php 202 2008-11-26 03:31:40Z marcellosales $
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

require_once 'PHPUnit/Framework.php';
require_once 'infinitymetrics/model/user/agent/reasoning/FullnameJNUsernameInMemoryCache.class.php';
require_once 'infinitymetrics/model/institution/Student.class.php';
require_once 'infinitymetrics/orm/PersistentUserPeer.php';
require_once 'infinitymetrics/orm/PersistentInstitutionPeer.php';
require_once 'infinitymetrics/orm/PersistentChannelPeer.php';
require_once 'infinitymetrics/orm/PersistentProjectPeer.php';
require_once 'infinitymetrics/orm/PersistentUserXInstitution.php';
/**
 * Tests for the Personal Agent class.
 *
 * @author Marcello de Sales <marcello.sales@gmail.com>
 */
class FullnameJNUsernameInMemoryCacheTest extends PHPUnit_Framework_TestCase {

    const EXISTING_USERNAME = "marcellosales";
    const EXISTING_FULL_NAME = "Marcello de Sales";
    const EXISTING_FIRST_NAME = "Marcello";
    const EXISTING_LAST_NAME = "de Sales";
    const NON_EXISTING_FULL_NAME = "Someone Else";
    const PROJECT_NAME = "ppm-8";
    const CHANNEL_ID = "users";

    private $user;

    private function deleteEverything() {
        //echo "Deleting the user and institution for setting up";
        $crit = new Criteria();
        $crit->add(PersistentUserPeer::JN_USERNAME, self::EXISTING_USERNAME);
        PersistentUserPeer::doDelete($crit);

        $crit->add(PersistentProjectPeer::PROJECT_JN_NAME, self::PROJECT_NAME);
        PersistentProjectPeer::doDelete($crit);

        $crit->add(PersistentChannelPeer::CHANNEL_ID, self::CHANNEL_ID);
        PersistentChannelPeer::doDelete($crit);
    }

    protected function setUp() {
        parent::setUp();
        $project = PersistentProjectPeer::retrieveByPK(self::PROJECT_NAME);
        if (!isset($project)) {
            //creates a new project in case it doesn't exist... It should never happen
            //but to make sure we have data on the server.
            $project = new PersistentProject();
            $project->setProjectJnName(self::PROJECT_NAME);
            $project->setSummary("Agent created this project for the RSS feeds...");
            $project->save();
        }
        $this->user = new User();
        $this->user->setFirstName(self::EXISTING_FIRST_NAME);
        $this->user->setLastName(self::EXISTING_LAST_NAME);
        $this->user->setEmail("marcello.sales@gmail.com");
        $this->user->setJnUsername(self::EXISTING_USERNAME);
        $this->user->setJnPassword("password");
        $this->user->save();

        $channel = PersistentChannelPeer::retrieveByPK(self::CHANNEL_ID, $project->getProjectJnName());
        if (!isset($channel)) {
            $channel = new PersistentChannel();
            $channel->setChannelId(self::CHANNEL_ID);
            $channel->setProjectJnName($project->getProjectJnName());
            $channel->setCategory("MAILING_LIST");
            $channel->setTitle("This is the users mailing list");
            $channel->save();
        }
        $entries = array();
        for ($i = 0; $i < 10; $i++) {
            $item = new PersistentEvent();
            $item->setEventId($i);
            $item->setJnUsername(self::EXISTING_FULL_NAME);
            $item->setChannel($channel);
            $item->setDate("Tue, 0$i Nov 2008 0$i:00:00 GMT");
        }
        $channel->save();
    }
    /**
     * Verify if the personal agent finds the user for a given fullname.
     */
    public function testFindingExistingUsernameForFullname() {
        $c = new Criteria();
        $c->add(PersistentEventPeer::JN_USERNAME, self::EXISTING_FULL_NAME);
        $events = PersistentEventPeer::doSelect($c);

        $this->assertNotNull($events. "The events for a given full name were not created on the persistence layer");
        $this->assertEquals(10, count($events), "The number of events saved is different than the ones created in memory");
        foreach($events as $event) {
            //the existing full name must have been created on the events. They will potentially come from RSS feeds
            $this->assertEquals(self::EXISTING_FULL_NAME, $event->getJnUsername(), "The full name is not the same");
        }
        $cache = FullnameJNUsernameInMemoryCache::getInstance();
        $marcelloUN = $cache->getUsernameFromFullname(self::EXISTING_FULL_NAME);

        $this->assertNotNull($marcelloUN, "The username should be retrieved by the full name");
        $this->assertEquals($marcelloUN, self::EXISTING_USERNAME, "The username should be the same because the full name
                                                                   matches the username");
        $this->assertTrue($cache->isFullnameWithUsernameInCache(self::EXISTING_FULL_NAME), "The fullname MUSt be kept in
                                                                                          cache all the execution time");
    }
    /**
     * Verify that the agent can't find a user matching the fullname given
     */
    public function testNotFindingExistingUsernameForFullname() {
        $cache = FullnameJNUsernameInMemoryCache::getInstance();
        $marcelloUN = $cache->getUsernameFromFullname(self::NON_EXISTING_FULL_NAME);
        
        $this->assertFalse(isset($marcelloUN), "The username was retrieve from the users... It's totally incorrect.");
        $this->assertFalse($cache->isFullnameWithUsernameInCache(self::NON_EXISTING_FULL_NAME), "There's MUST be no
                                                                     username related to a non-existing full name in
                                                                     the cache.");
    }
    
    protected function tearDown() {
        $this->deleteEverything();
    }
}
?>
