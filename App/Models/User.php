<?php

namespace App\Models;

use PDO;
use App\Token;
use App\Mail;
use App\Config;
use \Core\View;
use \App\Models\Incomes;
use \App\Models\Expenses;

/*
 * User model
 *
 * PHP version 7.0
 */
class User extends \Core\Model
{
	/**
     * Error messges
     *
     * @var array
     */
	 
	 public $errors = [];
	 
	/**
     * Class constructor
     *
     * @return void
     */
	 
	 public function __construct($data = [])
	{
		foreach ($data as $key => $value) {
			$this->$key = $value;
		};
	}
    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public function save()
    {
		
		$this->validate();
		
		if (empty($this->errors)) {
		
			$password_hash = password_hash($this->password, PASSWORD_DEFAULT);
			
			$token = new Token();
			$hashed_token = $token->getHash();
			$this->activation_token = $token->getValue();
			
			$sql = 'INSERT INTO users (name, email, password_hash, activation_hash)
						VALUES (:name, :email, :password_hash, :activation_hash)';
						
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			
			$stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
			$stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
			$stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
			$stmt->bindValue(':activation_hash', $hashed_token, PDO::PARAM_STR);
			
			$results = $stmt->execute();
			
			/*$newUserId = $this->getNewUserId();	
			Incomes::setDefaultIncomesCategory($newUserId);
			Expenses::setDefaultExpensesCategory($newUserId);
			Expenses::setDefaultPaymentMethods($newUserId); */
			
			return $results;
		}
		
		return false;
		
    }
	
	protected function getNewUserId()
	
	{	

		$sql1 = 'SELECT id FROM users WHERE email=:email';

        $db = static::getDB();
		
		$stmt1 = $db->prepare($sql1);

        $stmt1->bindValue(':email', $this->email, PDO::PARAM_STR);
		
		$stmt1->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt1->execute();       
		
		$user = $stmt1->fetch();
		$newUserId = $user->id;
		
		return $newUserId;		
	}


	/**
		 * Validate current propert values, adding validation error messeges to the errors array property
		 *
		 * @return void
		 */
	
	    public function validate()
		{
			// Name
			if ($this->name == '') {
				$this->errors['name'] = 'Imię jest wymagane';
			}
			
			//email address
			
			if (static::emailExists($this->email, $this->id ?? null)){
				$this->errors['email'] = 'Email jest zajęty';
			}
			if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false ){
				$this->errors['email'] = 'Nieprawidłowy email';
			}
			
			//password
			/*
			if ($this->password != $this-> password_confirmation) {
				$this->errors[] = 'Hasła muszą być jednakowe'; 
			}
			*/
			
			if (strlen($this->password < 6)) {
				$this->errors['password'] = 'Hasło musi mieć conajmiej 6 znaków';
			}
			
			if (preg_match('/.*[a-z]+.*/i', $this->password) == 0 ){
				$this->errors['password'] = 'Hasło musi mieć conajmiej jedną literę';
			}
			
			if (preg_match('/.*\d+.*/i', $this->password) == 0 ){
				$this->errors['password'] = 'Hasło musi mieć conajmiej jedną cyfrę';
			}
			
