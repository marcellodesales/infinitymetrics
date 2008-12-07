<?php

require_once('propel/Propel.php');

Propel::init("infinitymetrics/orm/config/om-conf.php");

require_once ('infinitymetrics/model/report/Report.class.php');
require_once ('infinitymetrics/model/InfinityMetricsException.class.php');

/**
 * Description of ReportController
 *
 * @author Andres Ardila
 */
class ReportController
{
    private function toTitleCase($str) {
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

        if (!isset($project_jn_name) || $project_jn_name == '') {
            throw new InfinityMetricsException('The projectJnName must be provided');
        }
        
        $project = PersistentProjectPeer::retrieveByPK($project_jn_name);

        if ($project == null) {
            throw new InfinityMetricsException('The project was not found');
        }
        
        $report = new Report();
        $metrics = $report->getReportMetrics($project);
        
        $categories = $report->getEventCategories();
        
        $script =

        "   <script type=\"text/javascript\" src=\"http://www.google.com/jsapi\"></script>
            <script type=\"text/javascript\">\n
              google.load('visualization', '1', {packages:['columnchart']});

              google.setOnLoadCallback(drawChart);

              function drawChart() {
                var barData = new google.visualization.DataTable();
                barData.addColumn('string', 'Event Category');";
                foreach ($metrics as $username => $cats){
                    $script .= "barData.addColumn('number', '".$username."');\n";
                }
                $script .= "barData.addRows(".count($categories).");\n";
                
                if (count($metrics))
                {
                    for ($i = 0; $i < count($categories); $i++)
                    {

                        $script .= "barData.setValue($i, 0, '".self::toTitleCase($categories[$i])."');\n";

                        $idx = 1;
                        foreach ($metrics as $key => $value) {
                            $script .= "barData.setValue($i, ".$idx.", ".$value[$categories[$i]].");\n";
                            $idx++;
                        }
                    }
                }

                $script .= "
                var barChart = new google.visualization.ColumnChart(document.getElementById('bar_chart_div'));
                barChart.draw(barData, {width: 420, height: 320, is3D: true, title: 'Project Metrics', 'isStacked': true, 'legend': 'bottom'});
              }
            </script>";

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
                    $script .= "barData.setValue($idx, 0, '".$key."');\n";
                    $script .= "barData.setValue($idx, 1, ".$value.");\n";
                    $idx++;
                }
                $script .= "
                var barChart = new google.visualization.ColumnChart(document.getElementById('bar_chart_div'));
                barChart.draw(barData, {width: 420, height: 320, is3D: true, title: 'Metrics for ".$user->getJnUsername()." in ".$project->getProjectJnName()."', 'legend': 'none'});
              }
            </script>
        ";

        return $script;
    }
    
    public function retrieveTopProjects($workspace_id, $num=null) {
        if (!isset($workspace_id) || $workspace_id == '') {
            throw new InfinityMetricsException('The workspace_id must be provided');
        }

        $ws = PersistentWorkspacePeer::retrieveByPK($workspace_id);

        if ($ws == NULL) {
            throw new InfinityMetricsException('The workspace was not found');
        }

        $report = new Report();
        $metrics = $report->getReportMetrics($ws);
        $projectTotals = $report->getMetricsTotalsByKey();
        
        arsort($projectTotals);

        if ($num == null) {
            return $projectTotals;
        }
        else {
            return array_splice($projectTotals, 0, (int)$num);
        }
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
        $categoryTotals = $report->getMetricsTotalsByCategory();
        $wsTotals = $report->getMetricsTotalsByKey();

        $categories = $report->getExtendedCategories();

        $script =

        "<script type=\"text/javascript\" src=\"http://www.google.com/jsapi\"></script>
        <script type=\"text/javascript\">
        google.load('visualization', '1', {packages:['piechart', 'table', 'columnchart']});

        google.setOnLoadCallback(drawChart);

        function drawChart() {
            var wsPieData = new google.visualization.DataTable();
            wsPieData.addColumn('string', 'Project');
            wsPieData.addColumn('number', 'Total Events');\n";

        $script .= "wsPieData.addRows(".count($wsTotals).");\n";

        $idx = 0;
        foreach ($wsTotals as $key => $value)
        {
            $script .= "wsPieData.setValue($idx, 0, '$key');\n";
            $script .= "wsPieData.setValue($idx, 1, $value);\n";
            $idx++;
        }

        $script .=

            "\nvar wsPieChart = new google.visualization.PieChart(document.getElementById('ws_pie_chart_div'));
            wsPieChart.draw(wsPieData, {width: 280, height: 280, is3D: true, title: 'Metrics by Project', legend: 'bottom'});\n";

        $script .=

            "var catPieData = new google.visualization.DataTable();
            catPieData.addColumn('string', 'Event Category');
            catPieData.addColumn('number', 'Total Events');\n";

        $script .= "catPieData.addRows(".count($categoryTotals).");\n";

        $idx = 0;
        foreach ($categoryTotals as $key => $value)
        {
            $script .= "catPieData.setValue($idx, 0, '".self::toTitleCase($key)."');\n";
            $script .= "catPieData.setValue($idx, 1, $value);\n";
            $idx++;
        }

        $script .=

            "\nvar catPieChart = new google.visualization.PieChart(document.getElementById('cat_pie_chart_div'));
            catPieChart.draw(catPieData, {width: 280, height: 280, is3D: true, title: 'Metrics by Category', legend: 'bottom'});\n


            var colChartData = new google.visualization.DataTable();\n
            colChartData.addColumn('string', 'Event Category');\n";

        foreach ($metrics as $pName => $cats){
            $script .= "colChartData.addColumn('number', '".$pName."');\n";
        }

        $script .= "colChartData.addRows(".count($categories).");\n";

        if (count($metrics))
        {
            for ($i = 0; $i < count($categories); $i++)
            {
                $script .= "colChartData.setValue($i, 0, '".self::toTitleCase($categories[$i])."');\n";

                $idx = 1;
                foreach ($metrics as $key => $value) {
                    $script .= "colChartData.setValue($i, ".$idx.", ".$value[$categories[$i]].");\n";
                    $idx++;
                }
            }
        }
        
        $script .= 

                "var barChart = new google.visualization.ColumnChart(document.getElementById('col_chart_div'));
                barChart.draw(colChartData, {width: 420, height: 360, is3D: true, title: 'Workspace Metrics', 'isStacked': true, 'legend': 'bottom'});\n\n

                var tableData = new google.visualization.DataTable();
                tableData.addColumn('string', 'Project');\n";
        
        foreach ($categories as $category) {
            $script .= "tableData.addColumn('number', '".self::toTitleCase($category)."');\n";
        }

        $script .= "tableData.addRows(".count($metrics).");\n";

        if (count($metrics))
        {
            $idx = 0;
            foreach ($metrics as $pName => $cats)
            {
                $script .= "tableData.setCell($idx, 0, '$pName');\n";
                for ($i = 0; $i < count($categories); $i++)
                {
                    $script .= "tableData.setCell($idx, ".($i+1).", {$cats[$categories[$i]]});\n";
                }
                $idx++;
            }
        }

        $script .=

                "var table = new google.visualization.Table(document.getElementById('table_chart_div'));
                table.draw(tableData, {showRowNumber: true});
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

                $script .= "barData.setValue($i, 0, '".self::toTitleCase($categories[$i])."');\n";

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
