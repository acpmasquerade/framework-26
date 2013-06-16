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

		if(!Helper_User::is_admin()){
			Template::notify("error","[Unauthorized Access]: You do not have permissions to enter the administration");
			redirect(Config::url(""));
		}
	}

	public function index(){
		Config::set("page_title", "Administration");
		Template::set("admin/index", array());
	}

	public function config(){
		Config::set("page_title", "Administration");
		Template::set("admin/config", array());	
	}

}