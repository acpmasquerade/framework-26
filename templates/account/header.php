<?php

$active_link = Config::get("active_link");

$user_info = Template::getvar("user_info");
$info = $user_info["info"];	

?>

<ul class="nav nav-pills">
	<li <?php echo ($active_link === "summary") ? "class=\"active\"" : "";?>><a href="<?php echo Config::url("account");?>">Summary</a></li>
	<?php if(Helper_User::is_super_user() || 
			((Helper_User::is_admin() &&
			(Helper_User::get_current_user_id() === $info->id) || 
			(Helper_User::get_current_user_client_id() === $info->client_id)))):?>
		<li <?php echo ($active_link === "password") ? "class=\"active\"" : "";?>><a href="<?php echo Config::url("account/password");?>">Change Password</a></li>
		<li <?php echo ($active_link === "credits") ? "class=\"active\"" : "";?>><a href="<?php echo Config::url("account/credits");?>">Credits</a></li>
	<?php endif;?>
</ul>