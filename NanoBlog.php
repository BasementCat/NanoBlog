<?php
	class NanoBlog{
		protected $post;
		public function __construct($path, $format='nbpost'){
			$formatClass=sprintf('NBP_%s', $format);
			if(!class_exists($formatClass)) throw new Exception("Can't find format class {$formatClass} for format {$format}");
			$this->post=new $formatClass($path);
		}

		public function post(){ return $this->post; }
	}

	abstract class NB_TextFormat{
		protected $Body;

		public abstract function __construct($body);
		public abstract function render();
	}

	class NBT_raw extends NB_TextFormat{
		public function __construct($path){
			$this->Body=$path;
		}
		public function render(){
			return $this->Body;
		}
	}

	abstract class NB_PostLoader{
		protected $Path, $File, $Title, $Time, $Author, $RawBody, $FmtBody;

		protected abstract function load();

		public function __construct($path){
			$this->Path=$path;
			$this->load();
		}
		public function getTitle(){ return $this->Title; }
		public function getTime(){ return $this->Time; }
		public function getFormattedTime($format='Y-m-d g:i a'){ return date($format, $this->Time); }
		public function getAuthor(){ return $this->Author; }
		public function getBody(){ return $this->RawBody; }
		public function getBodyFormatter(){ return $this->FmtBody; }
		public function getFormattedBody(){ return $this->FmtBody->render(); }
	}

	class NBP_json extends NB_PostLoader{
		protected function load(){
			$this->File=sprintf('%s.json', $this->Path);
			if(!file_exists($this->File)) throw new Exception("Failed to load {$this->File}");
			$data=json_decode(file_get_contents($this->File), true);
			$this->Title=$data['Title'];
			if(isset($data['Time']))
				$this->Time=$data['Time'];
			elseif(isset($data['TextTime']))
				$this->Time=strtotime($data['TextTime']);
			else
				$this->Time=filemtime($this->File);
			$this->Author=$data['Author'];
			$this->RawBody=$data['Body'];
			$fmtClass=sprintf('NBT_%s', $data['Format']);
			if(!class_exists($fmtClass)) throw new Exception("Can't find format class for {$data['Format']}: {$fmtClass}");
			$this->FmtBody=new $fmtClass($this->RawBody);
		}
	}

	class NBP_nbpost extends NB_PostLoader{
		protected function load(){
			$this->File=sprintf('%s.nbpost', $this->Path);
			if(!file_exists($this->File)) throw new Exception("Failed to load {$this->File}");
			$all_data_raw=file_get_contents($this->File);
			//detect line endings
			$line_endings=null;
			foreach(array("\r\n", "\n", "\r") as $end_type){
				if(preg_match("#{$end_type}#", $all_data_raw)){
					$line_endings=$end_type;
					break;
				}
			}
			if(!$line_endings) throw new Exception("Failed to detect line endings in {$this->File}");
			list($data_raw, $body)=preg_split("#(".$line_endings."){2}#", $all_data_raw, 2);
			$data=array();
			foreach(preg_split("#\r\n|\n|\r#", $data_raw) as $line){
				list($k, $v)=preg_split("#:\s+#", $line, 2);
				$data[$k]=$v;
			}
			$this->Title=$data['Title'];
			if(isset($data['Time']))
				$this->Time=$data['Time'];
			elseif(isset($data['TextTime']))
				$this->Time=strtotime($data['TextTime']);
			else
				$this->Time=filemtime($this->File);
			$this->Author=$data['Author'];
			$this->RawBody=$body;
			$fmtClass=sprintf('NBT_%s', $data['Format']);
			if(!class_exists($fmtClass)) throw new Exception("Can't find format class for {$data['Format']}: {$fmtClass}");
			$this->FmtBody=new $fmtClass($this->RawBody);
		}
	}