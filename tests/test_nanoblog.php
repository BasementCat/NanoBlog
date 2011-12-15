<?php
	class TestNanoBlog extends UnitTestCase{
		protected $root, $data_dir;

		function removeTestData(){
			if(file_exists($this->data_dir)){
				$dirQueue=array($this->data_dir);
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

		function setUp(){
			$this->root=dirname(__FILE__).'/';
			$this->data_dir=$this->root.'test-data/';

			include_once $this->root."/../NanoBlog.php";
			NanoBlog::$Root=$this->data_dir;

			$this->removeTestData();

			mkdir($this->data_dir, 0770, true);
			file_put_contents($this->data_dir.'file_test.nbpost', <<<EOT
Title:		This is a test...
TextTime:	Dec 13th 2011, 9:31 pm
Author:		Alec
Format:		raw

alsdkfjaklsdjflkajsdlkfjasldkjf
EOT
			);
			file_put_contents($this->data_dir.'file_test.json', <<<EOT
{
	"Title":	"This is a test...2",
	"TextTime":	"Dec 13th 2011, 9:37 pm",
	"Author":	"Alec",
	"Format":	"raw",
	"Body":		"alsdkfjaklsdjflkajsdlkfjasldkjf"
}
EOT
			);
		}

		function tearDown(){
			$this->removeTestData();
		}

		function testTestEnvironment(){
			$this->assertTrue(file_exists($this->data_dir), "Data directory exists");
			$this->assertTrue(is_dir($this->data_dir), "Data directory is a directory");
		}

		function testMissingFormatClass(){
			$this->assertFalse(class_exists("InvalidFormatClass"), "Class 'InvalidFormatClass' is not defined");
			$this->expectException('Exception', "'Exception' is thrown when missing format class is used");
			$nb=new NanoBlog('/path/is/not/used', 'InvalidFormatClass');
		}

		function testMissingPostFile_nbpost(){
			$file=$this->data_dir.'missing_file.nbpost';
			$this->assertFalse(file_exists($file));
			$this->expectException('Exception');
			$nb=new NanoBlog('missing_file', 'nbpost');
		}

		function testMissingPostFile_json(){
			$file=$this->data_dir.'missing_file.json';
			$this->assertFalse(file_exists($file));
			$this->expectException('Exception');
			$nb=new NanoBlog('missing_file', 'json');
		}

		function testLoadFile(){
			$nbfile=$this->data_dir.'file_test.nbpost';
			$jsfile=$this->data_dir.'file_test.json';
			$this->assertTrue(file_exists($nbfile));
			$this->assertTrue(file_exists($jsfile));
			$nb_nb=new NanoBlog('file_test', 'nbpost');
			$this->assertEqual($nb_nb->post()->getTitle(), 'This is a test...');
			$nb_js=new NanoBlog('file_test', 'json');
			$this->assertEqual($nb_js->post()->getTitle(), 'This is a test...2');
		}
	}