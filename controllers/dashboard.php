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

	/**
	 * The logged in user dashboard
	 * - throw some stats
	 * - throw some notifications
	 * - may be some account information.
	 */
	public function index(){
		// the user information can be obtained from the view via static session class
		// get the list of announcements from the database
		// if there are any, populate them with respective alerts

		$where = array("status" => "active");
		$announcements = Helper_Dashboard::get_announcements();

		$user = Session::getvar("user");

		// some demo chart to put something as a placeholder
		$chart_content = $this->demo_chart();

		$view_data["chart_block_content"] = $chart_content;
		$view_data["announcements"] = $announcements;

		Config::set("page_title", "Dashboard");

		Template::set("dashboard/index", $view_data);
	}

	private function demo_chart(){

		$base_url = Config::url("");

		$content = <<<EOT
		<div id="chart-container" class="block-body"></div>
		<script type="text/javascript">
			$(function () {
				$('#chart-container').highcharts({
					chart: {
						type: 'column'
					},
					title: {
						text: 'Statistics'
					},
					xAxis: {
                		categories: [ 'Apr,2013','May,2013','Jun,2013' ]
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
						name: 'Outgoing ',
						data: [100, 200, 300]
					}]
				});
			});
		</script>
		<script src="{$base_url}assets/js/highcharts.js"></script>
EOT;
		return $content;
	}

}