<?php

	class Config{

		private static $config = array();

		public static function set($var, $val){
			self::$config["{$var}"] = $val;
		}

		public static function get($var){
			if(isset(self::$config["{$var}"])){
				return self::$config["{$var}"];
			}else{
				return NULL;
			}
		}

		public static function hash($text){
			return md5($text."~moFald_");
		}

		public static function url($path){
			return Config::get("base_url")."{$path}";
		}

		public static function get_all(){
			return self::$config;
		}
	}