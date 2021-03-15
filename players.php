<?php 
include('class/User.php');
$user = new User();
$user->loginStatus();
include('include/header.php');
?>
<title>UnturnedLog - Home</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.js"></script>
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
<script src="js/events.js"></script>
    <script type="text/javascript" src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>


<?php
// Get Data set, to parse into the graph using php function. Usually along the bottom would be the time and up the Y would be the amount.
// Im aware that this code is messy it just needs to be cleaned up and made OOP by putting it in a seperate class which will inherit the DB connection.


$dbConnect = false;

$hostName = "bpg-06-fa-de.modern-hosting.com:3306";
$userName = "u423_hz7T62dhec";
$password = "SC8^j81s79^W8bH+LE3qlLTh";
$dbName = "s423_Main";
$conn = new mysqli($hostName, $userName, $password, $dbName);

if($conn->connect_error){
    die("Error failed to connect to MySQL: " . $conn->connect_error);
} else{
    $dbConnect = $conn;
}

// Get the User ID for Chat Messages for the last 7 days.
// 76561198236325606
$sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events;";
    //WHERE PlayerId = "."76561198236325606".
    //" AND EventTime >= now() - interval 7 day AND EventType = "."Killed Zombie".";";

// Sort them into how many per day
try {
    $result = mysqli_query($dbConnect, $sqlQuery);
} catch(Exception $e) {
    echo($e);
}

if (!$result) {
    echo("Query Failed");
} else {
    $numRows = mysqli_num_rows($result);
}

echo(json_encode($result));
// Sort them into how many per day Display on Graph.

// Need to get the date for the 7 days previous.
$dataPoints = array(
    array("y" => 6, "label" => date('Y-m-d', strtotime('-1 days'))),
    array("y" => 4, "label" => date('Y-m-d', strtotime('-2 days'))),
    array("y" => 5, "label" => date('Y-m-d', strtotime('-3 days'))),
    array("y" => 7, "label" => date('Y-m-d', strtotime('-4 days'))),
    array("y" => 4, "label" => date('Y-m-d', strtotime('-5 days'))),
    array("y" => 6, "label" => date('Y-m-d', strtotime('-6 days'))),
    array("y" => 7, "label" => date('Y-m-d', strtotime('-7 days'))),
);
?>


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
                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                }
            ]
        });
        chart.render();
    });
</script>

<?php include('include/container.php');?>
<div class="container contact">
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
                    <div class="stat-panel-number h1 ">0</div>
                    <div class="stat-panel-title text-uppercase">Steam Name</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-success text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h1 ">0</div>
                    <div class="stat-panel-title text-uppercase">Character Name</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-success text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h1 ">0</div>
                    <div class="stat-panel-title text-uppercase">Last Played on</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-success text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h1 ">0</div>
                    <div class="stat-panel-title text-uppercase">Punishments</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-danger text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h1 ">0</div>
                    <div class="stat-panel-title text-uppercase">Total Play Time</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-success text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h1 ">0</div>
                    <div class="stat-panel-title text-uppercase">HWID</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-success text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h1 ">0</div>
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
                    <div class="stat-panel-number h1 ">0</div>
                    <div class="stat-panel-title text-uppercase">Chat Messages Sent</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-success text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h1 ">0</div>
                    <div class="stat-panel-title text-uppercase">Killed Normal Zombies</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-success text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h1 ">0</div>
                    <div class="stat-panel-title text-uppercase">Killed Mega Zombies</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-danger text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h1 ">0</div>
                    <div class="stat-panel-title text-uppercase">Found Items</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-primary text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h1 ">0</div>
                    <div class="stat-panel-title text-uppercase">Resources Farmed</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-success text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h1 ">0</div>
                    <div class="stat-panel-title text-uppercase">Found Buildables</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-success text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h1 ">0</div>
                    <div class="stat-panel-title text-uppercase">Fish Caught</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-danger text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h1 ">0</div>
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
                    <div class="stat-panel-number h1 ">0</div>
                    <div class="stat-panel-title text-uppercase">Kills</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-success text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h1 ">0</div>
                    <div class="stat-panel-title text-uppercase">Deaths</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body bk-success text-light">
                <div class="stat-panel text-center">
                    <div class="stat-panel-number h1 ">0</div>
                    <div class="stat-panel-title text-uppercase">Headshots</div>
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
                <th>Type</th>
                <th>Data</th>
                <th>Server</th>
                <th>Time</th>
            </tr>
            </thead>
        </table>
    </div>
</div>	
<?php include('include/footer.php');?>