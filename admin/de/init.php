<?php
	@ini_set("track_errors", "1");
	//error_reporting(0);
	if (!session_id()) {
		if (is_writeable(dirname(__FILE__) . '/../temp')) {
			ini_set('session.save_handler', 'files');
			ini_set('session.save_path', dirname(__FILE__) . '/../temp');
		}
		session_start();
	}

	/* 
	For some reason some servers do not have these defines set which can cause 
	strange errors when uploading
	*/
	if (!defined('UPLOAD_ERR_OK')) {
		define('UPLOAD_ERR_OK', 0);
	}

	if (!defined('UPLOAD_ERR_INI_SIZE')) {
		define('UPLOAD_ERR_INI_SIZE', 1);
	}

	if (!defined('UPLOAD_ERR_FORM_SIZE')) {
		define('UPLOAD_ERR_FORM_SIZE', 2);
	}

	if (!defined('UPLOAD_ERR_PARTIAL')) {
		define('UPLOAD_ERR_PARTIAL', 3);
	}

	if (!defined('UPLOAD_ERR_NO_FILE')) {
		define('UPLOAD_ERR_NO_FILE', 4);
	}

	if (!defined('UPLOAD_ERR_NO_TMP_DIR')) {
		define('UPLOAD_ERR_NO_TMP_DIR', 6);
	}

	if (!defined('UPLOAD_ERR_CANT_WRITE')) {
		define('UPLOAD_ERR_CANT_WRITE', 7);
	}

	if (!defined('UPLOAD_ERR_EXTENSION')) {
		define('UPLOAD_ERR_EXTENSION', 8);
	}

	require_once(dirname(__FILE__).'/valid_files.php');

?>