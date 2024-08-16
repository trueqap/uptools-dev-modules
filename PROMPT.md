# upTools Dev Modules

upTools Dev Modules is a WordPress plugin that serves as a module loader for custom development projects. It allows easy management and activation of individual modules through the WordPress admin interface.

## Features

- Module loader functionality
- Admin interface for activating/deactivating modules
- Support for creating custom modules
- Internationalization support for easy translation
- Clean admin interface with only relevant notifications

## Installation

1. Upload the `uptools-dev-modules` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to 'Settings' > 'upTools Dev Modules' to manage your modules

## Usage

### Creating a New Module

1. Create a new PHP file in the `modules` directory of the plugin
2. Name your file using lowercase letters and hyphens, e.g., `your-module-name.php`
3. IMPORTANT: The file name directly influences the class name! The plugin generates the class name from the file name using these rules:
   - Replaces hyphens with spaces
   - Capitalizes the first letter of each word
   - Replaces spaces with underscores
   - Adds the "UpTools_" prefix
   
   For example:
   - `custom-order-column.php` → `UpTools_Custom_Order_Column`
   - `hello-world-module.php` → `UpTools_Hello_World_Module`
   - `sku-store-filter.php` → `UpTools_Sku_Store_Filter`

4. Use the following structure as a starting point:

```php
<?php
/**
 * Module Name
 *
 * Description of your module.
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
 * Your Module Class
 * IMPORTANT: The class name must exactly match the name generated from the file name!
 */
class UpTools_Your_Module_Name {

    /**
     * Get module info
     *
     * @return array Module information
     */
    public static function get_info() {
        return array(
            'name' => __( 'Your Module Name', 'uptools-dev-modules' ),
            'description' => __( 'Your module description.', 'uptools-dev-modules' ),
        );
    }

    /**
     * Initialize module functionality
     */
    public static function init() {
        // Add your module's functionality here
        add_action( 'wp_footer', array( __CLASS__, 'your_module_function' ) );
    }

    /**
     * Your module's function
     */
    public static function your_module_function() {
        echo '<p>' . esc_html__( 'This text was added by Your Module!', 'uptools-dev-modules' ) . '</p>';
    }
}
```

5. Implement your module's functionality within the class
6. Ensure that your class name exactly matches the name generated from the file name
7. The module will appear in the admin settings page where it can be activated or deactivated

### Important Notes on `get_info()` Method and File Naming

- The `get_info()` method MUST be implemented exactly as shown in the example above
- It MUST return an array with two keys: 'name' and 'description'
- Both 'name' and 'description' MUST use the `__()` function for translation
- The text domain for translations MUST be 'uptools-dev-modules'
- Do not add any additional keys to the returned array unless specifically instructed to do so in future updates
- The file name is crucial! Always use lowercase letters and hyphens when naming your file, and ensure that the class name exactly matches the name generated from the file name

### Example Module

Here's an example of a simple module that adds text to the admin footer:

```php
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
     * Get module info
     *
     * @return array Module information
     */
    public static function get_info() {
        return array(
            'name' => __( 'Example Module', 'uptools-dev-modules' ),
            'description' => __( 'This is an example module that demonstrates how to create and use a module in the upTools Dev Modules plugin.', 'uptools-dev-modules' ),
        );
    }

    /**
     * Initialize module functionality
     */
    public static function init() {
        add_action( 'admin_footer_text', array( __CLASS__, 'example_admin_footer_text' ) );
    }

    /**
     * Add example text to the admin footer
     *
     * @param string $text The existing admin footer text.
     * @return string Modified admin footer text.
     */
    public static function example_admin_footer_text( $text ) {
        return $text . ' ' . esc_html__( 'This text was added by the Example Module!', 'uptools-dev-modules' );
    }
}
```

This example module adds text to the admin footer. When activated, it will append "This text was added by the Example Module!" to the existing admin footer text.

### Managing Modules

1. Go to 'Settings' > 'upTools Dev Modules' in the WordPress admin
2. Check the boxes next to the modules you want to activate
3. Click 'Save Changes' to apply the changes

## Internationalization

The plugin is fully translatable. When creating new modules or adding text to existing ones, always use WordPress translation functions:

- `__()` for simple strings
- `_e()` to echo translated strings
- `esc_html__()` for escaped translations
- `esc_html_e()` to echo escaped translations

Always use 'uptools-dev-modules' as the text domain for these functions.

## Benefits of Using upTools Dev Modules

Using the upTools Dev Modules plugin offers several advantages for WordPress developers:

1. **Structured Development**: Provides a clear structure and framework for custom developments, making it easier to organize and maintain code.

2. **Modular Approach**: Encourages a modular approach to development, allowing you to separate functionalities into distinct, manageable units.

3. **Safe Loading**: Implements safe module loading practices, reducing the risk of conflicts between different custom functionalities.

4. **Error Handling**: Includes built-in error checking and handling, helping to identify and isolate issues in custom modules.

5. **Centralized Management**: Offers a centralized admin interface for activating and deactivating modules, providing better control over custom functionalities.

6. **Code Reusability**: Facilitates code reuse across different projects by encapsulating functionalities in modules.

7. **Improved Collaboration**: Makes it easier for multiple developers to work on the same project by clearly defining module boundaries.

8. **Performance Optimization**: Allows for selective activation of modules, potentially improving site performance by only loading necessary functionalities.

9. **Standardization**: Encourages adherence to coding standards and best practices across custom developments.

10. **Simplified Debugging**: By isolating functionalities in modules, it becomes easier to debug and maintain custom code.

## Development

When developing new modules or extending the plugin, please adhere to the [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/) and use best practices for plugin development.

## License

This plugin is licensed under the GPL v2 or later.

```
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
```

## Credits

Developed by the upTools Development Team. Visit our website at [https://uptools.io](https://uptools.io).
