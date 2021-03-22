<?php 
include('class/General.php');
$user = new User();
$user->loginStatus();
$player = new Players();
$ErrorMessage = $player->CheckPlayerInput("Other");

// Profile Picture
$PFPHash = $player->GetHash();

// Kills/Deaths Graph
$KillsUnencoded = $player->LineGraph("Individual", "Kills/Deaths");

$KillsGraph = json_decode($KillsUnencoded, true);


$Kills = ($KillsGraph["Kills"][0]);

$Deaths = ($KillsGraph["Deaths"][0]);


// Connects Kills Graph
$encoded = $player->BarGraph("Individual","Connected");

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

// Zombie Kills Graph
$encoded = $player->BarGraph("Individual","ZombieKills");

$ZommbieKillsGraph = json_decode($encoded, true);

$ZommbieKills = $ZommbieKillsGraph["Return"];

// Construct Array here instead of in Javascript due to outdated graph.
$ZommbieKillsPoints = array(
    array("y" => $ZommbieKills[date('Y-m-d', strtotime('-0 days'))], "label" => date('Y-m-d', strtotime('-0 days'))),
    array("y" => $ZommbieKills[date('Y-m-d', strtotime('-1 days'))], "label" => date('Y-m-d', strtotime('-1 days'))),
    array("y" => $ZommbieKills[date('Y-m-d', strtotime('-2 days'))], "label" => date('Y-m-d', strtotime('-2 days'))),
    array("y" => $ZommbieKills[date('Y-m-d', strtotime('-3 days'))], "label" => date('Y-m-d', strtotime('-3 days'))),
    array("y" => $ZommbieKills[date('Y-m-d', strtotime('-4 days'))], "label" => date('Y-m-d', strtotime('-4 days'))),
    array("y" => $ZommbieKills[date('Y-m-d', strtotime('-5 days'))], "label" => date('Y-m-d', strtotime('-5 days'))),
    array("y" => $ZommbieKills[date('Y-m-d', strtotime('-6 days'))], "label" => date('Y-m-d', strtotime('-6 days'))),
);

include('include/header.php');
?>
<title>UnturnedLog - Home</title>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.js"></script>
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
<script type="text/javascript" src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>


<style>
    .alert:empty {
        display: none;
    }
</style>

