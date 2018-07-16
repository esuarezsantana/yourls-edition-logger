<?php
/*
Plugin Name: Edition Logger
Plugin URI: https://github.com/esuarezsantana/yourls-edition-logger
Description: Log every link edition
Version: 1.0.1
Author: Eduardo Suarez-Santana, Marc-Antoine Minville
Author URI: http://e.suarezsantana.com/
* 
* Actions to add in logs : 
* activated_plugin / deactivated_plugin : done.
* add_new_link_already_stored
* add_option / update_option / delete_option : done.
* ip_flood
* 
*/

// No direct call
if( !defined( 'YOURLS_ABSPATH' ) ) die();

// Load required files.
require_once 'vendor/autoload.php';
require_once 'includes/class-akim-tools.php';
if (yourls_is_admin()) require_once 'admin_page.php';

// Register Global Actions.
yourls_add_action( 'plugins_loaded', 'editionlogger_environment_check' );

// Register Logging Actions.
yourls_add_action( 'insert_link',   'editionlogger_insert_link' );
yourls_add_action( 'delete_link',   'editionlogger_delete_link' );
yourls_add_action( 'pre_edit_link', 'editionlogger_edit_link' );

yourls_add_action( 'activated_plugin', 'editionlogger_activated_plugin' );
yourls_add_action( 'deactivated_plugin', 'editionlogger_deactivated_plugin' );

yourls_add_action( 'add_option', 'editionlogger_add_option' );
yourls_add_action( 'update_option', 'editionlogger_update_option' );
yourls_add_action( 'delete_option', 'editionlogger_delete_option' );


/**
 * Perform an environment check.
 *
 * @since     1.0.0
 *
 * @return    bool    False when one required param is missing.
 */
function editionlogger_environment_check() {
	
	// Reqired params list.
	$_EDITIONLOGGER_LOGFILE = defined('EDITIONLOGGER_LOGFILE') ? EDITIONLOGGER_LOGFILE: YOURLS_ABSPATH.'/'.yourls_get_option( 'logfile', 'admin/logs' );
	define('_EDITIONLOGGER_LOGFILE', $_EDITIONLOGGER_LOGFILE);
	
	$logfilename = yourls_get_option( 'logfilename', 'log_' );
	define('_EDITIONLOGGER_LOGFILE_PREFIX', $logfilename);
	
	# Load Akim_Tools and lock log folder to prevent public access.
	$tools = Akim_Tools::get_instance();
	$tools->lock_folder_with_haccess(_EDITIONLOGGER_LOGFILE);

	return true;
}


/**
 * Perform an environment check.
 *
 * @since     1.0.0
 *
 * @return    bool    False when one required param is missing.
 */
function editionlogger_new_klogger() {
	
	return new Katzgrau\KLogger\Logger( _EDITIONLOGGER_LOGFILE, Psr\Log\LogLevel::DEBUG, array (
		'prefix' => _EDITIONLOGGER_LOGFILE_PREFIX, // changes the log file prefix
	));
}


/**
 * Fires on Insert Link Action.
 *
 * @since     1.0.0
 *
 * @return    
 */
function editionlogger_insert_link() {
	
	$args = func_get_args();
	$args = $args[0];
	
	$insert  = $args[0];
	$url     = $args[1];
	$keyword = $args[2];

	if ($insert) {
		
		$log = editionlogger_new_klogger();
		$log->info("[".YOURLS_USER."] Link inserted: ( $keyword, $url )");
	}
}


/**
 * Fires on Delete Link Action.
 *
 * @since     1.0.0
 *
 * @return    
 */
function editionlogger_delete_link() {
	
	$args = func_get_args();
	$args = $args[0];
	
	$keyword = $args[0];

	$log = editionlogger_new_klogger();
	$log->info("[".YOURLS_USER."] Link deleted: ( $keyword )");
}


/**
 * Fires on Edit Link Action.
 *
 * @since     1.0.0
 *
 * @return    
 */
function editionlogger_edit_link() {
	
	$args = func_get_args();
	$args = $args[0];
	
	$url                   = $args[0];
	$keyword               = $args[1];
	$newkeyword            = $args[2];
	$new_url_already_there = $args[3];
	$keyword_is_ok         = $args[4];

	// same check as in the source
	if ( ( !$new_url_already_there || yourls_allow_duplicate_longurls() ) && $keyword_is_ok ) {
		
		$log = editionlogger_new_klogger();
		$log->info( "[".YOURLS_USER."] Link edited: $keyword -> ( $newkeyword, $url )" );
	}
}


/**
 * Fires on Activated Plugin Action.
 *
 * @since     1.0.1
 *
 * @return    
 */
function editionlogger_activated_plugin() {
	
	$args = func_get_args();
	$args = $args[0];
	
	$plugin = $args[0];

	$log = editionlogger_new_klogger();
	$log->info("[".YOURLS_USER."] Activated plugin: ( $plugin )");
}


/**
 * Fires on Deactivated Plugin Action.
 *
 * @since     1.0.1
 *
 * @return    
 */
function editionlogger_deactivated_plugin() {
	
	$args = func_get_args();
	$args = $args[0];
	
	$plugin = $args[0];

	$log = editionlogger_new_klogger();
	$log->info("[".YOURLS_USER."] Deactivated plugin: ( $plugin )");
}


/**
 * Fires on Add Option Action.
 *
 * @since     1.0.1
 *
 * @return    
 */
function editionlogger_add_option() {
	
	$args = func_get_args();
	$args = $args[0];
	
	$name = $args[0];
	$_value = (string) yourls_maybe_serialize($args[1]);

	$log = editionlogger_new_klogger();
	$log->info("[".YOURLS_USER."] Added option: ( $name, $_value )");
}


/**
 * Fires on Update Option Action.
 *
 * @since     1.0.1
 *
 * @return    
 */
function editionlogger_update_option() {
	
	$args = func_get_args();
	$args = $args[0];
	
	$name = $args[0];
	$oldvalue = (string) yourls_maybe_serialize($args[1]);
	$newvalue = (string) yourls_maybe_serialize($args[2]);

	$log = editionlogger_new_klogger();
	$log->info("[".YOURLS_USER."] Updated option: ( $name, $oldvalue, $newvalue )");
}


/**
 * Fires on Delete Option Action.
 *
 * @since     1.0.1
 *
 * @return    
 */
function editionlogger_delete_option() {
	
	$args = func_get_args();
	$args = $args[0];
	
	$name = $args[0];

	$log = editionlogger_new_klogger();
	$log->info("[".YOURLS_USER."] Deleted option: ( $name )");
}
