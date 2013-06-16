<div class="row-fluid">
	<div class="dialog">
		<div class="block">
			<p class="block-heading">Forgot Password ?</p>
			<div class="block-body">
				<form id="forgot-password" action="" method="post">
					
					<label for="phone">Phone:</label>
					<input type="text" name="phone" id="phone" class="span12" />
					
					<button type="submit" class="btn btn-primary pull-right">Reset</button>

					<small><em>
						* Please enter your email address
					</em></small>

					<div class="clearfix"></div>

				</form>
			</div>
		</div>
		<p>
            <a href="<?php echo Config::url("login");?>">Back to login</a>
            |
            <a href="<?php echo Config::url("login/signup");?>">Sign Up For a New Accout?</a>            
        </p>
	</div>
</div>