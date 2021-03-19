<?php
session_start();

class DbConfig {
    protected $serverName;
    protected $userName;
    protected $password;
    protected $dbName;
	
    function  __construct() {
        $this -> serverName = 'bpg-06-fa-de.modern-hosting.com:3306';
        $this -> userName = 'u423_hz7T62dhec';
        $this -> password = 'SC8^j81s79^W8bH+LE3qlLTh';
        $this -> dbName = 's423_Main';
    }
}

class Players extends DbConfig
{

    protected $hostName;
    protected $userName;
    protected $password;
    protected $dbName;
    private $dbConnect = false;

    public function __construct(){
        if(!$this->dbConnect){
            $database = new dbConfig();
            $this -> hostName = $database -> serverName;
            $this -> userName = $database -> userName;
            $this -> password = $database ->password;
            $this -> dbName = $database -> dbName;
            $conn = new mysqli($this->hostName, $this->userName, $this->password, $this->dbName);
            if($conn->connect_error){
                die("Error failed to connect to MySQL: " . $conn->connect_error);
            } else{
                $this->dbConnect = $conn;
            }
        }
        parent::__construct();
    }

    public function getPlayerList() {
        $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Players ";

        if(!empty($_POST["search"]["value"])){
            $sqlQuery .= 'WHERE (Id = "'.$_POST["search"]["value"].'" ';
            $sqlQuery .= ' OR SteamName LIKE "%'.$_POST["search"]["value"].'%" ';
        }

        if($_POST["length"] != -1){
            $sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }

        $result = mysqli_query($this->dbConnect, $sqlQuery);

        $numRows = mysqli_num_rows($result);

        $playerData = array();

        while($player = mysqli_fetch_assoc($result) ) {
            $playerRows = array();
            $playerRows[] = '<a href="http://unturned-log.test/players.php?player='.$player['Id'].'">'.$player['Id'].'</a>';
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


    public function getPlayerEvents() {

        // TODO: Error Handeling for this.

        $PlayerListErrorMessage = '';

        $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = '".$_POST["id"]."' ";

        // TODO: Parametise the SQL to protect from attacks.


        // TODO: Fix searching
        // If there is a search request.
        if(!empty($_POST["search"]["value"])){
            $sqlQuery .= '(EventType = "'.$_POST["search"]["value"].'" ';
            $sqlQuery .= ' OR EventData LIKE "%'.$_POST["search"]["value"].'%" ';
        }

        if(!empty($_POST["order"])){
            $sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
        } else {
            $sqlQuery .= 'ORDER BY EventTime DESC ';
        }

        if($_POST["length"] != -1){
            $sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }

        $result = mysqli_query($this->dbConnect, $sqlQuery);

        $filterednumRows = mysqli_num_rows($result);

        // Gets the total ammount of rows in the database for this user as its used for datatables list.
        $totalquery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = ".$_POST["id"].";" ;
        $totalresult = mysqli_query($this->dbConnect, $totalquery);
        $totalrows = mysqli_num_rows($totalresult);

        $EventData = array();

        // TODO: DO a inner join for grabbing the players server name that corresponds with the "ServerID"

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
            "recordsTotal"  	=>  $totalrows,
            "recordsFiltered" 	=> 	$totalrows,
            "data"    			=> 	$EventData
        );

        echo json_encode($output);

    }

    public function GetStatistic($statistic) {

        $sqlQuery = "";
        if ($statistic == "CHAT_MESSAGES") {
            $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = ".$_GET["player"]." AND EventType = 'Chat Message'";
        } else if ($statistic == "KILLED_ZOMBIES") {
            $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = ".$_GET["player"]." AND EventType = 'Killed Zombie'";
        } else if ($statistic == "KILLED_MEGA_ZOMBIES") {
            $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = ".$_GET["player"]." AND EventType = 'Killed Mega Zombie'";
        } else if ($statistic == "FOUND_PLANTS") {
            $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = ".$_GET["player"]." AND EventType = 'Found Plants'";
        } else if ($statistic == "FOUND_RESOURCES") {
            $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = " . $_GET["player"] . " AND EventType = 'Found Resource'";
        } else if ($statistic == "FARMED_RESOURCES") {
                $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = ".$_GET["player"]." AND EventType = 'Resource harvested'";
        } else if ($statistic == "PLAYER_HEADSHOTS") {
            $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = ".$_GET["player"]." AND EventType = 'Player Headshot'";
        } else if ($statistic == "FISH_CAUGHT") {
            $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = ".$_GET["player"]." AND EventType = 'Fish Caught'";
        } else if ($statistic == "BUILDABLE_PLACED") {
            $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = ".$_GET["player"]." AND EventType = 'Placed Buildable'";
        } else if ($statistic == "PUNISHMENTS") {
            $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = ".$_GET["player"]." AND EventType = 'Player Banned'";
        } else if ($statistic == "PLAYER_KILLS") {
            $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = ".$_GET["player"]." AND EventType = 'Player Kill'";
        } else if ($statistic == "PLAYER_DEATHS") {
            $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = ".$_GET["player"]." AND EventType = 'Death'";
        } else if ($statistic == "PLAYER_TELEPORTS") {
            $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = ".$_GET["player"]." AND EventType = 'Teleported'";
        } else {
            return "Error: Not a valid thing to display";
        }

        $result = mysqli_query($this->dbConnect, $sqlQuery);

        if (!$result) {
            return 0;
        } else {
            $amount =  mysqli_num_rows($result);
            return($amount);
        }

    }

    public function GetInformation($information) {

        $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Players WHERE Id = ".$_GET["player"].";";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $PlayerData = array();

        if ($information == "PLAYER_IP") {
            while($Player = mysqli_fetch_assoc($result) ) {
                $PlayerRows = array();
                $PlayerRows[] = $Player['Ip'];
                $PlayerData = $PlayerRows;
            };
            $ip = long2ip($PlayerData[0]);
            return($ip);
        } else if ($information == "TOTAL_PLAYTIME") {
            while($Player = mysqli_fetch_assoc($result) ) {
                $PlayerRows = array();
                $PlayerRows[] = $Player['TotalPlaytime'];
                $PlayerData = $PlayerRows;
            };
            $playtime = gmdate("H:i:s", $PlayerData[0]);
            return($playtime);

        } else if ($information == "LAST_PLAYED") {
            while($Player = mysqli_fetch_assoc($result) ) {
                $PlayerRows = array();
                $PlayerRows[] = $Player['LastLoginGlobal'];
                $PlayerData = $PlayerRows;
            };
            return($PlayerData[0]);
        } else if ($information == "CHARACTER_NAME") {
            while($Player = mysqli_fetch_assoc($result) ) {
                $PlayerRows = array();
                $PlayerRows[] = $Player['CharacterName'];
                $PlayerData = $PlayerRows;
            };
            return($PlayerData[0]);
        } else if ($information == "STEAM_NAME") {
            while($Player = mysqli_fetch_assoc($result) ) {
                $PlayerRows = array();
                $PlayerRows[] = $Player['SteamName'];
                $PlayerData = $PlayerRows;
            };
            return($PlayerData[0]);
        }


    }


    public function ZombieKillsGraph() {

        $sqlQuery = "SELECT EventTime FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = ".$_GET["player"]." AND EventType = 'Killed Zombie' AND EventTime >= now() - interval 7 day;";


        $result = mysqli_query($this->dbConnect, $sqlQuery);

        $EventData = array();
        $one = 0;
        $two = 0;
        $three = 0;
        $four = 0;
        $five = 0;
        $six = 0;
        $seven = 0;

        while($rowData = mysqli_fetch_array($result)){
            if (date("Y-m-d", strtotime($rowData["EventTime"])) == date("Y-m-d",strtotime('-0 days'))){
                $one = $one + 1;
            } else if (date("Y-m-d", strtotime($rowData["EventTime"])) == date("Y-m-d", strtotime('-1 days'))) {
                $two = $two + 1;
            } else if (date("Y-m-d", strtotime($rowData["EventTime"])) == date("Y-m-d", strtotime('-2 days'))) {
                $three = $three + 1;
            } else if (date("Y-m-d", strtotime($rowData["EventTime"])) == date("Y-m-d", strtotime('-3 days'))) {
                $four = $four + 1;
            } else if (date("Y-m-d", strtotime($rowData["EventTime"])) == date("Y-m-d", strtotime('-4 days'))) {
                $five = $five + 1;
            } else if (date("Y-m-d", strtotime($rowData["EventTime"])) === date("Y-m-d", strtotime('-5 days'))) {
                $six = $six + 1;
            } else if (date("Y-m-d", strtotime($rowData["EventTime"])) === date("Y-m-d", strtotime('-6 days'))) {
                $seven = $seven + 1;
            }
        }

        $output = array(
            "one"				=>	$one,
            "two"  	            =>  $two,
            "three" 	        => 	$three,
            "four"    			=> 	$four,
            "five"              => 	$five,
            "six"               => 	$six,
            "seven"             => 	$seven,
        );

        // Tally The Data into 7 rows corresponding to the last 7 days.
        return(json_encode($output));
    }
}

class User extends Dbconfig {

