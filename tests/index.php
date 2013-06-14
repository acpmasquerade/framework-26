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
			Config::set("email-domain", "mofald.gov.np");
            // DB::connect("localhost", "sparrowMofald", "yBvYpDbP4e3CcjED", "sparrowsms_mofald");
            // $db->quick_connect("sparrowMofald", "yBvYpDbP4e3CcjED", "sparrowsms_mofald", "localhost");

            $db_user = "sparrowMofald";
			$db_password = "yBvYpDbP4e3CcjED";
			$db_name = "sparrowsms_mofald";
			$db_host = "localhost";

            Config::set("session_scope_key", "sparrowsmsapi");
			Config::set("sparrowsms_client_id", "sparrowsmsapi");
			Config::set("sparrowsms_username", "sparrowsmsapi");
			Config::set("sparrowsms_password", "aa6af5e7280b6e16d18739bdd7cf6351");
			Config::set("sparrowsms_from", "5001");
		break;

		case "staging":
			error_reporting(0);
			Config::set("base_url", "http://api.sparrowsms.com/");
			Config::set("email-domain", "sparrowsms.com");
			// DB::connect("localhost", "mofald", "a9sd8yfajsdfasdfasd", "mofald");
			// $db->quick_connect("mofald", "a9sd8yfajsdfasdfasd", "mofald", "localhost");

			$db_user = "mofald";
			$db_password = "a9sd8yfajsdfasdfasd";
			$db_name = "mofald";
			$db_host = "localhost";

			Config::set("session_scope_key", "sparrowsmsapi");
			Config::set("sparrowsms_client_id", "sparrowsmsapi");
			Config::set("sparrowsms_username", "sparrowsmsapi");
			Config::set("sparrowsms_password", "aa6af5e7280b6e16d18739bdd7cf6351");
			Config::set("sparrowsms_from", "5001");
		break;

		case "development":
		default:
			error_reporting(E_ALL);
			Config::set("base_url", "http://localhost/api.sparrowsms.com/");
			Config::set("email-domain", "sparrowsms.com");
			// DB::connect("localhost", "mofald", "4ZEfX5fWVdEhMwTG", "mofald");
			// $db->quick_connect("mofald", "4ZEfX5fWVdEhMwTG", "mofald", "localhost");

			$db_user = "sparrowsmsapi";
			$db_password = "sparrowsmsapi";
			$db_name = "sparrowsms_apiclients";
			$db_host = "localhost";

			Config::set("session_scope_key", "sparrowsmsapi");
			Config::set("sparrowsms_client_id", "sparrowsmsapi");
			Config::set("sparrowsms_username", "sparrowsmsapi");
			Config::set("sparrowsms_password", "aa6af5e7280b6e16d18739bdd7cf6351");
			Config::set("sparrowsms_from", "5001");
		break;
	}

	global $db;

    $db = new DB();

    $db->quick_connect($db_user, $db_password, $db_name, $db_host);

	/** Final Routing **/
	require_once __DIR__.'/Lib/_Router.class.php';
	Router::initialize();

	/** End of file **/
