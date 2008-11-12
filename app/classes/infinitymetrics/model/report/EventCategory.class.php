<?php
/**
 * $Id: EventCategory.class.php 008 2008-11-12 05:11:55Z aardila $
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
 * Enumeration of EventCategory
 *
 * @author Andres Ardila
 */

class EventCategory
{
    /**
     * The Category as a string
     * @var <string>
     */
    private $category;

    /**
     * Default constructor
     * @return <EventCategory>
     */
    public function __construct() {
    }

    /**
     * Sets the category to COMMIT
     */
    public function commit() {
        $this->category = "COMMIT";
    }

    /**
     * Sets the category to DOCUMENT
     */
    public function document() {
        $this->category = "DOCUMENT";
    }

    /**
     * Sets the category to FORUM
     */
    public function forum () {
        $this->category = "FORUM";
    }

    /**
     * Sets the category to ISSUE
     */
    public function issue() {
        $this->category = "ISSUE";
    }

    /**
     * Sets the category to MAILING_LIST
     */
    public function mailingList() {
        $this->category = "MAILING_LIST";
    }

    /**
     * Gets the EventCategory as a string
     * @return <string> 
     */
    public function getEventCategory() {
        return $this->category;
    }

    /**
     * Returns the EventCategory as a satring
     * @return <string> 
     */
    public function __toString() {
        return $this->category;
    }
}
?>
