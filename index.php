<?php 
include('class/General.php');
$user = new User();
$user->loginStatus();
include('include/header.php');
?>
<title>UnturnedLog - Home</title>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.js"></script>
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
<script type="text/javascript" src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        var usersData = $('#userList').DataTable({
            "lengthChange": false,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                url: "action.php",
                type: "POST",
                data: {action: 'listplayer'},
                dataType: "json"
            },
            "language": {
                "lengthMenu": "_MENU_",
                "search": ""
            },
            "columnDefs": [
                {
                    "targets": [0, 1, 2],
                    "orderable": false,
                },
            ],
            "pageLength": 25
        });
    });
</script>

<?php include('include/container.php');?>

<?php //function ShowServerInfos() { global $S_APIKEY ; ?>
<!--        --><?php //$json_String = file_get_contents("https://unturned-servers.net/api/?object=server@element=detail&key=". $S_APIKEY.""); $arr = json_decode($json_String, true); ?>
<!--        <div class ="ServerInfos">-->
<!--            <div class ="InfoS"><span class ="InfoName"><i class="fall fa-info-circle icon"></i>Name   </span><span class="InfoValue SName">--><?php //echo $arr["name"];?><!--</span></div>-->
<!--            <div class ="InfoS"><span class ="InfoName"><i class="fall fa-info-circle icon"></i>Address  </span><span class="InfoValue SName">--><?php //echo $arr["name"];?><!--</span></div>-->
<!--            <div class ="InfoS"><span class ="InfoName"><i class="fall fa-info-circle icon"></i>Status   </span><span class="InfoValue SName">--><?php //echo $arr["name"];?><!--</span></div>-->
<!--            <div class ="InfoS"><span class ="InfoName"><i class="fall fa-info-circle icon"></i>Map   </span><span class="InfoValue SName">--><?php //echo $arr["name"];?><!--</span></div>-->
<!--            <div class ="InfoS"><span class ="InfoName"><i class="fall fa-info-circle icon"></i>Location   </span><span class="InfoValue SName">--><?php //echo $arr["name"];?><!--</span></div>-->
<!--            <div class ="InfoS"><span class ="InfoName"><i class="fall fa-info-circle icon"></i>Players   </span><span class="InfoValue SName">--><?php //echo $arr["name"];?><!--</span></div>-->
<!--            <div class ="InfoS"><span class ="InfoName"><i class="fall fa-info-circle icon"></i>Version   </span><span class="InfoValue SName">--><?php //echo $arr["name"];?><!--</span></div>-->
<!--        </div>-->

<div class="container contact">
	<?php include('include/menu.php');?>
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