    protected $hostName;
    protected $userName;
    protected $password;
    protected $dbName;
    private $userTable = 'user';
    private $dbConnect = false;

    public function __construct(){
        if(!$this->dbConnect){
            $database = new dbConfig();
            $this -> hostName = $database -> serverName;
            $this -> userName = $database -> userName;
            $this -> password = $database ->password;
            $this -> dbName = $database -> dbName;
            $conn = new mysqli($this->hostName, $this->userName, $this->password, $this->dbName);
            if($conn->connect_error){
                die("Error failed to connect to MySQL: " . $conn->connect_error);
            } else{
                $this->dbConnect = $conn;
            }
        }
        parent::__construct();
    }


    public function loginStatus (){
        if(empty($_SESSION["userid"])) {
            header("Location: login.php");
        }
    }

    public function login(){
        $errorMessage = '';
        if(!empty($_POST["login"]) && $_POST["loginId"]!=''&& $_POST["loginPass"]!='') {
            $loginId = $_POST['loginId'];
            $password = $_POST['loginPass'];
            if(isset($_COOKIE["loginPass"]) && $_COOKIE["loginPass"] == $password) {
                $password = $_COOKIE["loginPass"];
            } else {
                $password = md5($password);
            }
            $sqlQuery = "SELECT * FROM ".$this->userTable." 
				WHERE email='".$loginId."' AND password='".$password."' AND status = 'active'";
            $resultSet = mysqli_query($this->dbConnect, $sqlQuery);
            $isValidLogin = mysqli_num_rows($resultSet);
            if($isValidLogin){
                if(!empty($_POST["remember"]) && $_POST["remember"] != '') {
                    setcookie ("loginId", $loginId, time()+ (10 * 365 * 24 * 60 * 60));
                    setcookie ("loginPass",	$password,	time()+ (10 * 365 * 24 * 60 * 60));
                } else {
                    $_COOKIE['loginId' ]='';
                    $_COOKIE['loginPass'] = '';
                }
                $userDetails = mysqli_fetch_assoc($resultSet);
                $_SESSION["userid"] = $userDetails['id'];
                $_SESSION["name"] = $userDetails['first_name']." ".$userDetails['last_name'];
                header("location: index.php");
            } else {
                $errorMessage = "Invalid login!";
            }
        } else if(!empty($_POST["loginId"])){
            $errorMessage = "Enter Both user and password!";
        }
        return $errorMessage;
    }

