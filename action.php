<?php
include('./class/General.php');
$players = new Players();
$user = new User();
if(!empty($_POST['action']) && $_POST['action'] == 'listplayer') {
    $players->getPlayerList();
}
if(!empty($_POST['action']) && $_POST['action'] == 'listevents') {
    $players->getPlayerEvents();
}
if(!empty($_POST['action']) && $_POST['action'] == 'killsleaderboard') {
    $players->Leaderboard("Kills");
}
if(!empty($_POST['action']) && $_POST['action'] == 'deathsleaderboard') {
    $players->Leaderboard("Deaths");
}
if(!empty($_POST['action']) && $_POST['action'] == 'listUser') {
    $user->getUserList();
}
if(!empty($_POST['action']) && $_POST['action'] == 'userDelete') {
    $user->deleteUser();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getUser') {
    $user->getUser();
}
if(!empty($_POST['action']) && $_POST['action'] == 'addUser') {
    $user->addUser();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateUser') {
    $user->updateUser();
}
?>