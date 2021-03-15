<?php
include('./class/User.php');
$players = new Players();
if(!empty($_POST['action']) && $_POST['action'] == 'listplayer') {
        $playerTable = 'Edbtvplays_UnturnedLog_Players';
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

        $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Players;";


        $result = mysqli_query($dbConnect, $sqlQuery);

        $numRows = mysqli_num_rows($result);

        $playerData = array();

        while($player = mysqli_fetch_assoc($result) ) {
            $playerRows = array();
            $playerRows[] = $player['Id'];
            $playerRows[] = $player['CharacterName'];
            $playerRows[] = $player['SteamName'];
            $playerData[] = $playerRows;
        }

        $output = array(
            "draw"				=>	intval($_POST["draw"]),
            "recordsTotal"  	=>  $numRows,
            "recordsFiltered" 	=> 	$numRows,
            "data"    			=> 	$playerData
        );

        echo json_encode($output);
}
else if(!empty($_POST['action']) && $_POST['action'] == 'listevents') {
    $EventTable = 'Edbtvplays_UnturnedLog_Events';
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

    $id = 76561198236325606;
    $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = ".$_POST['id']." ";


    $result = mysqli_query($dbConnect, $sqlQuery);

    $numRows = mysqli_num_rows($result);

    $EventData = array();


    while($Event = mysqli_fetch_assoc($result) ) {
        $EventRows = array();
        $EventRows[] = $Event['EventType'];
        $EventRows[] = $Event['EventData'];
        $EventRows[] = $Event['ServerId'];
        $EventRows[] = $Event['EventTime'];
        $EventData[] = $EventRows;
    }
    // Inner join the server to get the Server name to display on the Event table.


    $output = array(
        "draw"				=>	intval($_POST["draw"]),
        "recordsTotal"  	=>  $numRows,
        "recordsFiltered" 	=> 	$numRows,
        "data"    			=> 	$EventData
    );

    echo json_encode($output);
}

?>