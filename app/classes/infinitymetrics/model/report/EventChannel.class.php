<?php
/**
 * $Id: Event.class.php 008 2008-11-12 05:11:55Z aardila $
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

require_once('infinitymetrics/model/workspace/Project.class.php');
require_once('infinitymetrics/model/report/Event.class.php');
require_once('infinitymetrics/model/report/EventCategory.class.php');

/**
 * Defines the Model for an EventChannel
 *
 * @author Andres Ardila
 */

class EventChannel
{
    /**
     * A collection of Events
     * @var <array_of_Events> $events
     */
    private $events;

    /**
     * The EventCategory for the EventChannel
     * @var <EventCategory> $category
     */
    private $category;
    
    /**
     * Default constructor
     * @return <EventChannel>
     */
    public function __construct() {
        $this->events = array();
    }

    /**
     * Builds the state of the EventChanel
     * @param <string> $description
     * @param <string> $name
     * @param <array_of_Events> $events
     * @param <EventCategory> $category 
     */
    public function builder(array $events, EventCategory $category) {
        $this->events = $events;
        $this->category = $category;
    }

    /**
     * Gets the EventCategory
     * @return <EventCategory> 
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * Gets the collection of Events
     * @return <array_of_Events> 
     */
    public function getEvents() {
        return $this->events;
    }

    /**
     * Searches the collection of events and returns an array of Events within
     * the date range provided
     * @param <type> $startDate
     * @param <type> $endDate
     * @return <array_of_Events> 
     */
    public function getEventsByDate(DateTime $startDate, DateTime $endDate) {
        $filteredEventList = array();
        
        foreach ($this->events as $event)
        {
            if ($event->getDateObject() >= $startDate &&
                $event->getDateObject() <= $endDate)
            {
                array_push($filteredEventList, $event);
            }
        }
        
        return $filteredEventList;
    }

    /**
     * Searches the collection of Events and returns and array of Events matching
     * for the given User
     * @param <User> $user
     * @return <array_of_Events> 
     */
    public function getEventsByUser(User $user) {
        $filteredEventList = array();

        foreach ($this->events as $event)
        {
            if ($event->getUser() == $user) {
                array_push($filteredEventList, $event);
            }
        }

        return $filteredEventList;
    }

    /**
     * Sets the EventChannel's category
     * @param <EventCategory> $category 
     */
    public function setCategory(EventCategory $category) {
        $this->category = $category;
    }

    /**
     * Sets the collection of Events
     * @param <array_of_Events> $events 
     */
    public function setEvents(array $events) {
        $this->events = $events;
    }

    /**
     * Adds the Event to the collection
     * @param <Event> $event 
     */
    public function addEvent(Event $event) {
        $this->events[] = $event;
    }

    /**
     * Determines if an EvenChannel does not contain any Events
     * @return <bool> 
     */
    public function hasNoEvents() {
        if ( count($this->events )) {
            return false;
        }
        else {
            return true;
        }
    }
}
?>
