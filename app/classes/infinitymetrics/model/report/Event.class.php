<?php
/**
 * $Id: Event.class.php 008 2008-11-12 05:11:55Z aardila $
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

require_once('infinitymetrics/model/user/User.class.php');

/**
 * Defines the Model for an Event.
 * An Event is defined as each item on a java.net project RSS feed
 *
 * @author Andres Ardila
 */

class Event
{
    /**
     * The Date on which the Event occurred.
     * @var <DateTime>
     */
    private $date;

    /**
     * The User that originated the Event
     * @var <User>
     */
    private $user;

    /**
     * Default constructor
     * @return <Event>
     */
    public function __construct() {
        $this->date = new DateTime();
    }

    /**
     * Builds the state of the Event
     * @param <User> $user
     * @param <DateTime> $date 
     */
    public function builder(User $user, DateTime $date) {
        $this->user;
        $this->date->setDate($date->format('Y'), $date->format('m'), $date->format('d'));
    }

    /**
     * Gets the DateTime object for the Event
     * @return <DateTime> 
     */
    public function getDateObject() {
        return $this->date;
    }
    
    /**
     * Gets the string corresponding to the date in the format 'Y-m-d'
     * @return <string> 
     */
    public function getDateString() {
        return $this->date->format('Y-m-d');
    }

    /**
     * Gets the User associated with the Event
     * @return <User> 
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Sets the Event's Date to the argument
     * @param <DateTime> $date 
     */
    public function setDate(DateTime $date) {
        $this->date->setDate($date->format('Y'), $date->format('m'), $date->format('d'));
    }

    /**
     * Sets the Event's User to the argument
     * @param <User> $user 
     */
    public function setUser(User $user) {
        $this->user = $user;
    }
}
?>
