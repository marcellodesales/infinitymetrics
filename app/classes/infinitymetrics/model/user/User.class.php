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

/**
 * Basic user class for the metrics workspace. User has username, password from 
 * Java.net.
 * 
 * @author Marcello de Sales <marcello.sales@gmail.com>
 * @version $Id$
 */
class User {

    /**
     * It's the first name of the user.
     * @var string the reference to the name
     */
    private $firstName;
    /**
     * It's the user's last name.
     * @var string the instance of th last name
     */
    private $lastName;
        /**
     * It's the name of the user.
     * @var RssItem instance
     */
    private $username;
    /**
     * Constructs a new user with a name {@link $rssItem}
     * @var firstName this is the first name.
     * @var firstName this is the first name.
     * @var firstName this is the first name.
     */
    public function  __construct($firstName, $lastName, $username) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->username = $username;
    }

    /**
     * Returns the value of the name
     */
    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setFirstName($newFirstName) {
        $this->firstName = $newFirstName;
    }

    public function setLastName($newLastName) {
        $this->lastName = $newLastName;
    }
}
?>
