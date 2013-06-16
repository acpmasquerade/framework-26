<?php

	/** 
	 * Yet another Public controller for home directing
	 */
	class Controller_Home extends Facetroller{
		
		function __construct(){
			parent::__construct();
			Config::set("navigation", true);
		}
		
		/**
		 * If I am logged in, take me to my dashboard
		 * Else, take me somewhere else. - may be docs page, or login page, or some decent homepage.
		 */
		function index(){
			if(Session::getvar("is_logged_in") === true){
				Config::set("page_title", "Dashboard");
				Template::set("dashboard", array());
			}else{
				redirect(Config::url("login"));
			}
		}
		
	}