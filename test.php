<?php
session_start();

$dbConnect = false;

$serverName = 'bpg-06-fa-de.modern-hosting.com:3306';
$userName = 'u423_hz7T62dhec';
$password = 'SC8^j81s79^W8bH+LE3qlLTh';
$dbName = 's423_Main';

$conn = mysqli_connect($serverName, $userName, $password, $dbName);

if($conn->connect_error){
    die("Error failed to connect to MySQL: " . $conn->connect_error);
} else{
    $dbConnect = $conn;
}

$id = "76561199112950263";
$sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = ".$_GET["player"]." AND EventType = 'Killed Zombie' AND EventTime >= now() - interval 7 day;";

//AND EventTime >= now() - interval 7 day AND EventType = "."Killed Zombie".";";


$result = mysqli_query($dbConnect, $sqlQuery);

while($rowData = mysqli_fetch_array($result)){
    echo $rowData["PlayerId"].' '.$rowData["EventTime"].' '.$rowData["EventType"].'<br>';
}

$result->close();

