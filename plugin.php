<?php
/*
Plugin Name: Edition Logger
Plugin URI: https://github.com/esuarezsantana/yourls-edition-logger
Description: Log every link edition
Version: 1.0
Author: Eduardo Suarez-Santana
Author URI: http://e.suarezsantana.com/
*/

// No direct call
if( !defined( 'YOURLS_ABSPATH' ) ) die();

require_once 'vendor/autoload.php';

function editionlogger_environment_check() {
	$required_params = array(
		'EDITIONLOGGER_KLOGGER_PATH', // path to KLogger
		'EDITIONLOGGER_LOGFILE',      // File to log
	);

	foreach ( $required_params as $pname ) {
		if ( !defined( $pname ) ) {
			$message = 'Missing defined parameter '.$pname.' in plugin '. $thisplugname;
			error_log( $message );
			return false;
		}
	}

	return true;
}


function editionlogger_insert_link ( $args ) {
	editionlogger_environment_check();
	$insert  = $args[0];
	$url     = $args[1];
	$keyword = $args[2];

	if ( $insert ) {

		$log = new Katzgrau\KLogger\Logger ( EDITIONLOGGER_LOGFILE );
		$log->info("[".YOURLS_USER."] Link inserted: ( $keyword, $url )");
	}
}


function editionlogger_delete_link ( $args ) {
	editionlogger_environment_check();
	$keyword = $args[0];

	$log = new Katzgrau\KLogger\Logger ( EDITIONLOGGER_LOGFILE);
	$log->info("[".YOURLS_USER."] Link deleted: ( $keyword )");
}


function editionlogger_edit_link ( $args ) {
	editionlogger_environment_check();
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
