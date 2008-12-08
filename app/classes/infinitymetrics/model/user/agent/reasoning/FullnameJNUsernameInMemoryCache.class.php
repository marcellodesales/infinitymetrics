<?php

final class FullnameJNUsernameInMemoryCache {
    /**
     * @var FullnameJNUsernameInMemoryCache is the single instance of this class
     */
    private static $singleton;
    /**
     *
     * @var array[fullName]=username of possible in-memory cache 
     */
    private $fullNamesUsernamesCache;
    /**
     * Contructs a new fullNameJnusernameInMemoryCache
     */
    private function  __construct() {
        $this->fullNamesUsernamesCache = array();
    }
    /**
     * @return Singleton the single instance of this class.
     */
    public static function getInstance() {
         if (self::$singleton == null) {
             self::$singleton = new FullnameJNUsernameInMemoryCache();
         }
         return self::$singleton;
    }
    /**
     * Associates a new username for a given fullName
     * @param string $username is a java.net username that has been found to match a given fullname
     * @param string $fullName is the fullname found to match the given fullname
     */
    public function addUsernameForFullNameToCache($username, $fullName) {
        $this->fullNamesUsernamesCache[$fullName] = $username;
    }
    public function isFullnameWithUsernameInCache($fullName) {
        return array_key_exists($fullName, $this->fullNamesUsernamesCache);
    }
    /**
     * @param string $fullName is the full name found in RSS feeds
     * @return string the possible match for a given full name. It might return null if the username
     * is not found.
     */
    public function getUsernameFromFullname($fullName) {
        if ($this->isFullnameWithUsernameInCache($fullName)) {
            return $this->fullNamesUsernamesCache[$fullName];
        } else {
            $possibleFullNameFromPersistence = $this->getPossibleUsernameForFullName($fullName);
            if (isset ($possibleFullNameFromPersistence) && $possibleFullNameFromPersistence != "") {
                $this->addUsernameForFullNameToCache($possibleFullNameFromPersistence, $fullName);
            }
            return $possibleFullNameFromPersistence;
        }
    }
    /**
     * @param string $fullName is the fullname found on an RSS feed
     * @return string the possible username for a given user
     */
    private function getPossibleUsernameForFullName($fullName) {
        require_once 'propel/Propel.php';
        Propel::init('infinitymetrics/orm/config/om-conf.php');
        require_once 'infinitymetrics/orm/om/PersistentBaseUserPeer.php';
        
        $con = Propel::getConnection(PersistentBaseUserPeer::DATABASE_NAME);
        $sql = "SELECT user.jn_username
                FROM channel
                  INNER JOIN event ON channel.channel_id = event.channel_id
                  INNER JOIN user  ON UPPER( event.jn_username ) LIKE UPPER( CONCAT( user.first_name, ' ', user.last_name ) )
                WHERE event.jn_username = '$fullName' Limit 1";
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $possibleUsername = null;
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
          $possibleUsername = $row[0];
        }
        return isset($possibleUsername) ? $possibleUsername : null;
    }
    /**
     * Udpdates the entries from Event table with the correct username for a given fullname
     * @param string $username is the registered username on the database
     * @param string $fullName is the fullname of the user found on RSS feeds
     */
    public function updatePersistentReporsitory($username, $fullName) {
        require_once 'infinitymetrics/orm/om/PersistentBaseUserPeer.php';
        $con = Propel::getConnection(PersistentBaseUserPeer::DATABASE_NAME);
        $sql = "update event set jn_username = '$username' WHERE jn_username='$fullName'";
        $stmt = $con->prepare($sql);
        $val = $stmt->execute();
    }
}
?>