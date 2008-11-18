<?php
require_once 'infinitymetrics/model/institution/Institution.class.php';
require_once 'infinitymetrics/model/user/User.class.php';
/**
 * The Student class for the metrics workspace.
 *
 * @author Gurdeep Singh <gurdeepsingh03@gmail.com>
 * @author Marcello de Sales <marcello.sales@gmail.com>
 *
 * Student class with support to Persistence.
 */
class Student extends User {

    public function  __construct() {
        parent::__construct();
        $this->setType("S");
    }
}
?>
