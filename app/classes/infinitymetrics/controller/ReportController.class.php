<?php

require_once('propel/Propel.php');

Propel::init("infinitymetrics/orm/config/om-conf.php");

require_once 'infinitymetrics/model/report/Report.class.php';

/**
 * Description of ReportController
 *
 * @author Andres Ardila
 */
class ReportController
{
    private function convertToTitleCase($str) {
        $str = strtolower($str);
        $str = substr_replace($str, strtoupper(substr($str, 0, 1)), 0, 1);
        $str = str_replace('_', ' ', $str);
        $spacePos = strpos($str, ' ');
        if ($spacePos !== false) {
            $str = substr_replace($str, strtoupper(substr($str,  $spacePos + 1, 1)), $spacePos + 1, 1 );
        }

        return $str;
    }
    
    public function retrieveProjectReport($project_jn_name) {

        //incomplete

        if (!isset($projectJnName) || $projectJnName == '') {
            throw new InfinityMetricsException('The projectJnName must be provided');
        }
        
        $project = PersistentProjectPeer::retrieveByPK($projectJnName);

        if ($project == NULL) {
            throw new InfinityMetricsException('The project was not found');
        }
        
        $eventChannels = $project->getChannels();
        
        $script =

        "   <script type=\"text/javascript\" src=\"http://www.google.com/jsapi\"></script>
            <script type=\"text/javascript\">\n
              google.load('visualization', '1', {packages:['columnchart']});

              google.setOnLoadCallback(drawChart);

              function drawChart() {
                var barData = new google.visualization.DataTable();
                barData.addColumn('string', 'Event Category');
                barData.addColumn('number', 'Number of Entries');
                barData.addRows(".count($eventChannels).");";

                for ($i = 0; $i < count($eventChannels); $i++) {
                    $script .= "barData.addColumn('string', '')";
                    $script .= "barData.setValue($i, 0, '".$eventChannels[$i]->getCategory()."');\n";
                    $script .= "barData.setValue($i, 1, ".count($eventChannels[$i]->getEvents()).");\n";
                }
                $script .= "
                var barChart = new google.visualization.ColumnChart(document.getElementById('bar_chart_div'));
                barChart.draw(barData, {width: 500, height: 340, is3D: true, title: 'Project Metrics By User and Category', 'legend': 'none'});
              }
            </script>
        ";

        return $script;
        

    }
    
    public function retrieveUserReport($user_id, $projectJnName) {
        $errors = array();

        if (!isset($user_id) || $user_id == '') {
            $errors['user_id'] = 'The user_id must be provided';
        }
        if (!isset($projectJnName) || $projectJnName == '') {
            $errors['user_id'] = 'The projectJnName must be provided';
        }

        if (count($errors)) {
            throw new InfinityMetricsException('Cannot generate Project Report', $errors);
        }

        $user = PersistentUserPeer::retrieveByPK($user_id);

        if ($user == NULL) {
            throw new InfinityMetricsException('The user does not exist in the database');
        }

        $project = PersistentProjectPeer::retrieveByPK($projectJnName);

        if ($project == NULL) {
            throw new InfinityMetricsException('The project was not found');
        }

        $report = new Report();
        $metrics = $report->getReportMetrics($user, $project);
        
        $script =

        "   <script type=\"text/javascript\" src=\"http://www.google.com/jsapi\"></script>
            <script type=\"text/javascript\">\n
              google.load('visualization', '1', {packages:['columnchart']});

              google.setOnLoadCallback(drawChart);

              function drawChart() {
                var barData = new google.visualization.DataTable();
                barData.addColumn('string', 'Event Category');
                barData.addColumn('number', 'Number of Entries');
                barData.addRows(".count($metrics).");";
                $idx = 0;
                foreach ($metrics as $key => $value) {
                    $script .= "barData.setValue($i, 0, '".$key."');\n";
                    $script .= "barData.setValue($i, 1, ".$value.");\n";
                    $idx++;
                }
                $script .= "
                var barChart = new google.visualization.ColumnChart(document.getElementById('bar_chart_div'));
                barChart.draw(barData, {width: 500, height: 340, is3D: true, title: 'Metrics For ".$user->getJnUsername()." in ".$project->getProjectJnName()."', 'legend': 'none'});
              }
            </script>
        ";

        return $script;
    }
    
