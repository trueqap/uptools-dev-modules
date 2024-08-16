<?php
/**
 * Example Module
 *
 * This is an example of how to create a module for the upTools Dev Modules plugin.
 *
 * @package UpToolsDevModules
 * @subpackage Modules
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Example Module Class
 */
class UpTools_Example_Module {

    /**
     * Module name
     *
     * @var string
     */
    public static $name = 'Example Module';

    /**
     * Module description
     *
     * @var string
     */
    public static $description = 'This is an example module that demonstrates how to create and use a module in the upTools Dev Modules plugin.';

    /**
     * Initialize the module
     */
    public function __construct() {
        add_action( 'init', array( $this, 'init' ) );
    }

    /**
     * Initialize module functionality
     */
    public function init() {
        // Add your module's functionality here
        add_action( 'admin_footer_text', array( $this, 'example_admin_footer_text' ) );
    }

    /**
     * Add example text to the admin footer
     *
     * @param string $text The existing admin footer text.
     * @return string Modified admin footer text.
     */
    public function example_admin_footer_text( $text ) {
        return $text . ' ' . esc_html__( 'This text was added by the Example Module!', 'uptools-dev-modules' );
    }

    /**
     * Get translated module name
     *
     * @return string Translated module name
     */
    public static function get_name() {
        return __( 'Example Module', 'uptools-dev-modules' );
    }

    /**
     * Get translated module description
     *
     * @return string Translated module description
     */
    public static function get_description() {
        return __( 'This is an example module that demonstrates how to create and use a module in the upTools Dev Modules plugin.', 'uptools-dev-modules' );
    }
}

// Initialize the module
new UpTools_Example_Module();