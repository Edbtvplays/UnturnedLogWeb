<?php 
include('class/General.php');
$user = new User();
$user->loginStatus();
$userDetail = $user->userDetails();
include('include/header.php');
?>
<title>UnturnedLog - Account</title>
<?php include('include/container.php');?>
<div class="container contact">
	<?php include('include/menu.php');?>
	<div class="table-responsive">		
		<div><span style="font-size:20px;">User Account Details:</span><div class="pull-right"><a href="edit_account.php">Edit Account</a></div>
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
				<th>Type</th>
				<td><?php echo $userDetail['type']; ?></td>
			</tr>		
		</table>
	</div>	
</div>	
<?php include('include/footer.php');?>