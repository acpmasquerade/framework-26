
<?php $info = Template::getvar("user_info"); ?>

<div class="row-fluid">
	<div class="block span6">
		<p class="block-heading">Account Information</p>
		<div class="block-body">
			<table class="table table-striped table-bordered">
				<tr>
                    <th>Username</th>
                    <td><?php echo $info->username;?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo $info->email;?></td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td><?php echo $info->phone;?></td>
                </tr>
                <tr>
                    <th>Role</th>
                    <td><?php echo $info->user_role;?></td>
                </tr>
                <tr>
                    <th>Created</th>
                    <td><?php echo $info->created;?></td>
                </tr>
			</table>
		</div>
	</div>


	<div class="block span6">

		<p class="block-heading">Some Status</p>	

		<div class="block-body">
			<table class="table table-striped table-bordered">
				<tr><td>&nbsp;<i class='icon icon-signal'></i></td></tr>
				<tr><td>&nbsp;<i class='icon icon-signal'></i></td></tr>
				<tr><td>&nbsp;<i class='icon icon-signal'></i></td></tr>
				<tr><td>&nbsp;<i class='icon icon-signal'></i></td></tr>
				<tr><td>&nbsp;<i class='icon icon-signal'></i></td></tr>
			</table>

		</div>


	</div>

</div>