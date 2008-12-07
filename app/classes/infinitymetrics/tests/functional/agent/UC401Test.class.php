<?php
/**
 * $Id: UC401Test.class.php 202 2008-11-10 12:01:40Z marcellosales $
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
 * Tests for the Use Case UC401 - U4001: Agent .
 *
 * @author Marcello de Sales <marcello.sales@gmail.com>
 */
class UC401Test extends PHPUnit_Framework_TestCase {

    const USERNAME_CORRECT = "marcellosales";
    const PASSWORD_CORRECT = "utn@9oad";

    private $user;
    private $project;

    /**
     * Setting up is run ALWAYS BEFORE the execution of a test method.
     */
    protected function setUp() {
        parent::setUp();
        PersistentProjectPeer::doDelete("ppm");
        $this->user = new PersistentUser();
        $this->user->setJnUsername(self::USERNAME_CORRECT);
        $this->user->setJnPassword(self::PASSWORD_CORRECT);

        $this->project = new PersistentProject();
        $this->project->setProjectJnName("ppm");
        $this->project->save();
    }
    
    public function testGetSubProjectsListWithParentProject() {
//       ALWAYS CALL THIS FIRST METHOD TO MAKE THE AUTHENTICATION.
        $subprojects = PersonalAgentController::collectChildrenProjects($this->user, $this->project->getProjectJnName());
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
        $subprojects = PersonalAgentController::collectChildrenProjects($this->user, "ppm-8");
        $this->assertNotNull($subprojects, "The list of subprojects for the ppm-8 project MUST exist, but empty");
        $this->assertTrue(count($subprojects) == 0, "PPM-8 project don't have subprojects");
    }

    protected function tearDown() {
        $this->user = null;
        $this->project->delete();
        $this->project = null;
    }
}
?>