<?php

class Controller_Admin extends Asstroller {

	private $user;
	private $user_id;

	private $user_role;

	public function __construct(){
		parent::__construct();
		Config::set("navigation", true);
		Loader::helper("general");
		Loader::helper("admin");
		Loader::helper("user");

		if(!Helper_User::is_super_user()){
			Template::notify("error","[Unauthorized Access]: You do not have permissions to enter the administration");
			redirect(Config::url(""));
		}
	}

	public function index(){
		Config::set("page_title", "Administration");
		Template::set("admin/index", array());
	}

	public function config($action = "list"){

		if($action){
			$method = "config_".$action;
			$this->$method();
		}
	}

	private function config_list(){
		
		Config::set("page_title", "Host Configurations");

		$host_configurations = Helper_Admin::get_client_configs();

		$view_data["host_configurations"] = $host_configurations;

		Template::set("admin/config_list", $view_data);
	}


	private function config_add(){
		Config::set("page_title", "Add Configuration Settings");

		$formdata = form_post_data(array("host", "client_id", "default_credits", "allowed_networks", "allowed_shortcodes"));

		if($_POST){

			$error_flag = false;

			$host = trim($formdata["host"]);
			$client_id = trim($formdata["client_id"]);
			$default_credits = trim($formdata["default_credits"]);
			$allowed_networks = $formdata["allowed_networks"];
			$allowed_shortcodes = $formdata["allowed_shortcodes"];

			// check if host field is blank or not
			if(strlen($formdata["host"]) > 0){
				// if it is not empty, then check if it is valid URL or not
				if(!filter_var($host, FILTER_VALIDATE_URL)){
					$error_flag = true;
					Template::notify("error", "Please enter a valid Host URL. e.g. http://www.sparrowsms.com");
				}

			} else {
				$error_flag = true;
				Template::notify("error", "Hostname cannot be empty");
			}

			// check if the default client_id has been submitted from the user
			if(strlen($client_id) <= 0){
				$error_flag = true;
				Template::notify("error", "Client ID not assigned. Please select one client ID");				
			}

			// default credits must also be mentioned/specified
			// the user input received from the user/form will be converted into integer first and then compared
			if((int)($default_credits) <= 0){
				// invalid value
				$error_flag = true;
				Template::notify("error", "Invalid Default Credits value given. Please enter a valid number. e.g. 100");
			}

			if(is_array($allowed_networks) && count($allowed_networks) > 0){
				// at least one network is selected

				$selected_networks = implode(",", $allowed_networks);
			} else {
				$error_flag = true;
				Template::notify("error", "Please select at least one network provider");
			}

			if(is_array($allowed_networks) && count($allowed_networks) > 0){

				if(count($allowed_networks) > 0){
					foreach ($allowed_networks as $some_allowed_network) {
						$_allowed_shortcode["{$some_allowed_network}"] = $allowed_shortcodes;
					}
					$selected_shortcodes = json_encode($_allowed_shortcode);
				} else {
				$error_flag = true;
				}
			} else {
				$error_flag = true;
				Template::notify("error", "Please select at least one shortcode");
			}

			if(!$error_flag){
				// prepare the data to be posted to the database
				$data["host"] = $host;
				$data["client_id"] = $client_id;
				$data["default_credits"] = $default_credits;
				$data["allowed_networks"] = $selected_networks;
				$data["allowed_shortcodes"] = $selected_shortcodes;

				if($this->db->insert(DB::db_tbl_client_config, $data)){
					Template::notify("success", "Configuration Settings has been added for {$host}");
					redirect(Config::url("admin/config"));
				} else {
					Template::notify("error", "Oops ! There was a problem adding configuration settings for {$host}");
				}
			}
		}

		Template::set("admin/config_add", array());
	}


	public function config_view($config_id){
		$where["id"] = $config_id;
		$host_configuration = Helper_Admin::get_client_config($where);

		$view_data["host_configuration"] = $host_configuration;

		Config::set("page_title", "Configuration Details for {$host}");
		Template::set("admin/config_view", $view_data);
	}

}