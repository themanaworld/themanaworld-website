<?php

require_once "includes/libs/libstrutils.php";

class TMWAccount
{
	const ACCOUNT_TBL = "tmw_accounts";
	
	const GENDER_MALE   = 1;
	const GENDER_FEMALE = 2;
	
	const STATE_PENDING = 0;
	const STATE_CREATED = 0;
	const STATE_FAILED  = 0;

	private $id;
	private $username;
	private $password;
	private $email;
	private $gender;
	private $state;
	private $registration;
	
	public static function getAccountCount()
	{
		$db = Database::getInstance();
		$sql = "SELECT COUNT(*) FROM " . TMWAccount::ACCOUNT_TBL;
		return $db->getValue( $sql );
	}
	
	public static function existsUsername($str)
	{
		$db = Database::getInstance();
		$sql = sprintf("SELECT COUNT(*) FROM " . TMWAccount::ACCOUNT_TBL .
			   " WHERE USERNAME = '%s'", $db->escape($str));
		return ($db->getValue($sql) == 1);
	}
	
	public function setUsername($name){ $this->username = $name; }
	public function setPassword($pwd){ $this->password = $pwd; }
	public function setEMail($email){ $this->email = $email; }
	public function setGender($gender){ $this->gender = $gender; }
	
	public function validate()
	{
		$errors = array();
		
		// check here for correct values..
		if (strlen($this->username) < 4)
			$errors[] = "Username is too short";
			
		if (strlen($this->password) < 4)
			$errors[] = "Password is too short";
			
		if (!check_chars($this->username))
			$errors[] = 'Username contains invalid characters. ' . BAD_STRING_DESC;
			
		if (!check_chars($this->password))
			$errors[] = 'Password contains invalid characters. ' . BAD_STRING_DESC;	
			
		if ($this->gender != TMWAccount::GENDER_MALE &&
			$this->gender != TMWAccount::GENDER_FEMALE )
		{
			$errors[] = 'Gender has to be Male or Female!';	
		}
		
		if (!filter_var($this->email, FILTER_VALIDATE_EMAIL))
		{
			$errors[] = 'EMail has wrong format.';	
		}
		
			
		// returns true if everything is fine ( test with === true)
		if (count($errors) == 0)
		{
			return true;
		}
		else
		{
			return $errors;
		}
	}
	
	
	
	public function storeAccount()
	{
		$db = Database::getInstance();
		$sql = sprintf( "INSERT INTO " . TMWAccount::ACCOUNT_TBL . 
		                "   (USERNAME, PASSWORD, EMAIL, GENDER) " . 
						"VALUES ('%s', '%s', '%s', %d) ", 
						$db->escape($this->username),
						$db->escape($this->password),
						$db->escape($this->email),
						$this->gender);
						
		$rows = $db->exec( $sql );
		return ( $rows == 1 );
	}
}

?>