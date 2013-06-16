<?php
	
	class Helper_User extends Helper{

		public static function get_deleted_users_count(){
			$x = self::db()->get_results("SELECT count(*) TOTAL from ".DB::db_tbl_users." WHERE status = 'deleted'; ");
			return $x->TOTAL;
		}

		public static function get_user($where = array()){
			return self::db()->get(DB::db_tbl_users, $where);
		}

		public static function get_user_meta($where = array()){
			$result = self::db()->get(DB::db_tbl_users_meta, $where);
			if($result){
				return $result[0];
			}
		}

		public static function get_user_metas($where = array()){
			return self::db()->get(DB::db_tbl_users_meta, $where);
		}

		public static function get_user_by_id($user_id){
			$user_id = mysql_real_escape_string(intval($user_id));
			$x = self::db()->get_results("SELECT * FROM  users WHERE `id` = '{$user_id}'; ");
			
			if(is_array($x) AND count($x)){
				return $x[0];
			}
			
			return false;
		}

		public function search($query = NULL){

			$query = self::db()->escape($query);
			
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
			$username = self::db()->escape($username);
			$x = self::db()->get_results("SELECT * FROM  ".DB::db_tbl_users." WHERE `username` = '{$username}' LIMIT 1; ");
			return $x[0];
		}

		public static function update_user($data, $where){
			
			if(!$where){
				return false;
			}

			self::db()->update(DB::db_tbl_users, $data, $where);
		}

		public static function login($username, $password){

			if(!$username || !$password){
				return false;
			}

			$username = self::db()->escape($username);
			$query = "SELECT * FROM ".DB::db_tbl_users." WHERE username='{$username}' AND `status` = 'active' LIMIT 1;";

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

					// @todo - can hook something if required pre-authenticated

					unset($user_result->password);
					self::register_session_variables($user_result);

					// @todo - can hook something if required post-authenticated

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

			$phone = self::db()->escape($phone);

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

			$username = self::db()->escape($username);
			$query_by_username = "SELECT * FROM ".DB::db_tbl_users." WHERE `username` = '{$username}' AND `status` != 'deleted' ";
			
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

		/**
		 * Add multiple meta records for a user 
		 */
		public static function add_user_meta($user_meta_data = array()){
			if(!$user_meta_data){
				return false;
			}

			foreach($user_meta_data as $some_user_meta_data){
				self::db()->insert(DB::db_tbl_users_meta, $some_user_meta_data);
			}
		}

		public static function send_new_user_email($user_info){
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
			$email_details["to"] = $user_info["email"];
			$email_details["subject"] = "Welcome";
			$email_details["message"] = $email_content;
			Helper_General::send_email($email_details);
		}

		public static function gravatar_image_url($email, $size = 80){
			$hash = md5(strtolower(trim($email)));
			return "http://www.gravatar.com/avatar/{$hash}?s={$size}";
		}

		public static function search_user_by_email_verifcation_code($code){
			// search meta
			$meta = Helper_User::get_user_metas(array("meta_value"=>$code, "meta_key"=>"email_activation_code"));

			if(count($meta) !== 1){
				return false;
			}

			$user_id = $meta[0]->user_id;

			$user_info["info"] = Helper_User::get_user_by_id($user_id);

			foreach($meta as $key=>$val){
				$user_info["meta"][$val->meta_key.""] = $val->meta_value;
			}

			return $user_info;			
		}

		public static function activate_email_for_user_id($user_id){
			$data = array();
			$where = array();

			$data["meta_value"] = "yes";

			$where["meta_key"] = "is_email_activated";
			$where["user_id"] = $user_id;

			return self::db()->update(DB::db_tbl_users_meta, $data, $where);
		}

		public static function generate_email_verification_code_for_user_id($user_id, $email_activation_code = NULL){

			$user_meta_data = array();

			if(!$email_activation_code){
				$email_activation_code = md5(time().uniqid().rand(1000, 9999).$_SERVER["REMOTE_ADDR"]);
			}

			$user_meta_data[] = array("user_id"=>$user_id, "meta_key"=>"is_email_activated", "meta_value"=>"no");
			$user_meta_data[] = array("user_id"=>$user_id, "meta_key"=>"email_activation_code", "meta_value"=>$email_activation_code);

			Helper_User::add_user_meta($user_meta_data);

			return $email_activation_code;
		}
	}