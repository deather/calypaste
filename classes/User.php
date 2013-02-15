<?php
	
class User{
	protected $_id;
	protected $_login;
	protected $_email;
	protected $_password;
	protected $_blocked;
	protected $_session;

	function __construct($login, $email, $password){
		$this->_login = $login;
		$this->_email = $email;
		$this->_password = hash("sha256", $password);
		$this->_blocked = 0;
	}

	function getLogin(){
		return $this->_login;
	}

	function getId(){
		return $this->_id;
	}

	function setSession($session_id){
		$this->_session = $session_id;
	}

	function save(){
		global $sql;

		$this->_id = $sql->saveObject("user", $this);
	}

	function flushSession(){
		$this->_session = NULL;
		$this->save();
	}

	function checkGoodPassword($password){
		return hash("sha256", $password) == $this->_password;
	}

	function changePassword($old_password, $new_password){
		if(hash("sha256", $old_password) != $this->_password)
			return;

		$this->_password = hash("sha256", $new_password);
		$this->save();
	}

	function isBlocked(){
		return ($this->_blocked == 1)? true : false;
	}

	function toArray(){
		return array(
			"id" => $this->_id,
			"login" => $this->_login,
			"email" => $this->_email,
			"password" => $this->_password,
			"blocked" => $this->_blocked,
			"session" => $this->_session
		);
	}

	function toString(){
		return "(User #{$this->_id})"." login=".$this->_login." email=".$this->_email;
	}

	static function loginExists($login){
		global $sql;

		$tmp = $sql->getObject("user", array("login"=>$login));
		return !empty($tmp);
	}

	static function getWithLogin($login){
		global $sql;
		
		$result = $sql->getObject("user", array("login" => $login));
		if(empty($result)){
			return NULL;
		}
		return User::populateUser($result);
	}

	static function getWithSession($session_id){
		global $sql;

		$result = $sql->getObject("user", array("session" => $session_id));
		if(empty($result)){
			return NULL;
		}
		return User::populateUser($result);
	}

	static private function populateUser($result){
		$user = new User($result['login'], $result['email'], $result['password']);
		$user->_password = $result['password'];
		$user->_id = $result['id'];
		$user->_blocked = $result['blocked'];
		$user->_session = $result['session'];

		return $user;
	}
}
?>
