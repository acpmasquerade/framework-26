<?php

class Controller_User extends Asstroller {

	public function __construct(){
		parent::__construct();

		Config::set("navigation", true);

		$this->user = Session::getvar("user");
		Loader::helper("user");
		Loader::helper("acl");


		if(!Helper_User::is_admin() && !Helper_User::is_reseller()){
			redirect(config::url(""));
		}
	}

	public function index(){

		if(Helper_User::is_admin()){
			$where = "`status` != 'deleted'";
		} else {
			$current_user_id = Helper_User::get_current_user_id();
			$where = "`status` != 'deleted' and id='{$current_user_id}'";
		}

		$users = Helper_User::get_users($where);

		$view_data["users"] = $users;

		Config::set("page_title", "Users");
		Template::set("users/index", $view_data);
	}

	public function add(){

		$formdata = form_post_data(array("client_id", "username", "password", "email", "phone", "user_role"));

		if($_POST){

			if(Helper_User::is_super_user()){
				$client_id = trim($formdata["client_id"]);
			}

			$username = trim($formdata["username"]);
			$password = trim($formdata["password"]);
			$email = trim($formdata["email"]);
			$phone = trim($formdata["phone"]);
			$user_role = trim($formdata["user_role"]);

			$error_flag = false;

			if(strlen($client_id) <= 0){
				// client ID is not submitted from the form
				// thus set the client ID based on the current user in the session
				$client_id = $this->user->client_id;
			}

			if(strlen($username) <= 0){
				$error_flag = true;
				Template::notify("error", "Username cannot be blank");
			} else {
				if(strlen($username) < 3){
					$error_flag = true;
					Template::notify("error", "Username should be at least 3 characters long");
				}
			}

			if(strlen($password) <= 0){
				$error_flag = true;
				Template::notify("error", "Password cannot be blank");
			}

			if(strlen(trim($email)) > 0){
				$email = trim($email);
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$error_flag = true;
					Template::notify("error","Invalid email address. Please enter a valid email address");
				}
			} else {
				$error_flag = true;
				Template::notify("error","'Email' cannot be blank.");
			}

			if(strlen(trim($phone)) > 0){
				if(!is_valid_phone_number($phone)){
					$error_flag = true;
					Template::notify("error", "Invalid Phone Number");
				}
			} else {
				$error_flag = true;
				Template::notify("error","'Phone' number is required to create an account");
			}

			if(strlen(trim($user_role)) > 0){
				// if user role is defined and the user role defined is admin,
				// then check whether the current user in the session is admin or not
				// only allow to add client_id and make admin, if the user is a super user

				if(!Helper_User::is_super_user() && $user_role === USER_ROLE_ADMIN){
					// this means, a non-super user is attempting to make a user an admin
					$user_role = USER_ROLE_USER;
					Template::notify("error", "[Authority breached] You do not have permissions to assign an admin");
				}

			} else {
				$user_role = USER_ROLE_USER;
			}

			if(!$error_flag){
				// if no errors occurred

				// in this case, create the user account
				// auto verify the phone number and set to active

				// but still validate if the user account exists based on phone number or either username

				if(!Helper_User::is_allowed_to_add_by_phone($phone)){
					Template::notify("error","There is already an account associated with the phone number '{$phone}'");
				} else {
					// means that the account is not bound to the phone number
					// now check if the username is already taken

					if(!Helper_User::is_allowed_to_add_by_phone($username)){
						Template::notify("error","The username {$username} has already been taken. Please try another one");
					} else {
						$data["username"] = $username;
						$data["password"] = Config::hash($password);
						$data["phone"] = $phone;
						$data["client_id"] = $client_id;
						$data["email"] = $email;
						$data["user_role"] = $user_role;
						$data["status"] = "active";

						// add the record to the database
						// after adding the record, send an email to the user

						$client_credits_id = Helper_User::add_user($data);

						if($client_credits_id){
							// the insertion is successfull

							// after inserting the user information into the database
							// add the meta information to verify phone number as well as email

							// 1. generate 5 digit random code and sent to the user via SMS
							// 2. set the is_phone_activated attribute to no
							// 3. generate a random email token
							// 4. set the is_email_activated attribute to no

							$client_credits_meta_data[] = array(
								"user_id" => $client_credits_id,
								"meta_key" => "is_phone_activated",
								"meta_value" => "yes"
							);

							Helper_User::add_user_meta($client_credits_meta_data);

							$user_info["client_id"] = $client_id;
							$user_info["email"] = $email;
							$user_info["password"] = $random_password;

							Helper_User::send_new_user_email($user_info);

							Template::notify("success", "{$username} has been created. The user has been notified by email");
							redirect(Config::url("user"));
						}
					}
				}
			}
		}
		
