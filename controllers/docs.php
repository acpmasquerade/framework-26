<?php

class Controller_Docs extends Facetroller {

	public function __construct(){
		parent::__construct();

		Config::set("navigation", true);

	}

	public function index(){

		Config::set("page_title", "Docs");
		$view_data = array();
		Template::set("docs/index", $view_data);
	}

}