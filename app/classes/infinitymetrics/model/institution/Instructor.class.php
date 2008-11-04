<?php
require_once 'infinitymetrics/model/user/User.class.php';
require_once 'Institution.class.php';
/**
 * The instructorinfinitymetrics class for the metrics workspace.
 * @author Gurdeep Singh <gurdeepsingh03@gmail.com>
 * @author Marcello de Sales <marcello.sales@gmail.com>
 */
class Instructor extends User {

    /**
     * This is the institution reference.
     * @var Institution instance of the institution class.
     */
    private $institution;

    public function  __construct($firstName) {
        parent::__construct($firstName);
    }

    public function setInstitution(Institution $institution) {
        $this->institution = $institution;
    }
}
   
?>
