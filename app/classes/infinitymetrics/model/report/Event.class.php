<?php

require_once('infinitymetrics/model/user/User.class.php');

/**
 * Description of Eventclass
 *
 * @author Andres Ardila
 */

class Event
{
    private $date;
    private $user;

    public function __construct() {
        $this->date = new DateTime();
    }

    public function builder(User $user, DateTime $date) {
        $this->user;
        $this->date;
    }

    public function getDateObject() {
        return $this->date;
    }
    
    public function getDateString() {
        return $this->date->format('Y-m-d');
    }

    public function getUser() {
        return $this->user;
    }

    public function setDate(DateTime $date) {
        $this->date = $date;
    }

    public function setUser(User $user) {
        $this->user = $user;
    }
}
?>
