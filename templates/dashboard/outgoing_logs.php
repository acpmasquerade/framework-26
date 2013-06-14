<?php
$outgoing_logs = Template::getvar("logs");
$max_results = Template::getvar("max_results");
$page_number = Template::getvar("page_number");
$page_prefix = Template::getvar("page_prefix");
$page_suffix = Template::getvar("page_suffix");
$limit = Template::getvar("limit");
$page_number = Template::getvar("page_number");

ob_start();
	build_pagination_links($page_number, $limit, $max_results, $page_prefix, $page_suffix, NULL, false);
	$pagination_links = ob_get_contents();
ob_end_clean();

?>

<table class="table table-striped table-bordered">
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
	</thead>

	<tbody>

		<?php if($outgoing_logs):?>

			<?php foreach($outgoing_logs as $some_outgoing_log): ?>
				<tr>
					<td><?php echo $some_outgoing_log->out_timestamp; ?></td>
					<td><?php echo $some_outgoing_log->created; ?></td>
					<td><?php echo $some_outgoing_log->out_receiver; ?></td>
					<td><?php echo $some_outgoing_log->out_network; ?></td>
					<td><?php echo $some_outgoing_log->out_message; ?></td>
					<td><?php echo $some_outgoing_log->out_sender; ?></td>
					<td><?php echo $some_outgoing_log->out_status; ?></td>
				</tr>
			<?php endforeach;?>

		<?php else: ?>
			<tr><td colspan="7" class="no-record" style="text-align: center;"><small>No Logs found !</small></td></tr>
		<?php endif;?>
	</tbody>	
</table>

<?php echo $pagination_links; ?>