    public function adminLoginStatus (){
        if(empty($_SESSION["adminUserid"])) {

            header("Location: index.php");
        }
    }

    public function adminLogin(){
        $errorMessage = '';
        if(!empty($_POST["login"]) && $_POST["email"]!=''&& $_POST["password"]!='') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $sqlQuery = "SELECT * FROM ".$this->userTable." 
				WHERE email='".$email."' AND password='".md5($password)."' AND status = 'active' AND type = 'administrator'";
            $resultSet = mysqli_query($this->dbConnect, $sqlQuery);
            $isValidLogin = mysqli_num_rows($resultSet);
            if($isValidLogin){
                $userDetails = mysqli_fetch_assoc($resultSet);
                $_SESSION["adminUserid"] = $userDetails['id'];
                $_SESSION["admin"] = $userDetails['first_name']." ".$userDetails['last_name'];
                header("location: dashboard.php");
            } else {
                 }
        } else if(!empty($_POST["login"])){
            $errorMessage = "Enter Both user and password!";
        }
        return $errorMessage;
    }

    public function getAuthtoken($email) {
        $code = md5(889966);
        $authtoken = $code."".md5($email);
        return $authtoken;
    }


    public function userDetails () {
        $sqlQuery = "SELECT * FROM ".$this->userTable." 
			WHERE id ='".$_SESSION["userid"]."'";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $userDetails = mysqli_fetch_assoc($result);
        return $userDetails;
    }

