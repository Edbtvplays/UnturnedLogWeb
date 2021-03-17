<?php
include('./class/User.php');
$players = new Players();
if(!empty($_POST['action']) && $_POST['action'] == 'listplayer') {
    $players->getPlayerList();
}
else if(!empty($_POST['action']) && $_POST['action'] == 'listevents') {
    $players->getPlayerEvents(($_POST['id']));
}

?>