<?php
	include_once 'common.php';
?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $lang['PAGE_TITLE']; ?></title>

	<link href="favicon.ico" rel="shortcut icon">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown">
		
		<div class="alert alert-danger alert-dismissable" id="login-alert11" style="display:none;"></div>
		<div class="alert alert-success alert-dismissable" id="login-alert12" style="display:none;"></div>
	
        <div>
            <div>
                <img src="img/icoop_logo.png" class="img-responsive" style="margin: 50px 0 20px 0;" />
            </div>
         
            <div class="form-group" style="margin-top:30px;">
                <input type="email" id="username" onkeydown="if(event.keyCode==13) document.getElementById('valider').click()" class="form-control" placeholder="<?php echo $lang['LOGIN_USERNAME']; ?>" required="">
            </div>
            <div class="form-group">
                <input type="password" id="password" onkeydown="if(event.keyCode==13) document.getElementById('valider').click()" class="form-control" placeholder="<?php echo $lang['LOGIN_PASSWORD']; ?>" required="">
            </div>
			
            <button type="submit" onclick="longin_connexion()" id="valider" style="background-color: #FF0B04;color: #fff;" class="btn block full-width m-b"><?php echo $lang['LOGIN_BTN']; ?></button>
         
			<a href="reset_link.php"><small><?php echo $lang['LOGIN_FORGOT_PASS']; ?></small></a>
            <p class="m-t"> <small>&copy; 2018-<?php echo date('Y'); ?> <a target="_blanck" href="http://dev4impact.com/">dev4impact Ltd.</a> <br/> @iCoop.live - Version 1.0 </small> </p>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="js/jquery-2.1.1.js"></script>
    <script src="js/bootstrap.min.js"></script>

	<script src="js/login.js"></script>
	
</body>

</html>