    public function editAccount () {
        $message = '';
        $updatePassword = '';
        if(!empty($_POST["passwd"]) && $_POST["passwd"] != '' && $_POST["passwd"] != $_POST["cpasswd"]) {
            $message = "Confirm passwords do not match.";
        } else if(!empty($_POST["passwd"]) && $_POST["passwd"] != '' && $_POST["passwd"] == $_POST["cpasswd"]) {
            $updatePassword = ", password='".md5($_POST["passwd"])."' ";
        }
        $updateQuery = "UPDATE ".$this->userTable." 
			SET first_name = '".$_POST["firstname"]."', last_name = '".$_POST["lastname"]."', email = '".$_POST["email"]."', mobile = '".$_POST["mobile"]."' , designation = '".$_POST["designation"]."', gender = '".$_POST["gender"]."' $updatePassword
			WHERE id ='".$_SESSION["userid"]."'";
        $isUpdated = mysqli_query($this->dbConnect, $updateQuery);
        if($isUpdated) {
            $_SESSION["name"] = $_POST['firstname']." ".$_POST['lastname'];
            $message = "Account details saved.";
        }
        return $message;
    }

    public function savePassword(){
        $message = '';
        if($_POST['password'] != $_POST['cpassword']) {
            $message = "Password does not match the confirm password.";
        } else if($_POST['authtoken']) {
            $sqlQuery = "
				SELECT email, authtoken 
				FROM ".$this->userTable." 
				WHERE authtoken='".$_POST['authtoken']."'";
            $result = mysqli_query($this->dbConnect, $sqlQuery);
            $numRows = mysqli_num_rows($result);
            if($numRows) {
                $userDetails = mysqli_fetch_assoc($result);
                $authtoken = $this->getAuthtoken($userDetails['email']);
                if($authtoken == $_POST['authtoken']) {
                    $sqlUpdate = "
						UPDATE ".$this->userTable." 
						SET password='".md5($_POST['password'])."'
						WHERE email='".$userDetails['email']."' AND authtoken='".$authtoken."'";
                    $isUpdated = mysqli_query($this->dbConnect, $sqlUpdate);
                    if($isUpdated) {
                        $message = "Password saved successfully. Please <a href='login.php'>Login</a> to access account.";
                    }
                } else {
                    $message = "Invalid password change request.";
                }
            } else {
                $message = "Invalid password change request.";
            }
        }
        return $message;
    }

    public function getUserList(){
        $sqlQuery = "SELECT * FROM ".$this->userTable." WHERE id !='".$_SESSION['adminUserid']."' ";
        if(!empty($_POST["search"]["value"])){
            $sqlQuery .= '(id LIKE "%'.$_POST["search"]["value"].'%" ';
            $sqlQuery .= ' OR first_name LIKE "%'.$_POST["search"]["value"].'%" ';
            $sqlQuery .= ' OR last_name LIKE "%'.$_POST["search"]["value"].'%" ';
            $sqlQuery .= ' OR designation LIKE "%'.$_POST["search"]["value"].'%" ';
            $sqlQuery .= ' OR status LIKE "%'.$_POST["search"]["value"].'%" ';
            $sqlQuery .= ' OR mobile LIKE "%'.$_POST["search"]["value"].'%") ';
        }
        if(!empty($_POST["order"])){
            $sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
        } else {
            $sqlQuery .= 'ORDER BY id DESC ';
        }
        if($_POST["length"] != -1){
            $sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }
        $result = mysqli_query($this->dbConnect, $sqlQuery);

        $sqlQuery1 = "SELECT * FROM ".$this->userTable." WHERE id !='".$_SESSION['adminUserid']."' ";
        $result1 = mysqli_query($this->dbConnect, $sqlQuery1);
        $numRows = mysqli_num_rows($result1);

        $userData = array();
        while( $users = mysqli_fetch_assoc($result) ) {
            $userRows = array();
            $status = '';
            if($users['status'] == 'active')	{
                $status = '<span class="label label-success">Active</span>';
            } else if($users['status'] == 'pending') {
                $status = '<span class="label label-warning">Inactive</span>';
            } else if($users['status'] == 'deleted') {
                $status = '<span class="label label-danger">Deleted</span>';
            }
            $userRows[] = $users['id'];
            $userRows[] = ucfirst($users['first_name']." ".$users['last_name']);
            $userRows[] = $users['gender'];
            $userRows[] = $users['email'];
            $userRows[] = $users['mobile'];
            $userRows[] = $users['type'];
            $userRows[] = $status;
            $userRows[] = '<button type="button" name="update" id="'.$users["id"].'" class="btn btn-warning btn-xs update">Update</button>';
            $userRows[] = '<button type="button" name="delete" id="'.$users["id"].'" class="btn btn-danger btn-xs delete" >Delete</button>';
            $userData[] = $userRows;
        }

        $output = array(
            "draw"				=>	intval($_POST["draw"]),
            "recordsTotal"  	=>  $numRows,
            "recordsFiltered" 	=> 	$numRows,
            "data"    			=> 	$userData
        );
        echo json_encode($output);
    }