<script>
    window.onload = function () {
        var chart = new CanvasJS.Chart("KillsDeaths", {
            animationEnabled: true,
            title:{
                text: "Player Kills/Deaths"
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
    $(document).ready(function(){
        // Gets All Parameters from the URL
        let searchParams = new URLSearchParams(window.location.search)

        let param = searchParams.get('player') // true

        // Tries to See if the Player Paramter is in the URL if it isnt throw a Error.

        console.log(param)
        if (!param) {
            document.getElementById("generalalert").innerHTML="No Player Parameter was Entered.";
            console.error("No Player Key Entered ")
            return 0
        }


        // If a Parameter does exsist.
        else {
            // Data Table Gets information for all Events.
            var EventsData = $('#eventList').DataTable({
                "lengthChange": false,
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    url: "action.php",
                    type: "POST",
                    data: {action: 'listevents', id: searchParams.get('player')},
                    dataType: "json"
                },
                "language": {
                    "lengthMenu": "_MENU_",
                    "search": "Search:"
                },
                "columnDefs": [
                    {
                        "targets": [0, 1, 2, 3],
                        "orderable": true,
                    },
                ],
                "pageLength": 25
            });
        }
    });
</script>

<script type="text/javascript">
    // Render a Chart
    $(function () {
        var chart = new CanvasJS.Chart("chartContainer", {
            theme: "theme2",
            animationEnabled: true,
            title: {
                text: "Zombies Killed"
            },
            data: [
                {
                    type: "column",
                    dataPoints: <?php echo json_encode($ZommbieKillsPoints, JSON_NUMERIC_CHECK); ?>
                }
            ]
        });
        chart.render();
    });
</script>

<script type="text/javascript">
    // Render a Chart
    $(function () {
        var chart = new CanvasJS.Chart("ConnectschartContainer", {
            theme: "theme2",
            animationEnabled: true,
            title: {
                text: "Times Connected"
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

<?php include('include/container.php');?>
<div class="container contact">
    <?php include('include/menu.php');?>
    <?php if ($ErrorMessage != '') { ?>
        <div id="login-alert" class="alert alert-danger col-sm-12"><?php echo $ErrorMessage; ?></div>
    <?php } ?>
    <div id="generalalert" class="alert alert-danger"></div>
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-10">
                <h3 class="panel-title">General Information</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-primary text-light">
                <div class="stat-panel text-center">
                    <?php if ($PFPHash != '') { ?>
                        <img alt="Qries" src="https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/b4/<?php echo $player->GetHash() ?>_full.jpg" width=150" height="150">
                    <?php } ?>
                    <div class="stat-panel-number h1 "><?php echo $player->GetInformation("STEAM_NAME"); ?> </div>
                    <div class="stat-panel-number h4 ">(<?php echo $_GET["player"]; ?>)</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-success text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h1 "><?php echo $player->GetInformation("CHARACTER_NAME"); ?></div>
                    <div class="stat-panel-title text-uppercase">Character Name</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-success text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h3"><?php echo $player->GetInformation("LAST_PLAYED"); ?></div>
                    <div class="stat-panel-title text-uppercase">Last Played on</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-success text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h3"><?php echo $player->GetInformation("LAST_SERVER"); ?></div>
                    <div class="stat-panel-title text-uppercase">Last Server Played</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-success text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h1 "><?php echo $player->GetStatistic("PUNISHMENTS"); ?></div>
                    <div class="stat-panel-title text-uppercase">Punishments</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-danger text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h1 "><?php echo $player->GetInformation("TOTAL_PLAYTIME"); ?></div>
                    <div class="stat-panel-title text-uppercase">Total Play Time</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-success text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h1 "><?php echo $player->GetInformation("PLAYER_IP"); ?></div>
                    <div class="stat-panel-title text-uppercase">IP</div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-10">
                <h3 class="panel-title">General Information</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-primary text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h1 "><?php echo $player->GetStatistic("CHAT_MESSAGES"); ?></div>
                    <div class="stat-panel-title text-uppercase">Chat Messages Sent</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-success text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h1 "><?php echo $player->GetStatistic("KILLED_ZOMBIES"); ?></div>
                    <div class="stat-panel-title text-uppercase">Killed Normal Zombies</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-success text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h1 "><?php echo $player->GetStatistic("KILLED_MEGA_ZOMBIES"); ?></div>
                    <div class="stat-panel-title text-uppercase">Killed Mega Zombies</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-danger text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h1 "><?php echo $player->GetStatistic("PLAYER_TELEPORTS"); ?></div>
                    <div class="stat-panel-title text-uppercase">Teleports</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-primary text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h1 "><?php echo $player->GetStatistic("FARMED_RESOURCES"); ?></div>
                    <div class="stat-panel-title text-uppercase">Resources Farmed</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-success text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h1 "><?php echo $player->GetStatistic("BUILDABLE_PLACED"); ?></div>
                    <div class="stat-panel-title text-uppercase">Buildables Places</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-success text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h1 "><?php echo $player->GetStatistic("FISH_CAUGHT"); ?></div>
                    <div class="stat-panel-title text-uppercase">Fish Caught</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-danger text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h1 "><?php echo $player->GetStatistic("FOUND_PLANTS"); ?></div>
                    <div class="stat-panel-title text-uppercase">Plants Gathered</div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-10">
                <h3 class="panel-title">PVP Information</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-primary text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h1 "><?php echo $player->GetStatistic("PLAYER_KILLS"); ?></div>
                    <div class="stat-panel-title text-uppercase">Player Kills</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-success text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h1 "><?php echo $player->GetStatistic("PLAYER_DEATHS"); ?></div>
                    <div class="stat-panel-title text-uppercase">Deaths</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-success text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h1 "><?php echo $player->GetStatistic("PLAYER_HEADSHOTS"); ?></div>
                    <div class="stat-panel-title text-uppercase">Player Headshots</div>
                </div>
            </div>
        </div>
    </div>
    <class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-10">
                    <h3 class="panel-title">Graphs</h3>
                </div>
            </div>
        </div>
        <div id="ConnectschartContainer" style="height: 370px; width: 100%;padding-bottom:100px"></div>
        <div id="KillsDeaths" style="height: 370px; width: 100%;padding-bottom:100px"></div>
        <div id="chartContainer" style="width:100%; height:300px;padding-bottom:100px"></div>
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-10">
                    <h3 class="panel-title">All Events</h3>
                </div>
            </div>
        </div>
        <table id="eventList" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>EventType</th>
                <th>EventData</th>
                <th>EventTime</th>
                <th>ServerId</th>
            </tr>
            </thead>
        </table>
    </div>
</div>	
<?php include('include/footer.php');?>