<?php
session_start();

// Class which holds the database configuration so i dont need to change the values in multiple places.
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

// This class is used in one instance for sorting an array by value, i had more plans for this but run out of time.
class multiSort
{
    protected $key;    //key in your array

    //runs the sort, and returns sorted array
    public function run ($myarray, $key_to_sort, $type_of_sort = '')
    {
        $this->key = $key_to_sort;

        if ($type_of_sort == 'desc')
            uasort($myarray, array($this, 'myreverse_compare'));
        else
            uasort($myarray, array($this, 'mycompare'));

        return $myarray;
    }

    //for ascending order
    private function mycompare($x, $y)
    {
        if ( $x[$this->key] == $y[$this->key] )
            return 0;
        else if ( $x[$this->key] < $y[$this->key] )
            return -1;
        else
            return 1;
    }

    //for descending order
    private function myreverse_compare($x, $y)
    {
        if ( $x[$this->key] == $y[$this->key] )
            return 0;
        else if ( $x[$this->key] > $y[$this->key] )
            return -1;
        else
            return 1;
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

    // Gets the player list on the home page.
    public function getPlayerList() {

        // Base SQL Query to get all the Players.
        $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Players ";

        // If there is a search value add to SQL to narrow down result to the search value.
        if(!empty($_POST["search"]["value"])) {
            $Search = $this->dbConnect->real_escape_string($_POST["search"]["value"]);
            $sqlQuery = 'SELECT * FROM Edbtvplays_UnturnedLog_Players WHERE Id = '.$Search.' OR SteamName = '.$Search.'  ';
        }

        // Needed for pagination for datatables, adds to the SQL statement with the amo8nt to display for that page.
        if($_POST["length"] != -1){
            $sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }

        $result = mysqli_query($this->dbConnect, $sqlQuery);

        // Tallys the amount of rows needed for Datatables.
        $numRows = mysqli_num_rows($result);

        $playerData = array();

        // For each of the Player rows returned.
        while($player = mysqli_fetch_assoc($result) ) {

            // Creates New Array for this row.
            $playerRows = array();

            // Adds Link to that users specific page aswell as the display for there ID into PlayerRows
            $playerRows[] = '<a href="http://unturned-log.test/players.php?player='.$player['Id'].'">'.$player['Id'].'</a>';

            // Adds Charatcer Name into the Array for this row.
            $playerRows[] = $player['CharacterName'];

            // Adds Steam Name into the array for this row.
            $playerRows[] = $player['SteamName'];

            // Adds this rows array to the Overall player Data
            $playerData[] = $playerRows;
        }

        // Constructs return for Datatables
        $output = array(
            "draw"				=>	intval($_POST["draw"]),
            "recordsTotal"  	=>  $numRows,
            "recordsFiltered" 	=> 	$numRows,
            "data"    			=> 	$playerData
        );

        // Returns the value
        echo json_encode($output);
    }

    public function ranking($type) {

        $PlayerssqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Players;";
        $Playersresult = mysqli_query($this->dbConnect, $PlayerssqlQuery);


        // Creates a Nested Array with all appropriate Values and assigns the players steam name to the Name value.
        $LeaderBoard = array();
        while($Player = mysqli_fetch_assoc($Playersresult) ) {
            $LeaderBoard += [$Player['Id'] => array("Name" => $Player['SteamName'], "Ranking" => 0, "Kills" => 0, "Deaths" => 0, "HeadShots" => 0)];
        }

        // Populated the array inside the Leaderboard array for that user with the amount of kills they have.
        $KillsQuery= "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE EventType = 'Player Kill';";
        $Killsresult = mysqli_query($this->dbConnect, $KillsQuery);
        while($Kills = mysqli_fetch_assoc($Killsresult) ) {
            $LeaderBoard[$Kills['PlayerId']]["Kills"] = $LeaderBoard[$Kills['PlayerId']]["Kills"] + 1;
        }

        // Populated the array inside the Leaderboard array for that user with the amount of Deaths they have.
        $DeathsQuery= "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE EventType = 'Death';";
        $Deathsresult = mysqli_query($this->dbConnect, $DeathsQuery);
        while($Deaths = mysqli_fetch_assoc($Deathsresult) ) {
            $LeaderBoard[$Deaths['PlayerId']]["Deaths"] = $LeaderBoard[$Deaths['PlayerId']]["Deaths"] + 1;
        }

        // Populated the array inside the Leaderboard array for that user with the amount of headshots they have.
        $HeadshotsQuery= "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE EventType = 'Player Headshot';";
        $Headshotsresult = mysqli_query($this->dbConnect, $HeadshotsQuery);
        while($Headshots = mysqli_fetch_assoc($Headshotsresult) ) {
            $LeaderBoard[$Headshots['PlayerId']]["HeadShots"] = $LeaderBoard[$Headshots['PlayerId']]["HeadShots"] + 1;
        }

        // Need to sort the Associative Array by Kills.
        $Multisort = new multisort();


        if ($type == "Kills") {
            $LeaderBoard = $Multisort->run($LeaderBoard, "Kills", "desc");
        } else if ($type == "Deaths") {
            $LeaderBoard = $Multisort->run($LeaderBoard, "Deaths", "desc");
        }

        $Ranking = 1;

        foreach ($LeaderBoard as $key => $val) {
            $LeaderBoard[$key]["Ranking"] = $Ranking.date('S',mktime(1,1,1,1,( (($Ranking>=10)+($Ranking>=20)+($Ranking==0))*10 + $Ranking%10) ));;
            $Ranking += 1;
        }

        return $LeaderBoard;

    }

    public function Leaderboard($type) {

        $LeaderBoard = $this->ranking($type);

        $search = $this->dbConnect->real_escape_string($_POST["search"]["value"]);

        if(!empty($search)){
            $LeaderBoard = $this->array_search($LeaderBoard, $search, ["Name", "Ranking"]);
        }

        $numRows = 0;
        $playerData = array();

        // This should only be completed if it isnt a search value. Otherwise perfom a search and then p
        foreach ($LeaderBoard as $key => $val) {
            $playerRows = array();
            $numRows += 1;
            $playerRows[] = $val["Ranking"];
            $playerRows[] = '<a href="http://unturned-log.test/players.php?player='.$key.'">'.$val["Name"].'</a>';
            if ($type == "Kills") {
                $playerRows[] = $val["Kills"];
            } Else if ($type == "Deaths") {
                $playerRows[] = $val["Deaths"];
            }
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

    public function array_search($array, $searchterm, $valuetype) {
        $newarray = array();
        foreach ($array as $key => $val) {
            foreach ($valuetype as $value) {
                if ($value == "Id") {
                    if($array[$key] == intval($searchterm)) {
                        $newarray += [$key => $array[$key]];
                    }
                }
                else {
                    if ($array[$key][$value] === $searchterm ) {
                        $newarray += [$key => $array[$key]];
                    }
                }
            }
        }
        return $newarray;
    }


    public function getPlayerEvents() {

        // Escapes the string for the ID.
        $id= $this->dbConnect->real_escape_string($_POST["id"]);

        // If the URL is not
        if (strlen($id) != 17) {
            // Set Error message to True and then Return
            return 0;
        }

        // Base Search query if nothing else is applied.
        $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = '".$id."' ";

        // If there is a search request.

        $search = $this->dbConnect->real_escape_string($_POST["search"]["value"]);
        if(!empty($search)){
            $sqlQuery = 'SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = '.$id.' AND EventType = '.$search.';';
        }

        // If there is order in the value order by the column value otherwise always order by EventTime.
        if(!empty($_POST["order"])){
            $sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
        } else {
            $sqlQuery .= 'ORDER BY EventTime DESC ';
        }

        // Needed for pagination for datatables, adds to the SQL statement with the amount to display for that page.
        if($_POST["length"] != -1){
            $sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }

        // Gets the result from the above SQL Query
        $result = mysqli_query($this->dbConnect, $sqlQuery);

        // Gets the total ammount of rows in the database for this user as its used for datatables list and pagination.
        $totalquery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = ".$id.";" ;
        $totalresult = mysqli_query($this->dbConnect, $totalquery);

        $EventData = array();

        // Gets the total rows by tallying the amount of rows returned in the above SQL.
        $totalrows = mysqli_num_rows($totalresult);

        // Loops through each returned row.
        while($Event = mysqli_fetch_assoc($result) ) {
            // Creates new Array for it.
            $EventRows = array();
            // Adds EventType to Array for this Row.
            $EventRows[] = $Event['EventType'];
            // Adds EventData to Array for this Row.
            $EventRows[] = $Event['EventData'];
            // Inner join to get the server name from the servers table using the ID in the Events table.
            $innerjoin = "SELECT Name FROM Edbtvplays_UnturnedLog_Servers INNER JOIN Edbtvplays_UnturnedLog_Events ON Edbtvplays_UnturnedLog_Servers.Id = '".$Event['ServerId']."';";
            $server = mysqli_query($this->dbConnect, $innerjoin);
            // Adds Event time to Array for this Row.
            $EventRows[] = $Event['EventTime'];

            // Adds Server Name to Array for this Row
            while($Server = mysqli_fetch_assoc($server) ) {
                $EventRows[] = $Server['Name'];
            }

            // Adds the Array generated for this run into the EventData Array.
            $EventData[] = $EventRows;
        }

        // Constructs return for Datatables
        $output = array(
            "draw"				=>	intval($_POST["draw"]),
            "recordsTotal"  	=>  $totalrows,
            "recordsFiltered" 	=> 	$totalrows,
            "data"    			=> 	$EventData
        );


        // Returns Value
        echo json_encode($output);
    }

    public function CheckPlayerInput($type)
    {

        $id = $this->dbConnect->real_escape_string($_GET["player"]);

        $ErrMessage = "";
        if ($type == "Internal") {
            if (!$id) {
                return False;
            }
            // If the URL is not
            if (strlen($id) != 17) {
                // Set Error message to True and then Return
                return False;
            }
            if (is_numeric($id) != 1) {
                // Set Error message to True then return
                return False;
            }
            return True;
        } else {
            if (!$id) {
                $ErrMessage = "No Player ID Provided";
                return $ErrMessage;
            }
            // If the URL is not
            if (strlen($id) != 17) {
                // Set Error message to True and then Return
                $ErrMessage = "The Player ID Provided is not of Valid Length";
                return $ErrMessage;
            }
            if (is_numeric($id) != 1) {
                // Set Error message to True then return
                $ErrMessage = "The Player ID Provided is not of Valid Format";
                return $ErrMessage;
            }
            Return "";
        }

    }

    // This gets statistics which require tallying of multiple rows in the Events table.
    public function GetStatistic($statistic) {

        $sqlQuery = "";

        // Escapes string for use in MYSQL
        $id = $this->dbConnect->real_escape_string($_GET["player"]);

        if ($this->CheckPlayerInput("Internal") == False) {
            return 0;
        }

        // The next section check the value parsed into this function to see what SQL Query it needs to run to return the appropriate statistic.
        if ($statistic == "CHAT_MESSAGES") {
            $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = ".$id." AND EventType = 'Chat Message'";
        } else if ($statistic == "KILLED_ZOMBIES") {
            $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = ".$id." AND EventType = 'Killed Zombie'";
        } else if ($statistic == "KILLED_MEGA_ZOMBIES") {
            $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = ".$id." AND EventType = 'Killed Mega Zombie'";
        } else if ($statistic == "FOUND_PLANTS") {
            $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = ".$id." AND EventType = 'Found Plants'";
        } else if ($statistic == "FOUND_RESOURCES") {
            $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = " .$id. " AND EventType = 'Found Resource'";
        } else if ($statistic == "FARMED_RESOURCES") {
                $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = ".$id." AND EventType = 'Resource harvested'";
        } else if ($statistic == "PLAYER_HEADSHOTS") {
            $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = ".$id." AND EventType = 'Player Headshot'";
        } else if ($statistic == "FISH_CAUGHT") {
            $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = ".$id." AND EventType = 'Fish Caught'";
        } else if ($statistic == "BUILDABLE_PLACED") {
            $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = ".$id." AND EventType = 'Placed Buildable'";
        } else if ($statistic == "PUNISHMENTS") {
            $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = ".$id." AND EventType = 'Player Banned'";
        } else if ($statistic == "PLAYER_KILLS") {
            $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = ".$id." AND EventType = 'Player Kill'";
        } else if ($statistic == "PLAYER_DEATHS") {
            $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = ".$id." AND EventType = 'Death'";
        } else if ($statistic == "PLAYER_TELEPORTS") {
            $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = ".$id." AND EventType = 'Teleported'";
        }

        // Check to see if the other parameter provided is valid to weather or not to actually return the value from it. This is used for error messages.
        $result = mysqli_query($this->dbConnect, $sqlQuery);

        // Checks if the Query worked. If not return 0
        if(!$result) {
            return 0;
        }
        $amount = mysqli_num_rows($result);
        return ($amount);
    }

    // This function is specifically for getting the profile picture hash.
    public function GetHash() {
        $id = $this->dbConnect->real_escape_string($_GET["player"]);

        if ($this->CheckPlayerInput("Internal") == False) {
            return 0;
        }

        // SQL Query for getting the PFP Hash from the Players Table.
        $sqlQuery = "SELECT ProfilePictureHash FROM Edbtvplays_UnturnedLog_Players WHERE Id = ".$id.";";
        $result = mysqli_query($this->dbConnect, $sqlQuery);

        // Sometimes this may be empty due to the plugin needed a steam API key which isnt neccecary for the plugin to operate in the configuration file.
        if (!$result) {
            return " ";
        }

        // Creates new Array needed for accessing data.
        $PlayerData = array();

        // For each return (although there should oinly be one this is the easiest method od
        while($Player = mysqli_fetch_assoc($result) ) {
            // Adds the Profile picture to the Player rows array
            $PlayerRows[] = $Player['ProfilePictureHash'];

            // Adds the Array for this row to the External Array.
            $PlayerData = $PlayerRows;
        }

        // Returns the Playerdata
        return $PlayerData[0];

    }

    // This Function is for getting information which doesnt involve tallying of statistics for more information type displays.
    public function GetInformation($information) {


        $id = $this->dbConnect->real_escape_string($_GET["player"]);

        if ($this->CheckPlayerInput("Internal") == False) {
            return 0;
        }

        $sqlQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Players WHERE Id = ".$id.";";
        $result = mysqli_query($this->dbConnect, $sqlQuery);

        // Generates the PlayerData array which is filled below depending on the $Information variable passed into this function
        $PlayerData = array();

        if ($information == "PLAYER_IP") {

            // Loops through the result (Although there should only be one row this is the easiest way to do it) All of these ifs are similar.
            while($Player = mysqli_fetch_assoc($result) ) {
                // Creates the PlayerRows Array for this row.
                $PlayerRows = array();

                // Adds the IP to the Array for Player Rows
                $PlayerRows[] = $Player['Ip'];

                // Adds the PlayerRow for this Database Row to the Main PlayerData Array.
                $PlayerData = $PlayerRows;
            }

            // Changes the return from the SQL to a readble IP Address.
            $ip = long2ip($PlayerData[0]);

            // Returns the IP
            return($ip);
        } else if ($information == "TOTAL_PLAYTIME") {
            while($Player = mysqli_fetch_assoc($result) ) {
                $PlayerRows = array();
                $PlayerRows[] = $Player['TotalPlaytime'];
                $PlayerData = $PlayerRows;
            }
            // Changes the Date into a readable format.
            $playtime = gmdate("H:i:s", $PlayerData[0]);
            return($playtime);

        } else if ($information == "LAST_PLAYED") {
            while($Player = mysqli_fetch_assoc($result) ) {
                $PlayerRows = array();
                $PlayerRows[] = $Player['LastLoginGlobal'];
                $PlayerData = $PlayerRows;
            }
            return($PlayerData[0]);
        } else if ($information == "CHARACTER_NAME") {
            while($Player = mysqli_fetch_assoc($result) ) {
                $PlayerRows = array();
                $PlayerRows[] = $Player['CharacterName'];
                $PlayerData = $PlayerRows;
            }
            return($PlayerData[0]);
        } else if ($information == "STEAM_NAME") {
            while($Player = mysqli_fetch_assoc($result) ) {
                $PlayerRows = array();
                $PlayerRows[] = $Player['SteamName'];
                $PlayerData = $PlayerRows;
            }
        } else if ($information == "KDR") {
            $KillsQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = ".$id." AND EventType = 'Player Kill'";
            $Killsresult = mysqli_query($this->dbConnect, $KillsQuery);
            $Kills = mysqli_num_rows($Killsresult);

            $DeathsQuery = "SELECT * FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = ".$id." AND EventType = 'Death'";
            $Deathsresult = mysqli_query($this->dbConnect, $DeathsQuery);
            $Deaths= mysqli_num_rows($Deathsresult);

            $KDR = $Kills/$Deaths;

            return (round($KDR, 2));

        } else if ($information == "LAST_SERVER") {
            while ($Player = mysqli_fetch_assoc($result)) {
                $PlayerRows = array();

                // Inner join needed to join the Server ID from the Players table to get the Server name from the Servers table.
                $innerjoin = "SELECT Name FROM Edbtvplays_UnturnedLog_Servers INNER JOIN Edbtvplays_UnturnedLog_Players ON Edbtvplays_UnturnedLog_Servers.Id = '" . $Player['ServerId'] . "';";
                $server = mysqli_query($this->dbConnect, $innerjoin);
                while ($Server = mysqli_fetch_assoc($server)) {
                    $PlayerRows[] = $Server['Name'];
                }
                $PlayerData = $PlayerRows;
            }
        } else if ($information == "KILLS_RANKING") {
            $Ranking = $this->ranking("Kills");
            return $Ranking[$id]["Ranking"];
        }
        return($PlayerData[0]);
    }

    // THis is for the Kills graph on the players individual pages.
    public function LineGraph($type, $Statistic)
    {
        $sqlQuery = "";
        if ($type == "Individual") {

            $id = $this->dbConnect->real_escape_string($_GET["player"]);

            // Checks if the ID entered in the URL is valid.
            if ($this->CheckPlayerInput("Internal") == False) {
                return 0;
            }

            if ($Statistic == "Kills/Deaths") {
                // SQL Query for selecting the Events over the last 7 days.
                $sqlQuery = "SELECT EventType, EventTime FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = " . $id . " AND EventType = 'Player Kill' AND EventTime >= now() - interval 7 day OR PlayerId = " . $id . " AND EventType = 'Death' AND EventTime >= now() - interval 7 day;";
            }
        } else if ($type == "Global") {
            if ($Statistic == "Kills/Deaths") {
                $sqlQuery = "SELECT EventType, EventTime FROM Edbtvplays_UnturnedLog_Events WHERE EventType = 'Player Kill' AND EventTime >= now() - interval 7 day OR EventType = 'Death' AND EventTime >= now() - interval 7 day;";
            }
        }

        // Runs the SQL Query.
        $result = mysqli_query($this->dbConnect, $sqlQuery);

        // Creates two Arrays one for kills one for deaths.
        $Kills = array();
        $Deaths = array();

        // Fills the associative arrays for the dates as the keys.
        for ($i=0; $i<7; $i++)
        {
            $date =  date("Y-m-d", strtotime($i." days ago"));
            $Kills += [$date => 0];
            $Deaths += [$date => 0];
        }

        // Loops through the return of the SQL and for each event checks the type and then increments the associative array where the key is that event date for that type.
        while ($rowData = mysqli_fetch_array($result)) {
            If ($rowData["EventType"] == "Death") {
                $Deaths[(date("Y-m-d", strtotime($rowData["EventTime"])))] = $Deaths[(date("Y-m-d", strtotime($rowData["EventTime"])))] + 1;
            }
            If ($rowData["EventType"] == "Player Kill") {
                $kills[(date("Y-m-d", strtotime($rowData["EventTime"])))] = $Kills[(date("Y-m-d", strtotime($rowData["EventTime"])))] + 1;
            }
        }

        // Creates the array out of the associative arrays.
        $output = array(
            "Kills" => array($Kills),
            "Deaths" => array($Deaths)
        );

        // Returns the value
        return(json_encode($output));
    }



    public function BarGraph($type, $statistic)
    {
        $sqlQuery = "";
        if ($type == "Individual") {
            // Escapes ID Entered in URL.
            $id = $this->dbConnect->real_escape_string($_GET["player"]);

            // Checks if the ID entered in the URL is valid.
            if ($this->CheckPlayerInput("Internal") == False) {
                return 0;
            }

            if ($statistic == "ZombieKills") {
                $sqlQuery = "SELECT EventTime FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = " . $id . " AND EventType = 'Killed Zombie' AND EventTime >= now() - interval 7 day;";
            } else if ($statistic == "Connected") {
                $sqlQuery = "SELECT EventTime FROM Edbtvplays_UnturnedLog_Events WHERE PlayerId = " . $id . " AND EventType = 'Connected' AND EventTime >= now() - interval 7 day;";
            }
        } else if ($type = "Global") {
            if ($statistic == "ZombieKills") {
                $sqlQuery = "SELECT EventTime FROM Edbtvplays_UnturnedLog_Events WHERE EventType = 'Killed Zombie' AND EventTime >= now() - interval 7 day;";
            } else if ($statistic == "Connected") {
                $sqlQuery = "SELECT EventTime FROM Edbtvplays_UnturnedLog_Events WHERE EventType = 'Connected' AND EventTime >= now() - interval 7 day;";
            }
        }


        // SQL Query for selecting the Events over the last 7 days.

        // Runs the SQL Query.
        $result = mysqli_query($this->dbConnect, $sqlQuery);

        // Creates one Array for the Zombie kills.
        $Construct = array();

        // Fills the associative arrays for the dates as the keys.
        for ($i=0; $i<7; $i++)
        {
            $date =  date("Y-m-d", strtotime($i." days ago"));
            $Construct  += [$date => 0];
        }

        while ($rowData = mysqli_fetch_array($result)) {
            // For each return Check if Kill, If kill get date then add
            $Construct[(date("Y-m-d", strtotime($rowData["EventTime"])))] = $Construct[(date("Y-m-d", strtotime($rowData["EventTime"])))] + 1;
        }

        $output = array(
            "Return"		=>	$Construct
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


    // FUnction for checking the user status
    public function loginStatus (){

        // If the session user id is empty redirect to login.
        if(empty($_SESSION["userid"])) {
            header("Location: login.php");
        }
    }

    // Used to login to the application
    public function login(){
        $errorMessage = '';

        // If the boxes are not empty
        if(!empty($_POST["login"]) && $_POST["loginId"]!=''&& $_POST["loginPass"]!='') {

            $loginId = $this->dbConnect->real_escape_string($_POST['loginId']);

            $password = $this->dbConnect->real_escape_string($_POST['loginPass']);

            if(isset($_COOKIE["loginPass"]) && $_COOKIE["loginPass"] == $password) {
                $password = $_COOKIE["loginPass"];
            } else {
                // Encrypys the password using MD5
                $password = md5($password);
            }

            // SQL Query for getting the user checking the email and password is equal to the encrypted one.
            $sqlQuery = "SELECT * FROM user WHERE email='".$loginId."' AND password='".$password."' AND status = 'active'";
            $resultSet = mysqli_query($this->dbConnect, $sqlQuery);

            // Gets the rows returned (Should only be one)
            $isValidLogin = mysqli_num_rows($resultSet);

            // Would only return something if it was a valid login.

            if($isValidLogin){
                // Checks if the remember was ticked or not
                if(!empty($_POST["remember"]) && $_POST["remember"] != '') {

                    // If so create a cookie for a really long time to be rememberde.
                    setcookie ("loginId", $loginId, time()+ (10 * 365 * 24 * 60 * 60));
                    setcookie ("loginPass",	$password,	time()+ (10 * 365 * 24 * 60 * 60));

                    // Otherwise do not set a cookie.
                } else {
                    $_COOKIE['loginId' ]='';
                    $_COOKIE['loginPass'] = '';
                }
                $userDetails = mysqli_fetch_assoc($resultSet);

                // Create a Session using the User details from the result set.
                $_SESSION["userid"] = $userDetails['id'];
                $_SESSION["name"] = $userDetails['first_name']." ".$userDetails['last_name'];

                if ($userDetails['type'] == "administrator") {
                    $_SESSION["adminUserid"] = $userDetails['id'];
                }
                // Also sets the header to a new location to redirect to there.
                header("location: index.php");

                // If not a valid login set the error message to invalid login.
            } else {
                $errorMessage = "Invalid login!";
            }

            // If the user entered nothing.
        } else if(!empty($_POST["loginId"])){
            $errorMessage = "Enter Both user and password!";
        }

        // Returns the Error message although there might not be one.
        return $errorMessage;
    }

    // This is the check to see if there a administrator, if not redirect them to login
    public function adminLoginStatus (){
        if(empty($_SESSION["adminUserid"])) {
            header("Location: http://unturned-log.test/login.php");
        }
    }


    // Function for getting the auth token.
    public function getAuthtoken($email) {
        $code = md5(889966);
        $authtoken = $code."".md5($email);
        return $authtoken;
    }

    // Function from retriving the User details from the DN
    public function userDetails () {
        $sqlQuery = "SELECT * FROM user WHERE id ='".$_SESSION["userid"]."';";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        return mysqli_fetch_assoc($result);
    }

    // Function used for editing the account.
    public function editAccount () {
        $message = '';
        $updateQuery = "UPDATE user SET first_name = '".$this->dbConnect->real_escape_string($_POST["firstname"])."', last_name = '".$this->dbConnect->real_escape_string($_POST["lastname"])."', email = '".$this->dbConnect->real_escape_string($_POST["email"])."' WHERE id ='".$_SESSION["userid"]."';";
        $isUpdated = mysqli_query($this->dbConnect, $updateQuery);
        if($isUpdated) {
            $_SESSION["name"] = $_POST['firstname']." ".$_POST['lastname'];
            $message = "Account details saved.";
        }
        return $message;
    }

    // Function for people to update passwords  on there own accounts.
    public function savePassword(){
        $message = '';
        if($_POST['password'] && $_POST['password'] != $_POST['cpassword']) {
            $message = "Password does not match the confirm password.";
        } else {
            $password = $this->dbConnect->real_escape_string($_POST['password']);
            $sqlUpdate = "UPDATE user SET password='".md5($password)."' WHERE id='".$_SESSION['userid']."' AND type='administrator';";
            $isUpdated = mysqli_query($this->dbConnect, $sqlUpdate);
            if($isUpdated) {
                $message = "Password saved successfully.";
            }
        }
        return $message;
    }


    // TODO: Fix the Displaying of the Userlist.
    // Gets the list of users.
    public function getUserList(){

        $sqlQuery = "SELECT * FROM user WHERE id !='1'";

        if(!empty($_POST["order"])){
            $sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
        } else {
            $sqlQuery .= 'ORDER BY id DESC ';
        }
        if($_POST["length"] != -1){
            $sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }
        $result = mysqli_query($this->dbConnect, $sqlQuery);

        $sqlQuery1 = "SELECT * FROM user WHERE id !='".$_SESSION['adminUserid']."' ";
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
            $userRows[] = $users['email'];
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


    // Deletes a User from the DB by setting its status to deleted.
    public function deleteUser(){
        if($_POST["userid"]) {
            $sqlUpdate = "UPDATE user SET status = 'deleted' WHERE id = '".$this->dbConnect->real_escape_string($_POST["userid"])."'";
            mysqli_query($this->dbConnect, $sqlUpdate);
        }
    }

    // Get user info from the DB
    public function getUser(){
        $sqlQuery = " SELECT * FROM user WHERE id = '".$this->dbConnect->real_escape_string($_POST["userid"])."';";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        echo json_encode($row);
    }

    // Function for updating user information on the admin side.
    public function updateUser() {
        if($_POST['userid']) {
            $updateQuery = "UPDATE user SET first_name = '".$this->dbConnect->real_escape_string($_POST["firstname"])."', last_name = '".$this->dbConnect->real_escape_string($_POST["lastname"])."', email = '".$this->dbConnect->real_escape_string($_POST["email"])."', status = '".$_POST["status"]."', type = '".$_POST['user_type']."' WHERE id ='".$_POST["userid"]."'";
            mysqli_query($this->dbConnect, $updateQuery);
        }
    }


    // TODO: Fix Add User on Admin Side
    public function addUser () {
        if($_POST["email"]) {
            $authtoken = $this->getAuthtoken($_POST['email']);
            $insertQuery = "INSERT INTO user (first_name, last_name, email, password, type, status, authtoken) VALUES ('".$this->dbConnect->real_escape_string($_POST["firstname"])."','".$this->dbConnect->real_escape_string($_POST["lastname"])."','".$this->dbConnect->real_escape_string($_POST["email"])."','".md5($_POST["password"])."','".$this->dbConnect->real_escape_string($_POST['user_type'])."', 'active', '".$authtoken."')";
            mysqli_query($this->dbConnect, $insertQuery);
        }
    }

    // Used for the Statistics of total users.
    public function totalUsers ($status) {
        $query = '';
        if($status) {
            $query = "WHERE status = '".$status."'";
        }
        $sqlQuery = "SELECT * FROM user $query";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $numRows = mysqli_num_rows($result);
        return $numRows;
    }
}
?>