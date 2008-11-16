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

/**
 * Basic user class for the metrics workspace. User has username, password from 
 * Java.net.
 * 
 * @author Marcello de Sales <marcello.sales@gmail.com>
 * @version $Id$
 */
class User extends PersistentUser {

    public function  __construct() {
    }

    public static function compare($a, $b) {
        print "calling the compare method";
        if ($a->getJnUsername() < $b->getJnUsername()) return -1;
        else if($a->getJnUsername() == $b->getJnUsername()) return 0;
        else return 1;
    }
    public function equals($other) {
        if ($other instanceof User) {
            return $this->getJnUsername() == $other->getJnUsername();
        } else {
            return false;
        }
    }
}
?>
