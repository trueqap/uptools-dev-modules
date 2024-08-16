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
 * Version:           1.0.0
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
define( 'UPTOOLS_DEV_MODULES_VERSION', '1.0.0' );
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
function uptools_load_modules() {
    static $loaded_modules = array();

    $active_modules = get_option( 'active_modules', array() );
    $modules_dir = UPTOOLS_DEV_MODULES_PLUGIN_DIR . 'modules/';
    
    if ( is_dir( $modules_dir ) ) {
        $files = scandir( $modules_dir );
        foreach ( $files as $file ) {
            if ( preg_match( '/^(.+)\.php$/', $file, $matches ) ) {
                $module_name = str_replace( '-', ' ', ucfirst( $matches[1] ) );
                if ( in_array( $module_name, $active_modules, true ) && !isset($loaded_modules[$module_name]) ) {
                    include_once $modules_dir . $file;
                    $class_name = 'UpTools_' . str_replace( ' ', '_', $module_name );
                    if ( class_exists( $class_name ) ) {
                        $loaded_modules[$module_name] = new $class_name();
                        if ( method_exists( $loaded_modules[$module_name], 'init' ) ) {
                            $loaded_modules[$module_name]->init();
                        }
                    }
                }
            }
        }
    }
}

// Load modules after WordPress has finished loading but before any headers are sent.
add_action( 'wp_loaded', 'uptools_load_modules' );