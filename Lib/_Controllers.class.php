<?php

	/**
	 * The Controller Class
	 * Add everything that is shared between the different controller types
	 * - eg. Facetroller and Asstroller
	 */
	class Controller{
		protected $db;

		public function __construct(){
			global $db;
			$this->db = $db;

			Loader::helper("general");
			Loader::helper("user");
			Loader::helper("account");
			Loader::helper("template");
		}
	}

	/**
	 * The Cute Front Controller 
	 */
	class Facetroller extends Controller{

		public function __construct(){
			parent::__construct();
		}
		
	}

	/**
	 * The Offensive Admin (Backend) Controller
	 */
	class Asstroller extends Controller{

		function __construct(){

			parent::__construct();

			if(Session::getvar("is_logged_in") !== true){
				redirect(Config::url("login"));
			}
		}
	}
