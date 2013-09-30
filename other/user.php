<?php

class User
{
	protected $db;
	public $login;
	public $logged = false; //eingeloggt?;
	public function __construct($login, $db){
		$this->db = $db;
		$this->login = $login;
		if ($login !== false && isset($_SESSION['dynamic_password']) && isset($_SESSION['user'])){ //prüfen ob eingeloggt
			
			try {
				$stmt = $this->db->prepare('SELECT * FROM dynamic_password WHERE user = :user');
				$stmt->execute(array('user' =>$_SESSION['user']));
				/*while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				
					print_r($row);
				}*/
				
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				if ($row['password'] == $_SESSION['dynamic_password']){
					$this->logged = true;
				}
			} catch(PDOException $e) {
				die('ERROR: ' . $e->getMessage());
			}
		}
	}
	
	public function login ($pw){
		$stmt = $this->db->prepare('SELECT * FROM users WHERE user = :user');
		$stmt->execute(array('user' => $this->login));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if (PassHash::check_password($row['password'], $pw)) {
		$dynamic_password = PassHash::hash($row['password']);
		$_SESSION['dynamic_password'] = $dynamic_password;
		$_SESSION['user'] = $this->login;
		
		$sql = "SELECT * FROM dynamic_password WHERE user = :user LIMIT 1"; 
            $q = $this->db->prepare($sql); 
            $q->execute(array(':user' => $this->login));
            $anz = $q->rowCount(); 
            if ($anz > 0)
				{ $sql="UPDATE dynamic_password SET password=:password WHERE user = :user LIMIT 1"; } 
			else
				{ $sql = "INSERT INTO dynamic_password (user,password) VALUES (:user,:password)"; }
		
		$q = $this->db->prepare($sql);
		$q->execute(array(
			':user'=>$this->login,
			':password'=>$dynamic_password)
		);
		
		return true; 
		} else { return false; }
	}
	
	public static function logout (){
		unset($_SESSION['user']);
		unset($_SESSION['dynamic_password']);
	}
	
	
	public static function register($reg, $db){ //$reg ist ein array
		//ist ein (getrimter) Wert leer?
		if(tisset($reg['reg_name']) && tisset($reg['reg_first_name']) && tisset($reg['reg_login']) && tisset($reg['reg_email']) && !empty($reg['reg_password'])  && !empty($reg['reg_password2'])){
			if($reg['reg_password'] != $reg['reg_password2']){
				return 'Passwords are not the same.';
			}
			if(strlen($reg['reg_login']) < 4) return 'Login to short. Min. 4';
			if(strlen($reg['reg_login']) > 15) return 'Login to long. Max. 15';
			if(strlen($reg['reg_password']) < 6) return 'Password to short. Min. 6';
			if(strlen($reg['reg_password']) > 30) return 'Password to short. Max. 30';
			if(check_email_address($reg['reg_email']) === false) return 'Your email address is not valid. Please check that.';
			//$stmt = $db->prepare('... FROM dynamic_password WHERE user = :user');
			//$stmt->execute(array('user' =>'blabla'));
			$sql = "INSERT INTO users (user,email,password,status) VALUES (:user,:email,:password,:status)";
			$q = $db->prepare($sql);
			$q->execute(array(
				':user'=>$reg['reg_login'],
                ':email'=>$reg['reg_email'],
				':password'=>PassHash::hash($reg['reg_password']),
				':status'=>0)
			);
		
			return true;
			}
		return false;
	}
	
	
	
}

?>