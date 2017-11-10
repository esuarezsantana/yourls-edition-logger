<?php
/* 
 * Admin Page
*/

// No direct call
if( !defined( 'YOURLS_ABSPATH' ) ) die();

// Register our plugin admin page
yourls_add_action( 'plugins_loaded', 'editionlogger_admin_page_add_page' );
function editionlogger_admin_page_add_page() {
	yourls_register_plugin_page( 'editionlogger', 'Audit Log', 'editionlogger_admin_page_do_page' );
	// parameters: page slug, page title, and function that will display the page itself
}

// Display admin page
function editionlogger_admin_page_do_page() {

	// Check if a form was submitted
	if( isset( $_POST['logfile'] ) ) {
		// Check nonce
		yourls_verify_nonce( 'editionlogger' );
		
		// Process form
		editionlogger_admin_page_update_option();
	}

	// Get value from database
	$logfile = yourls_get_option( 'logfile', 'admin/logs' );
	$logfilename = yourls_get_option( 'logfilename', 'log_' );
	$logfile_path_mask = _EDITIONLOGGER_LOGFILE.'/'._EDITIONLOGGER_LOGFILE_PREFIX;
	
	// Create nonce
	$nonce = yourls_create_nonce( 'editionlogger' );
	$path_root = YOURLS_ABSPATH;

	echo <<<HTML
		<h2>Edition Logger</h2>
		<p>This plugin is logging most of admin actions into log files.</p>
		<h3>Settings</h3>
		<form method="post">
		<input type="hidden" name="nonce" value="$nonce" />
		<p><label for="logfile">Log directory path</label> $path_root/ <input type="text" id="logfile" name="logfile" value="$logfile" />
		<br>Tip: If you wishes to put your log files outside the public folder, you can use "../" to go up one directory level.</p>
		<p><label for="logfile">Log File Name Prefix: </label> <input type="text" id="logfilename" name="logfilename" value="$logfilename" /></p>
		<p><input type="submit" value="Update Settings" /></p>
		</form>

HTML;
	
	echo '<h3>Logs</h3>';
	// List Log Files.
	$count = 0;
	foreach (glob($logfile_path_mask."*.txt") as $filename) {
		
		$count++;
	
		echo basename($filename)." (" . filesize($filename) . " octets) <br>\n";
		
		if (!empty($filename) && $count < 10) {
			$content = file_get_contents($filename);
			
			echo '<textarea>'.$content.'</textarea>';
		}
}
	
}

// Update option in database
function editionlogger_admin_page_update_option() {
	
	$logfile = $_POST['logfile'];
	$logfilename = $_POST['logfilename'];
	
	if( $logfile ) {
		// Validate logfile. ALWAYS validate and sanitize user input.
		// Here, we want a string
		$logfile = (string) $logfile;
		
		// Update value in database
		yourls_update_option( 'logfile', $logfile );
	}
	if( $logfilename ) {
		// Validate logfile. ALWAYS validate and sanitize user input.
		// Here, we want a string
		$logfilename = (string) $logfilename;
		
		// Update value in database
		yourls_update_option( 'logfilename', $logfilename );
	}
}
