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
<script src="js/players.js"></script>
<?php include('include/container.php');?>
<div class="container contact">
	<?php include('menu.php');?>
    <class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-10">
                    <h3 class="panel-title">Players</h3>
                </div>
            </div>
        </div>
        <table id="userList" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Id</th>
                <th>SteamName</th>
                <th>CharacterName</th>
            </tr>
            </thead>
        </table>
    </div>
</div>	
<?php include('include/footer.php');?>