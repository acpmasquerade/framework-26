<?php
	/** Session class **/

	class Session{

		private static $session_scope_key;

		private static function session_scope_key(){
			if(isset(self::$session_scope_key)){
				return self::$session_scope_key;
			}else{
				$session_scope_key = Config::get("session_scope_key");
				if(!$session_scope_key){
					$session_scope_key = "_";
				}
				
				self::$session_scope_key = $session_scope_key;
				return $session_scope_key;
			}
		}

		public static function setvar( $var, $val = NULL ) {
			$_SESSION["".self::session_scope_key()]["{$var}"] = $val;
		}

		public static function getvar($var){
			
			if(!isset($_SESSION["".self::session_scope_key()]["{$var}"])){
				return NULL;
			}

			return $_SESSION["".self::session_scope_key()]["{$var}"];
		}

		public static function unsetvar(Array $variables){
			foreach($variables as $some_variable){
				if(isset($_SESSION["".self::session_scope_key()]["{$some_variable}"])){
					unset($_SESSION["".self::session_scope_key()]["{$some_variable}"]);
				}
			}
		}
	}