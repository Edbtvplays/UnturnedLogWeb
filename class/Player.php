<?php
require('./User.php');

//class Players extends DbConfig
//{
//
//    protected $hostName;
//    protected $userName;
//    protected $password;
//    protected $dbName;
//    private $playerTable = 'Edbtvplays_UnturnedLog_Players';
//    private $dbConnect = false;
//
//    public function __construct()
//    {
//        if (!$this->dbConnect) {
//            $database = new dbConfig();
//            $this->hostName = $database->serverName;
//            $this->userName = $database->userName;
//            $this->password = $database->password;
//            $this->dbName = $database->dbName;
//            $conn = new mysqli($this->hostName, $this->userName, $this->password, $this->dbName);
//            if ($conn->connect_error) {
//                die("Error failed to connect to MySQL: " . $conn->connect_error);
//            } else {
//                $this->dbConnect = $conn;
//            }
//        }
//        parent::__construct();
//    }
//
//    public function getPlayerList()
//    {
//
//        $sqlQuery = "SELECT * FROM " . $this->playerTable . " WHERE ";
//
//        if (!empty($_POST["search"]["value"])) {
//            $sqlQuery .= '(Id LIKE "%' . $_POST["search"]["value"] . '%" ';
//            $sqlQuery .= ' OR SteamName LIKE "%' . $_POST["search"]["value"] . '%" ';
//            $sqlQuery .= ' OR CharacterName LIKE "%' . $_POST["search"]["value"] . '%" ';
//        }
//
//        if (!empty($_POST["order"])) {
//            $sqlQuery .= 'ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
//        } else {
//            $sqlQuery .= 'ORDER BY ID DESC ';
//        }
//        if ($_POST["length"] != -1) {
//            $sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
//        }
//
//        $result = mysqli_query($this->dbConnect, $sqlQuery);
//
//        $sqlQuery1 = "SELECT * FROM " . $this->playerTable;
//        $result1 = mysqli_query($this->dbConnect, $sqlQuery1);
//        $numRows = mysqli_num_rows($result1);
//
//        $playerData = array();
//        while ($player = mysqli_fetch_assoc($result)) {
//            $playerRows = array();
//
//            $playerRows[] = $player['ID'];
//            $playerRows[] = $player['CharacterName'];
//            $playerRows[] = $player['SteamName'];
//            $playerData[] = $playerRows;
//        }
//
//
//        $output = "hello";
//        console_log(json_encode($output));
//    }
//}