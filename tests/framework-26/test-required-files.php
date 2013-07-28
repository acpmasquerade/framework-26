<?php
	class RequiredFilesTest extends PHPUnit_Framework_TestCase
	{
		public function testLogFiles(){
			$this->assertFileExists(__DIR__."/../../Logs");
			$this->assertFileExists(__DIR__."/../../Logs/unescaped-db-where.log");
			$this->assertFileExists(__DIR__."/../../Logs/debug.log");
		}
	}