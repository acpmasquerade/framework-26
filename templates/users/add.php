<?php

$clients = Template::getvar("clients");
$formdata = Template::getvar("formdata");

?>

<form id="user-add-form" action="" method="post" class="form form-horizontal">

	<?php if(Helper_User::is_super_user()):?>

		<div class="control-group">
			<label class="control-label" for="client_id">Client ID</label>
			<div class="controls">
				<select name="client_id">
					<?php foreach($clients as $some_client): ?>
					<?php 
						if($formdata["client_id"] == $some_client->client_id) {$some_client_selected_status = "selected"; }
						else{$some_client_selected_status = ""; }
					?>
					<option value="<?php echo $some_client->client_id;?>" <?php echo $some_client_selected_status;?>>
						<?php echo $some_client->client_id;?>
					</option>
					<?php endforeach; ?>
				</select>
				<a href='#new-client' id='anchor-add-new-client' class='btn btn-primary btn-small'>
					<i class='icon icon-plus'></i>
				</a>
				Add new client ?
			</div>
		</div>

	<?php endif;?>

	<div class="control-group">
		<label class="control-label" for="username">Username</label>
		<div class="controls">
			<input type="text" name="username" id="username" class="input-large" />
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="password">Password</label>
		<div class="controls">
			<input type="password" name="password" id="password" class="input-large" />
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="email">Email</label>
		<div class="controls">
			<input type="text" name="email" id="email" class="input-large" />
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="phone">Phone</label>
		<div class="controls">
			<input type="text" name="phone" id="phone" class="input-large" />
		</div>
	</div>

	<?php if( Helper_User::is_admin() ): ?>

		<div class="control-group">
			<label class="control-label" for="user_role">User Role</label>
			<div class="controls">
				<select name="user_role" id="user_role">
					<option value="admin">User</option>
					<option value="admin">Reseller</option>

					<?php if( Helper_User::is_super_user() ):?>"
						<option value="admin">Admin</option>
					<?php endif;?>

				</select>
			</div>
		</div>

	<?php endif;?>

	<div class="form-actions">
		<button class="btn btn-primary" type="submit" id="btn-add-user">Save</button>
	</div>

</form>



<SCRIPT TYPE="text/javascript">
	var new_client_id = "";
	
	function prompt_new_client_id(){
		new_client_id = window.prompt("Please choose a new client_id");
		validate_new_client_id();
	}

	function validate_new_client_id(){
		$.ajax("<?php echo Config::url('user/');?>")
	}

	$(function(){
		$("#anchor-add-new-client").click(function(){
			// throw a bootbox or any form to take a new client_id as input.
			// alert("@todo - throw a bootbox");
			prompt_new_client_id();
		});
	})
</SCRIPT>