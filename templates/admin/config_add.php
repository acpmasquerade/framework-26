<form id="admin-config" action="" method="post" class="form form-horizontal">

	<div class="control-group">
		<label class="control-label" for="host">Host / Website</label>
		<div class="controls">
			<input type="text" name="host" class="input-large" id="host" />
		</div>	
	</div>

	<div class="control-group">
		<label class="control-label" for="client_id">Client ID</label>
		<div class="controls">
			<input type="text" name="client_id" class="input-large" id="client_id" />
		</div>	
	</div>

	<div class="control-group">
		<label class="control-label" for="default_credits">Default Credits</label>
		<div class="controls">
			<input type="text" name="default_credits" class="input-large" id="default_credits" />
		</div>	
	</div>

	<div class="control-group">
		<label class="control-label">Allowed Networks</label>
		<div class="controls">

			<?php foreach (Helper_General::$networks as $some_network): ?>
				<label class="checkbox inline">
					<input type="checkbox" name="allowed_networks[]" value="<?php echo $some_network;?>"
					class="input-large" checked="checked" />
					<?php echo ucwords($some_network);?>
				</label>
			<?php endforeach;?>
			
		</div>	
	</div>



	<div class="control-group">
		<label class="control-label">Allowed Shortcodes</label>
		<div class="controls">

			<?php foreach (Helper_General::$shortcodes as $some_shortcode): ?>
				<label class="checkbox inline">
					<input type="checkbox" name="allowed_shortcodes[]" value="<?php echo $some_shortcode;?>"
					class="input-large" checked="checked" />
					<?php echo ucwords($some_shortcode);?>
				</label>
				
			<?php endforeach;?>
		</div>	
	</div>

	<div class="form-actions">
		<button type="submit" name="save_configuration" class="btn btn-primary">Save</button>
	</div>

</form>