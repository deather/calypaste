<?php
include_once CLASSES."User.php";

class Paste {
	protected $_id;
	protected $_text;
	protected $_hash;
	protected $_user;
	protected $_end;
	protected $_public;

	function __construct($text="", $login=NULL, $public=0, $end=NULL){
		$this->_text = $text;
		$this->_user = NULL;
		if($login != NULL){
			$u = User::getWithLogin($login);
			if($u != NULL){
				$this->_user = $u->getId();
			}
		}
		$this->_public = $public;
		$this->_end = $end;
		$this->_hash = Paste::generateHash();
	}

	function save(){
		global $sql;

		$this->_id = $sql->saveObject("paste", $this);
	}

	function getHash(){
		return $this->_hash;
	}

	function getText(){
		return $this->_text;
	}

	function isPublic(){
		return ($this->_public == "f")? false : true;
	}

	function isOwner($user){
		if(!($user instanceof User)){
			return false;
		}
		return $this->_user === $user->getId();
	}

	function changeText($text){
		$this->_text = $text;
	}

	function toArray(){
		return array(
			"id" => $this->_id,
			"text" => $this->_text,
			"hash" => $this->_hash,
			"user" => $this->_user,
			"public" => $this->_public,
			"time_left" => $this->_end
		);
	}
	
	function toString(){
		return "(Paste #{$this->_id})"." hash=".$this->_hash." text=".$this->_text;
	}

	static function getWithHash($hash){
		global $sql;

		$result = $sql->getObject("paste", array("hash" => $hash));
		if(empty($result)){
			return NULL;
		}
		$paste = new Paste($result['text'], NULL, $result['public'], $result['end']);
		$paste->_user = $result['user'];
		$paste->_hash = $result['hash'];
		$paste->_id = $result['id'];

		return $paste;
	}

	private static function generateHash(){
		return hash("crc32", uniqid("paste_"));
	}
}

?>
