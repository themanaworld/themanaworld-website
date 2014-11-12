<?php
require_once('includes/libs/libladmin.php');
require_once('includes/models/account.php');
require_once('recaptcha-php/recaptchalib.php');
require_once('recaptcha-php/keys.php');

$showform = true;

if (isset($_POST['register']) && $_POST['register'] == "true") {
    $acc = new TMWAccount();
    $acc->setUsername($_POST['username']);
    $acc->setPassword1($_POST['password1']);
    $acc->setPassword2($_POST['password1']);
    $acc->setEMail($_POST['email']);
    $acc->setGender($_POST['gender']);

    $err = $acc->validate();

    if ($enable_captcha) {
        // check captcha
        $resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
        if (!$resp->is_valid) {
            $err[] = "The captcha was incorrect!";
        }
    }

    if (count($err) == 0) {
        // create the account
        if (!$acc->createAccount()) {
            $err[] = "The was an unknown error while creating the account";
        } else {
            $showform = false;
        }
    }
}
if ($showform) {
    include("includes/common.php");
    placeHeader("Registration");

?>
<p>With this form you can register for a new account. <i>We will never give your email to someone else or send you spam! Its only purpose is to be able to send you back whether account creation succeeded.</i></p>

<p style="background-color: #ede2da; padding: 5px; border: 1px solid
#9f9894; border-radius: 10px;"><i>Security warning:</i> Do not use the same username and password on
two different servers. The server admins can read all of them in clear text and
nothing stops them from trying them on other servers. It happened a lot in the
past that users of the official server got "hacked" because they ignored this
important precaution.</p>

<form action="registration.php" method="post">

<input type="hidden" name="register" value="true" />
<table>
    <?php if (isset($err))
    {
        foreach($err as $message) {
            echo "<tr><td colspan=\"2\" style=\"border: 1px solid red; color: red;\">".$message."</td></tr>";
        }
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
     <option value="M">Male</option>
     <option value="F">Female</option>
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

placeFooter();
} else {
    header("Location: /thank_you.php");
}
?>
