<?php
/**
 * Description of EventCategoryclass
 *
 * @author Andres Ardila
 */

class EventCategory {

    private $category;

    public function __construct() {
    }

    public function commit() {
        $this->category = "COMMIT";
    }

    public function document() {
        $this->category = "DOCUMENT";
    }

    public function forum () {
        $this->category = "FORUM";
    }

    public function issue() {
        $this->category = "ISSUE";
    }

    public function mailingList() {
        $this->category = "MAILING_LIST";
    }

    public function __toString() {
        return $this->category;
    }
}
?>
