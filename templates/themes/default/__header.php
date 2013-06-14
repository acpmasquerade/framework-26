<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo strip_tags(Config::get("page_title")); ?></title>
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <link 
            rel="stylesheet"
            type="text/css" 
            href="<?php echo Config::get("base_url"); ?>templates/lib/bootstrap/css/bootstrap.css">

        <link
            rel="stylesheet"
            type="text/css"
            href="<?php echo Config::get("base_url"); ?>templates/stylesheets/theme.css">
        <link
            rel="stylesheet"
            type="text/css"
            href="<?php echo Config::get("base_url"); ?>templates/lib/font-awesome/css/font-awesome.css">

        <link
            rel="stylesheet"
            type="text/css"
            href="<?php echo Config::get("base_url"); ?>templates/stylesheets/custom.css">

        <link
            rel="stylesheet"
            type="text/css"
            href="<?php echo Config::get("base_url"); ?>templates/stylesheets/datepicker.css">

            <link
            rel="stylesheet"
            type="text/css"
            href="<?php echo Config::get("base_url"); ?>templates/stylesheets/prettify.css">

        <script 
            src="<?php echo Config::get("base_url"); ?>templates/lib/jquery-1.7.2.min.js" 
        type="text/javascript"></script>

        <script 
            src="<?php echo Config::get("base_url"); ?>templates/js/bootstrap-datepicker.js" 
        type="text/javascript"></script>
        <script 
            src="<?php echo Config::get("base_url"); ?>templates/js/prettify.js" 
        type="text/javascript"></script>

        <!-- Demo page code -->

        <style type="text/css">
            #line-chart {
                height:300px;
                width:800px;
                margin: 0px auto;
                margin-top: 1em;
            }
            .brand { font-family: georgia, serif; }
            .brand .first {
                color: #ccc;
                font-style: italic;
            }
            .brand .second {
                color: #fff;
                font-weight: bold;
            }
        </style>

        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- Le fav and touch icons -->
    </head>

    <!--[if lt IE 7 ]> <body class="ie ie6"> <![endif]-->
    <!--[if IE 7 ]> <body class="ie ie7 "> <![endif]-->
    <!--[if IE 8 ]> <body class="ie ie8 "> <![endif]-->
    <!--[if IE 9 ]> <body class="ie ie9 "> <![endif]-->
    <!--[if (gt IE 9)|!(IE)]><!--> 
    <body class=""> 
        <!--<![endif]-->

        <div class="navbar">
            <div class="navbar-inner">
                <?php if (Session::getvar("is_logged_in") === true): ?>

                    <?php $user = Session::getvar("user"); ?>

                    <ul class="nav pull-right">
                        <li id="fat-menu" class="dropdown">
                            <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="icon-user"></i> <?php echo $user->client_id; ?> / <?php echo $user->username; ?>
                                <i class="icon-caret-down"></i>
                            </a>

                            <ul class="dropdown-menu">
                                <li><a tabindex="-1" href="<?php echo Config::url("account"); ?>">My Account</a></li>
                                <li class="divider"></li>
                                <li><a tabindex="-1" class="visible-phone" href="<?php echo Config::get("base_url"); ?>settings">Settings</a></li>
                                <li class="divider visible-phone"></li>
                                <li><a tabindex="-1" href="<?php echo Config::get("base_url"); ?>logout">Logout</a></li>
                            </ul>
                        </li>

                    </ul>
                <?php endif; ?>

                <a class="brand" href="<?php echo Config::url(""); ?>"><span class="first">Sparrow SMS</span> <span class="second">- API </span></a>
            </div>
        </div>

        <?php if (Config::get("navigation") === true): ?>

            <?php
            $navigation_bar = Config::get("navigation_bar");

            if (!$navigation_bar) {
                include_once __DIR__ . "/__navigation.php";
            } else {
                include_once __DIR__ . "/{$navigation_bar}.php";
            }
            ?>

            <div class="content">
                <div class="header">   
                    <?php if(Session::getvar("is_logged_in") === true): ?>

                        <?php
                            if(isset($user->available_credits)){
                                $user_credits = $user->available_credits;
                            } else {
                                $user_credits = 0;
                            }
                        ?>

                        <div class="pull-right">
                            <ul class="stats">
                                <li class="green">
                                    <i class="icon-briefcase"></i>
                                    <div class="details">
                                        <span class="big"><?php echo $user_credits;?> credits</span>
                                        <?php if($user->credit_expires == '0000-00-00 00:00:00'): ?>
                                            <span>Lifetime validity</span>
                                        <?php else: ?>

                                        <?php
                                            if(isset($user) AND $user){
                                                $_account_expiry = strtotime($user->credit_expires);
                                                $_validity_word = "";
                                                $_time = time();
                                                if($_time > $_account_expiry){
                                                    $_validity_word = "Expired";
                                                }else{
                                                    echo floor ( ($_account_expiry - $_time) / 86400 ) ." Days";
                                                    $_validity_word = floor ( ($_account_expiry - $_time) / 86400 ) ." Days Remaining";
                                                }
                                            }else{
                                                $_validity_word = "api.sparrowsms.com";
                                            }
                                            
                                            echo $_validity_word;
                                            
                                            ?>
                                            <span></span>
                                        <?php endif;?>
                                    </div>
                                </li>
                                <li class="orange">
                                    <i class="icon-calendar"></i>
                                    <div class="details">
                                        <span class="big"><?php echo date("F j, Y");?></span>
                                        <span><?php echo date("l");?></span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    <?php endif;?>
                    
                    <h1 class="page-title"><?php echo Config::get("page_title"); ?></h1>
                </div>

                <ul class="breadcrumb">
                    <li><a href="<?php echo Config::get("base_url"); ?>">Home</a> <span class="divider">/</span></li>
                    <li class="active"><?php echo ucwords(Config::get("page_title")); ?></li>
                </ul>


                <div class="container-fluid">
                    <div id="docs-container">
                        <?php
                        if (Config::get("header")) {
                            $header_hero_unit = Config::get("header");
                            if(file_exists(__DIR__."/{$header_hero_unit}.php")){
                                include_once __DIR__ . "/{$header_hero_unit}.php";
                            }else{
                                include_once realpath(__DIR__ . "/../../{$header_hero_unit}.php");
                            }
                        }
                        ?>



                    <?php endif; ?>

                    <?php if (Template::has_notifications()): ?>      
                        <?php foreach (Template::get_notifications() as $some_scope => $some_notifications): ?>
                            <?php if (count($some_notifications)): ?>
                                <div class='alert-group'>
                                    <?php foreach ($some_notifications as $some_notification_item): ?>
                                        <div class='alert alert-<?php echo $some_scope; ?>'>
                                            <?php echo $some_notification_item; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
