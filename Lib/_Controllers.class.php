<?php

	/** The App Router **/

	/** The Front Controller **/
	class Facetroller {

		protected $db;

		public function __construct(){
			global $db;
			$this->db = $db;

			// no need to check the login sessions
		}
	}

	/** The Admin (Backend) Controller **/
	class Asstroller {

		protected $db;

		function __construct(){

			if(Session::getvar("is_logged_in") !== true){
				redirect(Config::url("login"));
			}
			
			global $db;
			$this->db = $db;
		}
	}
