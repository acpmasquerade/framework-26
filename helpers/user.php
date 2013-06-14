<?php
	
	class Helper_User extends Helper{

		public static function get_deleted_users_count(){
			$x = self::db()->get_results("SELECT count(*) TOTAL from users WHERE status = '0'; ");
			return $x->TOTAL;
		}

		public static function get_user($where = array()){
			return self::db()->get_results(db_tbl_users, $where);
		}

		public static function get_user_by_id($user_id){
			$user_id = mysql_real_escape_string(intval($user_id));
			$x = self::db()->get_results("SELECT * FROM  users WHERE `id` = '{$user_id}'; ");
			return $x;
		}

		public function search($query = NULL){
			$where = "(`username` LIKE '%{$query}%' OR `email` LIKE '%{$query}%')";

			try {
				$results =  self::db()->get(DB::db_tbl_users, $where);

				if(!$results){
					return array();
				} else {
					return $results;
				}
			} catch (Exception $e){
				Template::notify("error", $e->getMessage());
				return false;
			}
		}

		public function get_current_user_role(){
			$current_user = Session::getvar("user");
			return $current_user->user_role;
		}

		public function get_current_user_id(){
			$current_user = Session::getvar("user");
			return $current_user->id;
		}

		public static function get_user_by_username($username){
			$username = mysql_real_escape_string($username);
			$x = self::db()->get_results("SELECT * FROM  ".DB::db_tbl_users." WHERE `username` = '{$username}'; ");
			return $x;
		}

		public static function login($client_id, $username, $password){

			if(!$client_id || !$username || !$password){
				return false;
			}

			$query = "SELECT * FROM ".DB::db_tbl_users." WHERE client_id = '{$client_id}' AND username='{$username}'";

			$user_result = self::db()->get_results($query);

			// check if there is any entry for the particular client/username combination
			if($user_result){
				// there is an entry for the provided client/username combination
				// thus get the user's information, particularly password
				// compare the hash (obtained from the database with the hash of the user input)

				$user_result = $user_result[0];

				$db_password_hash = $user_result->password;

				$user_input_password_hash = Config::hash($password);

				if($db_password_hash === $user_input_password_hash){
					// which means that the user is authenticated with the credentials supplied
					// since the user is authenticated into the system, enter the user's information in the session

					unset($user_result->password);
					self::register_session_variables($user_result);
					return true;
				}
			}
			return false;
		}

		public static function register_session_variables($user){
			
			Session::unsetvar(array("is_logged_in", "is_logged_in_id", "username", "user"));

			Session::setvar("is_logged_in", true);
			Session::setvar("is_logged_in_id", $user->id);
			Session::setvar("username", $user->username);
			Session::setvar("user", $user); // logged in user's information
		}

		// Check if the current user is admin or not
		public static function is_admin(){
			$current_user = Session::getvar("user");
			if( ( $current_user ) AND ( $current_user->user_role === USER_ROLE_ADMIN ) ){
				return true;
			}else{
				return false;
			}
		}

		public static function is_allowed_to_add_by_phone($phone){
			$query_by_phone = "SELECT * FROM ".DB::db_tbl_users." WHERE `phone` = '{$phone}' AND `status` != 'deleted'";

			try {
				$existing_user_by_phone = self::db()->get_results($query_by_phone);

				if(!$existing_user_by_phone){
					// this ensures that no error has occurred and no row has been returned
					// thus it should be ok to add an account with this phone number 
					// (other obligations shall be tested)
					return true;
				}
			} catch(Exception $e){
				Template::notification("error", $e->getMessage());
			}

			return false;
		}

		public function is_allowed_to_add_by_username($username){
			// means that the account is not bound to the phone number
			// now check if the username is already taken

			$query_by_username = "SELECT * FROM ".DB::db_tbl_users." WHERE `client_id` = '{$client_id}' AND `username` = '{$username}' AND `status` != 'deleted' ";
			
			try {
				$existing_user_by_username = self::db()->get_results($query_by_username);
				if(!$existing_user_by_username){
					// based on the parameters passed to this method, it should be OK to add an account
					// with this username and client_id
					return true;
				}

			} catch(Exception $e){
				Template::notification("error", $e->getMessage());
			}
			return false;
		}

		public static function allowed_to_add_new_user($client_id){
			// query the database if there is an admin for the particular client_id
			// if there is, do not allow to add the user

			$where = "`client_id` = '{$client_id}' AND `user_role` = '".USER_ROLE_ADMIN."' AND `status` != 'deleted'";

			try {
				$user = self::get_user($where);

				if(!$user){
					// if no errors occurred and still no rows returned, then there is no admin for the client_id
					return true;
				}

			} catch(Exception $e){
				Template::notify("error", $e->getMessage());
			}

			return false;
		}

		public static function add_user($data){


			// check if there are any settings defined for the current domain
			// if so, set the client_id, default credits, allowed network and allowed shortcode accordingly

			try {
				$user_id = self::db()->insert(DB::db_tbl_users, $data);

				if($user_id){
					return $user_id;
				}

			} catch (Exception $e){
				Theme::notify("error", $e->getMessage());
			}

			return false;
		}

		public static function add_user_meta($user_meta_data = array()){
			if(!$user_meta_data){
				return false;
			}

			foreach($user_meta_data as $some_user_meta_data){
				self::db()->insert(DB::db_tbl_users_meta, $some_user_meta_data);
			}
		}

		public static function send_new_user_email($use_info){
			// now compose an email to be sent to the user
			// the email shall contain 
			// 1. Welcome Note
			// 3. Username
			// 4. password (randomly generated)
			// 5. Login url

			$login_url = Config::url("login");

			$email_content = <<<EOT

			Dear {$username},

			Thank you for signing up.

			Please keep the following information safe. Below is your login information.

			<strong>Username</strong>: <em>{$user_info["username"]}</em>
			<strong>Password</strong>: <em>{$user_info["password"]}</em>
			<strong>Login</strong>: <em>{$login_url}</em>

			Regards,
EOT;
			Loader::helper("general");

			// SEND THE EMAIL TO THE RECIPIENT
			$email_details["to"] = $email;
			$email_details["subject"] = "Welcome";
			$email_details["message"] = $email_content;
			Helper_General::send_email($email_details);
		}

		public static function gravatar_image_url($email, $size = 80){
			$hash = md5(strtolower(trim($email)));
			return "http://www.gravatar.com/avatar/{$hash}?s={$size}";
		}
	}