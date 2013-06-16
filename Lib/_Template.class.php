<?php
	class Template{

		private static $notifications = array();
		private static $vars = array();

		const current_theme = "default";

		public static function render(){
			
			$page = Config::get("template");
			
			include_once __DIR__."/../templates/themes/".self::current_theme."/__header.php";
			
			$default_content_filename = __DIR__."/../templates/{$page}.php";
			$themed_content_filename = __DIR__."/../templates/themes/".self::current_theme."/{$page}.php";

			if(file_exists($themed_content_filename)){
				include_once $themed_content_filename;
			}else{
				include_once $default_content_filename;
			}

			/** 
			 * @todo - have to check this thing. 
			 * -better would be to differentiate the controller and theme types and proceed accordingly.
			 */
			if($page !== "login" && $page !== "forgot_password" && $page !== "signup"){
				include_once __DIR__."/../templates/themes/".self::current_theme."/__content_footer.php";
			}
			
			include_once __DIR__."/../templates/themes/".self::current_theme."/__footer.php";
			die();
		}

		public static function load($view_file = NULL, $viewdata = array()){
			if(!isset($view_file)){
				$view_file = Config::get("template");
			}

			foreach($viewdata as $key=>$val){
				self::setvar($key, $val);
			}

			$default_content_filename = __DIR__."/../templates/{$view_file}.php";
			$themed_content_filename = __DIR__."/../templates/themes/".self::current_theme."/{$view_file}.php";

			if(file_exists($themed_content_filename)){
				include_once $themed_content_filename;
			}else{
				include_once $default_content_filename;
			}
		}

		// public static function login(){
		// 	$page = Config::get("template");
		// 	include_once __DIR__."/../templates/__header.php";			
		// 	include_once __DIR__."/../templates/{$page}.php";
		// 	include_once __DIR__."/../templates/__footer.php";

		// 	// close db connections if any
		// 	// DB::close();
		// 	die();
		// }

		// Just a wrapper function for Config::set("template", "template-name"); 
		public static function set($template, $viewdata = array()){
			Config::set("template",$template);
			
			foreach($viewdata as $key=>$val){
				self::setvar($key, $val);
			}

			return;
		}

		public static function notify($type, $message){
			self::$notifications["{$type}"][] = $message;
		}

		public static function has_notifications(){
			if(count(self::$notifications)){
				return true;
			}
			return false;
		}

		public static function get_notifications($scope = NULL){
			if(isset($scope)){
				if(isset(self::$notifications["{$scope}"])){
					return self::$notifications["{$scope}"];
				}else{
					return array();
				}
			}
			return self::$notifications;
		}

		public static function clear_notifications($scope = NULL){
			if(isset($scope)){
				unset(self::$notifications["{$scope}"]);
			}else{
				unset(self::$notifications);
			}
		}

		public static function persist_notifications(){
			$_SESSION["_template_notifications_"] = self::$notifications;
		}

		public static function load_persist_notifications(){
			if(isset($_SESSION["_template_notifications_"])){
				self::$notifications = $_SESSION["_template_notifications_"];
			}
			unset($_SESSION["_template_notifications_"]);
		}

		public static function setvar($var, $val){
			self::$vars["{$var}"] = $val;
		}

		public static function getvar($var){
			if(isset(self::$vars["{$var}"])){
				return self::$vars["{$var}"];
			}else{
				return NULL;
			}
		}

		public static function pagination_links($page, $max_results = NULL){

			if(isset($_GET["page_number"])){
				$page_number = intval($_GET['page_number']);
				if($page_number <= 0 ){
					$page_number = 1;
				}
			}else{
				$page_number = 1;
			}

			$limit = isset($_GET["limit"]) ? $ $_GET["limit"] : DB::default_limit;
		}
	}
