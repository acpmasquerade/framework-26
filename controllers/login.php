<?php

class Controller_Login extends Facetroller {


	public function __construct(){
		parent::__construct();
		Loader::helper("general");
		Loader::helper("clients");
		Loader::helper("user");
	}

	/**
	 * 
	 * login action
	*/
	public function index(){

		if($_POST){
			$client_id = $_POST["client_id"];
			$username = $_POST['username'];
			$password = $_POST["password"];

			if(!$client_id OR !$username OR !$password){
				Template::notify("error", "Insufficient Parameters");
				redirect(Config::url("login"));
				return;
			}

			$username_escaped = $this->db->escape($username);
			$password_escaped = Config::hash($password);

			if(Helper_User::login($client_id, $username, $password)){
				redirect("dashboard");
			} else {
				Template::notify("error","Invalid Login details. Please check your login information and try again");
			}
		}

		if(Config::get("is_logged_in") === true){
			redirect(Config::url("dashboard"));
		}

		Config::set('page_title', "Login");
		Template::set("login", array());
	}


	public function signup(){

		$formdata = form_post_data(array("username", "email", "phone"));

		if($_POST){

			$error_flag = false;

			if(strlen(trim($formdata["username"])) > 0){
				$username = trim($formdata["username"]);
			} else {
				$error_flag = true;
				Template::notify("error","'Username' cannot be blank");
			}

			if(strlen(trim($formdata["email"])) > 0){
				$email = trim($formdata["email"]);
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					Template::notify("error","Invalid email address. Please enter a valid email address");
				}
			} else {
				$error_flag = true;
				Template::notify("error","'Email' cannot be blank.");
			}

			if(strlen(trim($formdata["phone"])) > 0){
				$phone = trim($formdata["phone"]);
			} else {
				$error_flag = true;
				Template::notify("error","'Phone' number cannot be blank");
			}

			// check if there are any errors
			// if there are, populate the errors in the notification session
			// else, take it to process

			if(!$error_flag){
				/**
				 * the client_id will be extracted from the database
				 * based on the web address, client ID will be assigned.
				 * by default, it will be demo
			 	*/

				// if there are no errors, then check for the existing record, based on 
			 	// 1. phone number
			 	// 2. or client_id - username pair

				if(!Helper_User::is_allowed_to_add_by_phone($phone)){
					Template::notify("error","There is already an account associated with the phone number '{$phone}'");
				} else {
					// means that the account is not bound to the phone number
					// now check if the username is already taken

					if(!Helper_User::is_allowed_to_add_by_phone($username)){
						Template::notify("error","The username {$username} has already been taken. Please try another one");
					} else {
						$random_password = random_password();

						$data["client_id"] = $client_id;
						$data["username"] = $username;
						$data["password"] = Config::hash($random_password);
						$data["email"] = $email;
						$data["phone"] = $phone;

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
								"meta_value" => "no"
							);

							$phone_activation_code = rand(1000,9999);

							$client_credits_meta_data[] = array(
								"user_id" => $client_credits_id,
								"meta_key" => "phone_activation_code",
								"meta_value" => $phone_activation_code
							);

							Helper_User::add_user_meta($client_credits_meta_data);

							$this->send_sms($phone, $phone_activation_code, $username);

							$user_info["client_id"] = $client_id;
							$user_info["email"] = $email;
							$user_info["password"] = $random_password;

							Helper_User::send_new_user_email($user_info);

							Template::notify("success", "Thank You for signing up with Sparrow SMS. Please check your email for login information".PHP_EOL."Your password is {$random_password}");
							redirect(Config::url("login"));
						}

					}
				}

			}
		}

		Config::set("page_title", "Sparrow SMS API SIgnup");
		Template::set("api-signup", array());
	}

	private function send_sms($to, $phone_activation_code, $username){
		// send an SMS to the user's phone number along with the activation code

		$sms_text = "Send API {$phone_activation_code} to 4001 to activate your account. Sparrow SMS";

		Loader::helper("sms");

		$log_data["username"] = $username;

		Helper_Sms::push($to, $sms_text, $log_data);
	}

	public function verify($type){
		if(!isset($type)){
			die("Invalid Attempt !");
		}

		if(isset($_REQUEST["code"])){
			$activation_code = trim($_REQUEST["code"]);
		} else {
			die("Invalid Attempt !");
		}

		switch ($type) {
			case "phone":
				// if the mode of activation is phone number
				// then match the phone number with the record in the database

				if(isset($_REQUEST["phone"])){
					$phone_number = $_REQUEST["phone"];
				}

				$where["phone"] = $phone_number;

				break;

			case "email":

				if(isset($_REQUEST["email"])){
					$email = $_REQUEST["email"];
				}

				$where["email"] = $email;

				break;
			
			default:
				die("Invalid Activation Mode");
				break;
		}

		$client = Helper_Clients::get_client($where);

		if(isset($client["info"])){
			// there is a user associated with that user info/attributes, either email or phone number
			// get the client_id
			// P.S. this client_id is not the ALPHABETICAL client_id column, rather it is the primary key
			// to maintain legacy and backward compatibility

			$user_id = $client["info"]->id;

			$client_meta = $client["meta"];

			if(!isset($client_meta["is_phone_activated"])){
				die("Please request a new activation code");
			}

			$is_phone_activated = trim($client_meta["is_phone_activated"]);

			if($is_phone_activated !== "no"){
				// since the phone is already activated, throw an error to the user
				die("Invalid Attempt. Your phone number is already activated.");
			}

			// 1. get the phone activation code from the database
			// 2. compare it with the code sent by the user
			// 3. if it matches, activate the account

			if(!isset($client_meta["phone_activation_code"])){
				die("Invalid Code. Please request a new activation code.");
			}

			$phone_activation_code = trim($client_meta["phone_activation_code"]);

			if($phone_activation_code === $activation_code){
				// the activation code sent by the user matches with the one stored in the database
				// thus activate the account
				// set the is_phone_activated attribute to "YES"

				$client_credit_data["status"] = "active";

				$where = array();

				$where["id"] = $user_id;

				if($this->db->update(DB::db_tbl_client_credits, $client_credit_data, $where)){
					// the main account is set activated

					$where = array();

					$client_credit_meta_data["meta_value"] = "yes";
					$where["meta_key"] = "is_phone_activated";
					$where["user_id"] = $user_id;

					$this->db->update(DB::db_tbl_client_credits_meta, $client_credit_meta_data, $where);

					die("activated");
				} else {
					die("There was an error activating your account. Please try again later");
				}
			} else {
				die("Incorrect Activation Code");
			}
		}
	}

	public function credit_topup(){

		$remote_address = $_SERVER["REMOTE_ADDR"];

		if($remote_address !== "127.0.0.1" && $remote_address !== $_SERVER["SERVER_ADDR"]){
			die("Unauthorized Access");
		}

		if($_REQUEST){
			// based on the phone number, get the client_ID (alphabet based)
			// with that, query the configured amount of topup credits from the database
			// update the credit info of the user

			Loader::helper("clients");

			if(!isset($_REQUEST["phone"])){
				die("Invalid Data Format. Please contact the system administrator for this issue");
			}
			$phone = trim($_REQUEST["phone"]);

			if(!is_valid_phone_number($phone)){
				die("Invalid Attempt. Intrusion Detected");
			}

			$where = "`phone` = '{$phone}' AND `status` != 'deleted'";
			$client_credit_info = Helper_Clients::get_client_credit($where);

			if(!$client_credit_info){
				die("There is no account associated with your phone number. Please try with a valid account's phone number");
			}

			// account exists
			// get the client ID of that phone number
			// for legacy, client_id in the db is a varchar column rather than an identifier
			$user_id = $client_credit_info->id;
			$client_id = $client_credit_info->client_id;

			$where = array();

			Loader::helper("admin");

			$where["client_id"] = $client_id;
			$where["host"] = Helper_General::host;

			$host_config_record = Helper_Admin::get_client_config($where);

			if($host_config_record){
				// if there is a defined entry for that particular host
				// get the default number of SMS to topup
				$credit_topup_amount = $host_config_record->credit_topup_amount;
			} else {
				// if not
				// get the default number of SMS to topup defined in the code
				$credit_topup_amount = Helper_General::default_credit_topup_amount;
			}

			if(!$credit_topup_amount){
				die("System Error: Cannot add credits to your account. Please try again later");
			}

			Loader::helper("credits");

			$credits_data["credits"] = $credit_topup_amount;
			$credits_data["user_id"] = $user_id;
			$credits_data["ref"] = "sms-query";
			$credits_data["remarks"] = "{$credit_topup_amount} added to {$client_credit_info->username}'s account for FREE !";

			if(Helper_Credits::topup_user_credit($credits_data)){
				die("{$credit_topup_amount} credits have been added to your account. Thank You for using Sparrow SMS.");
			} else {
				die("There was an error adding credits to your account. Please contact the system support");
			}
		}	
	}

	public function forgot_password(){

		if($_POST){

			if(isset($_POST["phone"])){
				$phone = $_POST["phone"];

				// check if the number entered is a valid phone number or not
				if(!is_valid_phone_number($phone)){
					Template::notify("error", "Invalid phone number");
				} else {
					// with everything fine, proceed
					$where = "`phone` = '{$phone}' AND `status` != 'deleted'";
					$user = Helper_Clients::get_client($where);

					if($user){
						$user_info = $user["info"];
						$user_email = $user_info->email;

						if(filter_var($user_email, FILTER_VALIDATE_EMAIL)){
							// if the email stored in the database is valid

							$user_id = $user_info->id;

							$user_meta = $user["meta"];

							$password_reset_token = NULL;

							$record_exists = false;

							if(isset($user_meta["password_reset_dump"])){

								$record_exists = true;

								$password_reset_dump = json_decode($user_meta["password_reset_dump"]);

								if($password_reset_dump->status === "active"){
									// if there is already an active token, resend that token
									$password_reset_token = $password_reset_dump->password_reset_token;
								}
								
							}

							// if there is a value set to $password_reset_token
							// then proceed
							// else, generate a new one
							// there might be chances that password reset token is not stored in database( by some errors)
							if(!isset($password_reset_token) || strlen(trim($password_reset_token)) <=0 ){
								// generate an password reset token for this particular account
								$password_reset_token = substr(md5(rand(100,999).time().$user_id.rand(1111,9999)), 5, 15);
							}

							$password_reset_token_details["to"] = $user_email;
							$password_reset_token_details["status"] = "active";
							$password_reset_token_details["password_reset_token"] = $password_reset_token;
							$password_reset_token_details["modified"] = time();

							$password_reset_token_dump_data = array(
								"meta_key" => "password_reset_dump",
								"meta_value" => json_encode($password_reset_token_details)
							);

							$proceed = false;

							if($record_exists){
								// udpate the record in the database

								$update_credit_meta_where["user_id"] = $user_info->id;
								$update_credit_meta_where["meta_key"] = "password_reset_token";

								$update_credit_data["meta_value"] = $password_reset_token;

								if($this->db->update(DB::db_tbl_client_credits_meta, $update_credit_data, $update_credit_meta_where)){
									
									$update_credit_meta_where["meta_key"] = "password_reset_dump";
									
									if($this->db->update(DB::db_tbl_client_credits_meta, $password_reset_token_dump_data, $update_credit_meta_where)){
										$proceed = true;
									}
								}
							} else {
								// add a new entry

								$password_reset_token_dump_data["user_id"] = $user_info->id;

								$password_reset_token_data["user_id"] = $user_info->id;
								$password_reset_token_data["meta_key"] = "password_reset_token";
								$password_reset_token_data["meta_value"] = $password_reset_token;

								if($this->db->insert(DB::db_tbl_client_credits_meta, $password_reset_token_data)){
									if($this->db->insert(DB::db_tbl_client_credits_meta, $password_reset_token_dump_data)){
										$proceed = true;
									}
								}
							}

							if($proceed === true){
								// send an email

								$email_details["to"] = $user_email;
								$email_details["subject"] = "Sparrow SMS API Password Reset";
								$password_reset_url = Config::url("login");

								$email_details["message"] = <<<EOT


								Dear {$user_info->username},

								You have requested to reset your password at Sparrow SMS's Developer Panel.
								Please click on the link below to proceed with password reset.

								<a href="{$password_reset_url}/reset/{$password_reset_token}">
									Click Here
								</a>

								<p>If you have difficulties in clicking the link above, kindly please copy and paste the link below and paste in your browser.

								{$password_reset_url}/reset/{$password_reset_token}

								Thank You for using Sparrow SMS.

								Happy Coding !
EOT;

								echo ($email_details["message"]);

								die();

								Helper_General::send_email($email_details);

								Template::notify("success", "An email has been sent to {$user_email} with password reset instructions.");

								redirect(Config::url("login"));
							}
						}
					}
				}
			} else {
				Template::notify("error", "Please enter a phone number");
			}
		}

		Config::set("page_title", "Reset Password");
		Template::set("forgot_password", array());
	}

	public function reset($reset_token = NULL){
		if(!$reset_token){
			Template::set("404", array());
			return;
		}

		$password_reset_token_where["meta_key"] = "password_reset_token";
		$password_reset_token_where["meta_value"] = $reset_token;

		// check if the password reset token is stored in the databse (is valid)

		$password_reset_token_record = Helper_Clients::get_clients_meta($password_reset_token_where);

		if($password_reset_token_record){
			// there is a record
			$user_id = $password_reset_token_record->user_id; // this is the user ID

			$password_reset_token_dump_where["meta_key"] = "password_reset_dump";
			$password_reset_token_dump_where["user_id"] = $user_id;

			$password_reset_token_dump = Helper_Clients::get_clients_meta($password_reset_token_dump_where);

			if($password_reset_token_dump){
				$_dump = $password_reset_token_dump->meta_value;

				$dump_values = json_decode($_dump, true);

				if($dump_values["status"] === "active"){

					$new_password = random_password();

					$new_password_hash = Config::hash($new_password);

					$clients_update_data["password"] = $new_password_hash;
					$clients_update_where["id"] = $user_id;

					if($this->db->update(DB::db_tbl_client_credits, $clients_update_data, $clients_update_where)){
						// also change the dump settings in the meta table as expired/used
						$dump_values["status"] = "expired";

						$password_reset_token_dump = array();

						$password_reset_token_dump["meta_value"] = json_encode($dump_values);

						$password_reset_token_dump_where["user_id"] = $user_id;
						$password_reset_token_dump_where["meta_key"] = "password_reset_dump";

						if($this->db->update(DB::db_tbl_client_credits_meta, $password_reset_token_dump, $password_reset_token_dump_where)){
							// compose and send an email to the user, emailing the new password

							$email_details["to"] = $dump_values["to"];
							$email_details["subject"] = "Sparrow SMS Developers API - Password Reset Successfull";
							$email_details["message"] = <<<EOT

							Your password has been reset. Please keep a note of your new password and keep it safe.

							New Password: <strong>{$new_password}</strong>

							Thank You for using Sparrow SMS.

							Happy Coding !
EOT;
							Helper_General::send_email($email_details);
							Template::notify("success", "Your password has been reset. Please check your email for the new password {$new_password}");
						} else {
							Template::notify("error", "System Error ! Please try again later.");
						}
					} else {
						Template::notify("error", "System Error ! Please try again later.");
					}
				} else {
					// the token is either expired or invalid
					// either ways it is not active and cannot be used to validate
					// generate a new password and send to the user's email
					Template::notify("error", "Invalid Reset Token. The token is either expired or already used. Please request a new one");
				}
			} else {
				Template::notify("error", "Invalid Reset Token. Please request a new token");
			}
		} else {
			Template::notify("error", "Invalid Reset Token. Please check the token and try again.");
		}

		Template::set("login");
	}


	public static function logout(){
		Config::set("is_logged_in", false);
		Config::set("template", "login");
		Config::set("navigation", false);

		Session::unsetvar(array("user", "username", "is_logged_in", "is_logged_in_id"));

		Template::notify("success", "Logged out of the system successfully");

		redirect(Config::url("login"));
	}

}