<?php 
include('class/General.php');
$user = new User();
$user->loginStatus();
$message = '';
if(!empty($_POST["update"]) && $_POST["update"]) {
	$message = $user->editAccount();
}
$userDetail = $user->userDetails();
include('include/header.php');
?>
<title>UnturnedLog - Edit Account</title>
<?php include('include/container.php');?>
<div class="container contact">
	<?php include('include/menu.php');?>
	<div>
		<div class="panel">
			<div class="panel-heading">
				<div class="panel-title">Edit Account Details</div>				
			</div>  
			<div class="panel-body col-md-7">
				<form class="form-horizontal" role="form" method="POST" action="">				
					<?php if($message != '') { ?>
						<div id="login-alert" class="alert alert-danger col-sm-12"><?php echo $message; ?></div>                            
					<?php } ?>	
					<div class="form-group">
						<label for="firstname" class="col-md-3 control-label">First Name*</label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="firstname" placeholder="First Name" value="<?php echo $userDetail['first_name'];?>" >
						</div>
					</div>
					<div class="form-group">
						<label for="lastname" class="col-md-3 control-label">Last Name</label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="lastname" placeholder="Last Name" value="<?php echo $userDetail['last_name'];?>" >
						</div>
					</div>					
					<div class="form-group">
						<label for="email" class="col-md-3 control-label">Email*</label>
						<div class="col-md-9">
							<input type="email" class="form-control" name="email" placeholder="Email Address" value="<?php echo $userDetail['email'];?>" required>
						</div>
					</div>
						<div class="col-md-offset-3 col-md-9">
							<button id="btn-signup" type="submit" name="update" value="update_account" class="btn btn-info"><i class="icon-hand-right"></i> &nbsp Save Changes</button>			
						</div>
					</div>							
				</form>
			 </div>
		</div>
	</div>	
</div>	
<?php include('include/footer.php');?>