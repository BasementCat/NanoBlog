<?php
	require_once(dirname(__FILE__).'/simpletest/autorun.php');

	$TestRoot=null;
	$TestDataDir=null;

	function removeTestData(){
		global $TestRoot, $TestDataDir;
		if(file_exists($TestDataDir)){
			$dirQueue=array($TestDataDir);
			$dirDeleteQueue=array();
			while($dirQueue){
				$curdir=array_shift($dirQueue);
				$files=glob($curdir.'/*');
				foreach($files as $file){
					if(is_dir($file))
						$dirQueue[]=$file;
					else
						unlink($file);
				}
				$dirDeleteQueue[]=$curdir;
			}
			foreach(array_reverse($dirDeleteQueue) as $dir) rmdir($dir);
		}
	}

	function doTestSetUp(){
		global $TestRoot, $TestDataDir;
		$TestRoot=dirname(__FILE__).'/';
		$TestDataDir=$TestRoot.'test-data/';

		include_once $TestRoot."/../src/NanoBlog.php";
		NanoBlog::$Root=$TestDataDir;

		removeTestData();

		mkdir($TestDataDir, 0770, true);
		file_put_contents($TestDataDir.'file_test.nbpost', <<<EOT
Title:		This is a test...
TextTime:	Dec 13th 2011, 9:31 pm
Author:		Alec
Format:		raw

alsdkfjaklsdjflkajsdlkfjasldkjf
EOT
		);
		file_put_contents($TestDataDir.'file_test.json', <<<EOT
{
"Title":	"This is a test...2",
"TextTime":	"Dec 13th 2011, 9:37 pm",
"Author":	"Alec",
"Format":	"raw",
"Body":		"alsdkfjaklsdjflkajsdlkfjasldkjf"
}
EOT
		);
		file_put_contents($TestDataDir.'zydeco_test.nbpost', <<<EOT
Title:		This is a test...
TextTime:	Dec 13th 2011, 9:31 pm
Author:		Alec
Format:		raw

*hi*
EOT
		);
	}

	function doTestTearDown(){
		removeTestData();
	}

	class AllTests extends TestSuite{
		function AllTests(){
			parent::__construct();
			$this->addFile(dirname(__FILE__).'/test_nanoblog.php');
			$this->addFile(dirname(__FILE__).'/test_zydeco_integration.php');
		}


	}