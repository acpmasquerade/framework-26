<?php

	require_once __DIR__."/_Config.class.php";
	require_once __DIR__."/_Session.class.php";
	require_once __DIR__."/_Loader.class.php";
	require_once __DIR__."/_Template.class.php";
	// require_once __DIR__."/_DB.class.php";
	require_once __DIR__."/_Database.class.php";
	require_once __DIR__.'/_utils.functions.php';
	require_once realpath("helpers/helper.php");
	require_once __DIR__."/_Controllers.class.php";

	// Load any notifications from earlier page before redirection
	Template::load_persist_notifications();

/** End of file **/
/** _Bootstrap.php **/
