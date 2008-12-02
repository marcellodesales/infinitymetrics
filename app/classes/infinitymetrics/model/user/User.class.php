<?php
/**
 * $Id: User.class.php 202 2008-11-02 21:31:40Z marcellosales $
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

require_once 'infinitymetrics/orm/PersistentUser.php';
require_once 'infinitymetrics/model/user/UserTypeEnum.class.php';
/**
 * Basic user class for the metrics workspace. User has username, password from 
 * Java.net.
 * 
 * @author Marcello de Sales <marcello.sales@gmail.com>
 * @version $Id$
 */
class User extends PersistentUser {
    /**
     * @var UserTypeEnum is the enumaration with the types of user
     */
    private $typeEnum;
    /**
     * Constructs a new User with the type as Java.Net user ("JAVANET")
     */
    public function  __construct() {
        $this->typeEnum = UserTypeEnum::getInstance();
        $this->setType($this->typeEnum->JAVANET);
    }
    /**
     * Compare method is called whenever the user instance is being sorted
     * in a list. All classes that participate on sorting should implement
     * this method.
     * @param User $a is the first User instance
     * @param User $b is the second User instance
     * @return int the comparison value for the user.
     */
    public static function compare($a, $b) {
        if ($a->getJnUsername() < $b->getJnUsername()) return -1;
        else if($a->getJnUsername() == $b->getJnUsername()) return 0;
        else return 1;
    }
    /**
     * Compares 2 instances of the User class by comparing the Java.net username.
     * @param User $other is the other user to be compared
     * @return boolean if the given user "other" is the same as the current instance.
     */
    public function equals($other) {
        if ($other instanceof User) {
            return $this->getJnUsername() == $other->getJnUsername();
        } else {
            return false;
        }
    }


}
?>
