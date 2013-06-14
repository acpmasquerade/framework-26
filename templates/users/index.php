<?php $users = Template::getvar("users");?>

<style type="text/css">
	
	.action-bar {
		margin-bottom: 10px;
	}

	.status{
		text-align: center !important;
	}

	.status.status-active{
		background: lightgreen !important;
	}

	.status.status-inactive{
		background: orange !important;
	}
	.status.status-deleted{
		background: grey !important;
	}

</style>

<div class="row-fluid">
	<div class="action-bar span12">
		<div class="span6 pull-right">
			<form class="form-search pull-right" action="<?php echo Config::url("user/search");?>" method="post">
				<input type="text" class="input-xlarge" name="query" placeholder="Search by username or email or phone">
				<button type="submit" class="btn btn-primary">Search</button>
			</form>
		</div>
		<a href="<?php echo Config::url("user/add");?>" class="btn btn-primary">Add</a>
	</div>
</div>

</div>

<div class="row-fluid">

	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th colspan=2>Username</th>
				<th>Client ID</th>
				<th>Email</th>
				<th>Phone</th>
				<th>Role</th>
				<th>Status</th>
				<th width="10">Login</th>
				<th width="10">Delete</th>
			</tr>
		</thead>

		<tbody>

			<?php if($users):?>

				<?php $current_user_user_id = Helper_User::get_current_user_id(); ?>

				<?php foreach($users as $some_user): ?>

					<tr>
						<td width=20>
							<img src="<?php echo Helper_User::gravatar_image_url($some_user->email, 20); ?>" />
						</td>
						<td>
							<a href="<?php echo Config::url("account/view/{$some_user->id}");?>" title="View Profile">
								<?php echo $some_user->username;?>
							</a>
						</td>
						<td><?php echo $some_user->client_id;?></td>
						<td><?php echo $some_user->email;?></td>
						<td><?php echo $some_user->phone;?></td>
						<td><?php echo $some_user->user_role;?></td>
						<td class='status status-<?php echo $some_user->status;?>'><?php echo $some_user->status;?></td>
						<?php if(Helper_ACL::has_higher_privileges($some_user->user_role)):?>
						<td>							
							<?php if($some_user->id !== $current_user_user_id): ?>
							<a href="<?php echo Config::url("user/login/{$some_user->id}");?>">
								<i class='icon icon-share-alt btn btn-primary btn-small'></i>
							</a>
							<?php endif; ?>
						</td>
						<td>
							<a href="<?php echo Config::url("user/delete/{$some_user->id}") ;?>">
								<i class='icon icon-remove btn btn-danger btn-small'></i>
							</a>
						</td>
						<?php else: ?>
						<td>-</td>
						<td>-</td>
						<?php endif;?>
					</tr>

				<?php endforeach;?>

			<?php else: ?>

				<tr>
					<td class="no-record" colspan="7">No results found !</td>
				</tr>

			<?php endif;?>
		</tbody>

	</table>
</div>