<?php

final class DateTimeUtil {

    /**
     * Converts the human-readable into a date object
     * @param string $humanReadableDateTime in the format Tue, 25 Nov 2008 08:00:00 GMT. The conversion
     * will take place using the current LOCALE, so for this date it would be 2008-11-25 00:00:00
     * @return date for the format specified.
     */
    public static function getPublicationDateHumanReadable($humanReadableDateTime) {
        return date("D, j M Y G:i:s T", strtotime($humanReadableDateTime));
    }

    /**
     * Converts the ISO Date into a date object
     * @param string $truncateDateTime in the form of 2008-11-25T08:00:00Z
     * @return <type>
     */
    public static function getMySQLDateAndTime($truncateDateTime) {
        return date("Y-m-d G:i:s", strtotime($truncateDateTime));
    }

    /**
     * Converts the ISO Date into a date object
     * @param string $truncateDateTime in the form of 2008-11-25T08:00:00Z
     * @return <type>
     */
    public static function getMySQLDate($truncateDateTime) {
        return date("Y-m-d", strtotime($truncateDateTime));
    }
}

        $a = "Thu, 20 Nov 2008 07:42:35 GMT";
        $b = DateTimeUtil::getMySQLDate($a);
        $a = "2008-11-25T19:00:00Z";
        $b = DateTimeUtil::getMySQLDate($a);
?>
