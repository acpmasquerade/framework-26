<?php
	class Controller_Home extends Facetroller{
		
		function __construct(){
			parent::__construct();
			Config::set("navigation", true);
		}
		
		function index(){
			if(Session::getvar("is_logged_in") === true){
				Config::set("page_title", "Dashboard");
				Template::set("dashboard", array());
			}else{
				redirect(Config::url("login"));
			}
		}
		
	}