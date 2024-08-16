<?php
/**
 * The plugin bootstrap file
 *
 * @link              https://uptools.io
 * @since             1.0.0
 * @package           UpToolsDevModules
 *
 * @wordpress-plugin
 * Plugin Name:       upTools Dev Modules
 * Plugin URI:        https://uptools.io
 * Description:       A module loader plugin for upTools development team.
 * Version:           1.0.1
 * Author:            upTools Development Team
 * Author URI:        https://uptools.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       uptools-dev-modules
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// Define plugin constants.
define( 'UPTOOLS_DEV_MODULES_VERSION', '1.0.1' );
define( 'UPTOOLS_DEV_MODULES_PLUGIN_NAME', 'uptools-dev-modules' );
define( 'UPTOOLS_DEV_MODULES_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'UPTOOLS_DEV_MODULES_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * The core plugin class.
 */
require_once UPTOOLS_DEV_MODULES_PLUGIN_DIR . 'includes/class-uptools-dev-modules.php';

/**
 * Begins execution of the plugin.
 *
 * @since 1.0.0
 */
function run_uptools_dev_modules() {
    $plugin = new UpTools_Dev_Modules( UPTOOLS_DEV_MODULES_PLUGIN_NAME, UPTOOLS_DEV_MODULES_VERSION );
    $plugin->run();
    return $plugin;
}

/**
 * The main function to instantiate the plugin.
 *
 * @since 1.0.0
 */
function uptools_dev_modules() {
    static $plugin = null;

    if ( null === $plugin ) {
        $plugin = run_uptools_dev_modules();
    }

    return $plugin;
}

// Initialize the plugin
uptools_dev_modules();

/**
 * Load and activate modules.
 */
function uptools_load_module_info() {
    $modules_dir = UPTOOLS_DEV_MODULES_PLUGIN_DIR . 'modules/';
    $module_info = array();
    
    if ( is_dir( $modules_dir ) ) {
        $files = scandir( $modules_dir );
        foreach ( $files as $file ) {
            if ( preg_match( '/^(.+)\.php$/', $file, $matches ) ) {
                $module_name = str_replace( '-', ' ', ucfirst( $matches[1] ) );
                $module_file = $modules_dir . $file;
                
                if ( file_exists( $module_file ) ) {
                    include_once $module_file;
                    $class_name = 'UpTools_' . str_replace( ' ', '_', $module_name );
                    if ( class_exists( $class_name ) && method_exists( $class_name, 'get_info' ) ) {
                        $module_info[$module_name] = $class_name::get_info();
                    }
                }
            }
        }
    }
    
    return $module_info;
}

function uptools_load_active_modules() {
    $active_modules = get_option( 'active_modules', array() );
    $modules_dir = UPTOOLS_DEV_MODULES_PLUGIN_DIR . 'modules/';
    
    foreach ( $active_modules as $module_name ) {
        $file = strtolower(str_replace(' ', '-', $module_name)) . '.php';
        $module_file = $modules_dir . $file;
        
        if ( file_exists( $module_file ) ) {
            include_once $module_file;
            $class_name = 'UpTools_' . str_replace( ' ', '_', $module_name );
            if ( class_exists( $class_name ) && method_exists( $class_name, 'init' ) ) {
                $class_name::init();
            }
        }
    }
}

// Load module info when WordPress initializes
add_action( 'init', 'uptools_load_module_info' );

// Load active modules after WordPress has finished loading but before any headers are sent.
add_action( 'wp_loaded', 'uptools_load_active_modules' );
