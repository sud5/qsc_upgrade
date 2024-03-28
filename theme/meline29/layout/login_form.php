<?php
	if(!isloggedin() or isguestuser()){
?>

<form method="post" id="cf_login" class="loginform span3" action="<?php echo $CFG->wwwroot.'/login/index.php' ?>">
	<h2><?php print_string("login") ?></h2>
	<div class="username_label inlinetag">
		
	</div>
	
	<div class="usename_input inlinetag">
		<input type="text" name="username" value="" id="cs_login_username" placeholder="<?php print_string("username") ?>"/>
	</div>
	
	<div class="password_label inlinetag">
	
	</div>
	
	<div class="password_input inlinetag">
		<input type="password" name="password" value="password" id="login_password" placeholder="Password"/>
	</div> 
	
	<div class="rememberpass">
        <input type="checkbox" value="1" id="rememberusername" name="rememberusername">
        <label for="rememberusername">Remember username</label>
    </div>
	

<div class="submit_input inlinetag">
		
		<input type="submit" id="loginbtn" value="<?php print_string("login") ?>" />
		<div class="forgetpass"><a href="forgot_password.php"><?php print_string("forgotten") ?></a></div>
		
	</div>
	
</form>

             <form action="index.php" method="post" id="guestlogin">
                  <div class="guestform">                               
                      <input type="hidden" name="username" value="guest" />
                      <input type="hidden" name="password" value="guest" />
                      <input class="exbutton" type="submit" value="<?php print_string("loginguest") ?>" />                               
                      <div class="clearfix"></div>  
                  </div>
              </form>
<?php 
}else{
	?>
<?php
}
?>

			
