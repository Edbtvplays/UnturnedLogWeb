<?php 
include('./class/General.php');
$user = new User();
$user->adminLoginStatus();

include('./include/header.php');
?>
<title>UnturnedLog - Admin Dashboard</title>
<link rel="stylesheet" href="css/style.css">
<?php include('./include/container.php');?>
<div class="container contact">
	<?php include 'include/menu.php'; ?>
    <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
        <ul class="nav nav-pills nav-stacked" style="border-right:1px solid black">
            <li><a href="admindashboard.php"><i class="fa fa-dashboard"></i>Admin Dashboard</a></li>
            <li><a href="user_list.php"><i class="fa fa-tags"></i> User List</a></li>
        </ul>
    </div>
	<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">   
		<strong><span class="fa fa-dashboard"></span>Admin Dashboard</strong>
		<hr>		
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-body bk-primary text-light">
								<div class="stat-panel text-center">
									<div class="stat-panel-number h1 "><?php echo $user->totalUsers(""); ?></div>
									<div class="stat-panel-title text-uppercase">Total Users</div>
								</div>
							</div>											
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-body bk-success text-light">
								<div class="stat-panel text-center">
									<div class="stat-panel-number h1 "><?php echo $user->totalUsers('active'); ?></div>
									<div class="stat-panel-title text-uppercase">Total Active Users</div>
								</div>
							</div>											
						</div>
					</div>		
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-body bk-success text-light">
								<div class="stat-panel text-center">
									<div class="stat-panel-number h1 "><?php echo $user->totalUsers('pending'); ?></div>
									<div class="stat-panel-title text-uppercase">Total Pending Users</div>
								</div>
							</div>											
						</div>
					</div>													
					<div class="col-md-3">
						<div class="panel panel-default">
							<div class="panel-body bk-danger text-light">
								<div class="stat-panel text-center">												
									<div class="stat-panel-number h1 "><?php echo $user->totalUsers('deleted'); ?></div>
									<div class="stat-panel-title text-uppercase">Total Deleted Users</div>
								</div>
							</div>											
						</div>
					</div>							
				</div>
			</div>
		</div>		
	</div>
</div>	
<?php include('./include/footer.php');?>