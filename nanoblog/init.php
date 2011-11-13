<?php
	//define('POST_URL_INDEX',		DATA_DIR.'/post-urls.json');
	//define('USERS_FILE',			DATA_DIR.'/users.json');

	include 'nb.php';

	if(!nb_installed()){
		if(($status=nb_install())!==true) nb_errorPage("Installation Error", $status, "500 Internal Server Error");
	}
	if(($status=nb_sanityCheck())!==true) nb_errorPage("Sanity Check Failed", $status, "500 Internal Server Error");

	if(!nb_request()) nb_request(null, 'home');

	if(!file_exists('actions/'.nb_request(0))) nb_errorPage("Not Found", "The page that you requested could not be found.", "404 Not Found");