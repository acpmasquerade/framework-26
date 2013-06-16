<?php

$active_link = Config::get("active_link");

$user_info = Template::getvar("user_info");

$info = $user_info;

?>

<ul class="nav nav-pills">
	<li <?php echo ($active_link === "summary") ? "class=\"active\"" : "";?>>
		<a href="<?php echo Config::url("account");?>">Summary</a>
	</li>
	<?php if(Helper_User::is_admin() || Helper_User::get_current_user_id() === $user_info->id ) : ?>
		<li <?php echo ($active_link === "password") ? "class=\"active\"" : "";?>><a href="<?php echo Config::url("account/password");?>">Change Password</a></li>
	<?php endif;?>
</ul>