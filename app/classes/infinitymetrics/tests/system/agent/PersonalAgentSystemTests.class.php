<?php
/**
 * $Id: StudentSystemTest.class.php 202 2008-12-01 16:43:40Z marcellosales $
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
include_once 'infinitymetrics/model/user/PersonalAgent.class.php';
include_once 'infinitymetrics/model/institution/Student.class.php';

/**
 * System Tests for the Agent and the ScreenScraper API.
 *
 * @author Marcello de Sales <marcello.sales@gmail.com>
 */
class PersonalAgentSystemTests extends PHPUnit_Framework_TestCase {
    /**
     * @var Student|Instructor|User is the instance of a java.net User.
     */
    private $user;
    /**
     * @var PersonalAgent is the instance of the PersonalAgent that will represent the User.
     */
    private $agent;

    const USERNAME = "marcellosales";
    const PASSWORD = "utn@9oad";

    protected function setUp() {
        parent::setUp();
        $this->user = new Student();
        $this->user->setFirstName("Marcello");
        $this->user->setLastName("de Sales");
        $this->user->setEmail("marcello.sales@gmail.com");
        $this->user->setJnUsername(self::USERNAME);
        $this->user->setJnPassword(self::PASSWORD);

        $this->agent = new PersonalAgent($this->user);
    }

    public function testPersonalAgentSuccessulLogin() {
//       ALWAYS CALL THIS FIRST METHOD TO MAKE THE AUTHENTICATION.
        $this->assertTrue($this->agent->areUserCredentialsValidOnJN());
    }

    public function testPersonalAgentFailureLogin() {

    }

    /**
     * The test to verify the email address, fullname and list of projects the user are in the profile
     * (
    [glassfish] => Array
        (
            [0] => Observer
        )
    [jax-ws] => Array
        (
            [0] => Observer
        )
    [ppm] => Array
        (
            [0] => Content Developer
            [1] => Developer
        )
    [ppm-8] => Array
        (
            [0] => Developer
            [1] => Project Owner
        )
    [sv-web-jug] => Array
        (
            [0] => Observer
        )
    )
     */
    public function testPersonalAgentRetrieveUserProfile() {
        $userProfile = $this->agent->getAuthenticatedUserProfile();

        $this->assertNotNull($userProfile, "The user profile should've been created for the existing user");
        $this->assertArrayHasKey("username", $userProfile, "The user's profile doesn't contain the username key");
        $this->assertArrayHasKey("fullName", $userProfile, "The user's profile doesn't contain the fullName key");
        $this->assertArrayHasKey("email", $userProfile, "The user's profile doesn't contain the email key");
        $this->assertArrayHasKey("memberships", $userProfile, "The user's profile doesn't contain the memberships key");

        $this->assertEquals($this->agent->getJnUsername(), $userProfile["username"], "The value of the java.net username
               retrieved by the Agent is incorrect.");
        $this->assertEquals("", $userProfile["fullName"], "The fullname of the user should be enpty since Java.net
                         doesn't provide the user's full name");
        $this->assertEquals($this->user->getEmail(), $userProfile["email"], "The value of the email retrieved by the
                          Agent is incorrect.");
        $this->assertTrue(count($userProfile["memberships"]) > 0, "The membership must contain at least one element.");

        $this->assertEquals($this->user->getEmail(), $this->agent->getAuthenticatedEmail(), "The value of the email
               retrieved by the Agent is incorrect.");
        $this->assertEquals(null, $this->agent->getAuthenticatedFullName(), "The value of the fullname retrieved by
               the agent is incorrect.");
        $this->assertTrue(count(array_keys($this->agent->getAuthenticatedProjectsMembershipList())) > 1);

        foreach($this->agent->getAuthenticatedProjectsMembershipList() as $projectName => $rolesArray) {
            $this->assertNotEquals("", $projectName, "The name of the membership is null");
            foreach ($rolesArray as $role) {
                $this->assertNotEquals("", $role, "The name of the role is null");
            }
        }
    }

    public function testGetRssChannelListForProject() {;
//       ALWAYS CALL THIS FIRST METHOD TO MAKE THE AUTHENTICATION.
        $this->assertTrue($this->agent->areUserCredentialsValidOnJN());
        $channels = $this->agent->getListOfRssChannels("ppm-8");
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

    public function testGetSubProjectsListWithParentProject() {
//       ALWAYS CALL THIS FIRST METHOD TO MAKE THE AUTHENTICATION.
        $this->assertTrue($this->agent->areUserCredentialsValidOnJN());
        $subprojects = $this->agent->getSubprojectsFromProject("ppm");
        $this->assertNotNull($subprojects, "The list of subprojects for the ppm project MUST be non-empty");
        $this->assertTrue(count($subprojects) > 10, "PPM project has more than 10 projects");
        foreach ($subprojects as $project) {
            $this->assertNotEquals("", $project, "The name of the subproject MUST not be eympt");
            $this->assertArrayHasKey("title", $project, "The subproject doesn't contain the title");
            $this->assertArrayHasKey("name", $project, "The subproject doesn't contain the java.net name");
        }
    }

    public function testGetSubprojectsListWithChildProject() {
//       ALWAYS CALL THIS FIRST METHOD TO MAKE THE AUTHENTICATION.
        $this->assertTrue($this->agent->areUserCredentialsValidOnJN());
        $subprojects = $this->agent->getSubprojectsFromProject("ppm-8");
        $this->assertNotNull($subprojects, "The list of subprojects for the ppm-8 project MUST exist, but empty");
        $this->assertTrue(count($subprojects) == 0, "PPM-8 project don't have subprojects");
    }

    public function testGetProjectOwnersList() {
//       ALWAYS CALL THIS FIRST METHOD TO MAKE THE AUTHENTICATION.
        $this->assertTrue($this->agent->areUserCredentialsValidOnJN());
        $owners = $this->agent->getProjectOwnersList("ppm");
        $this->assertNotNull($owners, "The list of owners for the ppm project MUST be non-empty");
        foreach ($owners as $ownerName) {
            $this->assertNotEquals("", $ownerName, "The name of the owner MUST not be eympt");
        }
        try {
            $owners = $this->agent->getProjectOwnersList("349i934i934");
            $this->assertNull($owners, "There MUST BE no owners  list for a non-existing project");
        } catch (Exception $e) {
            $this->assertNotNull($e);
        }
    }

    protected function tearDown() {
        $this->agent = null;
        $this->user = null;
    }
}
?>
