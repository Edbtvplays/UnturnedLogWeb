<div id="top-nav" class="navbar navbar-inverse navbar-static-top" style="background:#c4e3f3;color:white;border-color:white;">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">Menu</a>
            <a class="navbar-brand" href="#">Leaderboards</a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#"><i class="fa fa-user-circle"></i> <?php echo strtoupper($_SESSION['name']); ?> <span class="caret"></span></a>
                    <ul id="g-account-menu" class="dropdown-menu" role="menu">
                        <li><a href="account.php"><i class="fa fa-user-secret"></i> My Profile</a></li>
                        <li><a href="/admin"><i class="fa fa-user-secret"></i>Admin Area</a></li>
                    </ul>
                </li>
                <li><a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
            </ul>
        </div>
    </div>    
</div>
