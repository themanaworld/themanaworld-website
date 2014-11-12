<?php
class GameAccountPage extends SpecialPage {

    public function __construct() {
        $this->err = array();
        parent::__construct('GameAccount');
    }
     
    public function execute( $par ) {
        $request = $this->getRequest();
        $output = $this->getOutput();
        $this->setHeaders();
        global $wgTMWAccountLib;
        $check_ladmin = new $wgTMWAccountLib();
        if($check_ladmin->socket) {
            $check_ladmin->close();
            if(!self::processForm($request)) {
                $wikitext = self::showForm();
                $output->addWikiText($wikitext);
            }
        } else {
            $wikitext = self::accountsOffline();
            $output->addWikiText($wikitext);
        }
    }

    public function processForm($request) {
        if ($request->getText('register') == "true") {
            $acc = new TMWAccount();
            $acc->setUsername($request->getText('username'));
            $acc->setPassword1($request->getText('password1'));
            $acc->setPassword2($request->getText('password2'));
            $acc->setEMail($request->getText('email'));
            $acc->setGender($request->getText('gender'));

            $this->err = $acc->validate();
            global $wgCaptchaClass;
            global $wgCaptchaClass, $wgConfirmAccountCaptchas;
            if ($wgConfirmAccountCaptchas) {
                $captcha = new $wgCaptchaClass;
                if (!$captcha->passCaptcha()) {
                    $this->err[] = "The captcha was incorrect!";
                }
            }
            if (count($this->err) > 0) {
                return false;
            }
            // create the account
            if (!$acc->createAccount()) {
                $this->err[] = "The was an unknown error while creating the account";
                return false;
            } else {
                self::showSuccess();
                return true;
            }
        }
        return false;
    }

    public function showForm() {
        $output = $this->getOutput();
        $form = ('<p>With this form you can register for a new account. <i>We will never give your email to someone else or send you spam! Its only purpose is to be able to send you back whether account creation succeeded.</i></p><p style="background-color: #ede2da; padding: 5px; border: 1px solid #9f9894; border-radius: 10px;"><i>Security warning:</i> Do not use the same username and password on two different servers. It happened a lot in the past that users of the official server got "hacked" because they ignored this important precaution.</p>');
        $form .= '<form method="post" name="gameaccount" action="'.$this->getTitle()->getLocalUrl().'">';
        $form .= '<input type="hidden" name="register" value="true" /><table>';
        foreach($this->err as $message) {
            $form .= "<tr><td colspan=\"2\" style=\"border: 1px solid red; color: red;\">".$message."</td></tr>";
        }
        $form .= '<tr><td>Username:</td><td><input type="text" size="20" name="username" /></td></tr>
                  <tr><td>Password:</td><td><input type="password" size="20" name="password1" /></td></tr>
                  <tr><td>Retype password:</td><td><input type="password" size="20" name="password2" /></td></tr>
                  <tr><td>EMail:</td><td><input type="text" size="30" name="email" /></td></tr>
                  <tr><td>Gender:</td>
                  <td>
                     <select name="gender">
                       <option value="0" selected></option>
                       <option value="M">Male</option>
                       <option value="F">Female</option>
                     </select>
                  </td>
                  </tr><tr><td colspan="2">';
		global $wgCaptchaClass, $wgConfirmAccountCaptchas;
        if ($wgConfirmAccountCaptchas) {
            $captcha = new $wgCaptchaClass;
            $form .= $captcha->getForm();
        }
        $form .= '</td></tr><tr>
                  <td colspan="2" style="text-align:right">
                    <input type="submit" value="Register" />
                  </td></tr></table></form>';
        $output->addHTML($form);
    }

    public function showSuccess() {
        $thank_you = "<p>Your account was created! In a few minutes you should receive an email with verification of your new account.</p>
        <p><em>If the account doesn't work, please ask for help on the <a href='https://forums.themanaworld.org/viewforum.php?f=3'>Forums</a> or <a href='https://webchat.freenode.net/?channels=#themanaworld'>Support (IRC)</a>.</em></p>";
        $output = $this->getOutput();
        $output->addHTML($thank_you);
    }

    public function accountsOffline() {
        $output = $this->getOutput();
        $offline_msg = "<p>The Mana World Account service is currently offline<em>please ask for help on the <a href='https://forums.themanaworld.org/viewforum.php?f=3'>Forums</a> or <a href='https://webchat.freenode.net/?channels=#themanaworld'>Support (IRC)</a>.</em></p>";
        $output->addHTML($offline_msg);
    }
}
?>
