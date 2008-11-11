<?php

require_once('infinitymetrics/model/report/Report.class.php');

$startDate = new DateTime('2008-06-01');
$endDate = new DateTime('2008-11-30');

$EventChannels = array();
$controlList = array();

for ($i = 0; $i < 10; $i++)
{
    $eventChannel = new EventChannel();
    $controlEventChannel = new EventChannel();

    for ($j = 0; $j < 50; $j++)
    {
        $event = new Event();
        $mo = rand()%12 + 1;
        $day = rand()%28 + 1;
        $dateStr = '2008-'.$mo.'-'.$day;
        $date = new DateTime($dateStr);
        $event->setDate($date);
        $eventChannel->addEvent($event);
        if ($date >= $startDate && $date <= $endDate) {
            $controlEventChannel->addEvent($event);
        }
    }
    array_push($EventChannels, $eventChannel);
    array_push($controlList, $controlEventChannel);
}

?>