    public function deleteUser(){
        if($_POST["userid"]) {
            $sqlUpdate = "
				UPDATE ".$this->userTable." SET status = 'deleted'
				WHERE id = '".$_POST["userid"]."'";
            mysqli_query($this->dbConnect, $sqlUpdate);
        }
    }
    public function getUser(){
        $sqlQuery = "
			SELECT * FROM ".$this->userTable." 
			WHERE id = '".$_POST["userid"]."'";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        echo json_encode($row);
    }

    public function updateUser() {
        if($_POST['userid']) {
            $updateQuery = "UPDATE ".$this->userTable." 
			SET first_name = '".$_POST["firstname"]."', last_name = '".$_POST["lastname"]."', email = '".$_POST["email"]."', mobile = '".$_POST["mobile"]."' , designation = '".$_POST["designation"]."', gender = '".$_POST["gender"]."', status = '".$_POST["status"]."', type = '".$_POST['user_type']."'
			WHERE id ='".$_POST["userid"]."'";
            $isUpdated = mysqli_query($this->dbConnect, $updateQuery);
        }
    }

    public function saveAdminPassword(){
        $message = '';
        if($_POST['password'] && $_POST['password'] != $_POST['cpassword']) {
            $message = "Password does not match the confirm password.";
        } else {
            $sqlUpdate = "
				UPDATE ".$this->userTable." 
				SET password='".md5($_POST['password'])."'
				WHERE id='".$_SESSION['adminUserid']."' AND type='administrator'";
            $isUpdated = mysqli_query($this->dbConnect, $sqlUpdate);
            if($isUpdated) {
                $message = "Password saved successfully.";
            }
        }
        return $message;
    }

    public function adminDetails () {
        $sqlQuery = "SELECT * FROM ".$this->userTable." 
			WHERE id ='".$_SESSION["adminUserid"]."'";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $userDetails = mysqli_fetch_assoc($result);
        return $userDetails;
    }

    public function addUser () {
        if($_POST["email"]) {
            $authtoken = $this->getAuthtoken($_POST['email']);
            $insertQuery = "INSERT INTO ".$this->userTable."(first_name, last_name, email, gender, password, mobile, designation, type, status, authtoken) 
				VALUES ('".$_POST["firstname"]."', '".$_POST["lastname"]."', '".$_POST["email"]."', '".$_POST["gender"]."', '".md5($_POST["password"])."', '".$_POST["mobile"]."', '".$_POST["designation"]."', '".$_POST['user_type']."', 'active', '".$authtoken."')";
            $userSaved = mysqli_query($this->dbConnect, $insertQuery);
        }
    }

    public function totalUsers ($status) {
        $query = '';
        if($status) {
            $query = " AND status = '".$status."'";
        }
        $sqlQuery = "SELECT * FROM ".$this->userTable." 
		WHERE id !='".$_SESSION["adminUserid"]."' $query";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $numRows = mysqli_num_rows($result);
        return $numRows;
    }
}
?>