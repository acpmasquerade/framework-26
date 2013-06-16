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
	switch(ENVIRONMENT){
		case "production":
			error_reporting(0);
			Config::set("base_url", "http://production.yourapp.com");

            $db_user = "dbuser";
			$db_password = "dbpass";
			$db_name = "dbname";
			$db_host = "localhost";

            Config::set("session_scope_key", "framework26");

			Config::set("default-email-name", "acpmasquerade");
			Config::set("default-email", "info@acpmasquerade.com");

		break;

		case "staging":
			error_reporting(0);
			Config::set("base_url", "http://staging.yourapp.com");

            $db_user = "dbuser";
			$db_password = "dbpass";
			$db_name = "dbname";
			$db_host = "localhost";

            Config::set("session_scope_key", "framework26");

			Config::set("default-email-name", "acpmasquerade");
			Config::set("default-email", "info@acpmasquerade.com");

		break;

		case "development":
		default:
			error_reporting(E_ALL);
			Config::set("base_url", "http://localhost/work/framework-26/");

			$db_user = "framework26";
			$db_password = "framework26";
			$db_name = "framework26";
			$db_host = "localhost";

			Config::set("session_scope_key", "framework26");

			Config::set("default-email-name", "acpmasquerade");
			Config::set("default-email", "info@acpmasquerade.com");

		break;
	}

	define("SITE_URL", Config::get("base_url"));
	define("DEFAULT_EMAIL_NAME", Config::get("default-email-name"));
	define("DEFAULT_EMAIL", Config::get("default-email"));

	// global $db;
    $db = new DB();
    $db->quick_connect($db_user, $db_password, $db_name, $db_host);

	/** Final Routing **/
	require_once __DIR__.'/Lib/_Router.class.php';
	Router::initialize();

	/** End of file **/
