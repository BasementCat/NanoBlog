<?php
	class NBT_wikitext_zydeco extends NB_TextFormat{
		public static $ZydecoPath='./';
		private static $setup=null;

		private static function do_setup(){
			if(self::$setup) return;
			if(self::$setup===false) throw new Exception('Zydeco integration setup has already failed.');
			$z_parser=self::$ZydecoPath.'ZydecoParser.php';
			$z_node=self::$ZydecoPath='ZydecoNode.php';
			try{
				if(!file_exists($z_parser)) throw new Exception("Cannot find Zydeco parser (expected $z_parser)");
				if(!file_exists($z_node)) throw new Exception("Cannot find Zydeco node (expected $z_node)");
				include $z_parser;
				include $z_node;
				self::$setup=true;
			}catch(Exception $e){
				self::$setup=false;
				throw $e;
			}
		}

		public function __construct($path){
			self::do_setup();
			$this->Body=$path;
		}
		public function render(){
			$parser=new ZydecoParser($this->Body);
			return $parser->getHtml();
		}
	}