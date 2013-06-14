<?php
	session_start();

	define("ENVIRONMENT", "development");

	/** Bootstrap everything **/
	require_once __DIR__."/Lib/_Bootstrap.php";	

	/** Config and environment setup **/
	switch(ENVIRONMENT){
		case "production":
			error_reporting(0);
			Config::set("base_url", "http://devapi.sparrowsms.com");

            $db_user = "dbuser";
			$db_password = "dbpass";
			$db_name = "dbname";
			$db_host = "localhost";

            Config::set("session_scope_key", "sparrowsmsapi");
			Config::set("sparrowsms_client_id", "sparrowsmsapi");
			Config::set("sparrowsms_username", "sparrowsmsapi");
			Config::set("sparrowsms_password", "aa6af5e7280b6e16d18739bdd7cf6351");
			Config::set("sparrowsms_from", "5001");

			Config::set("default-email-name", "Sparrow SMS");
			Config::set("default-email", "developers@sparrowsms.com");		

		break;

		case "staging":
			error_reporting(0);
			Config::set("base_url", "http://devapi.sparrowsms.com");

            $db_user = "dbuser";
			$db_password = "dbpass";
			$db_name = "dbname";
			$db_host = "localhost";

            Config::set("session_scope_key", "sparrowsmsapi");
			Config::set("sparrowsms_client_id", "sparrowsmsapi");
			Config::set("sparrowsms_username", "sparrowsmsapi");
			Config::set("sparrowsms_password", "aa6af5e7280b6e16d18739bdd7cf6351");
			Config::set("sparrowsms_from", "5001");

			Config::set("default-email-name", "Sparrow SMS");
			Config::set("default-email", "developers@sparrowsms.com");		

		break;

		case "development":
		default:
			error_reporting(E_ALL);
			Config::set("base_url", "http://localhost/work/framework-26/");

			$db_user = "framework26";
			$db_password = "framework26";
			$db_name = "framework26";
			$db_host = "localhost";

			Config::set("session_scope_key", "sparrowsmsapi");
			Config::set("sparrowsms_client_id", "sparrowsmsapi");
			Config::set("sparrowsms_username", "sparrowsmsapi");
			Config::set("sparrowsms_password", "aa6af5e7280b6e16d18739bdd7cf6351");
			Config::set("sparrowsms_from", "5001");

			Config::set("default-email-name", "Sparrow SMS");
			Config::set("default-email", "developers@sparrowsms.com");		

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
