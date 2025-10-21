# GitHub Copilot Instructions for WP Abilities Test

## Repository Overview

This is a WordPress plugin that demonstrates the WordPress Abilities API. It provides examples of registering and executing custom abilities using both PHP and JavaScript.

## Project Context

- **Type**: WordPress Plugin
- **Primary Language**: PHP (WordPress plugin), JavaScript (client-side abilities)
- **WordPress Version**: 6.0 or higher
- **PHP Version**: 7.4 or higher
- **Key Dependency**: WordPress Abilities API plugin (required)

## File Structure

```
wp-abilities-test/
├── assets/
│   └── wp-abilities-test.js    # JavaScript abilities registration and UI interactions
├── wp-abilities-test.php        # Main plugin file with PHP ability registration
├── composer.json                # Composer dependencies and coding standards
└── README.md                    # Documentation
```

## Coding Standards

This project follows **WordPress Coding Standards (WPCS)**. All PHP code should adhere to these standards.

### Checking Code Standards

```bash
composer install
./vendor/bin/phpcs
```

### Key Conventions

- Use WordPress-style function naming: `prefix_function_name()`
- Use WordPress escaping functions: `esc_html()`, `esc_attr()`, `esc_url()`, etc.
- Use WordPress translation functions: `__()`, `esc_html__()`, `esc_attr__()`, etc.
- Follow WordPress indentation: tabs for indentation, spaces for alignment
- Use WordPress documentation standards for DocBlocks
- Check for `ABSPATH` in plugin files to prevent direct access

## Development Guidelines

### PHP Development

1. **Plugin Structure**: This is a simple, single-file WordPress plugin with all PHP code in `wp-abilities-test.php`
2. **Hooks**: Use WordPress hooks (`add_action`, `add_filter`) for all functionality
3. **Abilities Registration**: Register abilities using the `wp_register_ability()` function from the Abilities API
4. **Security**: Always validate capabilities and sanitize/escape data
5. **Text Domain**: Use `'wp-abilities-test'` for all translations

### JavaScript Development

1. **Dependencies**: JavaScript code depends on the `wp-abilities` script from the Abilities API plugin
2. **API Access**: Access abilities API via `wp.abilities.registerAbility()` and `wp.abilities.executeAbility()`
3. **Modern JavaScript**: Use modern ES6+ syntax (const/let, arrow functions, async/await)

### Ability Registration

When working with abilities:

- **PHP Abilities**: Register in the `abilities_api_init` action
- Include `label`, `description`, `output_schema`/`input_schema`, `execute_callback`, and `permission_callback`
- Use schema validation for inputs and outputs
- **JavaScript Abilities**: Register using `wp.abilities.registerAbility()` with name, label, description, callback, and permissionCallback

## Testing

Currently, this is a demonstration plugin without automated tests. Testing is done manually through the WordPress admin interface:

1. Navigate to **WP Abilities Test** menu in WordPress admin
2. Test the PHP ability with **Check Debug Status** button
3. Test the JavaScript ability with **Alert User** button

## Dependencies

### Composer (Development)

```json
{
  "require-dev": {
    "wp-coding-standards/wpcs": "^3.0"
  }
}
```

### WordPress Dependencies

- WordPress Abilities API plugin (runtime dependency)
- WordPress 6.0+
- PHP 7.4+

## Common Tasks

### Adding a New PHP Ability

1. Create the ability registration function hooked to `abilities_api_init`
2. Define the schema (input/output)
3. Implement the execute callback
4. Implement the permission callback
5. Update the admin page UI if needed

### Adding a New JavaScript Ability

1. Register the ability in `assets/wp-abilities-test.js`
2. Define the input schema
3. Implement the callback function
4. Implement the permission callback
5. Add UI controls in the admin page if needed

## Code Style Examples

### PHP Function Documentation

```php
/**
 * Brief description of the function.
 *
 * Longer description if needed.
 *
 * @param string $param1 Description of parameter.
 * @return bool True on success, false on failure.
 */
function my_plugin_function( $param1 ) {
    // Function body
}
```

### Escaping Output

```php
// Escaping text
esc_html_e( 'Text to translate', 'wp-abilities-test' );

// Escaping attributes
echo '<input value="' . esc_attr( $value ) . '" />';

// Escaping URLs
echo '<a href="' . esc_url( $url ) . '">Link</a>';
```

## Important Notes

- This plugin requires the WordPress Abilities API plugin to function
- All ability names should follow the `namespace/ability-name` pattern
- Permission callbacks should always check user capabilities
- Admin pages should be accessible only to users with appropriate permissions
- Always use WordPress functions for database access, file operations, and HTTP requests
