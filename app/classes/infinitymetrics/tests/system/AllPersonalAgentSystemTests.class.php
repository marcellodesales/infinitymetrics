<?php
/**
 * $Id: AllUserSystemTests.class.php 202 2008-12-01 17:42:40Z marcellosales $
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
require_once 'infinitymetrics/tests/system/agent/PersonalAgentSystemTests.class.php';
require_once 'infinitymetrics/tests/system/agent/FullnameJNUsernameInMemoryCacheSystemTest.class.php';
/**
 * All System Tests for the Personal Agent component. It includes system tests for the Personal Agent itself and
 * for the his/her intelligence components.
 *
 * @author Marcello de Sales <marcello.sales@gmail.com>
 */
class AllPersonalAgentComponentSystemTests {
    
    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit Framework');

        $suite->addTestSuite('PersonalAgentSystemTests');
        $suite->addTestSuite('FullnameJNUsernameInMemoryCacheSystemTests');

        return $suite;
    }
}
?>