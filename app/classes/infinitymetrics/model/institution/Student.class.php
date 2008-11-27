<?php
/**
 * $Id: Student.class.php 202 2008-10-02 03:31:40Z marcellosales $
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
require_once 'infinitymetrics/model/institution/Institution.class.php';
require_once 'infinitymetrics/model/user/User.class.php';
require_once 'infinitymetrics/model/user/UserTypeEnum.class.php';
/**
 * The Student class for the metrics workspace.
 *
 * @author Gurdeep Singh <gurdeepsingh03@gmail.com>
 * @author Marcello de Sales <marcello.sales@gmail.com>
 *
 * Student class with support to Persistence.
 *
 * Call to the method isStudent(), inherited from User, must return true
 */
class Student extends User {
    /**
     * Constructs a new Student with type "STUDENT"
     */
    public function  __construct() {
        parent::__construct();
        $type = UserTypeEnum::getInstance();
        $this->setType($type->STUDENT);
    }
}
?>
