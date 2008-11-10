<?php

 /*============================*\
| | Description of CustomEvent | |
| |                            | |
| | @author Brett Fisher       | |
 \*============================*/

class CustomEvent
{
    private $enumCE = array("Open" => 1,"Resolved" => 2);
    private $entries;
    private $cEState;

    public function __construct()
    {
        $this->entries = array();
    }

    public function builder(array $newEntries)
    {
        $this->entries = $newEntries;
        $this->cEState = $enumCE["Open"];
    }

    public function getEntries()
    {
        return $this->entries;
    }

    public function getState()
    {
        return $this->cEState;
    }

    public function setState($newState)
    {
        $this->cEState = $newState;
    }
}

?>
