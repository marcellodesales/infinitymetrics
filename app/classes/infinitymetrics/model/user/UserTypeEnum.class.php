<?php
/**
 * $Id: UserTypeEnum.class.php 202 2008-11-26 12:14:40Z marcellosales $
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
require_once 'infinitymetrics/util/Enum.class.php';
/**
 * UserTypeEnum is the enumaration on the persistent types of the User.
 */
class UserTypeEnum extends Enum {

    /**
     * @var UserTypeEnum is the singleton instance of this enum
     */
    private static $singleton;

    /**
     * Constructs a new CustomEventState with the following constants:
     * STUDENT, INSTRUCTOR, JAVANET
     */
    public function  __construct() {
        parent::__construct("STUDENT", "INSTRUCTOR", "JAVANET");
    }

    /**
     * @return UserTypeEnum the single instance of this enum.
     */
    public static function getInstance() {
         if (self::$singleton == null) {
             self::$singleton = new UserTypeEnum();
         }
         return self::$singleton;
    }
}
?>
