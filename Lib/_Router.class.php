<?php
	/**
	 * The Router class
	 * @author : acpmasquerade@gmail.com
	 * @package : Framework26
	 */

	class Router{
		
		public static $request_path;
		public static $request_page;
		public static $request_arguments;

		public static $controller;
		public static $controller_action;
		public static $controller_arguments;

		public static function initialize(){
			if (isset($_SERVER['PATHINFO']) AND strlen($_SERVER["PATHINFO"]) > 1) {
				Router::$request_path = substr($_SERVER["PATHINFO"], 1);
			} else {
				Router::$request_path = "dashboard";
			}

			Router::$request_path = strtolower(Router::$request_path);
			Config::set("page_title", ucwords(Router::$request_path));

			Router::$request_page = "";
			Router::$request_arguments = "";

			list(Router::$request_page, $request_arguments) = explode("/", Router::$request_path."/", 2);
			$request_arguments = substr($request_arguments, 0, -1);
			Router::$request_arguments = explode('/', $request_arguments);

			if(isset($_SESSION['is_logged_in']) AND $_SESSION["is_logged_in"] === true){

				$session_user_id = $_SESSION["is_logged_in_id"];

				Config::set("is_logged_in", true);
				Config::set("template", "dashboard");
				Config::set("navigation", true);

				// current_user
				Loader::helper("user");
				$current_user = Helper_User::get_user_by_id($session_user_id);

				if(!$current_user){
					Template::notify("error", "Fatal:: Unable to fetch the logged in user session");
					// force logout
					$_SESSION["is_logged_in"] = FALSE;
					redirect(Config::url("logout"));
				}

				$current_user = $current_user[0];

				Config::set("username", $current_user->username);
				Config::set("user", $current_user);
				Config::set("id", $current_user->id);
				Config::set("role", $current_user->role);

			}else{
				Config::set("is_logged_in", false);
				Config::set("template", "login");
			}

			Config::set("template", "blank");
			Config::set("page_title", "Page not found");

			Router::route();
		}

		private static function prepare_route_arguments(){

		}

		private static function route(){

			if(method_exists("Default_Router", Router::$request_page)){
				call_user_func_array(array("Default_Router", Router::$request_page), Router::$request_arguments);
			}else{

				// check the request_path and pick the respective router
				$controller = Router::$request_page;
				$controller_arguments = Router::$request_arguments;
				
				$controller_include_dir = realpath(__DIR__."/../controllers");
				$controller_include_path = realpath("{$controller_include_dir}/{$controller}.php");

				if(!$controller_include_path){
					// check in the directories

					while($controller_include_dir){
						
						$controller_include_dir = realpath("{$controller_include_dir}/{$controller}");

						// break, if the predicted directory does not exist.
						if(!$controller_include_dir){
							break;
						}

						// alternate controller is the controller with same name as the directory name
						$controller_alterate = $controller;
						$controller_include_path_alternate = realpath("{$controller_include_dir}/{$controller_alterate}.php");

						// preferred controller is the controller with the name as in argument
						$controller = array_shift($controller_arguments);
						$controller_include_path = realpath("{$controller_include_dir}/{$controller}.php");

						// if the preferred controller exists, thats it
						if($controller_include_path){
							break;
						}else{
							// if the preffered controller does not exist, 
							// check for the alternate controller
							if($controller_include_path_alternate){
								// prepend the chopped argument back to the arguments array
								array_unshift($controller_arguments, $controller);
								// and define the alternate controller as the actual controller								
								$controller = $controller_alterate;
								// and define the alternate controller path as the actual controller path
								$controller_include_path = $controller_include_path_alternate;
								// break, its over with the loop now.
								break;
							}else{
								if($controller_arguments){
									continue;
								}else{
									break;
								}
							}
						}
					}
				}
				
				if($controller_include_path){

					if(isset($controller_arguments[0]) AND $controller_arguments[0]){
						$controller_action = $controller_arguments[0];
						array_shift($controller_arguments);
					}else{
						$controller_action = "index";
						$controller_arguments = array();
					}
					
					Router::$controller = $controller;
					Router::$controller_action = $controller_action;
					Router::$controller_arguments = $controller_arguments;

					Config::set("controller", Router::$controller);

					require_once $controller_include_path;
					
					$controller_class = "Controller_".ucwords($controller);

					if(class_exists($controller_class)){
						$controller_object = new $controller_class;
						try{
							$controller_class_methods = get_class_methods($controller_object);
							if(in_array("_remap", $controller_class_methods)){
								call_user_func_array(array($controller_object, "_remap") , array($controller_action, $controller_arguments));
							}else{
								if(in_array($controller_action, $controller_class_methods)){
									call_user_func_array(array($controller_object, $controller_action) , $controller_arguments);
								}else{
									// Config::set("template", "blank");
									// Config::set("page_title", "Page not found - 1");

									Template::set("404", array());
								}
							}
						}catch (Exception $e){
							// Config::set("template", "blank");
							// Config::set("page_title", "Exception Occured.");

							Template::set("500", array());
						}
					}
				}else{
					Config::set("template", "blank");
					Config::set("page_title", "Page not found - 0");
				}

			}

			Template::render();
		}

	}

	class Default_Router {

		// private static $db;

		// public static function login(){

		// 	global $db;
		// 	self::$db = $db;

		// 	if($_POST){
		// 		$client_id = $_POST["client_id"];
		// 		$username = $_POST['username'];
		// 		$password = $_POST["password"];

		// 		$username_escaped = mysql_real_escape_string($username);
		// 		$password_escaped = Config::hash($password);

		// 		$query = "SELECT * FROM client_credits WHERE client_id = '{$client_id}' AND username='{$username}'";

		// 		$user_result = self::$db->get_results($query);

		// 		// check if there is any entry for the particular client/username combination
		// 		if($user_result){
		// 			// there is an entry for the provided client/username combination
		// 			// thus get the user's information, particularly password
		// 			// compare the hash (obtained from the database with the hash of the user input)

		// 			$user_result = $user_result[0];

		// 			$db_password_hash = $user_result->password;

		// 			$user_input_password_hash = Config::hash($password);

		// 			if($db_password_hash == $user_input_password_hash){
		// 				// which means that the user is authenticated with the credentials supplied

		// 				// since the user is authenticated into the system, enter the user's information in the session
		// 				// $_SESSION["is_logged_in"] = true;
		// 				// $_SESSION["is_logged_in_id"] = $user_result->id;

		// 				Session::setvar("is_logged_in", true);
		// 				Session::setvar("is_logged_in_id", $user_result->id);

		// 				header("Location: ".Config::url("home"));
		// 			}else{
		// 				Template::notify("error", "Login failed");
		// 			}
		// 		}

		// 	}

		// 	if(Config::get("is_logged_in") === true){
		// 		redirect(Config::url("home"));
		// 	}

		// 	Config::set('page_title', "Login");
		// 	Template::set("login", array());
		// }

		public function logout(){
			redirect(Config::url("login/logout"));
		}

		// public static function logout(){
		// 	Config::set("is_logged_in", false);
		// 	Config::set("template", "login");
		// 	$_SESSION["is_logged_in"] = false;
		// 	$_SESSION["user"] = new stdClass();
		// 	Config::set("navigation", false);

		// 	redirect(Config::url("login"));
		// }
	}

