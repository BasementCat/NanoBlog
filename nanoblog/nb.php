<?php
	function nb_install(){
		if(!mkdir(DATA_DIR, 0770, true)) return "Failed to create data directory: ".DATA_DIR;
		//if(!file_put_contents(POST_URL_INDEX, "{}")) return "Failed to create post index: ".POST_URL_INDEX;
		//if(!file_put_contents(USERS_FILE, "{}")) return "Failed to create user file: ".USERS_FILE;
		foreach(get_declared_classes() as $class){
			if(!is_subclass_of($class, 'Model')) continue;
			if(!($status=$class::install())) return "Failed to install model {$class}: {$status}";
		}
		return true;
	}

	function nb_installed(){
		return file_exists(DATA_DIR);
	}

	function nb_sanityCheck(){
		if(!file_exists(DATA_DIR)) return "Data directory doesn't exist: ".DATA_DIR;
		//if(!file_exists(POST_URL_INDEX)) return "Index doesn't exist: ".POST_URL_INDEX;
		//if(!file_exists(USERS_FILE)) return "User db doesn't exist: ".USERS_FILE;
		foreach(get_declared_classes() as $class){
			if(!is_subclass_of($class, 'Model')) continue;
			if(!($status=$class::init())) return "Failed to init model {$class}: {$status}";
		}
		return true;
	}

	function nb_request($index=null, $setTo=null){
		static $request=null, $request_s=null;
		if($request===null||$setTo!==null){
			if($setTo!==null){
				$source=is_array($setTo)?implode('/', $setTo):$setTo;
			}else{
				$source=URL_SOURCE==='pathinfo'?(isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:null):(isset($_GET[URL_SOURCE])?$_GET[URL_SOURCE]:'');
			}
			$request=explode('/', trim($source, "\r\n\t /"));
			$request_s=implode('/', $request);
		}
		return $index===null?$request:($index===""?$request_s:(isset($request[$index])?$request[$index]:null));
	}
	
	function nb_errorPage($title, $error, $code){
		header('HTTP/1.1 '.$code);
		?>
<html>
	<head>
		<title><?php echo $title; ?></title>
		<style type="text/css">
			body{ font-family: "Trebuchet MS", sans-serif; }
			div#wrapper_outer{ position: relative; background: #eee; float: none; margin: 0 auto; width: 950px; border-radius: 10px; padding: 10px; height: 50%; min-height: 50%; }
			h1{ text-align: center; width: 100%; border-bottom: 1px solid #000; }
			p{ float: none; margin: 0 auto; }
			blockquote{ float: none; margin: 0.4em auto; border-radius: 4px; background: #fcc; padding: 0.2em; width: 75%; }
		</style>
	</head>
	<body>
		<div id="wrapper_outer">
			<h1><?php echo $title; ?></h1>
			<p>
				NanoBlog has encountered an error and cannot continue.  The reported
				error is:
			</p>
			<blockquote>
				<?php echo $error; ?>
			</blockquote>
		</div>
	</body>
</html>
		<?php
		ob_end_flush();
		die();
	}