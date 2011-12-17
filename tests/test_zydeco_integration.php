<?php
	class TestZydecoIntegration extends UnitTestCase{
		function setUp(){
			include_once(dirname(__FILE__).'/../src/NB_ZydecoWikitext.php');
			NBT_wikitext_zydeco::$ZydecoPath=dirname(__FILE__).'/../../zydeco/src/';
			return doTestSetUp();
		}
		function tearDown(){ return doTestTearDown(); }

		function testLoadFileWithZydecoWikitext(){
			global $TestDataDir;
			$nb_nb=new NanoBlog('zydeco_test', 'nbpost');
			$this->assertEqual($nb_nb->post()->getFormattedBody(), '<strong>hi</strong>');
		}
	}