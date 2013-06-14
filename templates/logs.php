<?php

$log_records = Template::getvar("logs");
$log_type = Template::getvar("log_type");

?>

<table class="table table-striped">

	<thead>
		<tr>
			<th>Timestamp</th>
			<th>Created</th>
			<th>Receiver</th>
			<th>Network</th>
			<th>Message</th>
			<th>Sender</th>
			<th>Status</th>
		</tr>

<?php if($log_records):?>

	<?php foreach($log_records as $some_log_record):?>

		<tr>
			<td><?php echo $some_log_record["timestamp"];?></td>
			<td><?php echo $some_log_record["created"];?></td>
			<td><?php echo $some_log_record["receiver"];?></td>
			<td><?php echo $some_log_record["network"];?></td>
			<td><?php echo $some_log_record["message"];?></td>
			<td><?php echo $some_log_record["sender"];?></td>
			<td><?php echo $some_log_record["status"];?></td>
		</tr>

	<?php endforeach?>

<?php else: ?>

	<p align="center"><em>No Logs Found !</em></p>

<?php endif;?>
