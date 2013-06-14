<table class="table table-bordered table-striped">
	<tr>
		<th>HTTP Response Code</th>
		<th>Response Type</th>
		<th>API Response Code</th>
		<th>Message</th>
		<th>Remarks</th>
	</tr>
	
	<tr>
		<td>403</td>
		<td>Forbidden</td>
		<td>Error 1: 403</td>
		<td>You must be someone whom I know. Client Id not supplied</td>
		<td>&nbsp;</td>
	</tr>
	
	<tr>
		<td>403</td>
		<td>Forbidden</td>
		<td>Error 2: 403</td>
		<td>Unauthorized Caller IP</td>
		<td>When API call is restricted to some IPs only.</td>
	</tr>
	
	<tr>
		<td>400</td>
		<td>Syntax Error</td>
		<td>Error 3: 400</td>
		<td>Empty SMS Message Text</td>
		<td>&nbsp;</td>
	</tr>
	
	<tr>
		<td>500</td>
		<td>Server Error</td>
		<td>Error 4: 500</td>
		<td>Connection Error</td>
		<td>&nbsp;</td>
	</tr>
	
	<tr>
		<td>500</td>
		<td>Server Error</td>
		<td>Error 5: 500</td>
		<td>Connection Error</td>
		<td>&nbsp;</td>
	</tr>
	
	<tr>
		<td>403</td>
		<td>Forbidden</td>
		<td>Error 6: 403</td>
		<td>No such user</td>
		<td>&nbsp;</td>
	</tr>
	
	<tr>
		<td>403</td>
		<td>Forbidden</td>
		<td>Error 7: 403</td>
		<td>Authentication Failed</td>
		<td>&nbsp;</td>
	</tr>
	
	<tr>
		<td>403</td>
		<td>Forbidden</td>
		<td>Error 8: 403</td>
		<td>Zero SMS Credits</td>
		<td>&nbsp;</td>
	</tr>
	
	<tr>
		<td>403</td>
		<td>Forbidden</td>
		<td>Error 9: 403</td>
		<td>Credits Expired on &lt;some-expiry-date&gt;</td>
		<td>&nbsp;</td>
	</tr>
	
	<tr>
		<td>403</td>
		<td>Forbidden</td>
		<td>Error 10: 403</td>
		<td>No Shortcodes available in your Roster</td>
		<td>&nbsp;</td>
	</tr>
	
	<tr>
		<td>400</td>
		<td>Syntax Error</td>
		<td>Error 11: 400</td>
		<td>Must supply a Shortcode for Network &lt;some-network-&gt;</td>
		<td>&nbsp;</td>
	</tr>

	<tr>
		<td>400</td>
		<td>Syntax Error</td>
		<td>Error 110: 400</td>
		<td>Recipient number is missing or invalid.</td>
		<td>&nbsp;</td>
	</tr>
	
	<tr>
		<td>403</td>
		<td>Forbidden</td>
		<td>Error 12: 403</td>
		<td>Shortcode &lt;some-shortcode&gt; is not allowed for Network &lt;some-network&gt;</td>
		<td>&nbsp;</td>
	</tr>
	
	<tr>
		<td>403</td>
		<td>Forbidden</td>
		<td>Error 13: 403</td>
		<td>Shortcode &lt;some-shortcode&gt; is not allowed for Network &lt;some-network&gt;</td>
		<td>&nbsp;</td>
	</tr>
	
	<tr>
		<td>403</td>
		<td>Forbidden</td>
		<td>Error 14: 403</td>
		<td>Shortcode &lt;some-shortcode&gt; is not allowed for Network &lt;some-network&gt;</td>
		<td>&nbsp;</td>
	</tr>
	
	<tr>
		<td>403</td>
		<td>Forbidden</td>
		<td>Error 14a: 403</td>
		<td>Invalid Identity</td>
		<td>&nbsp;</td>
	</tr>
	
	<tr>
		<td>403</td>
		<td>Forbidden</td>
		<td>Error 15: 403</td>
		<td>Insufficient SMS Credits</td>
		<td>&nbsp;</td>
	</tr>
	
	<tr>
		<td>500</td>
		<td>Communication Error</td>
		<td>Error 16: 500</td>
		<td>Server Communication Error</td>
		<td>&nbsp;</td>
	</tr>
	
	<tr>
		<td>500</td>
		<td>Transaction Error</td>
		<td>Error 17: 500</td>
		<td>Error in Database Transaction</td>
		<td>&nbsp;</td>
	</tr>
	
	<tr>
		<td>500</td>
		<td>Server Error</td>
		<td>Error 100:</td>
		<td>FATAL Error</td>
		<td>&nbsp;</td>
	</tr>
	
	<tr>
		<td>202</td>
		<td>Accepted</td>
		<td>Error 200:</td>
		<td>Some Errors Exist</td>
		<td>Partial success</td>
	</tr>
	
	<tr>
		<td>200</td>
		<td>OK</td>
		<td>Success 200:</td>
		<td>No Errors Occured</td>
		<td>Complete success</td>
	</tr>
</table>