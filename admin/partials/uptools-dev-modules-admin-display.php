<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      1.0.0
 * @package    UpToolsDevModules
 * @subpackage UpToolsDevModules/admin/partials
 */
?>

<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    
    <div class="uptools-dev-modules-description">
        <p><?php esc_html_e( 'Welcome to the upTools Dev Modules settings page. This page allows you to manage and configure the various modules available in the upTools Dev Modules plugin.', 'uptools-dev-modules' ); ?></p>
        <p><?php esc_html_e( 'Here, you can activate or deactivate individual modules based on your specific needs. Each module provides unique functionality to enhance your WordPress development experience.', 'uptools-dev-modules' ); ?></p>
        <p>
            <?php
            printf(
                wp_kses(
                    /* translators: %s: GitHub repository URL */
                    __( 'If you want to develop a custom module, you can easily do so by following our development guidelines. Check out our <a href="%s" target="_blank">GitHub repository</a> for examples and detailed documentation on module development.', 'uptools-dev-modules' ),
                    array(
                        'a' => array(
                            'href'   => array(),
                            'target' => array(),
                        ),
                    )
                ),
                'https://github.com/uptools-development/uptools-dev-modules'
            );
            ?>
        </p>
    </div>

    <form action="options.php" method="post">
        <?php
        settings_fields( $this->plugin_name );
        ?>
        <table class="uptools-modules-table">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Module', 'uptools-dev-modules' ); ?></th>
                    <th><?php esc_html_e( 'Description', 'uptools-dev-modules' ); ?></th>
                    <th><?php esc_html_e( 'Status', 'uptools-dev-modules' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $modules = $this->get_available_modules();
                $active_modules = get_option( 'active_modules', array() );
                foreach ( $modules as $module_name => $module_path ) :
                    $checked = in_array( $module_name, $active_modules, true ) ? 'checked' : '';

                    include_once $module_path;
                    $class_name = 'UpTools_' . str_replace( ' ', '_', $module_name );

                    if ( class_exists( $class_name ) && method_exists( $class_name, 'get_name' ) && method_exists( $class_name, 'get_description' ) ) {
                        $display_name = $class_name::get_name();
                        $description = $class_name::get_description();
                    } else {
                        $display_name = $module_name;
                        $description = __( 'No description available.', 'uptools-dev-modules' );
                    }
                ?>
                <tr>
                    <td data-column="<?php esc_attr_e( 'Module', 'uptools-dev-modules' ); ?>">
                        <span class="uptools-module-name"><?php echo esc_html( $display_name ); ?></span>
                    </td>
                    <td data-column="<?php esc_attr_e( 'Description', 'uptools-dev-modules' ); ?>">
                        <span class="uptools-module-description"><?php echo esc_html( $description ); ?></span>
                    </td>
                    <td data-column="<?php esc_attr_e( 'Status', 'uptools-dev-modules' ); ?>" class="uptools-module-toggle">
                        <input type="checkbox" id="module-<?php echo esc_attr( $module_name ); ?>" name="active_modules[]" value="<?php echo esc_attr( $module_name ); ?>" <?php echo $checked; ?>>
                        <label for="module-<?php echo esc_attr( $module_name ); ?>"></label>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php submit_button( __( 'Save Changes', 'uptools-dev-modules' ) ); ?>
    </form>
</div>
