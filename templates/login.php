<div class="row-fluid">
    <div class="dialog">
        <div class="block">
            <p class="block-heading">Sign In</p>
            <div class="block-body">
                <form method="POST" action="<?php echo Config::get("base_url");?>login">

                    <label for="client_id">Client ID</label>
                    <input type="text" name="client_id" id="client_id" class="span12" />

                    <label for="username">Username</label>
                    <input type="text" class="span12" name="username">

                    <label for="password">Password</label>
                    <input type="password" class="span12" name="password">
                    
                    <input type='submit' class="btn btn-primary pull-right" value="Sign In" />
                    <!-- <label class="remember-me"><input type="checkbox"> Remember me</label> -->
                    <div class="clearfix"></div>
                </form>
            </div>
        </div>
        <p>
            <a href="<?php echo Config::url("login/signup");?>">Sign Up</a> 
            |
            <a href="<?php echo Config::url("login/forgot_password");?>">Forgot password?</a>
        </p>
    </div>
</div>