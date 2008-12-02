<?php
/**
 * $Id: UC403Test.class.php 202 2008-11-10 12:01:40Z marcellosales $
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
require_once 'PHPUnit/Framework.php';
require_once 'infinitymetrics/controller/UserManagementController.class.php';
require_once 'infinitymetrics/orm/PersistentUserPeer.php';

/**
 * Tests for the Use Case UC403
 *
 * @author Marcello de Sales <marcello.sales@gmail.com>
 */
class UC403Test extends PHPUnit_Framework_TestCase {

    const USERNAME_CORRECT = "marcellosales";
    const PASSWORD_CORRECT = "utn@9oad";

    private $user;
    private $project;

    /**
     * Setting up is run ALWAYS BEFORE the execution of a test method.
     */
    protected function setUp() {
        parent::setUp();

        $this->user = new PersistentUser();
        $this->user->setJnUsername(self::USERNAME_CORRECT);
        $this->user->setJnPassword(self::PASSWORD_CORRECT);

        $this->project = new PersistentProject();
        $this->project->setProjectJnName("ppm");
    }
    /**
     * Test the collection of the RSS channels and the forum names and ids from an existing project.
     */
    public function testCollectEventsListForExistingProject() {
//       ALWAYS CALL THIS FIRST METHOD TO MAKE THE AUTHENTICATION.
        $channels = PersonalAgentController::collectProjectEventsList($this->user, $this->project->getProjectJnName());
        $this->assertNotNull($channels);
        $this->assertNotNull($channels["mailingLists"]);
        $this->assertNotNull($channels["forums"]);

        foreach($channels["mailingLists"] as $channelId => $values) {
            $this->assertNotEquals("", $channelId, "The id of the channel is null");
            foreach ($values as $meta) {
                $this->assertNotEquals("", $meta, "The description or value of the channel is null");
            }
        }

        foreach($channels["forums"] as $channelId => $values) {
            $this->assertNotEquals("", $channelId, "The id of the channel is null");
            foreach ($values as $meta) {
                $this->assertNotEquals("", $meta, "The description or value of the channel is null");
            }
        }
    }
    /**
     * The test an exceptional registration where the student enteres an
     * existing username.
     */
    public function testCollectEventsListForNonExistingProject() {
        $events = PersonalAgentController::collectProjectEventsList($this->user, "dsdsdsd");
        $this->assertNotNull($events, "The list of the events must exist, but empty");
        $this->assertArrayHasKey("mailingLists", $events);
        $this->assertArrayHasKey("forums", $events);
        $this->assertTrue(count($events["mailingLists"]) == 0, "There should be no mailing lists for a
                                                                                               non-existing project");
        $this->assertTrue(count($events["forums"]) == 0, "There should be no forums for a non-existing project");
    }

    protected function tearDown() {
        $this->user = null;
        $this->project = null;
    }
}
?>