<?php
	require_once(dirname(__FILE__).'/simpletest/autorun.php');
	class AllTests extends TestSuite{
		function AllTests(){
			parent::__construct();
			$this->addFile(dirname(__FILE__).'/test_nanoblog.php');
		}
	}