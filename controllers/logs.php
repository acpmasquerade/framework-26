<?php
	class Controller_Logs extends Asstroller{
		function __construct(){
			parent::__construct();

			Config::set("navigation", true);

			Config::set("controller", "report");
		}

		function index(){
			Config::set("page_title", "Reports");
			Template::set("report/index", array());
		}
	}