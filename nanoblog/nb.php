<?php
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