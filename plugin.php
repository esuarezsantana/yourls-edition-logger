<?php
/*
Plugin Name: Edition Logger
Plugin URI: https://github.com/esuarezsantana/yourls-edition-logger
Description: Log every link edition
Version: 1.0.1
Author: Eduardo Suarez-Santana, Marc-Antoine Minville
Author URI: http://e.suarezsantana.com/
*/

// No direct call
if( !defined( 'YOURLS_ABSPATH' ) ) die();

require_once 'vendor/autoload.php';
require_once 'includes/class-akim-tools.php';

function editionlogger_environment_check() {
	$required_params = array(
		#'EDITIONLOGGER_KLOGGER_PATH', // path to KLogger // Note required anymore
		'EDITIONLOGGER_LOGFILE',      // File to log
	);

	foreach ( $required_params as $pname ) {
		if ( !defined( $pname ) ) {
			$message = 'Missing defined parameter '.$pname.' in plugin Edition Logger';
			error_log( $message );
			return false;
		}
	}
	
	# Load Akim_Tools and lock log folder to prevent public access.
	$tools = Akim_Tools::get_instance();
	$tools->lock_folder_with_haccess(EDITIONLOGGER_LOGFILE);

	return true;
}


function editionlogger_insert_link() {
	
	editionlogger_environment_check();
	
	$args = func_get_args();
	$args = $args[0];
	
	$insert  = $args[0];
	$url     = $args[1];
	$keyword = $args[2];

	if ($insert) {
		
		$log = new Katzgrau\KLogger\Logger( EDITIONLOGGER_LOGFILE );
		$log->info("[".YOURLS_USER."] Link inserted: ( $keyword, $url )");
	}
}


function editionlogger_delete_link ( $args ) {
	
	editionlogger_environment_check();
	
	$args = func_get_args();
	$args = $args[0];
	
	$keyword = $args[0];

	$log = new Katzgrau\KLogger\Logger ( EDITIONLOGGER_LOGFILE);
	$log->info("[".YOURLS_USER."] Link deleted: ( $keyword )");
}


function editionlogger_edit_link ( $args ) {
	
	editionlogger_environment_check();
	
	$args = func_get_args();
	$args = $args[0];
	
	$url                   = $args[0];
	$keyword               = $args[1];
	$newkeyword            = $args[2];
	$new_url_already_there = $args[3];
	$keyword_is_ok         = $args[4];

	// same check as in the source
	if ( ( !$new_url_already_there || yourls_allow_duplicate_longurls() ) && $keyword_is_ok ) {
		
		$log = new Katzgrau\KLogger\Logger ( EDITIONLOGGER_LOGFILE );
		$log->info( "[".YOURLS_USER."] Link edited: $keyword -> ( $newkeyword, $url )" );
	}
}


yourls_add_action( 'insert_link',   'editionlogger_insert_link' );

yourls_add_action( 'delete_link',   'editionlogger_delete_link' );

yourls_add_action( 'pre_edit_link', 'editionlogger_edit_link' );
