<?php

define("USER_ROLE_ADMIN", "admin");
define("USER_ROLE_RESELLER", "reseller");
define("USER_ROLE_USER", "user");

define("SUPER_USER", "admin");

class Helper {
	
	protected static $db;

	protected function db(){
		global $db;
		if(!isset(self::$db)){
			self::$db = $db;
		}
		return self::$db;
	}
}