		$resposneCaptcha = $this->validateCaptcha();
			if(!($resposneCaptcha->success))
			{
				$validation_successful = false;
				$this->errors['captcha'] = "Potwierdź, że nie jesteś botem.";
		}
			
		} 
		
		/**
		* Check if email is uniqe
		*
		* @param string $email email address
		*
		* @return boolean True if email already exists, otherwise false
		*
		**/
		
		public static function emailExists($email, $ignore_id = null)
		{
			$user = static::findByEmail($email);
			
			if ($user) {
				if ($user->id != $ignore_id) {
					return true;
				}
			}
			return false;
		}
		
		/**
		* Find the user model by email address
		*
		* @param string $email email address
		*
		* @return User object if found, otherwise false
		*
		**/
		
		public static function findByEmail($email)
		{
			$sql = 'SELECT * FROM users WHERE email = :email';
			
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':email', $email, PDO::PARAM_STR);
			
			$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
			
			$stmt->execute();
			
			return $stmt->fetch();
		}
		
		/**
		* Authenticate the user by email address and password
		*
		* @param string $email email address
		*
		* @param string $password password
		*
		* @return User object if user authenticated, otherwise false
		*
		**/
		
		public static function authenticate($email, $password)
		{
			
			$user = static::findByEmail($email);
			
			if ($user && $user->is_active){
				if(password_verify($password, $user->password_hash))
				{
					return $user;
				}
			}
			
			return false;
		}
		
		/*
		* Find the user model by id address
		*
		* @param string $id The user ID
		*
		* @return User object if found, otherwise false
		*
		*/
			public static function findById($id)
		{
			$sql = 'SELECT * FROM users WHERE id = :id';
			
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':id', $id, PDO::PARAM_INT);
			
			$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
			
			$stmt->execute();
			
			return $stmt->fetch();
		}
		
		/*
		* Remember the login by inserting a new uniqe token nto the remembered_logins table
		* for this user record
		*
		* @return boolean True if the login was remembered sucessfully, false otherwise
		*
		*/
			public function rememberLogin()
		{
			$token = new Token();
			$hashed_token =  $token->getHash();
			$this->remember_token = $token->getValue();
			
			$this->expiry_timestamp= time() + 60 * 60* 24 * 30; // 30 days
			
			$sql = 'INSERT INTO remembered_logins (token_hash, user_id, expires_at)
						VALUES (:token_hash, :user_id, :expires_at)';
			
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			
			$stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
			$stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
			$stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $this->expiry_timestamp),  PDO::PARAM_STR);
			
			return $stmt->execute();
			
		}
		
			/*
		* Send password reset instruction to the user specified
		*
		* @param string $email The email address
		*
		* @return boolean True if the login was remembered sucessfully, false otherwise
		*
		*/
		 public static function sendPasswordReset($email)
    {
        $user = static::findByEmail($email);

        if ($user) {

            if ($user->startPasswordReset()) {

                $user->sendPasswordResetEmail();

            }
        }
    }

    /**
     * Start the password reset process by generating a new token and expiry
     *
     * @return void
     */
		protected function startPasswordReset()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->password_reset_token = $token->getValue();

        $expiry_timestamp = time() + 60 * 60 * 2;  // 2 hours from now

        $sql = 'UPDATE users
                SET password_reset_hash = :token_hash,
                    password_reset_expire = :expires_at
                WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $expiry_timestamp), PDO::PARAM_STR);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Send password reset instructions in an email to the user
     *
     * @return void
     */
    protected function sendPasswordResetEmail()
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/password/reset/' . $this->password_reset_token;

        $text = View::getTemplate('Password/reset_email.txt', ['url' => $url]);
        $html = View::getTemplate('Password/reset_email.html', ['url' => $url]);

        Mail::send($this->email, 'Password reset', $text, $html);
    }

    /**
     * Find a user model by password reset token and expiry
     *
     * @param string $token Password reset token sent to user
     *
     * @return mixed User object if found and the token hasn't expired, null otherwise
     */
    public static function findByPasswordReset($token)
    {
        $token = new Token($token);
        $hashed_token = $token->getHash();

        $sql = 'SELECT * FROM users
                WHERE password_reset_hash = :token_hash';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        $user = $stmt->fetch();

        if ($user) {
            
            // Check password reset token hasn't expired
            if (strtotime($user->password_reset_expire) > time()) {

                return $user;
            }
        }
    }
	
	  /**
     * Reset the password
     *
     * @param string $password the new password
     *
     * @return boolean True if the password was update succesfully, false otherwise
     */
    public function resetPassword($password)
    {
		$this->password = $password;
		
		$this->validate();
		
		if (empty($this->errors)) {
			
			$password_hash = password_hash($this->password, PASSWORD_DEFAULT);
			
			$sql = 'UPDATE users
                SET password_hash = :password_hash,
					   password_reset_hash = NULL,
					   password_reset_expire = NULL
                WHERE id = :id';

			$db = static::getDB();
			$stmt = $db->prepare($sql);

			$stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
			$stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
			
			return $stmt->execute();
		}
		
		return false;

    }
	
	/**
     * Send activation token email to the user
     *
     * @return void
     */
    public function sendActivationEmail()
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/signup/activate/' . $this->activation_token;

        $text = View::getTemplate('Signup/activation_email.txt', ['url' => $url]);
        $html = View::getTemplate('Signup/activation_email.html', ['url' => $url]);

        Mail::send($this->email, 'Account activation', $text, $html);
    }
	
	/**
     * Activate the user account with the specified activation token
     *
	 *@param string $value Activation token from the URL
	 *
     * @return void
     */
    public static function activate($value)
    {
        $token = new Token($value);
		$hashed_token = $token->getHash();
		
		
		
        $token = new Token($value);
        $hashed_token = $token->getHash();

        $sql = 'UPDATE users
                SET is_active = 1,
                    activation_hash = null
                WHERE activation_hash = :hashed_token';
				
		$sql1 = 'Select *
		FROM users
		WHERE activation_hash = :hashed_token';

        $db = static::getDB();
		
		$stmt1 = $db->prepare($sql1);

        $stmt1->bindValue(':hashed_token', $hashed_token, PDO::PARAM_STR);
		
		$stmt1->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt1->execute();       
		
		$user = $stmt1->fetch();
		$user_id = $user->id;
		
		Incomes::setDefaultIncomesCategory($user_id);
		Expenses::setDefaultExpensesCategory($user_id);
		Expenses::setDefaultPaymentMethods($user_id);
		
		
		
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':hashed_token', $hashed_token, PDO::PARAM_STR);

        $stmt->execute();            
    }
	
		public function updateProfile()
    {
        $this->validateNameAndEmail();
		

        if (empty($this->errors)) {
			

			$sql = 'UPDATE users SET name = :name, email = :email WHERE id = :user_id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
			$stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
			
			$results = $stmt->execute();

            return $results;
        }

        return false;
    }	
	
	protected function validateNameAndEmail() 
	{
        // Name
        if ($this->name == '') {
            $this->errors['name'] = 'Wprowadź imię.';
        }

        // email address
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors['email'] = 'Podaj poprawny adres e-mail.';
        }
        if (static::emailExists($this->email, $this->id ?? null)) {
            $this->errors['email'] = 'Istnieje konto o podanym adresie e-mail.';
        }		
	}
	
		protected function validatePassword() 
	{
		if (preg_match('/(?=.*?[0-9])(?=.*?[A-Za-z]).+/', $this->password) == 0) {
			$this->errors['password'] = 'Hasło musi posiadać przynajmniej 1 literę i 1 cyfrę.';
		}
		
		if (strlen($this->password) < 6 || strlen($this->password) > 20) {
			$this->errors['password'] = 'Hasło musi posiadać od 6 do 20 znaków.';
		}
	}
	
	public function changeUserPassword()
    {
        $this->validatePassword();
		
		$is_valid = static::validateOldPassword($this->oldPassword,$_SESSION['user_id']);
		

        if (empty($this->errors) && $is_valid) {

            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $sql = 'UPDATE users SET password_hash = :new_password_hash WHERE id = :id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);


            $stmt->bindValue(':new_password_hash', $password_hash, PDO::PARAM_STR);
			$stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
			
			$results = $stmt->execute();
			
            return $results;
        }

        return false;
    }
	
	public function deleteAccount()
    {
        	$sql = 'DELETE FROM users WHERE id = :user_id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

			$stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
			
			$stmt->execute();
			
    }	
	
	public static function validateOldPassword($password,$userId)
    {
		$user = static::findByID($userId);

        if ($user) {
            if (password_verify($password, $user->password_hash)) {
                return true;
            }
        }
		
        return false;
    }
	
	protected function validateCaptcha()
	{
		
		$checkCaptcha = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.Config::CAPTCHA_SECRET.'&response='.$_POST['g-recaptcha-response']);
		
		return json_decode($checkCaptcha);

	}
	
}