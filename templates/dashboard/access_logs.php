<?php 
$logs = Template::getvar("logs");
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
			<th rowspan="2">ID</th>
			<th rowspan="2">Created</th>
			<th rowspan="2">Username</th>
			<th rowspan="2">Client_id</th>
			<th colspan="3">Response</th>
			<th rowspan="2">Request</th>
		</tr>

		<tr>
			<th>Headers</th>
			<th>Code</th>
			<th>Content</th>
		</tr>

	</thead>

	<tbody>

		<?php if($logs):?>

			<?php foreach($logs as $some_log_item): ?>
				<tr>
					<td><?php echo $some_log_item->id;?></td>
					<td><?php echo $some_log_item->created; ?></td>
					<td><?php echo $some_log_item->username;?></td>
					<td><?php echo $some_log_item->client_id;?></td>

					<?php $some_log_item_headers = json_decode($some_log_item->headers, TRUE); ?>
					<?php 
						if(!is_array($some_log_item_headers)){
							$some_log_item_headers = array($some_log_item_headers);
						}
						$some_log_item_header_pieces = explode(" ", $some_log_item_headers[0]);
					?>

					<td class='http http-<?php echo substr($some_log_item_header_pieces[1], 0, 2); ?>'>
						<?php echo $some_log_item_headers[0]; ?>
					</td>
					<td class='http http-<?php echo substr($some_log_item_header_pieces[1], 0, 2); ?>'>
						<?php echo $some_log_item_header_pieces[1]; ?>
					</td>
					<td>
						<?php echo array_pop(json_decode($some_log_item->message));?>
					</td>
					<td>
						<table class='table'>
						<?php foreach(json_decode($some_log_item->request) as $some_request_key=>$some_request_item): ?>
						<tr>
							<th><?php echo $some_request_key;?></th><td><?php echo $some_request_item;?></td>
						</tr>
						<?php endforeach;?>
						</table>
					</td>
				</tr>
			<?php endforeach; ?>

		<?php else: ?>
			<tr><td colspan="8" class="no-record" style="text-align: center;">
				<small>No Logs found !</small>
			</td></tr>
		<?php endif;?>
	</tbody>
</table>

<?php build_pagination_links($page_number, $limit, $max_results, $page_prefix, $page_suffix, NULL, false); ?>