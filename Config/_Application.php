<?php

	/** The main Application Config file **/
	/** info@acpmasquerade.com **/
	/** Framework-26 **/

	switch(ENVIRONMENT){
		case "production":
			error_reporting(0);
			Config::set("base_url", "http://production.yourapp.com/");

			Config::set("db", array(
				"user"=>"dbuser", 
				"password"=>"dbpass", 
				"name"=>"dbname", 
				'host'=>"localhost"));

            Config::set("session_scope_key", "framework26");

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

            Config::set("session_scope_key", "framework26");

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

			Config::set("session_scope_key", "framework26");

			Config::set("default-email-name", "acpmasquerade");
			Config::set("default-email", "info@acpmasquerade.com");

		break;
	}
