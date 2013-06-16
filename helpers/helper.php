<?php

define("USER_ROLE_ADMIN", "admin");
define("USER_ROLE_USER", "user");

class Helper {
	
	protected static $db;

	protected function db(){
		global $db;
		if(!isset(self::$db)){
			self::$db = $db;
		}
		return self::$db;
	}

	public static function db_debug(){
		self::db()->debug();
	}
}
