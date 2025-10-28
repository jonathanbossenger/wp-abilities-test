# WP Abilities Test

A WordPress plugin designed to test and demonstrate the WordPress Abilities API. This plugin provides examples of how to register and execute custom abilities in WordPress using both PHP and JavaScript.

## Description

WP Abilities Test is a demonstration plugin that showcases the WordPress Abilities API functionality. It includes two example abilities:

- **PHP Ability (`my-plugin/debug-status`)**: Retrieves the status of WordPress debugging constants (WP_DEBUG, WP_DEBUG_DISPLAY, WP_DEBUG_LOG)
- **JavaScript Ability (`my-plugin/alert-user`)**: Displays custom alert messages to users in the WordPress admin

The plugin adds an admin menu page where these abilities can be tested interactively.

## Features

- 🛠️ PHP ability registration example with schema validation
- 🎯 JavaScript ability registration using the wp.abilities API
- 🖥️ Admin interface for testing abilities
- 🔒 Permission callbacks for secure ability execution
- 📋 Input/Output schema definitions

## Requirements

- WordPress 6.0 or higher
- PHP 7.4 or higher
- WordPress [Abilities API](https://github.com/WordPress/abilities-api) (required dependency)

## Installation

1. Clone the Abilities API from the [GitHub repository](https://github.com/WordPress/abilities-api) and install the required dependencies:
   ```bash
       cd wp-content/plugins/
       git clone git@github.com:WordPress/abilities-api.git
       cd abilities-api
       composer install
       npm install
       npm run build
   ```
   
2. Activate the Abilities API plugin through the WordPress admin panel or using WP-CLI:
   ```bash
   wp plugin activate abilities-api
   ```

3. Clone or download this repository to your WordPress plugins directory:
   ```bash
   cd wp-content/plugins/
   git clone https://github.com/jonathanbossenger/wp-abilities-test.git
   ```

4. Install dependencies (for development):
   ```bash
   composer install
   ```

5. Activate the plugin through the WordPress admin panel or using WP-CLI:
   ```bash
   wp plugin activate wp-abilities-test
   ```

## Usage

### Testing in WordPress Admin

1. After activating the plugin, navigate to **WP Abilities Test** in the WordPress admin menu
2. Click **Check Debug Status** to execute the PHP ability and see the WordPress debug configuration
3. Enter a custom message in the text field and click **Alert User** to execute the JavaScript ability

### Category Registration

All abilities must belong to a category. Categories must be registered using the `abilities_api_categories_init` action hook. The plugin demonstrates category registration:

```php
add_action( 'wp_abilities_api_categories_init', 'my_plugin_register_test_abilities_category' );
function my_plugin_register_test_abilities_category() {
    wp_register_ability_category(
        'test-abilities',
        array(
            'label'       => __( 'Test Abilities', 'wp-abilities-test' ),
            'description' => __( 'Abilities for testing the WordPress Abilities API.', 'wp-abilities-test' ),
        )
    );
}
```

### PHP Ability Example

The plugin demonstrates how to register a PHP ability with a category:

```php
wp_register_ability(
    'my-plugin/debug-status',
    array(
        'label'               => __( 'Get the WordPress debug status', 'my-plugin' ),
        'description'         => __( 'Retrieves the status of the WordPress Debugging Constants.', 'my-plugin' ),
        'category'            => 'test-abilities',
        'output_schema'       => array(
            'type'       => 'object',
            'properties' => array(
                'debug'         => array(
                    'type'        => 'boolean',
                    'description' => 'Status of WP_DEBUG constant',
                ),
                // ... more properties
            ),
        ),
        'execute_callback'    => 'my_plugin_register_debug_status_execute_callback',
        'permission_callback' => 'my_plugin_register_debug_status_permission_callback',
        'meta'                => array(
            'type' => 'tool',
        ),
    )
);
```

### JavaScript Ability Example

The plugin also demonstrates JavaScript ability registration with a category:

```javascript
const { registerAbility, executeAbility } = wp.abilities;

registerAbility({
    name: 'my-plugin/alert-user',
    label: 'Alert User',
    description: 'Display an alert message to the user',
    category: 'test-abilities',
    input_schema: {
        type: 'object',
        properties: {
            message: { type: 'string' },
        },
        required: ['message']
    },
    callback: async ({ message }) => {
        alert(message);
    },
    permissionCallback: () => {
        return !!wp.data.select('core').getCurrentUser();
    }
});
```

## Development

### Code Standards

This plugin follows WordPress Coding Standards. To check your code:

```bash
composer install
./vendor/bin/phpcs
```

### File Structure

```
wp-abilities-test/
├── assets/
│   └── wp-abilities-test.js    # JavaScript abilities and UI interactions
├── wp-abilities-test.php        # Main plugin file with PHP abilities
├── composer.json                # Composer dependencies
└── README.md                    # This file
```

## License

This project is maintained by [Jonathan Bossenger](mailto:jonathanbossenger@gmail.com).

## Contributing

This is a test/demonstration plugin. Feel free to use it as a reference for implementing the WordPress Abilities API in your own projects.

## Support

For questions or issues related to the WordPress Abilities API itself, please refer to the main Abilities API documentation.
