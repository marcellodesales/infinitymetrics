<?php

require_once('infinitymetrics/model/report/Report.class.php');
$report = new Report();

$startDate = new DateTime('2008-06-01');
$endDate = new DateTime('2008-11-30');

$report->setEventChannels(array());
$controlList = array();

for ($i = 0; $i < 10; $i++)
{
    $eventChannel = new EventChannel();
    $controlEventChannel = new EventChannel();

    for ($j = 0; $j < 20; $j++)
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
    $report->addEventChannel($eventChannel);
    array_push($controlList, $controlEventChannel);
}

$filteredChannelList = $report->filterByDate($startDate, $endDate);


$temp = $report->getEventChannels();

if ( count($filteredChannelList) == count($report->getEventChannels()) )
{
    echo "Count of event channels matches<br />";
    
    for ($k = 0; $k < count($filteredChannelList); $k++)
    {
        if (count($filteredChannelList[$k]) == count($temp[$k]))
        {
            echo "Number of events is equal in EventChannel[".$k."]<br />";
        }
    }
}

//$result = array_diff($filteredChannelList, $controlList);
//echo $result;

//if ($filteredList == $controlList) {
//echo "Equal";
//}


//echo "done";
?>
