<?php $host_configurations = Template::getvar("host_configurations");?>


<div class="row-fluid">
	<div class="action-bar span12">
		<a href="<?php echo Config::url("admin/config/add");?>" class="btn btn-primary">Add Configuration</a>
	</div>
</div>

<table class="table table-striped table-bordered">

	<thead>
		<tr>
			<th>Host</th>
			<th>Client ID</th>
			<th>Default Credits</th>
			<th>Action</th>
		</tr>
	</thead>

	<tbody>

		<?php foreach($host_configurations as $some_host_configuration):?>

			<tr>
				<td>
					<a href="<?php echo Config::url("admin/config/view/{$some_host_configuration->id}");?>">
						<?php echo $some_host_configuration->host;?>
					</a>
				</td>
				<td><?php echo $some_host_configuration->client_id;?></td>
				<td><?php echo $some_host_configuration->default_credits;?></td>
				<td>
					<a href="<?php echo Config::url("admin/config/edit/{$some_host_configuration->id}");?>">Edit</a>
					/
					<a href="<?php echo Config::url("admin/config/delete/{$some_host_configuration->id}");?>">Delete</a>
				</td>
			</tr>

		<?php endforeach;?>

	</tbody>

</table>