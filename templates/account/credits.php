<?php $credit_history = Template::getvar("credit_history"); ?>

<table class="table table-striped table-bordered">

	<thead>
		<th>Last Balance</th>
		<th>Credits Added</th>
		<th>Available Credits</th>
		<th>Date</th>
		<th>Remarks</th>
	</thead>

	<tbody>

		<?php if(isset($credit_history) AND is_array($credit_history)): ?>
			<?php foreach($credit_history as $some_credit_history): ?>

				<tr>
					<td><?php echo $some_credit_history->credits_remaining; ?></td>
					<td><?php echo $some_credit_history->credits_added; ?></td>
					<td><?php echo $some_credit_history->credits_available; ?></td>
					<td><?php echo $some_credit_history->created; ?></td>
					<td><?php echo $some_credit_history->remarks; ?></td>	
				</tr>

			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td colspan=5>
					Record(s) not available
				</td>
			</tr>
		<?php endif; ?>

	</tbody>

</table>