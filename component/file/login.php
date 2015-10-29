<?php 
	include "component/config/koneksi.php";
?>
<html lang="en">
    <head>
        <title>SiZakat LAZISBA</title>
		<meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/bootstrap-glyphicons.css" />
        <link rel="stylesheet" href="css/unicorn.login.css" />
    </head>
    <body>
        <div id="container">
            <div id="logo">
                <img src="images/logo.png" alt="Logo LAZISBA" />
            </div>
            <div id="loginbox">            
                <form id="loginform" method='post' action="component/server/login.php">
    				<p>Enter username and password to continue.</p>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span><input class="form-control" type="text" placeholder="Username" name="username"/>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span><input class="form-control" type="password" placeholder="Password" name="password" />
                    </div>
                      
                    <hr />
                    <div class="form-actions">
                       <a href='index.php' class='btn btn-default btn-mini pull-left'>&laquo;Kembali Ke Halaman Depan</a> <div class="pull-right"><input type="submit" class="btn btn-default" value='login' name="login"/></div>
                    </div>
					<br />
				<div class="input-group">
				
				</div>
                </form>
				
            </div>
        </div>
        
        <script src="js/jquery.min.js"></script>  
        <script src="js/unicorn.login.js"></script> 
    </body>
</html>
