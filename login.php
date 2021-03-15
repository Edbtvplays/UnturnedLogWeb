<?php 
include('class/User.php');
$user = new User();
$errorMessage =  $user->login();
include('include/header.php');
?>
<title>UnturnedLog - Login</title>
<?php include('include/container.php');?>
<div class="container contact">	
	<h2>UnturnedLog - Please Login</h2>
	<div class="col-md-6">                    
		<div class="panel panel-info" >
			<div class="panel-heading">
				<div class="panel-title">Log In</div>                        
			</div> 
			<div style="padding-top:30px" class="panel-body" >
				<?php if ($errorMessage != '') { ?>
					<div id="login-alert" class="alert alert-danger col-sm-12"><?php echo $errorMessage; ?></div>                            
				<?php } ?>
				<form id="loginform" class="form-horizontal" role="form" method="POST" action="">                                    
					<div style="margin-bottom: 25px" class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
						<input type="text" class="form-control" id="loginId" name="loginId"  value="<?php if(isset($_COOKIE["loginId"])) { echo $_COOKIE["loginId"]; } ?>" placeholder="email">                                        
					</div>                                
					<div style="margin-bottom: 25px" class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
						<input type="password" class="form-control" id="loginPass" name="loginPass" value="<?php if(isset($_COOKIE["loginPass"])) { echo $_COOKIE["loginPass"]; } ?>" placeholder="password">
					</div>            
					<div class="input-group">
					  <div class="checkbox">
						<label>
						  <input  type="checkbox" id="remember" name="remember" <?php if(isset($_COOKIE["loginId"])) { ?> checked <?php } ?>> Remember me
						</label>
					  </div>
					</div>
					<div style="margin-top:10px" class="form-group">                               
						<div class="col-sm-12 controls">
						  <input type="submit" name="login" value="Login" class="btn btn-info">						  
						</div>						
					</div>
				</form>   
			</div>                     
		</div>  
	</div>
</div>	
<?php include('include/footer.php');?>