    public function retrieveTopProjects($workspace_id, $num) {
        
    }
    
    public function retrieveWorkspaceReport($workspace_id) {
        if (!isset($workspace_id) || $workspace_id == '') {
            throw new InfinityMetricsException('The workspace_id must be provided');
        }
        
        $ws = PersistentWorkspacePeer::retrieveByPK($workspace_id);
        
        if ($ws == NULL) {
            throw new InfinityMetricsException('The workspace was not found');
        }
        
        $report = new Report();
        $metrics = $report->getReportMetrics($ws);

        $categories = $report->getExtendedCategories();
        
        $script =

        "   <script type=\"text/javascript\" src=\"http://www.google.com/jsapi\"></script>
            <script type=\"text/javascript\">
              google.load('visualization', '1', {packages:['columnchart']});

              google.setOnLoadCallback(drawChart);

              function drawChart() {
                var barData = new google.visualization.DataTable();
                barData.addColumn('string', 'Event Category');\n";

                foreach ($metrics as $pName => $cats){
                    $script .= "barData.addColumn('number', '".$pName."');\n";
                }
                
                $script .= "barData.addRows(".count($categories).");\n";

                if (count($metrics))
                {
                    for ($i = 0; $i < count($categories); $i++)
                    {

                        $script .= "barData.setValue($i, 0, '".self::convertToTitleCase($categories[$i])."');\n";

                        $idx = 1;
                        foreach ($metrics as $key => $value) {
                            $script .= "barData.setValue($i, ".$idx.", ".$value[$categories[$i]].");\n";
                            $idx++;
                        }
                    }
                }
                
                $script .= "
                var barChart = new google.visualization.ColumnChart(document.getElementById('bar_chart_div'));
                barChart.draw(barData, {width: 420, height: 320, is3D: true, title: 'Workspace Metrics', 'isStacked': true, 'legend': 'bottom'});
              }
            </script>";

        return $script;
    }

    public function retrieveWorkspaceCollectionReport($user_id) {
        if (!isset($user_id) || $user_id == '') {
            throw new InfinityMetricsException('The user_id is required to generate this report');
        }

        $report = new Report();
        $metrics = $report->getWorkspaceCollectionMetrics($user_id);

        $categories = $report->getExtendedCategories();

        $script =   "<script type=\"text/javascript\" src=\"http://www.google.com/jsapi\"></script>
                     <script type=\"text/javascript\">
                     google.load('visualization', '1', {packages:['columnchart']});

                     google.setOnLoadCallback(drawChart);

                     function drawChart() {
                        var barData = new google.visualization.DataTable();
                        barData.addColumn('string', 'Event Category');";

        foreach ($metrics as $wsName => $cats){
                    $script .= "barData.addColumn('number', '".$wsName."');\n";
        }

        $script .= "barData.addRows(".count($categories).");\n";

        if (count($metrics))
        {
            for ($i = 0; $i < count($categories); $i++)
            {

                $script .= "barData.setValue($i, 0, '".self::convertToTitleCase($categories[$i])."');\n";

                $idx = 1;
                foreach ($metrics as $key => $value) {
                    $script .= "barData.setValue($i, ".$idx.", ".$value[$categories[$i]].");\n";
                    $idx++;
                }
            }
        }

        $script .= "
                var barChart = new google.visualization.ColumnChart(document.getElementById('bar_chart_div'));
                barChart.draw(barData, {width: 420, height: 320, is3D: true, title: 'Workspace Collection Metrics', 'isStacked': true, 'legend': 'bottom'});
              }
            </script>";

        return $script;
    }
}
?>
