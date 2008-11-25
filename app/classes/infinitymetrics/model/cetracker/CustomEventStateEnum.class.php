<?php

require_once 'infinitymetrics/util/Enum.class.php';

class CustomEventStateEnum extends Enum {

    /**
     * @var CustomEventStateEnum is the singleton instance of this enum 
     */
    private static $singleton;

    /**
     * Constructs a new CustomEventState with the following constants:
     * OPEN, RESOLVED
     */
    public function  __construct() {
        parent::__construct("OPEN", "RESOLVED");
    }

    /**
     * @return WorkspaceState the single instance of this enum.
     */
    public static function getInstance() {
         if (self::$singleton == null) {
             self::$singleton = new CustomEventStateEnum();
         }
         return self::$singleton;
    }
}
?>
