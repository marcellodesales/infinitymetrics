<?php
/**
 * $Id: Report.class.php 008 2008-11-12 05:11:55Z aardila $
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

require_once('infinitymetrics/model/report/EventChannel.class.php');

/**
 * Defines the Model for a Report.
 * A Report is composed of a Project object and an array of EventChannel objects
 *
 * @author Andres Ardila
 * @version $Id$
 */

class Report
{
    /**
     * The Description of the EventChannel
     * @var <string>  $description
     */
    private $description;

    /**
     * The Name of the EventChannel
     * @var <string> $name
     */
    private $name;

    /**
     * The list of EventChannels contained in the Report
     * @var <array_of_EventChannels>
     */
    private $eventChannels;

    /**
     * Defautlt constructor
     * @return <Report>
     */
    public function __construct() {
        $this->eventChannels = array();
    }

    /**
     * Builds the state of the Report
     * @param <array_of_EventChannels> $eventChannels
     */
    public function builder($name, $description, array $eventChannels) {
        $this->name = $name;
        $this->description = $description;
        $this->eventChannels = $eventChannels;
    }

    /**
     * Gets the Description
     * @return <string>
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Gets the Name
     * @return <string>
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Gets the Report's current array of EventChannels
     * @return <array_of_EventChannels>
     */
    public function getEventChannels() {
        return $this->eventChannels;
    }

    /**
     * Searches through all EventChannels and returns an array of those matching
     * the given EventCategory
     * @param <EventCategory> $category
     * @return <array_of_EventChannels> that match $category
     */
    public function filterByCategory(EventCategory $category) {
        $filteredList = array();

        foreach ($this->eventChannels as $channel)
        {
            $cloneChannel = clone $channel;
            if ($cloneChannel->getCategory() == $category) {
                array_push($filteredList, $cloneChannel);
            }
        }

        return $filteredList;
    }

    /**
     * Searches through the Events in all EventChannles and returns an array of
     * EventChannels containing only Events within the range of Dates
     * @param <DateTime> $startDate
     * @param <DateTime> $endDate
     * @return <array_of_EventChannels> with Events within the date range specified
     */
    public function filterByDate(DateTime $startDate, DateTime $endDate) {
        $filteredChannelList = array();

        foreach ($this->eventChannels as $channel)
        {
            $cloneChannel = clone $channel;
            $cloneChannel->setEvents($cloneChannel->getEventsByDate($startDate, $endDate));
            $filteredChannelList[] = $cloneChannel;
        }

        return $filteredChannelList;
    }

    /**
     * Searches through the Events in all EventChannels and returns an array of
     * EventChannels containing only the Events that match the User
     * @param <User> $user
     * @return <array_of_EventChannels> with Events matching the given User
     */
    public function filterByUser(User $user) {
        $filteredChannelList = array();

        foreach ($this->eventChannels as $channel)
        {
            $cloneChannel = clone $channel;
            $cloneChannel->setEvents($cloneChannel->getEventsByUser($user));
            $filteredChannelList[] = $cloneChannel;
        }

        return $filteredChannelList;
    }

    /**
     * Sets the EventChannel's Description
     * @param <string> $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * Sets the Name
     * @param <string> $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Sets the Report's list of EventChannels to the argument
     * @param <array_of_EventChannels> $eventChannels 
     */
    public function setEventChannels(array $eventChannels) {
        $this->eventChannels = $eventChannels;
    }

    /**
     * Adds the EventChannel to the list of EventChannels in the Report
     * @param <EventChannel> $eventChannel 
     */
    public function addEventChannel(EventChannel $eventChannel) {
        $this->eventChannels[] = $eventChannel;
    }
}
?>
