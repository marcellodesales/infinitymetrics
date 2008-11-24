<?php
/**
 * $Id: CustomEvent.class.php 008 2008-11-12 05:11:55Z PST fisher_brett $
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

require_once('infinitymetrics/model/customtracker/CustomEventState.class.php');
require_once 'infinitymetrics/orm/PersistentCustomEvent.php';

/**
 * Defines the model for a custom event.
 * A custom event is defined as an event entered in directly by the instructor.
 *
 * @author Brett Fisher <fghtikty@gmail.com>
 */

class CustomEvent extends PersistentCustomEvent {

    /**
     * Constructs [CustomEvent] with [title] as a parameter.
     */
    public function __construct($newTitle) {
        /**
         *The state is automatically set as the default database to (Open)
         */
        $this->setTitle($newTitle);
        $this->setDate(new DateTime("now"));
    }

    public function resolve() {
        $this->setState("R");
    }

    public function reopen() {
        $this->setState("O");
    }
    
    /**
     * Compares 2 instances of the CustomEvent class by comparing the Title.
     * @param CustomEvent $other is the other custom event to be compared.
     * @return boolean if the given custom event "other" is the same as the
     *   current instance.
     */
    public function equals($other) {
        if ($other instanceof CustomEvent) {
            return $this->getTitle() == $other->getTitle();
        } else {
            return false;
        }
    }
}
?>
