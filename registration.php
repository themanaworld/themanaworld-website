<?php

	require_once('recaptcha-php/recaptchalib.php');
	$publickey = "..."; // you got this from the signup page
	$privatekey = ":::";

  	include("includes/common.php");
	placeHeader("Registration");
	$showform = true;
  
	if (isset($_POST['register']) && $_POST['register'] == "true")
	{
		// handle registration  	
		if (!isset($_POST['username']) || strlen($_POST['username']) < 4)
		{
			$err = "Username is not given or too short!"; $showform = true;
		}
		else if (!isset($_POST['password1']) || strlen($_POST['password1']) < 4)
		{
			$err = "Password is not given or too short!"; $showform = true;
		}
		else if (!isset($_POST['password2']) || strlen($_POST['password2']) < 4)
		{
			$err = "Password is not given or too short!"; $showform = true;
		}
		else if ($_POST['password2'] != $_POST['password1'])
		{
			$err = "The given passwords don't match!"; $showform = true;
		}
		else
		{
			// check captcha
			$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

			if (!$resp->is_valid) 
			{
				$err = "The captcha was incorret!"; $showform = true;
			}
			else
			{
		
				// everything was fine, create account
				$showform = false;
				
				// create a new account with ladmin here....
			}
		}
		
		
	}
  
  	if ($showform)
  	{
  
?>

<p>With this form you can register for a new account.</p>

<form action="registration.php" method="post">

	<input type="hidden" name="register" value="true" />
	<table>
		<?php if (isset($err))
		{
			echo "<tr><td colspan=\"2\" style=\"border: 1px solid red; color: red;\">" . 
				$err . "</td></tr>";
		}
		?>
		<tr>
			<td>Username:</td>
			<td><input type="text" size="20" name="username" /></td>
		</tr>
		<tr>
			<td>Password:</td>
			<td><input type="password" size="20" name="password1" /></td>
		</tr>
		<tr>
			<td>Retype password:</td>
			<td><input type="password" size="20" name="password2" /></td>
		</tr>
		<tr>
			<td colspan="2">
				<?php echo recaptcha_get_html($publickey); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align:right">
				<input type="submit" value="Register" />
			</td>
		</tr>
	</table>
</form>


<?php

	} // end of showform
	else
	{
	?>
		<p>Your account has been created!</p>
	<?php }
	placeFooter();
?>

