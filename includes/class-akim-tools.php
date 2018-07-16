<?php
/**
 * Akim Tools for Yourls Edition Logger.
 *
 * @version	1.0.0
 * @package   Akim_Framework
 * @author    Marc-Antoine Minville <me@marc-antoine-minville.com>
 * @license   GPL-2.0+
 * @link      http://marc-antoine-minville.com
 * @copyright 2014 Marc-Antoine Minville
 */

if( !defined( 'YOURLS_ABSPATH' ) ) die();

if ( ! class_exists( 'Akim_Tools' ) ) :

/**
 * Akim Tools class.
 */
class Akim_Tools {
	
	
	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 * @access	protected
	 * @var      object
	 */
	protected static $instance = null;
	
	
	/**
	 * Initialize the package by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	public function __construct() {
		
		// Auto-create instance.
		self::$instance = $this;
		
		// Run `mika_tools_loaded` actions.
		#do_action( 'mika_tools_loaded' );
	}
	
	
	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {
		
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
	
	
	#############
	### TOOLS ###
	#############
		
	public function lock_folder_with_haccess($folder_path) {
		
		// htaccess file path
		$htaccess_path = $folder_path.'/'.'.htaccess';
		
		// Check if htaccess is there, or create it
		if (!file_exists($htaccess_path)) {
			
			// htaccess content
			$htaccess = "";
			$htaccess .= "Order Deny,Allow\n";
			$htaccess .= "Deny from all\n";
			$htaccess .= "Allow from none\n";
			
			// Create the .htaccess file
			file_put_contents($htaccess_path, $htaccess, LOCK_EX);
		}
	}

} // End of Class

endif; // Endif class exists.
