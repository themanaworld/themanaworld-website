<?php
require_once("includes/libs/libstrutils.php");
require_once("includes/models/email.php");

class TMWAccount
{
    const GENDER_MALE   = 'M';
    const GENDER_FEMALE = 'F';

    private $errors;
    private $ladmin;
    private $ladmin_result;

    private static function existsUsername($str)
    {
        $ladmin = New phpLadmin();
        $ladmin_result = $ladmin->account_info($str);
        $ladmin->close();
        return ($ladmin_result['account_id'] > 0 ? true : false);
    }

    public function setUsername($name){ $this->username = $name; }
    public function setPassword1($pwd){ $this->password1 = $pwd; }
    public function setPassword2($pwd){ $this->password2 = $pwd; }
    public function setEMail($email){ $this->email = $email; }
    public function setGender($gender){ $this->gender = $gender; }

    public function validate()
    {
        $errors = array();

        // check here for correct values..
        if (strlen($this->username) < 4) {
            $errors[] = "Username is too short";
        }

        if (strlen($this->username) >= 24) {
            $errors[] = "Username is too long";
        }

        if (strlen($this->password1) < 4) {
            $errors[] = "Password is too short";
        }

        if (strlen($this->password1) >= 24) {
            $errors[] = "Password is too long";
        }

        if (strlen($this->email) < 4) {
            $errors[] = "EMail is too short";
        }

        if (strlen($this->email) >= 40) {
            $errors[] = "EMail is too long";
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "EMail is not valid";
        }

        if (!check_chars($this->username)) {
            $errors[] = 'Username contains invalid characters.<br/> '.BAD_STRING_DESC;
        }

        if (!check_chars($this->password1)) {
            $errors[] = 'Password contains invalid characters.<br/> '.BAD_STRING_DESC;  
        }

        if ($this->password1 != $this->password2)
        {
            $errors[] = "The given passwords don't match!";
        }

        if ($this->gender != self::GENDER_MALE &&
            $this->gender != self::GENDER_FEMALE )
        {
            $errors[] = 'Gender has to be Male or Female!'; 
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL))
        {
            $errors[] = 'EMail has wrong format.';  
        }

        if (self::existsUsername($this->username))
        {
            $errors[] = "The username is in use!";
        }

        if (count($errors) > 0)
        {
            return $errors;
        }
        return null;
    }

    private function sendEMail() {
        $email = New TMWEMail();
        $email->mail_to = $this->email;
        $email->subject = "The Mana World Registration";
        $data = "== Account Created ==\n";
        $data .= "  Welcome ".$this->username." to The Mana World! Your account should now be enabled. If you have any problems with your login contact The Mana World via the Support (live) or Forums (maybe a delay) for help. Game tips & walkthroughs are available on the Wiki. Our Forums are a great place to ask for advice and discuss possible changes. News is available in the client or on the Home Site.
        Godspeed Adventurer,
            The Mana World\n";
        $email->getEmailTemplate($data);

        return $email->sendEMail();
    }

    public function createAccount()
    {
        $ladmin = New phpLadmin();
        $ladmin_result = $ladmin->create_account($this->username, $this->password1, $this->gender, $this->email);
        $ladmin->close();
        if(($ladmin_result['account_id'] > 0 ? true : false)) {
            $this->sendEMail();
            return true;
        } else {
            return false;
        }
    }
}
?>
