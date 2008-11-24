<?php
/**
 * $Id: CustomEventEntry.class.php 008 2008-11-12 05:11:55Z PST fisher_brett $
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

require_once('infinitymetrics/orm/PersistentCustomEventEntry.php');

/**
 * Defines the model for a customEventEntry.
 * A customEventEntry is defined as new additional text added to a custom event.
 *
 * @author Brett Fisher <fghtikty@gmail.com>
 */
class CustomEventEntry extends PersistentCustomEventEntry {

    /**
     * Constructs [CustomEventEntry] with [notes] as a parameter.
     */
    public function __construct($newNotes) {
        $this->setNotes($newNotes);
        $this->setDate(new DateTime("now"));
    }

    /**
     * Compares 2 instances of the CustomEventEntry class by comparing the
     *   Notes.
     * @param CustomEventEntry $other is the other custom event entry to be
     *   compared.
     * @return boolean if the given custom event entry "other" is the same as
     *   the current instance.
     */
    public function equals($other) {
        if ($other instanceof CustomEventEntry) {
            return $this->getNotes() == $other->getNotes();
        } else {
            return false;
        }
    }
}
?>