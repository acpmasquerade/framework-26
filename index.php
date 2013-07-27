<?php
	/**
	 * - Framework-26
	 * - hopefully an ultra-light PHP Framework
	 * - @acpmasquerade
	 * - License : Pick one yourself.
	 */
	session_start();

	define("ENVIRONMENT", "development");

	/** Bootstrap everything **/
	require_once __DIR__."/Lib/_Bootstrap.php";	

	/** Config and environment setup **/
	
	if(!defined('ENVIRONMENT')){
		die("Unable to determine the runtime environment.");
	}

	require_once __DIR__."/Config/_Application.php";

	// Load the Kint Debugger, if enabled
	if(Config::get("kint_debug") === true){
		Bootstrap::Kint_Debugger();
	}

	define("SITE_URL", Config::get("base_url"));
	define("DEFAULT_EMAIL_NAME", Config::get("default-email-name"));
	define("DEFAULT_EMAIL", Config::get("default-email"));

	// global $db;
    $db = new DB();
    $db_args = Config::get("db");
    $db->quick_connect($db_args["user"], $db_args["password"], $db_args["name"], $db_args["host"]);

	/** Final Routing **/
	require_once __DIR__.'/Lib/_Router.class.php';
	Router::initialize();

	/** End of file **/
