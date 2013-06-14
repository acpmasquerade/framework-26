<div class="row-fluid">
	<div class="block span6">
		<p class="block-heading">Account Information</p>
		<div class="block-body">
			<table class="table table-striped table-bordered">
				<tr><th>Username </th><td><?php echo $info->username;?></td></tr>
				<tr><th>Email </th><td><?php if(isset($info->email)) { echo $info->email; } ?></td></tr>
				<tr><th>Client ID </th><td><?php if(isset($info->client_id)) { echo $info->client_id; } ?></td></tr>
				<tr><th>Phone </th><td><?php if(isset($info->phone)) { echo $info->phone; } ?></td></tr>
			</table>
		</div>
	</div>


	<div class="block span6">

		<p class="block-heading">Credits Status</p>	

		<div class="block-body">
			<table class="table table-striped table-bordered">
				<tr>
					<th>Policy </th>
					<td><?php if(isset($info->credit_policy)) { echo $info->credit_policy; } ?></td>
				</tr>
				<tr>
					<th>Available</th>
					<td><?php echo $info->available_credits;?></td>
				</tr>
				<tr>
					<th>Consumed</th>
					<td>
						<?php echo $info->consumed_credits;?>
						<?php echo anchor("account/credits", "History"); ?>
					</td>
				</tr>
				<tr>
					<th>Validity</th>
					<td>
						<?php 
							if($info->credit_expires == '0000-00-00 00:00:00'){
		                        echo "<span class='label label-success'>Lifetime</span>";
		                    }else{
	                            $_account_expiry = strtotime($info->credit_expires);
	                            $_time = time();
	                            if($_time > $_account_expiry){
	                                echo "<span class='label label-important'>Expired</span>";
	                            }else{
	                                $days_remaining = floor ( ($_account_expiry - $_time) / 86400 );

	                                if($days_remaining < 5){
										$label_flag = 'important';
	                                }elseif($days_remaining < 10){
										$label_flag = 'warning';
	                                }else{
										$label_flag = 'success';
	                                }

	                                echo "<span class='label label-{$label_flag}'>{$days_remaining} Days remaining</span>";
	                            }
	                        }
                        ?>
                    </td>
				</tr>
			</table>

		</div>


	</div>

</div>