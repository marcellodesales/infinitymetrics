<?php
/**
 * $Id: CustomEventController.class.php 202 2008-11-10 12:01:40Z PST Brett
 * Fisher $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITYs, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the Berkeley Software Distribution (BSD).
 * For more information please see <http://ppm-8.dev.java.net>.
 */

require_once 'propel/Propel.php';
Propel::init('infinitymetrics/orm/config/om-conf.php');

require_once 'infinitymetrics/model/InfinityMetricsException.class.php';
require_once 'infinitymetrics/model/workspace/Project.class.php';
require_once 'infinitymetrics/model/cetracker/CustomEvent.class.php';
require_once 'infinitymetrics/model/cetracker/CustomEventEntry.class.php';
require_once 'infinitymetrics/model/cetracker/CustomEventStateEnum.class.php';

/*
 * @author Brett Fisher <fghtikty@gmail.com>
 */
final class CustomEventController {
    /**
     * Authenticate the input of a note or title.
     * @param string $notesOrTitle is the note or title for an event or entry.
     * @return boolean based on success of authentication.
     */
    public static function validateInputEntry($notes, $eventId) {
        $error = array();
        if (!isset($notes) || $notes == "") {
            $error["notes"] = "The notes are empty.\n";
        }
        if (!isset($eventId) || $eventId == "") {
            $error["event_id"] = "The id number is empty.\n";
        }
        if (count($error) > 0) {
            throw new InfinityMetricsException(
                "There are errors in the input.\n", $error);
            return false;
        }
        return true;
    }
    /**
     * This method implements the addition of Entry to a Custom Event UC202.
     *
     * @param string $notes the notes of the entry.
     * @param smallint $event_id the $event_id of the event to add the entry to.
     */
    public static function createEntry($notes, $event_id) {
        try {
            CustomEventController::validateInputEntry($notes, $event_id);
            $entry = new CustomEventEntry($notes);

            $criteria = new Criteria(PersistentCustomEventPeer::CUSTOM_EVENT_ID,
                $event_id);
            $results = PersistentCustomEventPeer::doSelect($criteria);

            // The below loop will only loop once.
            foreach($results as $events) {
                $events->addCustomEventEntry($entry);
                echo $events->getCustomEventId();
                $entry->setCustomEventId($events->getCustomEventId());
                $events->save();
            }
            return $entry;
        } catch (Exception $e) {
            throw $e;
        }
    }
    /**
     * Authenticate the input of a note or title.
     * @param string $notesOrTitle is the note or title for an event or entry.
     * @return boolean based on success of authentication.
     */
    public static function validateInputEvent($notes, $title, $projectJnName) {
        $error = array();
        if (!isset($notes) || $notes == "") {
            $error["notes"] = "The notes are empty.\n";
        }
        if (!isset($title) || $title == "") {
            $error["title"] = "The title is empty.\n";
        }
        if (!isset($projectJnName) || $projectJnName == "") {
            $error["project_jn_name"] = "The project jn name is empty.\n";
        }
        if (count($error) > 0) {
            throw new InfinityMetricsException(
                "There are errors in the input.\n", $error);
            return false;
        }
        return true;
    }
    /**
     * This method implements the addition of a Custom Event to a Project UC200.
     *
     * @param string $notes the notes of the entry.
     * @param string $title the title of the event to add the entry to.
     * @param string $project_jn_name the name of the project to add the event
     *     to.
     */
    public static function createEvent($notes, $title, $project_jn_name) {
        try {
            CustomEventController::validateInputEvent($notes, $title,
                $project_jn_name);

            $criteria = new Criteria(PersistentProjectPeer::PROJECT_JN_NAME,
                $project_jn_name);
            $results = PersistentProjectPeer::doSelect($criteria);

            // The below loop will only loop once.
            foreach($results as $projects) {
                $event = new CustomEvent($title);
                $entry = new CustomEventEntry($notes);

                $event->setProjectJnName($project_jn_name);
                $entry->setCustomEventId($event->getCustomEventId());

                $event->addCustomEventEntry($entry);
                $projects->addCustomEvent($event);

                $projects->save();
            }
            return $event;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
?>