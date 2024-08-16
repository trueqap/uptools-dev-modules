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
2. Use the following structure as a starting point:

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
 */
class UpTools_Your_Module {

    /**
     * Module name
     *
     * @var string
     */
    public static $name = 'Your Module Name';

    /**
     * Module description
     *
     * @var string
     */
    public static $description = 'Your module description.';

    /**
     * Initialize the module
     */
    public function init() {
        // Add your module's functionality here
        add_action( 'wp_footer', array( $this, 'your_module_function' ) );
    }

    /**
     * Your module's function
     */
    public function your_module_function() {
        echo '<p>' . esc_html__( 'This text was added by Your Module!', 'uptools-dev-modules' ) . '</p>';
    }

    /**
     * Get translated module name
     *
     * @return string Translated module name
     */
    public static function get_name() {
        return __( 'Your Module Name', 'uptools-dev-modules' );
    }

    /**
     * Get translated module description
     *
     * @return string Translated module description
     */
    public static function get_description() {
        return __( 'Your module description.', 'uptools-dev-modules' );
    }
}
```

3. Implement your module's functionality within the class
4. The module will appear in the admin settings page where it can be activated or deactivated

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
