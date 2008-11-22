<?php

require_once 'infinitymetrics/orm/PersistentInstitution.php';
/**
 * Description of Institution
 *
 * @author Andres Ardila
 * @author Marcello de Sales
 */
class Institution extends PersistentInstitution {

    /**
     * Compares 2 instances of the User class by comparing the Java.net username.
     * @param Institution $other is the other user to be compared
     * @return boolean if the given user "other" is the same as the current instance.
     */
    public function equals($other) {
        if ($other instanceof Institution) {
            return $this->abbreviation == $other->getAbbreviation();
        } else false;
    }
    /**
     * Compare method is called whenever the user instance is being sorted
     * in a list. All classes that participate on sorting should implement
     * this method.
     * @param Institution $a is the first User instance
     * @param Institution $b is the second User instance
     * @return int the comparison value for the user.
     */
    public static function compare($a, $b) {
        if ($a->getAbbreviation() < $b->getAbbreviation()) return -1;
        else if($a->getAbbreviation() == $b->getAbbreviation()) return 0;
        else return 1;
    }
}
?>