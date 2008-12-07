<?php
/**
 * $Id: AllUserFunctionalTests.class.php 202 2008-11-10 21:31:40Z marcellosales $
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
require_once 'infinitymetrics/tests/functional/user/UC001Test.class.php';
require_once 'infinitymetrics/tests/functional/user/UC002Test.class.php';
require_once 'infinitymetrics/tests/functional/user/UC003Test.class.php';
require_once 'infinitymetrics/tests/functional/user/UC004Test.class.php';
require_once 'infinitymetrics/tests/functional/user/UC005Test.class.php';
require_once 'infinitymetrics/tests/functional/user/UC006Test.class.php';
require_once 'infinitymetrics/tests/functional/user/UC002aTest.class.php';
/**
 * 
 * All Functional Tests for the User Component.
 *
 * @author Marcello de Sales <marcello.sales@gmail.com>
 */
class AllUserComponentFunctionalTests {
    
    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit Framework');

        $suite->addTestSuite('UC001Test');
        $suite->addTestSuite('UC002Test');
        $suite->addTestSuite('UC003Test');
        $suite->addTestSuite('UC004Test');
        $suite->addTestSuite('UC005Test');
        $suite->addTestSuite('UC006Test');
        $suite->addTestSuite('UC002aTest');
        
        return $suite;
    }
}
?>