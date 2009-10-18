<?php

	require_once('recaptcha-php/recaptchalib.php');
	$publickey = "6LexmAQAAAAAAJD-07K2pF5RvTfIdRrlE4lKbUZ2"; // you got this from the signup page
	$privatekey = ":::";

	include("includes/common.php");
	placeHeader("Registration");
	$showform = true;

	function check_chars($string)
	{
		return ctype_graph($string) && (strpos($string, '"') === FALSE);
	}
	$bad_string_desc = 'Only printable characters (except spaces and ") are allowed.';
  
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
		else if (!check_chars($_POST['username']))
		{
		  $err = 'Username contains invalid characters. ' . $bad_string_desc; $showform = true;
		}
		else if (!check_chars($_POST['password1']))
		{
		  $err = 'Password contains invalid characters. ' . $bad_string_des; $showform = true;
		}
		else if ($_POST['password2'] != $_POST['password1'])
		{
			$err = "The given passwords don't match!"; $showform = true;
		}
		else if ($_POST['gender'] != 1 && $_POST['gender'] != 2)
		{
		  $err = 'Please select your preferred gender.'; $showform = true;
		}
		else
		{
			// check captcha
			$resp = recaptcha_check_answer ($privatekey, $_SERVER["REMOTE_ADDR"],
			$_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);

			if (!$resp->is_valid)
			{
				$err = "The captcha was incorrect!"; $showform = true;
			}
			else
			{
				$username = escapeshellarg($_POST['username']);
				$password = escapeshellarg($_POST['password1']);
				$gender = ($_POST['gender'] == 1) ? "Male" : "Female";
	
				// create a new account
				$handle = popen("/home/eathena/webexec/runladmin.sh add $username $gender $password", "r");
				$retstr = fgets($handle);
				$retval = pclose($handle);

				if ($retstr === FALSE)
				{
					$err = "There was an unknown error while creating account.";
					$showform = true;
				}
				else if (strpos($retstr, 'successfully created'))
				{
					// everything was fine, created account
					$showform = false;
				}
				else
				{
					$err = $retstr;
					$showform = true;
				}
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
		<p>Your account has been created!</p>
	<?php }
	placeFooter();
?>

