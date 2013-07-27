<?php

	/** The main Application Config file **/
	/** info@acpmasquerade.com **/
	/** Framework-26 **/

	/**
	 *	base_url
	 *	; The application BASE URL
	 *
	 *	db [ user=> , password => , name =>, host =>]
	 *	; database connection parameters
	 *
	 *	db_saftey_debug
	 *	; Enable / Disable DB Saftey Debugging. If enabled, Logs will be written inside Logs/ folder.
	 *
	 *	debug_bar
	 *	; If enabled, will display a debug bar at the top showing benchmarking stats and other information.
	 *
	 *	kint_debug
	 *	; If enabled, will enable the Kint debugging. - http://raveren.github.io/kint
	 *
	 *	session_scope_key
	 *	; If different sites / apps hosted in same domain, this helps in separating the SESSION scope
	 *
	 *	default-email-name
	 *	; Name for automatic emails
	 *
	 *	default-email - 
	 *	; From Email address for automatic emails
	 *
	 *	regex_valid_phone_number
	 *	; Used by is_valid_phone_number method to determine whether an input is valid or not.
	 *	; can be configurable so that number validation can be chosen as per requirements.
	 */

	switch(ENVIRONMENT){
		case "production":
			error_reporting(0);
			Config::set("base_url", "http://production.yourapp.com/");

			// Database
			Config::set("db", array(
				"user"=>"dbuser", 
				"password"=>"dbpass", 
				"name"=>"dbname", 
				'host'=>"localhost"));

			// Debug
			Config::set("db_saftey_debug", true);
			Config::set("debug_bar", false);
			Config::set("kint_debug", false);

			// Session
            Config::set("session_scope_key", "framework26");

            // Regular expressions
            Config::set("regex_valid_phone_number", "/^(977)?9[6-8][0-9]{8}$/");

            // Email
			Config::set("default-email-name", "acpmasquerade");
			Config::set("default-email", "info@acpmasquerade.com");

		break;

		case "staging":
			error_reporting(0);
			Config::set("base_url", "http://staging.yourapp.com/");

			Config::set("db", array(
				"user"=>"dbuser", 
				"password"=>"dbpass", 
				"name"=>"dbname", 
				'host'=>"localhost"));

			Config::set("db_saftey_debug", true);
			Config::set("debug_bar", false);
			Config::set("kint_debug", false);

            Config::set("session_scope_key", "framework26");

            Config::set("regex_valid_phone_number", "/^(977)?9[6-8][0-9]{8}$/");

			Config::set("default-email-name", "acpmasquerade");
			Config::set("default-email", "info@acpmasquerade.com");

		break;

		case "development":
		default:
			error_reporting(E_ALL);
			Config::set("base_url", "http://localhost/work/framework-26/");

			Config::set("db", array(
				"user"=>"framework26", 
				"password"=>"framework26", 
				"name"=>"framework26", 
				'host'=>"localhost"));

			Config::set("db_saftey_debug", true);
			Config::set("debug_bar", true);
			Config::set("kint_debug", true);

			Config::set("session_scope_key", "framework26");

            Config::set("regex_valid_phone_number", "/^(977)?9[6-8][0-9]{8}$/");

			Config::set("default-email-name", "acpmasquerade");
			Config::set("default-email", "info@acpmasquerade.com");

		break;
	}
