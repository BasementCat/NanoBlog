<?php
	$dirs=array(
		DATA_DIR
		DATA_DIR.'posts'
	);

	$out=array();

	foreach($dirs as $dir){
		$status=mkdir($dir, 0770, true);
		$out[]=array("Creating directory '{$dir}'", $status);
	}