		Config::set("page_title", "New User Account");
		$view_data = array();
		$view_data["clients"] = Helper_Clients::get_distinct_client_ids();
		$view_data["formdata"] = $formdata;
		Template::set("users/add", $view_data);
	}

	public function login($user_id = NULL){
		if(!$user_id){
			Template::set("404", array());
		}

		// get the user information from the database

		$where = "`id` = $user_id AND `status` != 'deleted'";

		$user_result = Helper_Clients::get_client_credit($where);

		if(!$user_result){
			Template::notify("error", "Invalid User Account");
		} else {
			// check if the current user in the session is reseller or not
			// if so, check the user account being logged in as is a user level account - not reseller or admin

			if(Helper_User::is_admin() || Helper_User::is_reseller()){
				$user = Session::getvar("user");

				if(Helper_User::is_reseller()){
					if($user->user_role !== USER_ROLE_USER AND ($user->client_id !== $user_result->client_id)){
						Config::set("page_title", "Permission Denied");
						Template::set("403", array());
						return;
					}
				}

				unset($user_result->password);
				try {
					Helper_User::register_session_variables($user_result);
					Template::notify("success", "Successfully logged in as {$user_result->username}");
					redirect(Config::url("dashboard"));
				} catch(Exception $e){
					Template::notify("error", $e->getMessage());
					Template::notify("error", "Cannot Log In to the user's account");
				}
			}
		}
		redirect(Config::url("user"));
	}

	public function search(){
		$view_data["users"] = array();
		$view_data["mode"] = "search";
		
		if($_POST){
			$formdata = form_post_data(array("query"));

			$query = trim($formdata["query"]);

			if(strlen($query) > 0){
				// if something is entered in the search field
				// based on that keyword/query, search from the database to match:
				// 	- email address
				// 	- username
				$search_result = Helper_User::search($query);
				$view_data["users"] = $search_result;
			} else {
				Template::notify("error", "No search key defined");
			}
		}
		Config::set("page_title", "Search Results");
		Template::set("users/index", $view_data);
	}

	public function delete($user_id = NULL){
		if(!$user_id){
			Config::set("page_title", "Page not found");
			Template::set("404", array());
			return;
		}

		if(Helper_User::is_admin() || Helper_User::is_reseller()){

			$where["id"] = $user_id;

			$user_result = Helper_Clients::get_client_credit($where);

			if(!$user_result){
				Template::notify("error", "Invalid User Account");
			} else {
				// check if the current user in the session is reseller or not
				// if so, check the user account being logged in as is a user level account - not reseller or admin

				if(Helper_User::is_admin() || Helper_User::is_reseller()){
					$user = Session::getvar("user");

					if(Helper_User::is_reseller()){
						if($user->user_role !== USER_ROLE_USER AND ($user->client_id !== $user_result->client_id)){
							Config::set("page_title", "Permission Denied");
							Template::set("403", array());
							return;
						}
					}

					// if nothing goes wrong, then we are ready to delete the user

					$where["id"] = $user_id;
					$data["status"] = "deleted";

					if(Helper_Clients::update_client_credit($data, $where)){
						Template::notify("success", "{$user_result->username} has been deleted from the system");
					}
				}
			}
		}

		redirect(Config::url("user"));
	}
}