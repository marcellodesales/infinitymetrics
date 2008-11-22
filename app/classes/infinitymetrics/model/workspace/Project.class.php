<?php

require_once('infinitymetrics/orm/PersistentProject.php');

/**
 * Description of Project
 *
 * @author Andres Ardila
 */

class Project extends PersistentProject
{
    public function __construct() {
        parent::__construct();
    }

    public function builder($jn_name, $summary) {
        $this->setProjectJnName($jn_name);
        $this->setSummary($summary);
    }
}
?>
