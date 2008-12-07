<?php
/**
 * $Id: UC002aTest.class.php 202 2008-11-10 12:01:40Z Gurdeep Singh $
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
require_once 'infinitymetrics/controller/UserManagementController.class.php';

/**
 * Tests for new institution registered by Instructor while registering himself in the system.
 * @author Gurdeep singh <gurdeepsingh03@gmail.com>
 */
class UC002aTest extends PHPUnit_Framework_TestCase {

    const INSTITUTION_NAME = "SAN FRANCISCO STATE UNIVERSITY";
    const INSTITUTION_ABBV = "SFSU";
    const CITY = "SAN FRANCISCO";
    const STATE = "CALIFORNIA";
    const COUNTRY = "USA";
    

    private $instructor;
    private $student;
    private $project;

    public function  __construct() {
        $this->userTypeEnum = UserTypeEnum::getInstance();
    }

    private function cleanUpAll() {
       PersistentInstitutionPeer::doDeleteAll();
       PersistentUserPeer::doDeleteAll();
       PersistentUserXInstitutionPeer::doDeleteAll();
       PersistentProjectPeer::doDeleteAll();
       PersistentUserXProjectPeer::doDeleteAll();
        
    }

    /**
     * Setting up is run ALWAYS BEFORE the execution of a test method.
     */
    protected function setUp() {
        parent::setUp();
        $this->cleanUpAll();

        $this->project = new PersistentProject();
        $this->project->setProjectJnName("PPM");
        $this->project->setSummary("Project paticipation Metrics");
        $this->project->save();

     }
    /**
     * The test of a successful registration when a institution doesn't exist and
     * its correct values are entered.
     * also tested wheteher it is then related to user in UserXInstitution Table.
     */
    public function testSuccessfulInstitutionRegistration() {
        try {
            //Saving the Institution
            $createdInstitution = UserManagementController::registerInstitution(self::INSTITUTION_NAME,self::INSTITUTION_ABBV,
                                                          self::CITY,self::STATE,self::COUNTRY);

            $this->assertNotNull($createdInstitution, "The created institution is not null");
            $this->instructor = UserManagementController::registerInstructor("gurdeep","1234","g@gmail.com","Gurdeep",
                                                             "Singh",$this->project->getProjectJnName(),true,self::INSTITUTION_ABBV,"Teacher101");
           
            $instructorInstitution = PersistentUserXInstitutionPeer::retrieveByPk($this->instructor->getUserId(),
                                                                              $createdInstitution->getInstitutionId());
            $this->assertNotNull($instructorInstitution, "The user x institution relation was not created for
                                                                                                      the institution");

             $this->student = UserManagementController::registerStudent("marcello","1234", "email2gmail.com","Marcello",
                                                           "Sales","25435646",$this->project->getProjectJnName(),self::INSTITUTION_ABBV,true);

            $studentInstitution = PersistentUserXInstitutionPeer::retrieveByPk($this->student->getUserId(),
                                                                              $createdInstitution->getInstitutionId());

              $this->assertNotNull($studentInstitution, "The user x institution relation was not created for
                                                                                                      this institution");



        } catch (InfinityMetricsException $ime){
            $this->fail("The successful institution registration failed: " . $ime);
        }
    }
    /**
     * The test of an exceptional registration where the instructor doesn't enter
     * some of the field values.
     */
    public function testMissingFieldsInstitutionRegistration() {
        try {
            $createdInstitution = UserManagementController::registerInstitution("","","",self::STATE,self::COUNTRY);
            $this->fail("The exceptional institution registration failed with missing fields");

        } catch (InfinityMetricsException $ime) {
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["institutionName"]);
            $this->assertNotNull($errorFields["abbreviation"]);
            $this->assertNotNull($errorFields["city"]);
        }

        try {
            $missingCityandOthers = UserManagementController::registerInstitution(self::INSTITUTION_NAME,self::INSTITUTION_ABBV,
                                                                "","","");

            $this->fail("The exceptional institution registration failed with missing city, state ad country");

        } catch (InfinityMetricsException $ime) {
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["city"]);
            $this->assertNotNull($errorFields["stateProvince"]);
            $this->assertNotNull($errorFields["country"]);
        }

        try {
            $createdInstitution = UserManagementController::registerInstitution(self::INSTITUTION_NAME,"",
                                                               "",self::STATE,self::COUNTRY);

            $this->fail("The exceptional institution registration scenario failed for
                    missing abbereviation, and other fields");
        } catch (InfinityMetricsException $ime) {
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["abbreviation"]);
            $this->assertNotNull($errorFields["city"]);
        }
    }
    /**
     * The test an exceptional registration where the instructor tyr to register a alrady
     * registered institution.
     */
    public function testRegisterExistingInstitution() {
        try {
            //registering the institution
            $createdInstitution = UserManagementController::registerInstitution(self::INSTITUTION_NAME,self::INSTITUTION_ABBV,
                                                           self::CITY,self::STATE,self::COUNTRY);

            //registering the institution once again. This time it throws an exception
            $createdInstitution = UserManagementController::registerInstitution(self::INSTITUTION_NAME,self::INSTITUTION_ABBV,
                                                           self::CITY,self::STATE,self::COUNTRY);

            $this->fail("The exceptional registration failed for existing instructor");
        } catch (InfinityMetricsException $ime) {
            $errorFields = $ime->getErrorList();
            $this->assertNotNull($errorFields);
            $this->assertNotNull($errorFields["institutionAlreadyRegistered"]);
       }
    }

    protected function tearDown() {
        $this->cleanUpAll();
    }
}
?>



