<?php
	class TestNanoBlog extends UnitTestCase{
		function setUp(){ return doTestSetUp(); }
		function tearDown(){ return doTestTearDown(); }
		
		function testTestEnvironment(){
			global $TestRoot, $TestDataDir;
			$this->assertTrue(file_exists($TestDataDir), "Data directory exists");
			$this->assertTrue(is_dir($TestDataDir), "Data directory is a directory");
		}

		function testMissingFormatClass(){
			$this->assertFalse(class_exists("InvalidFormatClass"), "Class 'InvalidFormatClass' is not defined");
			$this->expectException('Exception', "'Exception' is thrown when missing format class is used");
			$nb=new NanoBlog('/path/is/not/used', 'InvalidFormatClass');
		}

		function testMissingPostFile_nbpost(){
			global $TestRoot, $TestDataDir;
			$file=$TestDataDir.'missing_file.nbpost';
			$this->assertFalse(file_exists($file));
			$this->expectException('Exception');
			$nb=new NanoBlog('missing_file', 'nbpost');
		}

		function testMissingPostFile_json(){
			global $TestRoot, $TestDataDir;
			$file=$TestDataDir.'missing_file.json';
			$this->assertFalse(file_exists($file));
			$this->expectException('Exception');
			$nb=new NanoBlog('missing_file', 'json');
		}

		function testLoadFile(){
			global $TestRoot, $TestDataDir;
			$nbfile=$TestDataDir.'file_test.nbpost';
			$jsfile=$TestDataDir.'file_test.json';
			$this->assertTrue(file_exists($nbfile));
			$this->assertTrue(file_exists($jsfile));
			$nb_nb=new NanoBlog('file_test', 'nbpost');
			$this->assertEqual($nb_nb->post()->getTitle(), 'This is a test...');
			$nb_js=new NanoBlog('file_test', 'json');
			$this->assertEqual($nb_js->post()->getTitle(), 'This is a test...2');
		}
	}