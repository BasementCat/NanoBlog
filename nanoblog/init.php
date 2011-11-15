<?php
	include 'nb.php';
	
	if(!nb_request()) nb_request(null, 'index');

	switch(nb_request(0)){
		default:
			$postFile=DATA_DIR.trim(nb_request(''), '/').'.json';
			if(!file_exists($postFile)){
				header('HTTP/1.1 404 not found');
				$TEMPLATE=array(
					'Title'=>'Error 404: Not Found',
					'Body'=>'The post that you specified could not be found. ('.nb_request('').')'
				);
			}
	}