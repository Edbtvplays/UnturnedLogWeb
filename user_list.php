<?php 
include('./class/General.php');
$user = new User();
$user->adminLoginStatus();
include('./include/header.php');

?>
<title>UnturnedLog - Admin User List</title>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>

<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
<script type="text/javascript">
    $(document).ready(function(){
        var usersData = $('#userList').DataTable({
            "lengthChange": false,
            "processing":true,
            "serverSide":true,
            "bFilter": false,
            "order":[],
            "ajax":{
                url: "action.php",
                type:"POST",
                data: {action: 'listUser'},
                dataType: "json"
            },
            "columnDefs":[
                {
                    "targets":[0, 7, 8],
                    "orderable":false,
                },
            ],
            "pageLength": 10
        });
        $(document).on('click', '.delete', function(){
            var userid = $(this).attr("id");
            var action = "userDelete";
            if(confirm("Are you sure you want to delete this user?")) {
                $.ajax({
                    url:"action.php",
                    method:"POST",
                    data:{userid:userid, action:action},
                    success:function(data) {
                        usersData.ajax.reload();
                    }
                })
            } else {
                return false;
            }
        });
        $('#addUser').click(function(){
            $('#userModal').modal('show');
            $('#userForm')[0].reset();
            $('#passwordSection').show();
            $('.modal-title').html("<i class='fa fa-plus'></i> Add User");
            $('#action').val('addUser');
            $('#save').val('Add User');
        });

        $(document).on('click', '.update', function(){
            var userid = $(this).attr("id");
            var action = 'getUser';
            $.ajax({
                url:'action.php',
                method:"POST",
                data:{userid:userid, action:action},
                dataType:"json",
                success:function(data){
                    $('#userModal').modal('show');
                    $('#userid').val(data.id);
                    $('#firstname').val(data.first_name);
                    $('#lastname').val(data.last_name);
                    $('#email').val(data.email);
                    $('#password').val(data.password);
                    $('#passwordSection').hide();
                    if(data.status == 'active') {
                        $('#active').prop("checked", true);
                    } else if(data.gender == 'pending') {
                        $('#pending').prop("checked", true);
                    }
                    if(data.type == 'general') {
                        $('#general').prop("checked", true);
                    } else if(data.type == 'administrator') {
                        $('#administrator').prop("checked", true);
                    }
                    $('.modal-title').html("<i class='fa fa-plus'></i> Edit User");
                    $('#action').val('updateUser');
                    $('#save').val('Save');
                }
            })
        });
        $(document).on('submit','#userForm', function(event){
            event.preventDefault();
            $('#save').attr('disabled','disabled');
            var formData = $(this).serialize();
            $.ajax({
                url:"action.php",
                method:"POST",
                data:formData,
                success:function(data){
                    $('#userForm')[0].reset();
                    $('#userModal').modal('hide');
                    $('#save').attr('disabled', false);
                    usersData.ajax.reload();
                }
            })
        });
    });
</script>

<link rel="stylesheet" href="css/style.css">
<?php include('./include/container.php');?>
<div class="container contact">
	<?php include 'include/menu.php'; ?>
    <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
        <ul class="nav nav-pills nav-stacked" style="border-right:1px solid black">
            <li><a href="admindashboard.php"><i class="fa fa-dashboard"></i>Admin Dashboard</a></li>
            <li><a href="user_list.php"><i class="fa fa-tags"></i>User List</a></li>
        </ul>
    </div>
	<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">   
		<a href="#"><strong><span class="fa fa-dashboard"></span>User List</strong></a>
		<hr>		
		<div class="panel-heading">
			<div class="row">
				<div class="col-md-10">
					<h3 class="panel-title"></h3>
				</div>
				<div class="col-md-2" align="right">
					<button type="button" name="add" id="addUser" class="btn btn-success btn-xs">Add</button>
				</div>
			</div>
		</div>
		<table id="userList" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Email</th>
					<th>Role</th>
                    <th>Status</th>
                    <th></th>
					<th></th>
				</tr>
			</thead>
		</table>
	</div>
	<div id="userModal" class="modal fade">
    	<div class="modal-dialog">
    		<form method="post" id="userForm">
    			<div class="modal-content">
    				<div class="modal-header">
    					<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"><i class="fa fa-plus"></i> Edit User</h4>
    				</div>
    				<div class="modal-body">
						<div class="form-group">
							<label for="firstname" class="control-label">First Name*</label>
							<input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" required>							
						</div>
						<div class="form-group">
							<label for="lastname" class="control-label">Last Name</label>							
							<input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name">							
						</div>	   	
						<div class="form-group">
							<label for="lastname" class="control-label">Email*</label>							
							<input type="text" class="form-control"  id="email" name="email" placeholder="Email" required>							
						</div>	 
						<div class="form-group" id="passwordSection">
							<label for="lastname" class="control-label">Password*</label>							
							<input type="password" class="form-control"  id="password" name="password" placeholder="Password" required>							
						</div>
						<div class="form-group">
							<label for="gender" class="control-label">Status</label>							
							<label class="radio-inline">
								<input type="radio" name="status" id="active" value="active" required>Active
							</label>;
							<label class="radio-inline">
								<input type="radio" name="status" id="pending" value="pending" required>Pending
							</label>							
						</div>
						<div class="form-group">
							<label for="user_type" class="control-label">User Type</label>							
							<label class="radio-inline">
								<input type="radio" name="user_type" id="general" value="general" required>General
							</label>;
							<label class="radio-inline">
								<input type="radio" name="user_type" id="administrator" value="administrator" required>Administrator
							</label>							
						</div>	
    				</div>
    				<div class="modal-footer">
    					<input type="hidden" name="userid" id="userid" />
    					<input type="hidden" name="action" id="action" value="updateUser" />
    					<input type="submit" name="save" id="save" class="btn btn-info" value="Save" />
    					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    				</div>
    			</div>
    		</form>
    	</div>
    </div>
</div>	
<?php include('./include/footer.php');?>