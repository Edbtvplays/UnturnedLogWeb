<?php
include('class/General.php');
$user = new User();
$user->loginStatus();
include('include/header.php');
$players = new Players();

// Kills/Deaths Graph
$KillsUnencoded = $players->LineGraph("Global", "Kills/Deaths");

$KillsGraph = json_decode($KillsUnencoded, true);


$Kills = ($KillsGraph["Kills"][0]);

$Deaths = ($KillsGraph["Deaths"][0]);

// Connects Kills Graph
$encoded = $players->BarGraph("Global","Connected" );

$ConnectsGraph = json_decode($encoded, true);

$Connects = $ConnectsGraph["Return"];

// Construct Array here instead of in Javascript due to outdated graph.
$ConnectsArray = array(
    array("y" => $Connects[date('Y-m-d', strtotime('-0 days'))], "label" => date('Y-m-d', strtotime('-0 days'))),
    array("y" => $Connects[date('Y-m-d', strtotime('-1 days'))], "label" => date('Y-m-d', strtotime('-1 days'))),
    array("y" => $Connects[date('Y-m-d', strtotime('-2 days'))], "label" => date('Y-m-d', strtotime('-2 days'))),
    array("y" => $Connects[date('Y-m-d', strtotime('-3 days'))], "label" => date('Y-m-d', strtotime('-3 days'))),
    array("y" => $Connects[date('Y-m-d', strtotime('-4 days'))], "label" => date('Y-m-d', strtotime('-4 days'))),
    array("y" => $Connects[date('Y-m-d', strtotime('-5 days'))], "label" => date('Y-m-d', strtotime('-5 days'))),
    array("y" => $Connects[date('Y-m-d', strtotime('-6 days'))], "label" => date('Y-m-d', strtotime('-6 days'))),
);
?>
<title>UnturnedLog - Home</title>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.24/b-1.7.0/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.24/b-1.7.0/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
<script type="text/javascript" src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>

<script>
    window.onload = function () {
        var chart = new CanvasJS.Chart("KillsDeaths", {
            animationEnabled: true,
            title:{
                text: "Global Player Kills/Deaths"
            },
            axisY: {
                suffix: " "
            },
            legend:{
                cursor: "pointer",
                fontSize: 16,
                itemclick: toggleDataSeries
            },
            toolTip:{
                shared: true
            },
            data: [{
                name: "Player Deaths",
                type: "spline",
                yValueFormatString: "#0.##",
                showInLegend: true,
                dataPoints: [
                    { x: new Date(<?php echo date('Y,m,d', strtotime('-1 months -0 days')) ?>), y: <?php echo $Deaths[date('Y-m-d', strtotime('-0 days'))] ?> },
                    { x: new Date(<?php echo date('Y,m,d', strtotime('-1 months -1 days')) ?>), y: <?php echo $Deaths[date('Y-m-d', strtotime('-1 days'))] ?> },
                    { x: new Date(<?php echo date('Y,m,d', strtotime('-1 months -2 days')) ?>), y: <?php echo $Deaths[date('Y-m-d', strtotime('-2 days'))] ?> },
                    { x: new Date(<?php echo date('Y,m,d', strtotime('-1 months -3 days')) ?>), y: <?php echo $Deaths[date('Y-m-d', strtotime('-3 days'))] ?> },
                    { x: new Date(<?php echo date('Y,m,d', strtotime('-1 months -4 days')) ?>), y: <?php echo $Deaths[date('Y-m-d', strtotime('-4 days'))] ?> },
                    { x: new Date(<?php echo date('Y,m,d', strtotime('-1 months -5 days')) ?>), y: <?php echo $Deaths[date('Y-m-d', strtotime('-5 days'))] ?> },
                    { x: new Date(<?php echo date('Y,m,d', strtotime('-1 months -6 days')) ?>), y: <?php echo $Deaths[date('Y-m-d', strtotime('-6 days'))] ?> }
                ]
            },
                {
                    name: "Player Kills",
                    type: "spline",
                    yValueFormatString: "#0.##",
                    showInLegend: true,
                    dataPoints: [
                        { x: new Date(<?php echo date('Y,m,d', strtotime('-1 months -0 days')) ?>), y: <?php echo $Kills[date('Y-m-d', strtotime('-0 days'))] ?> },
                        { x: new Date(<?php echo date('Y,m,d', strtotime('-1 months -1 days')) ?>), y: <?php echo $Kills[date('Y-m-d', strtotime('-1 days'))] ?> },
                        { x: new Date(<?php echo date('Y,m,d', strtotime('-1 months -2 days')) ?>), y:  <?php echo $Kills[date('Y-m-d', strtotime('-2 days'))] ?> },
                        { x: new Date(<?php echo date('Y,m,d', strtotime('-1 months -3 days')) ?>), y: <?php echo $Kills[date('Y-m-d', strtotime('-3 days'))] ?> },
                        { x: new Date(<?php echo date('Y,m,d', strtotime('-1 months -4 days')) ?>), y: <?php echo $Kills[date('Y-m-d', strtotime('-4 days'))] ?> },
                        { x: new Date(<?php echo date('Y,m,d', strtotime('-1 months -5 days')) ?>), y: <?php echo $Kills[date('Y-m-d', strtotime('-5 days'))] ?> },
                        { x: new Date(<?php echo date('Y,m,d', strtotime('-1 months -6 days')) ?>), y: <?php echo $Kills[date('Y-m-d', strtotime('-6 days'))] ?> }
                    ]
                }]
        });
        chart.render();

        function toggleDataSeries(e){
            if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
            }
            else{
                e.dataSeries.visible = true;
            }
            chart.render();
        }

    }
