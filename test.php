<?php
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

$sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events;";


$result = mysqli_query($dbConnect, $sqlQuery);

$numRows = mysqli_num_rows($result);

echo($numRows);

?>

