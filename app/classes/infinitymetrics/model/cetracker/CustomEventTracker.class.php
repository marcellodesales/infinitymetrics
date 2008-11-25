<?php

 /*===================================*\
| | Description of CustomEventTracker | |
| |                                   | |
| | @author Brett Fisher              | |
 \*===================================*/

class CustomEventTracker
{
    private $customEvents;

    public function __construct()
    {
        $this->customEvents = array();
    }

    public function builder(array $newCustomEvents)
    {
        $this->customEvents = $newCustomEvents;
    }

    public function getCEList()
    {
        return $this->customEvents;
    }
}

?>