</script>

    <script type="text/javascript">
        // Render a Chart
        $(function () {
            var chart = new CanvasJS.Chart("ConnectschartContainer", {
                theme: "theme2",
                animationEnabled: true,
                title: {
                    text: "Players Connected"
                },
                data: [
                    {
                        type: "column",
                        dataPoints: <?php echo json_encode($ConnectsArray, JSON_NUMERIC_CHECK); ?>
                    }
                ]
            });
            chart.render();
        });
    </script>

<script type="text/javascript">
    $(document).ready(function() {
        var usersData = $('#userList').DataTable({
            "lengthChange": false,
            "processing": true,
            "serverSide": true,
            "lengthMenu": true,
            "order": [],
            "ajax": {
                url: "action.php",
                type: "POST",
                data: {action: 'killsleaderboard'},
                dataType: "json"
            },
            "bInfo": false,
            "language": {
                "lengthMenu": "One",
                "search": "Player Search:"
            },
            "columnDefs": [
                {
                    "targets": [0, 1, 2],
                    "orderable": false,
                },
            ],
            "pageLength": 25,
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        var usersData = $('#DeathList').DataTable({
            "lengthChange": false,
            "processing": true,
            "serverSide": true,
            "lengthMenu": true,
            "order": [],
            "ajax": {
                url: "action.php",
                type: "POST",
                data: {action: 'deathsleaderboard'},
                dataType: "json"
            },
            "bInfo": false,
            "language": {
                "lengthMenu": "One",
                "search": "Player Search:"
            },
            "columnDefs": [
                {
                    "targets": [0, 1, 2],
                    "orderable": false,
                },
            ],
            "pageLength": 25,
        });
    });
</script>

<?php include('include/container.php');?>
    <div class="container contact">
        <?php include('include/menu.php');?>
        <class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-10">
                    <h3 class="panel-title">Kills Leaderboards</h3>
                </div>
            </div>
        </div>
        <table id="userList" class="table table-bordered table-striped">
            <div class="buttons"> </div>
            <thead>
            <tr>
                <th>Ranking</th>
                <th>Username</th>
                <th>Kills</th>
            </tr>
            </thead>
        </table>
        <div id="KillsDeaths" style="height: 370px; width: 100%;padding-bottom:100px"></div>
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-10">
                    <h3 class="panel-title">Deaths Leaderboards</h3>
                </div>
            </div>
        </div>
        <table id="DeathList" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Ranking</th>
                <th>Username</th>
                <th>Deaths</th>
            </tr>
            </thead>
        </table>
        <div id="ConnectschartContainer" style="width:100%; height:300px;padding-bottom:100px"></div>
    </div>
</div>


<?php include('include/footer.php');?>