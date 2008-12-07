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

require_once('infinitymetrics/controller/MetricsWorkspaceController.class.php');

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
     * The Description of the Report
     * @var <string>  $description
     */
    private $description;

    /**
     * The Name of the Report
     * @var <string> $name
     */
    private $name;

    /**
     * The metrics in the Report
     * @var <array>
     */
    private $metrics;

    /**
     * A list of valid Event Categories
     * @var <array>
     */
    private $validEventCategories = array(  
                                            'COMMIT',
                                            #'DOCUMENTATION',
                                            'FORUM',
                                            'ISSUE',
                                            'MAILING_LIST'
                                    );

    private $validExtendedCategories;

    /**
     * Defautlt constructor
     * @return <Report>
     */
    public function __construct() {
        $this->metrics = array();
        $this->validExtendedCategories = $this->validEventCategories;
        $this->validExtendedCategories[] = 'CUSTOM_EVENT';
    }

    /**
     * Builds the state of the Report
     * @param <string> $name
     * @param <string> $description
     */
    public function builder($name, $description) {
        $this->name = $name;
        $this->description = $description;
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
     * Returns the array of ValidEventCategories
     * @return <array>
     */
    public function getEventCategories() {
        return $this->validEventCategories;
    }

    /**
     * Returns the array of ValidExtendedCategories
     * @return <array>
     */
    public function getExtendedCategories() {
        return $this->validExtendedCategories;
    }

    public function getMetricsTotalsByCategory() {
        $categories = array();
        $total = array();

        foreach ($this->metrics as $key => $value)
        {
            $categories = array_keys($value);
            break;
        }

        foreach ($categories as $category)
        {
            $total[$category] = 0;
        }

        foreach ($this->metrics as $key => $cats)
        {
            foreach ($cats as $cat => $value)
            {
                foreach ($total as $category => $val)
                {
                    if ($cat == $category) {
                        $total[$category] += $value;
                    }
                }
            }
        }
        
        return $total;
    }

    public function getMetricsTotalsByKey() {
        $total = array();

        foreach ($this->metrics as $key => $value)
        {
            $total[$key] = array_sum($value);
        }
        
        return $total;

    }

    public function getWorkspaceCollectionMetrics($user_id) {
        if (!isset($user_id) || $user_id == '') {
            throw new InfinityMetricsException('The user_id is required to generate this report');
        }

        $wsCollection = MetricsWorkspaceController::retrieveWorkspaceCollection($user_id);
        
        $this->metrics = array();

        foreach ($wsCollection as $type => $workspaces)
        {
            foreach ($workspaces as $ws)
            {
                $projectTotals[$ws->getTitle()] = array();
                
                foreach($ws->getProjects() as $project)
                {
                    $projectTotals[$ws->getTitle()][$project->getProjectJnName()] = $project->getTotalEventsByCategory();
                }

                $this->metrics[$ws->getTitle()] = array();

                foreach (self::getExtendedCategories() as $category)
                {
                    $this->metrics[$ws->getTitle()][$category] = 0;
                    
                    foreach ($projectTotals[$ws->getTitle()] as $pName => $pCategories)
                    {
                        foreach ($pCategories as $categoryKey => $value)
                        {
                            if ($categoryKey == $category) {
                                $this->metrics[$ws->getTitle()][$category] += $value;
                            }
                        }
                    }
                }
            }
        }

        $this->metrics = self::metrics_natsort_keys($this->metrics);
 
        return $this->metrics;
    }

    public function getReportMetrics($PersistentObject, $AuxiliaryPersistentObject = NULL) {
        if (!isset($PersistentObject) || $PersistentObject == '') {
            throw new InfinityMetricsException('This method needs an PersistentObject as an argument');
        }

        if ($PersistentObject instanceof PersistentUser)
        {
            if (!isset($AuxiliaryPersistentObject)) {
                throw new InfinityMetricsException('The Auxiliary parameter was not given');
            }
            if (!($AuxiliaryPersistentObject instanceof PersistentProject)) {
                throw new InfinityMetricsException('The Auxiliary parameter must be of type PersistentProject');
            }
            $this->metrics = array();
            $userJnName = $PersistentObject->getJnUsername();
            $project = clone $AuxiliaryPersistentObject;
            $criteria = new Criteria();

            foreach ($this->validEventCategories as $category)
            {
                $this->metrics[$category] = 0;
                $criteria->clear();
                $criteria->add(PersistentChannelPeer::CATEGORY, $category);
                $criteria->addAnd(PersistentChannelPeer::PROJECT_JN_NAME, $project->getProjectJnName());
                $channels = PersistentChannelPeer::doSelect($criteria);

                foreach ($channels as $channel)
                {
                    $criteria->clear();
                    $criteria->add(PersistentEventPeer::JN_USERNAME, $userJnName);

                    $this->metrics[$category] += count($channel->getEvents($criteria));
                }
            }

            return $this->metrics;
        }//end User Report

        elseif ($PersistentObject instanceof PersistentProject)
        {
            $this->metrics = array();
            $criteria = new Criteria();

            $project = clone $PersistentObject;
            
            $channels = $project->getChannels();

            foreach ($channels as $channel) {
                foreach ($channel->getEvents() as $event){
                    if (!array_key_exists($event->getJnUsername(), $this->metrics)) {
                        $this->metrics[$event->getJnUsername()] = array();
                    }
                }
            }
            
            foreach ($this->metrics as $jnUsername => $value)
            {
                foreach ($this->validEventCategories as $category)
                {
                    $this->metrics[$jnUsername][$category] = 0;

                    $criteria->clear();
                    $criteria->add(PersistentProjectPeer::PROJECT_JN_NAME, $project->getProjectJnName());
                    $criteria->add(PersistentChannelPeer::CATEGORY, $category);
                    $channels = PersistentChannelPeer::doSelect($criteria);

                    foreach ($channels as $channel)
                    {
                        $criteria->clear();
                        $criteria->add(PersistentEventPeer::JN_USERNAME, $jnUsername);
                        $criteria->add(PersistentEventPeer::CHANNEL_ID, $channel->getChannelId());
                        $criteria->add(PersistentEventPeer::PROJECT_JN_NAME, $channel->getProjectJnName());

                        $this->metrics[$jnUsername][$category] += PersistentEventPeer::doCount($criteria);
                    }
                }
            }

            $this->metrics = self::metrics_natsort_keys($this->metrics);
            
            return $this->metrics;
        }//end Project Report

        elseif ($PersistentObject instanceof PersistentWorkspace)
        {
            $this->metrics = array();
            $projects = $PersistentObject->getProjects();

            foreach ($projects as $project) {
                $this->metrics[$project->getProjectJnName()] = $project->getTotalEventsByCategory();
            }

            $this->metrics = self::metrics_natsort_keys($this->metrics);
            
            return $this->metrics;
        }//end Workspace Report

        else {
            throw new Exception ('Cannot generate a report for that argument');
        }
    }

    private function metrics_natsort_keys(array $metrics) {
        $sorted = array();
        $keys = array_keys($metrics);
        natsort($keys);

        foreach ($keys as $key)
        {
            $sorted[$key] = array();
        }

        foreach ($metrics as $key => $value)
        {
            $sorted[$key] = $value;
        }

        return $sorted;
    }
}
?>
