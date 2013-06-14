<?php

class Controller_Dashboard extends Asstroller {

	private $user_id;

	public function __construct(){
		parent::__construct();
		Config::set("navigation", true);

		$this->user_id = Session::getvar("is_logged_in_user_id");

		Loader::helper("dashboard");
		Loader::helper("acl");
	}

	public function index(){
		// the user information can be obtained from the view via static session class
		// get the list of announcements from the database
		// if there are any, populate them with respective alerts

		$where = array("status" => "active");
		$announcements = Helper_Dashboard::get_announcements();

		$user = Session::getvar("user");

		$stats = Helper_Dashboard::report_generate_consumption_graph_by_client_id($user->client_id);

		$chart_content = $this->generate_consumption_report($stats);

		$view_data["chart_block_content"] = $chart_content;
		$view_data["announcements"] = $announcements;

		Config::set("page_title", "Dashboard");

		Template::set("dashboard/index", $view_data);
	}

	private function generate_consumption_report($consumption_data){

		$base_url = Config::get("base_url");

		if(count($consumption_data->keys) > 0){
			$categories = $consumption_data->keys;
			$values= $consumption_data->values;

			foreach ($categories as $cat_key => $some_category) {
				$_categories["{$cat_key}"] = "'{$some_category}'";
			}

			$_categories_str = implode(",", $_categories);

			$_values_str = implode(",", $values);

			$content = <<<EOT
			<div id="container" class="block-body"></div>
			<script type="text/javascript">
				$(function () {
					$('#container').highcharts({
						chart: {
							type: 'column'
						},
						title: {
							text: 'Monthly SMS Credits Consumption'
						},
						xAxis: {
							categories: [{$_categories_str}]
						},
						yAxis: {
							min: 0,
							title: {
								text: 'Credits'
							}
						},
						tooltip: {
							headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
							pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
								'<td style="padding:0"><b>{point.y}</b></td></tr>',
							footerFormat: '</table>',
							shared: true,
							useHTML: true
						},
						plotOptions: {
							column: {
								pointPadding: 0.2,
								borderWidth: 0
							}
						},
						series: [{
							name: 'Outgoing SMS',
							data: [{$_values_str}]
						}]
					});
				});
			</script>
			<script src="{$base_url}assets/js/highcharts.js"></script>
EOT;
		} else {
			$content = "<div class=\"block-body\">";
				$content .= "<strong>No Transactions found in past two months.</strong>";
				$content .= "<br /><small>Why not start using the service</small>";
			$content .=  "</div>";
		}
		return $content;
	}

	public function logs($log_type = "outgoing", $page_number = 1, $limit = 10){

		if(!$log_type){
			Config::set("page_title", "404 - Page not found");
		}

		$user = Session::getvar("user");

		$log_type = strtolower($log_type);

		Loader::helper("pagination");

		switch ($log_type) {
			case "access":
				$where = array();
				$log_record = Helper_Dashboard::get_access_logs($where, NULL, $limit, pagination_offset($page_number, $limit));
				$max_results = Helper_Dashboard::get_number_of_access_logs($where);
				break;

			case "outgoing":
			default:
				$where["out_client_id"] = $user->client_id;
				$where["out_username"] = $user->username;
				$log_record = Helper_Dashboard::get_outgoing_logs($where, NULL, $limit, pagination_offset($page_number, $limit));
				$max_results = Helper_Dashboard::get_number_of_outgoing_logs($where);
				break;
		}


		$view_data["page_number"] = $page_number;
		$view_data["limit"] = $limit;
		$view_data["max_results"] = $max_results;
		$view_data["page_prefix"] = Config::url("dashboard/logs/{$log_type}/");
		$view_data["page_suffix"] = $max_results;

		$view_data["logs"] = $log_record;
		$view_data["log_type"] = $log_type;

		Config::set("controller", "report");
		Config::set("page_title", ucwords("{$log_type} Logs"));
		Template::set("dashboard/{$log_type}_logs", $view_data);
	}

}