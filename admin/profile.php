<?php 
include('../class/General.php');
$user = new User();
$user->adminLoginStatus();
$message  = '';
$userDetail = $user->adminDetails();
include('include/header.php');
?>
<title>UnturnedLog - Admin Profile</title>
<link rel="stylesheet" href="css/style.css">
<?php include('include/container.php');?>
<div class="container contact">
	<?php include 'menus.php'; ?>
	<div class="table-responsive">		
		<div><span style="font-size:20px;">Admin Details:</span><div class="pull-right"></div>
		<table class="table table-boredered">		
			<tr>
				<th>Name</th>
				<td><?php echo $userDetail['first_name']." ".$userDetail['last_name']; ?></td>
			</tr>
			<tr>
				<th>Email</th>
				<td><?php echo $userDetail['email']; ?></td>
			</tr>
			<tr>
				<th>Password</th>
				<td>**********</td>
			</tr>
			<tr>
				<th>Gender</th>
				<td><?php echo $userDetail['gender']; ?></td>
			</tr>
			<tr>
				<th>Mobile</th>
				<td><?php echo $userDetail['mobile']; ?></td>
			</tr>
			<tr>
				<th>Designation</th>
				<td><?php echo $userDetail['designation']; ?></td>
			</tr>
			<tr>
				<th>User Type</th>
				<td><?php echo $userDetail['type']; ?></td>
			</tr>		
		</table>
	</div>
</div>	
<?php include('include/footer.php');?>