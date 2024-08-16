<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 * @package    UpToolsDevModules
 * @subpackage UpToolsDevModules/admin
 * @author     upTools Development Team <info@uptools.io>
 */
class UpTools_Dev_Modules_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param    string    $plugin_name       The name of this plugin.
     * @param    string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        // Add action to run admin_init tasks
        add_action( 'admin_init', array( $this, 'admin_init' ) );

        // Add action to remove other admin notices on our plugin page
        add_action( 'admin_head', array( $this, 'remove_other_admin_notices' ) );

        // Add action to enqueue styles only on our plugin page
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles( $hook ) {
        // Only load CSS on our plugin's page
        if ( 'settings_page_' . $this->plugin_name === $hook ) {
            wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/uptools-dev-modules-admin.css', array(), $this->version, 'all' );
        }
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/uptools-dev-modules-admin.js', array( 'jquery' ), $this->version, false );
    }

    /**
     * Add options page
     */
    public function add_plugin_admin_menu() {
        add_options_page(
            'upTools Dev Modules Settings',
            'upTools Dev Modules',
            'manage_options',
            $this->plugin_name,
            array( $this, 'display_plugin_setup_page' )
        );
    }

    /**
     * Add settings action link to the plugins page.
     */
    public function add_action_links( $links ) {
        $settings_link = array(
            '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __( 'Settings', $this->plugin_name ) . '</a>',
        );
        return array_merge( $settings_link, $links );
    }

    /**
     * Render the settings page for this plugin.
     */
    public function display_plugin_setup_page() {
        include_once 'partials/uptools-dev-modules-admin-display.php';
    }

    /**
     * Register settings
     */
    public function register_settings() {
        add_settings_section(
            'uptools_dev_modules_general',
            __( 'General Settings', 'uptools-dev-modules' ),
            array( $this, 'uptools_dev_modules_general_cb' ),
            $this->plugin_name
        );

        add_settings_field(
            'active_modules',
            __( 'Active Modules', 'uptools-dev-modules' ),
            array( $this, 'active_modules_cb' ),
            $this->plugin_name,
            'uptools_dev_modules_general',
            array( 'label_for' => 'active_modules' )
        );

        register_setting( $this->plugin_name, 'active_modules', array( $this, 'validate_active_modules' ) );
    }

    /**
     * Render the text for the general section
     */
    public function uptools_dev_modules_general_cb() {
        echo '<p>' . __( 'Select which modules you want to activate.', 'uptools-dev-modules' ) . '</p>';
    }

    /**
     * Render the active modules checkbox
     */
    public function active_modules_cb() {
        $active_modules = get_option( 'active_modules', array() );
        $all_modules = $this->get_available_modules();

        echo '<fieldset>';
        foreach ( $all_modules as $module_name => $module_data ) {
            $checked = in_array( $module_name, $active_modules, true ) ? 'checked' : '';
            echo '<label for="' . esc_attr( $module_name ) . '">';
            echo '<input type="checkbox" id="' . esc_attr( $module_name ) . '" name="active_modules[]" value="' . esc_attr( $module_name ) . '" ' . $checked . '>';
            echo esc_html( $module_name );
            echo '</label><br>';
        }
        echo '</fieldset>';
    }

    /**
     * Validate active modules
     */
    public function validate_active_modules( $input ) {
        $all_modules = $this->get_available_modules();
        $valid = array();

        if ( is_array( $input ) ) {
            foreach ( $input as $module ) {
                if ( array_key_exists( $module, $all_modules ) ) {
                    $valid[] = $module;
                }
            }
        }

        return $valid;
    }

    /**
     * Get available modules
     *
     * @return array An array of available modules
     */
    public function get_available_modules() {
        $modules_dir = UPTOOLS_DEV_MODULES_PLUGIN_DIR . 'modules/';
        $modules = array();

        if ( is_dir( $modules_dir ) ) {
            $files = scandir( $modules_dir );
            foreach ( $files as $file ) {
                if ( preg_match( '/^(.+)\.php$/', $file, $matches ) ) {
                    $module_name = str_replace( '-', ' ', ucfirst( $matches[1] ) );
                    $modules[ $module_name ] = $modules_dir . $file;
                }
            }
        }

        return $modules;
    }

    /**
     * Check for new modules and update the active_modules option
     */
    public function check_for_new_modules() {
        $all_modules = $this->get_available_modules();
        $active_modules = get_option( 'active_modules', array() );

        // Remove any active modules that no longer exist
        $active_modules = array_intersect( $active_modules, array_keys( $all_modules ) );

        // New modules are not added to active_modules, so they remain inactive by default

        update_option( 'active_modules', $active_modules );
    }

    /**
     * Run admin init tasks
     */
    public function admin_init() {
        $this->check_for_new_modules();
    }

    /**
     * Remove other admin notices on our plugin page
     */
    public function remove_other_admin_notices() {
        $screen = get_current_screen();
        if ( $screen->id === 'settings_page_' . $this->plugin_name ) {
            remove_all_actions( 'admin_notices' );
            remove_all_actions( 'all_admin_notices' );
        }
    }
}
