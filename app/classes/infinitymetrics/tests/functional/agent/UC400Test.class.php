<?php
/**
 * $Id: UC400Test.class.php 202 2008-11-10 12:01:40Z marcellosales $
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
require_once 'infinitymetrics/util/EncodingUtil.class.php';
/**
 * Tests for the Use Case UC400.
 *
 * @author Marcello de Sales <marcello.sales@gmail.com>
 */
class UC400Test extends PHPUnit_Framework_TestCase {
    
    const USERNAME_CORRECT = "marcellosales";
    const PASSWORD_CORRECT = "utn@9oad";

    private $user;
    private $project;

    public function cleanUpAll() {
        PersistentProjectPeer::doDeleteAll();
        PersistentEventPeer::doDeleteAll();
        PersistentChannelPeer::doDeleteAll();
    }

    /**
     * Setting up is run ALWAYS BEFORE the execution of a test method.
     */
    protected function setUp() {
        parent::setUp();
        $this->cleanUpAll();
        
        $this->user = new PersistentUser();
        $this->user->setJnUsername(self::USERNAME_CORRECT);
        $this->user->setJnPassword(self::PASSWORD_CORRECT);

        $this->project = new PersistentProject();
        $this->project->setProjectJnName("ppm");
        $this->project->save();
        //ask the agent to collect the subprojects names from the parent project's java.net page
        $subProjectsNames = PersonalAgentController::collectChildrenProjects($this->user,
                                                                             $this->project->getProjectJnName());
        foreach ($subProjectsNames as $subPro) {
            $subproject = new PersistentProject();
            $subproject->setProjectJnName($subPro["name"]);
            $subproject->setParentProjectJnName($this->project->getProjectJnName());
            $subproject->setSummary(EncodingUtil::makeUnicodeCharSetFromUFT8($subPro["title"]));
            $subproject->save();
        }
    }
    /**
     * The test of a successful registration when a student doesn't exist and
     * enters the correct values.
     */
    public function testCollectRssChannelsExistingProject() {
        try {
            PersonalAgentController::collectRssData($this->user, $this->project->getProjectJnName());
            $c = new Criteria();
            $c->add(PersistentProjectPeer::PARENT_PROJECT_JN_NAME, $this->project->getProjectJnName());
            $subprojects = PersistentProjectPeer::doSelect($c);
            $this->assertNotNull($subprojects, "The parent project " .$this->project->getProjectJnName() ." must have".
                                                                                  " all its subprojects registered");
            foreach($subprojects as $subPr) {

                $c->clear();
                $c->add(PersistentChannelPeer::PROJECT_JN_NAME, $subPr->getProjectJnName());
                $this->assertTrue(PersistentChannelPeer::doCount($c) > 0, "There must be channels registered under ".
                                                               "the subproject project " . $subPr->getProjectJnName());
                $c->clear();
                $c->add(PersistentEventPeer::PROJECT_JN_NAME, $subPr->getProjectJnName());
                $this->assertTrue(PersistentEventPeer::doCount($c) > 0, "There must be events registered under the "
                                                                         . "subproject " . $subPr->getProjectJnName());
            }
            
        } catch (InfinityMetricsException $ime){
            $this->fail("The successful user's creditial authentication scenario failed: " . $ime);
        }
    }
    /**
     * The test of an exceptional registration where the student doesn't enter
     * some of the field values.
     */
//    public function testCollectRssFeedsForUnknownProject() {
//        try {
//            PersonalAgentController::collectRssData($user, $this->project->getProjectJnName());
//            $this->fail("The exception must be thrown when the project is invalid");
//
//        } catch (Exception $ime){
//            $this->assertNotNull($ime, "The exception is thrown when the agent can't collect RSS for a non-existing user");
//        }
//    }
    /**
     * The test an exceptional registration where the student enteres an
     * existing username.
     */
//    public function testRecollectionOfRssFeedForPreviouslyUsedProject() {
//        try {
//            PersonalAgentController::collectRssData($user, $this->project->getProjectJnName());
//
//        } catch (InfinityMetricsException $ime){
//            $this->assertNotNull($ime, "The exception is thrown when the user has passed the wrong credentials");
//        }
//        try {
//            $agent = PersonalAgentController::authenticateJavanetUser("", self::PASSWORD_CORRECT);
//            $this->fail("The missing java.net username should not create a new agent: " . $ime);
//
//        } catch (InfinityMetricsException $ime){
//            $this->assertNotNull($ime);
//        }
//        try {
//            $agent = PersonalAgentController::authenticateJavanetUser(self::USERNAME_CORRECT, "");
//            $this->fail("The missing password should not let the framework create a agent: " . $ime);
//
//        } catch (InfinityMetricsException $ime){
//            $this->assertNotNull($ime);
//        }
//    }

    protected function tearDown() {
        $this->user = null;
//        $this->cleanUpAll();
    }
}
?>