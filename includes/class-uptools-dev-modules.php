<?php
/**
 * The core plugin class.
 *
 * @since      1.0.0
 * @package    UpToolsDevModules
 * @subpackage UpToolsDevModules/includes
 * @author     upTools Development Team <info@uptools.io>
 */

class UpTools_Dev_Modules {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      UpTools_Dev_Modules_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * The array of registered modules.
     *
     * @since    1.0.0
     * @access   protected
     * @var      array    $modules    The array of registered modules.
     */
    protected $modules = array();

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     * @param    string    $plugin_name       The name of the plugin.
     * @param    string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->register_default_modules();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - UpTools_Dev_Modules_Loader. Orchestrates the hooks of the plugin.
     * - UpTools_Dev_Modules_i18n. Defines internationalization functionality.
     * - UpTools_Dev_Modules_Admin. Defines all hooks for the admin area.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {
        require_once UPTOOLS_DEV_MODULES_PLUGIN_DIR . 'includes/class-uptools-dev-modules-loader.php';
        require_once UPTOOLS_DEV_MODULES_PLUGIN_DIR . 'includes/class-uptools-dev-modules-i18n.php';
        require_once UPTOOLS_DEV_MODULES_PLUGIN_DIR . 'admin/class-uptools-dev-modules-admin.php';

        $this->loader = new UpTools_Dev_Modules_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the UpTools_Dev_Modules_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {
        $plugin_i18n = new UpTools_Dev_Modules_i18n();
        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {
        $plugin_admin = new UpTools_Dev_Modules_Admin( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );
        $this->loader->add_filter( 'plugin_action_links_' . plugin_basename( UPTOOLS_DEV_MODULES_PLUGIN_DIR . 'uptools-dev-modules.php' ), $plugin_admin, 'add_action_links' );
    }

    /**
     * Register default modules from the modules directory.
     *
     * @since    1.0.0
     * @access   private
     */
    private function register_default_modules() {
        $modules_dir = UPTOOLS_DEV_MODULES_PLUGIN_DIR . 'modules/';

        if ( is_dir( $modules_dir ) ) {
            $modules = scandir( $modules_dir );
            foreach ( $modules as $module ) {
                if ( '.' === $module || '..' === $module || 'example-module.php' === $module ) {
                    continue;
                }

                $module_file = $modules_dir . $module;
                if ( is_file( $module_file ) && preg_match( '/\.php$/', $module_file ) ) {
                    $this->register_module( basename( $module_file, '.php' ), $module_file );
                }
            }
        }
    }

    /**
     * Register a new module.
     *
     * @since    1.0.0
     * @access   public
     * @param    string $name      The name of the module.
     * @param    string $file_path The file path of the module.
     */
    public function register_module( $name, $file_path ) {
        $this->modules[ $name ] = $file_path;
    }

    /**
     * Get all registered modules.
     *
     * @since    1.0.0
     * @access   public
     * @return   array    The array of registered modules.
     */
    public function get_registered_modules() {
        return $this->modules;
    }

    /**
     * Load all registered modules.
     *
     * @since    1.0.0
     * @access   public
     */
    public function load_modules() {
        foreach ( $this->modules as $name => $file_path ) {
            $this->load_module( $file_path );
        }
    }

    /**
     * Load a single module file with error handling.
     *
     * @since    1.0.0
     * @access   private
     * @param    string $module_file    The path to the module file.
     */
    private function load_module( $module_file ) {
        if ( file_exists( $module_file ) ) {
            include_once $module_file;
        } else {
            error_log( 'UpTools Dev Modules: Unable to load module file ' . $module_file );
        }
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->load_modules();
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }
}
