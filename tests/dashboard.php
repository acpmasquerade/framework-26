<?php

require_once "PHPUnit/Autoload.php";

require_once "../unit-index.php";
require_once "../controllers/dashboard.php";

class DashboardTest extends PHPUnit_Framework_TestCase {

	private $dashboard;

	public function __construct(){
		$this->dashboard = new Controller_Dashboard();
	}

	public function setUp(){
		parent::setUp();
	}

	public function tearDown(){
		// parent::tearDown();
	}

	public function testTwo(){
		$this->assertTrue(true);
	}

	public function testOne(){
		$this->assertTrue($this->dashboard->unit_test());
	}

}
