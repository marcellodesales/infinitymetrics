<?php
    include '../template/header-no-left-nav.php';
?>
    <div id="content-wrap">
        <div id="inside">
            <div id="sidebar-right">
                <div id="block-user-3" class="block block-user">
                    <h2>Students in this project:</h2>

                    <div class="content">
                    
                        <div class="item-list">
                            <ul>
                                <li class="first"><b>jsmith</b> (Leader)</li>
                                <li>geek82</li>
                                <li>shawnp</li>
                                <li>sarag</li>
                                <li class="last">becca</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div id="content">

                <br />
                <h2>Project Metrics Report</h2>
                <br />

                <div class="t"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr">
                    <div class="content-in">

                        <h3><?php echo $_GET['project_id'] ?></h3>
                        <?php echo "<a href=\"https://{$_GET['project_id']}.dev.java.net\">https://{$_GET['project_id']}.dev.java.net</a>" ?>
                        <br /><br />
                        <!--Load the AJAX API-->
                        <script type="text/javascript" src="http://www.google.com/jsapi"></script>
                        <script type="text/javascript">

                          // Load the Visualization API and the piechart package.
                          google.load('visualization', '1', {packages:['piechart', 'columnchart']});

                          // Set a callback to run when the API is loaded.
                          google.setOnLoadCallback(drawChart);

                          // Callback that creates and populates a data table,
                          // instantiates the pie chart, passes in the data and
                          // draws it.
                          function drawChart() {
                            var pieData = new google.visualization.DataTable();
                            pieData.addColumn('string', 'Event Category');
                            pieData.addColumn('number', 'Number of Entries');
                            pieData.addRows(5);
                            pieData.setValue(0, 0, 'Commits');
                            pieData.setValue(0, 1, 11);
                            pieData.setValue(1, 0, 'Discussion Forums');
                            pieData.setValue(1, 1, 2);
                            pieData.setValue(2, 0, 'Mailing Lists');
                            pieData.setValue(2, 1, 2);
                            pieData.setValue(3, 0, 'Custom Events');
                            pieData.setValue(3, 1, 2);
                            pieData.setValue(4, 0, 'Issues');
                            pieData.setValue(4, 1, 7);

                            var barData = new google.visualization.DataTable();
                            barData.addColumn('string', 'Student Name');
                            barData.addColumn('number', 'Commits');
                            barData.addColumn('number', 'Discussion Forums');
                            barData.addColumn('number', 'Mailing Lists');
                            barData.addColumn('number', 'Custom Events');
                            barData.addColumn('number', 'Issues');
                            barData.addRows(5);
                            barData.setValue(0, 0, 'jsmith');
                            barData.setValue(0, 1, 34);
                            barData.setValue(0, 2, 6);
                            barData.setValue(0, 3, 16);
                            barData.setValue(0, 4, 0);
                            barData.setValue(0, 5, 24);
                            barData.setValue(1, 0, 'geek82');
                            barData.setValue(1, 1, 36);
                            barData.setValue(1, 2, 6);
                            barData.setValue(1, 3, 20);
                            barData.setValue(1, 4, 14);
                            barData.setValue(1, 5, 32);
                            barData.setValue(2, 0, 'shawnp');
                            barData.setValue(2, 1, 6);
                            barData.setValue(2, 2, 4);
                            barData.setValue(2, 3, 2);
                            barData.setValue(2, 4, 0);
                            barData.setValue(2, 5, 18);
                            barData.setValue(3, 0, 'sarag');
                            barData.setValue(3, 1, 6);
                            barData.setValue(3, 2, 2);
                            barData.setValue(3, 3, 8);
                            barData.setValue(3, 4, 0);
                            barData.setValue(3, 5, 8);
                            barData.setValue(4, 0, 'becca');
                            barData.setValue(4, 1, 48);
                            barData.setValue(4, 2, 12);
                            barData.setValue(4, 3, 38);
                            barData.setValue(4, 4, 4);
                            barData.setValue(4, 5, 69);


                            var pieChart = new google.visualization.PieChart(document.getElementById('pie_chart_div'));
                            pieChart.draw(pieData, {width: 500, height: 240, is3D: true, title: 'Project Metrics By Category'});

                            var barChart = new google.visualization.ColumnChart(document.getElementById('bar_chart_div'));
                            barChart.draw(barData, {width: 500, height: 340, is3D: true, title: 'Project Metrics By Student and Category', 'isStacked': true, 'legend': 'bottom', 'titleY': 'Number of Events'});
                          }
                        </script>

                        <div id="pie_chart_div"></div>
                        <br />
                        <div id="bar_chart_div"></div>

                        <?php


                        ?>
                        <br /><br />
                    </div>
                    <br class="clear">
                </div></div></div></div></div></div></div></div>
            </div>
        </div>
        <BR>
      </div>
<?php
    include '../template/footer.php';
?>