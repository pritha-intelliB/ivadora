<?php include 'includes/session.php'; ?>
<?php
  if(isset($_SESSION['user'])){
    header('location: cart_view.php');
  }

  if(isset($_SESSION['captcha'])){
    $now = time();
    if($now >= $_SESSION['captcha']){
      unset($_SESSION['captcha']);
    }
  }

?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition register-page">
<div  style="background:url(site/images/pattern.png) repeat center center; height:100%; width:100%; position:absolute; top:0; z-index:-1"></div>
<div class="register-box">
  	<?php
      if(isset($_SESSION['error'])){
        echo "
          <div class='callout callout-danger text-center'>
            <p>".$_SESSION['error']."</p> 
          </div>
        ";
        unset($_SESSION['error']);
      }

      if(isset($_SESSION['success'])){
        echo "
          <div class='callout callout-success text-center'>
            <p>".$_SESSION['success']."</p> 
          </div>
        ";
        unset($_SESSION['success']);
      }
    ?>
  	<div class="register-box-body">
    	<p class="login-box-msg">Register a new membership</p>

    	<form action="register.php" method="POST" enctype="multipart/form-data">
        <input type="hidden"  name="newregister" value="yes">
          <div class="form-group has-feedback">
            <input type="text" class="form-control" name="firstname" placeholder="Firstname" value="<?php echo (isset($_SESSION['firstnamej'])) ? $_SESSION['firstnamej'] : '' ?>" required>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="text" class="form-control" name="lastname" placeholder="Lastname" value="<?php echo (isset($_SESSION['lastname'])) ? $_SESSION['lastname'] : '' ?>"  required>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
      		<div class="form-group has-feedback">
        		<input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo (isset($_SESSION['email'])) ? $_SESSION['email'] : '' ?>" required>
        		<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      		</div>
            
      		<div class="form-group has-feedback">
        		<input type="text" class="form-control" name="phone" placeholder="Phone" value="<?php echo (isset($_SESSION['contact_info'])) ? $_SESSION['contact_info'] : '' ?>" required>
        		<span class="glyphicon glyphicon-phone form-control-feedback"></span>
      		</div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" name="password" placeholder="Password" value="<?php echo (isset($_SESSION['password'])) ? $_SESSION['password'] : '' ?>" required>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" name="repassword" placeholder="Retype password" value="<?php echo (isset($_SESSION['repassword'])) ? $_SESSION['repassword'] : '' ?>" required>
            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="text" class="form-control" name="eventname" placeholder="Add Event Name" value="<?php echo (isset($_SESSION['eventname'])) ? $_SESSION['eventname'] : '' ?>" required>
            <span class="glyphicon"></span>
          </div>          
          <div class="form-group has-feedback">
          	Add Event Date
            <input type="date" class="form-control" name="eventdate" placeholder="Add Event Date" value="" required>
            <span class="glyphicon"></span>
          </div>
          <?php
            if(!isset($_SESSION['captcha'])){
              echo '
                <di class="form-group" style="width:100%;">
                  <div class="g-recaptcha" data-sitekey="6LeIxcgUAAAAADvRHn-4jZzrkFPWny0Bu_DtUq_0"></div>
                </di>
              ';
            }
          ?>
          <hr>
      		<div class="row">
    			<div class="col-xs-4">
                
          			<input type="submit" class="btn btn-primary btn-block btn-flat" name="signup" value="signup">
        		</div>
      		</div>
    	</form>
      <br>
      <a href="login.php">I already have a membership</a><br>
      <a href="index.php"><i class="fa fa-home"></i> Home</a>
  	</div>
</div>
	
<?php include 'includes/scripts.php' ?>
</body>
</html>