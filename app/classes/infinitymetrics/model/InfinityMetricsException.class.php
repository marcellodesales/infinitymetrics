<?php

class InfinityMetricsException extends Exception {

    private $errorList;
    const INFINITY_METRICS_ERROR_CODE = 34354;

    public function  __construct($message, $errorList) {
        parent::__construct($message, self::INFINITY_METRICS_ERROR_CODE);
        $this->errorList = $errorList;
    }

    /*
     * Returns the Error List that generated this exception.
     * It should be a hash map of:
     * key: error attribute
     * value: error message
     * $error[key] = value;
     * E.G. $error["username"] = "Usersname is incorrect";
     */
    public function getErrorList() {
        return $this->errorList;
    }
}
?>
