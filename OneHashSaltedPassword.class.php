<?php
class OneHashSaltedPassword 
{//Created by Ákos Nikházy 
 //http://yzahk.in
	
	/*********************
		This class is for generate and check password hashes that actually contains the salt too.
		
		The idea:
		
		This way in the database you can store a single hash value in the password column while
		it is salted properly so even at the same password you will see different hash values.
		
		Also if an attacker steals only your user database they will assume you store your password
		in plain sha256, not knowing it is actually a combination of a password and a salt, making 
		their work harder.
	
	*********************/	
	
	function __construct($_sitekey = '') 
	{//if this gives further strength to the password hash. Used in HashPassword
		$this->siteKey	= $_sitekey;
	}
	
	private $siteKey;
	
	public function GenerateSaltedPassword($password)
	{//from plain text it generates a salted password with a random new salt. You should save this value to database instead of plain text passwords.
		
		return  $this->HashPassword($password,$this->RandomSalt());
		
	}
	
	public function CheckPassword($password,$hashedPassword)
	{//checks if typed in password is correct.
		/*********************
			$password - the password typed in
			$hashedPassword - the password from database, generated with GenerateSaltedPassword() before. 
					It looks like an sha256 hash (64 char hexa), but in reality the first 32 character 
					comes from the password, the second 32 char comes from the salt.
					
					dd3350b26bb70a5644f76fd4f21f24ade10b2cbbc0b8fcd6282c49d37c9c1abc => dd3350b26bb70a5644f76fd4f21f24ad e10b2cbbc0b8fcd6282c49d37c9c1abc
		
		*********************/
		
		
		if($this->HashPassword($password,substr($hashedPassword,-32)) == $hashedPassword)
			return true;
				
		return false;
		
		
	}
	
	
	private function HashPassword($password,$hash)
	{//this is where you hash the password in CheckPassword() and in GenerateSaltedPassword() too.
		/*********************
			$password - the plain text password
			$hash	  - the second 32 char of an hashed password OR the second 32 char of a generated password
			
			Important: after you start to use this class in production never change the value of the $hashedPassword
			variable and it always should contain the $password variable whatever you do with it.
			
			For example you can further harden your hash's strength if you do this:
				$hashedPassword = $password.$hash.$password;
			or maybe this:
				$hashedPassword = hash('sha256',$password . 'something');
				
			You decide how you hash the password. But once you decided: do not change it anymore
		*********************/
		$hashedPassword = $password;
		
		return substr(hash('sha256',$this->siteKey.$hashedPassword.substr($hash,-32)),0,32).substr($hash,-32);
		
	}
	
	
	private function RandomSalt()
	{//generates a random salt. Only part of it will be used.
			
		return hash('sha256',random_int(1, 999999999) . microtime(true));
		
	}
}
?>