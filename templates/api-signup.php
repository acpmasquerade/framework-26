<div class="row-fluid">
	<div class="dialog">
		<div class="block">
			<p class="block-heading">Signup</p>
			<div class="block-body">
				<form id="api-signup" action="" method="post">
					<label for="username">Username</label>
					<input type="text" name="username" class="span12" id="username" />

					<label for="email">Email</label>
					<input type="text" name="email" class="span12" id="email" />
					
	                <input type="submit" class="btn btn-primary pull-right" value="Sign Up" />

	                <div class="clearfix"></div>
				</form>
			</div>
		</div>
		<a href="<?php echo Config::url("login");?>">Already have an account ? Sign in here !</a> 
	</div>
</div>