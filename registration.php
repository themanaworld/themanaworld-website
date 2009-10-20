<?php

	require_once('includes/libs/libmysql.php');
	require_once('includes/models/account.php');
	require_once('recaptcha-php/recaptchalib.php');
	
	$publickey      = "6LexmAQAAAAAAJD-07K2pF5RvTfIdRrlE4lKbUZ2"; // you got this from the signup page
	$privatekey     = ":::";
	$enable_captcha = true;  // modify this in production

	$showform = true;

	if (isset($_POST['register']) && $_POST['register'] == "true")
	{
		$showform = false;
		$err = "";
		$acc = new TMWAccount();
		$acc->setUsername($_POST['username']);
		$acc->setPassword($_POST['password1']);
		$acc->setEMail($_POST['email']);
		$acc->setGender($_POST['gender']);
		
		$val = $acc->validate();
		if (is_array($val))
		{
			foreach( $val as $error)
			{
				$err .= $error . "<br />";
			}
			$showform = true;
		}
		
		if ($_POST['password2'] != $_POST['password1'])
		{
			$err .= "The given passwords don't match!"; $showform = true;
		}
		
		if (TMWAccount::existsUsername( $_POST['username'] ))
		{
			$err .= "The username is in use!"; $showform = true;
		}
		
		if ($enable_captcha)
		{
			// check captcha
			$resp = recaptcha_check_answer ($privatekey, $_SERVER["REMOTE_ADDR"],
				$_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
		
			if (!$resp->is_valid)
			{
				$err .= "The captcha was incorrect!"; $showform = true;
			}
		}

		if (!$showform)
		{
			// create the account
			if (!$acc->storeAccount())
			{
				$err = "The was an unknown error while storing your new account";
				$showform = true;
			}
		}
	}
  
	include("includes/common.php");
	placeHeader("Registration");
  
  
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
			<td>EMail:</td>
			<td><input type="text" size="30" name="email" /></td>
		</tr>
		<tr>
			<td>Gender:</td>
      <td>
       <select name="gender">
         <option value="0" selected></option>
         <option value="1">Male</option>
         <option value="2">Female</option>
       </select>
      </td>
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
		<p>Your account has been created and was scheduled for creation! In a few minutes you should receive an email with verification of your new account.</p>
	<?php }
	placeFooter();
?>

