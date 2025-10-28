<?php
/**
 * Plugin Name: WP Abilities Test
 * Description: A plugin to test custom WordPress Abilities.
 *
 * @package wp-abilities-test
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! function_exists( 'wp_register_ability' ) ) {
    wp_die( 'The Abilities API is not available.' );
}

add_action( 'admin_enqueue_scripts', 'wp_register_ability_admin_enqueue_scripts' );
/**
 * Enqueue the Abilities API client script in the WordPress admin only.
 *
 * @return void
 */
function wp_register_ability_admin_enqueue_scripts() {
	wp_enqueue_script(
		'wp-abilities-test',
		plugin_dir_url( __FILE__ ) . 'assets/wp-abilities-test.js',
		array( 'wp-abilities' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'assets/wp-abilities-test.js' ),
		true
	);
}

add_action( 'admin_menu', 'wp_register_ability_admin_menu' );
/**
 * Registers the admin menu page for testing the Abilities API.
 *
 * @return void
 */
function wp_register_ability_admin_menu() {
	add_menu_page(
		'WP Abilities Test',
		'WP Abilities Test',
		'manage_options',
		'wp-abilities-test',
		'wp_register_ability_admin_page',
		'dashicons-shield-alt',
		20
	);
}

/**
 * Renders the admin page for testing the Abilities API.
 * Renders a button to test the PHP my-plugin/debug-status ability, and display the results in a preformatted block.
 * Renders a button to test the JavaScript my-plugin/alert-user ability, which shows an alert to the user. The message is fetched from an input field.
 *
 * @return void
 */
function wp_register_ability_admin_page() {
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'WP Abilities Test', 'wp-abilities-test' ); ?></h1>
		<p><?php esc_html_e( 'This page tests the Abilities API.', 'wp-abilities-test' ); ?></p>
		<div id="wp-abilities-test-root">
			<button id="check-debug-status" class="button button-primary">
				<?php esc_html_e( 'Check Debug Status', 'wp-abilities-test' ); ?>
			</button>
			<pre id="debug-status-result" style="margin-top:20px; background:#f1f1f1; padding:10px; border:1px solid #ccc;"></pre>
			<hr />
			<div style="margin-top:10px;">
				<label for="alert-message"><?php esc_html_e( 'Alert Message:', 'wp-abilities-test' ); ?></label>
				<input type="text" id="alert-message" value="<?php esc_attr_e( 'Hello from Abilities API!', 'wp-abilities-test' ); ?>" style="width:300px; margin-left:10px;" />
			</div>
			<button id="alert-user" class="button button-secondary" style="margin-top:20px;">
				<?php esc_html_e( 'Alert User', 'wp-abilities-test' ); ?>
			</button>
		</div>
	</div>
	<?php
}

add_action( 'wp_abilities_api_categories_init', 'my_plugin_register_test_abilities_category' );
/**
 * Registers the 'test-abilities' category.
 *
 * @return void
 */
function my_plugin_register_test_abilities_category() {
	wp_register_ability_category(
		'test-abilities',
		array(
			'label'       => __( 'Test Abilities', 'wp-abilities-test' ),
			'description' => __( 'Abilities for testing the WordPress Abilities API.', 'wp-abilities-test' ),
		)
	);
}

add_action( 'wp_abilities_api_init', 'my_plugin_register_debug_status_ability' );
/**
 * Registers the 'my-plugin/debug-status' ability.
 *
 * @return void
 */
function my_plugin_register_debug_status_ability() {
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
					'debug_display' => array(
						'type'        => 'boolean',
						'description' => 'Status of WP_DEBUG_DISPLAY constant',
					),
					'debug_log'     => array(
						'type'        => 'boolean',
						'description' => 'Status of WP_DEBUG_LOG constant',
					),
				),
			),
			'execute_callback'    => 'my_plugin_register_debug_status_execute_callback',
			'permission_callback' => 'my_plugin_register_debug_status_permission_callback',
			'meta'                => array(
				'type' => 'tool',
                'show_in_rest' => true,
			),
		)
	);
}

/**
 * Executes the ability to get the WordPress debug status.
 *
 * @return array An array containing the status of WP_DEBUG, WP_DEBUG_DISPLAY, and WP_DEBUG_LOG constants.
 */
function my_plugin_register_debug_status_execute_callback() {
	$debug         = defined( 'WP_DEBUG' ) && WP_DEBUG;
	$debug_display = defined( 'WP_DEBUG_DISPLAY' ) && WP_DEBUG_DISPLAY;
	$debug_log     = defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG;
	return array(
		'debug'         => $debug,
		'debug_display' => $debug_display,
		'debug_log'     => $debug_log,
	);
}

/**
 * Permission callback to check if the current user can manage options.
 *
 * @return bool True if the user can manage options, false otherwise.
 */
function my_plugin_register_debug_status_permission_callback() {
	return current_user_can( 'manage_options' );
}

// Check if the MCP Adapter plugin is installed and active
if ( class_exists( WP\MCP\Core\McpAdapter::class ) ) {
    $adapter = WP\MCP\Core\McpAdapter::instance();
    add_action('mcp_adapter_init', function($adapter) {
        $adapter->create_server(
            'ai-experiments-server',                    // Unique server identifier
            'ai-experiments',                    // REST API namespace
            'mcp',                            // REST API route
            'My AI Experiments Server',                  // Server name
            'My AI Experiments Server',       // Server description
            'v1.0.0',                        // Server version
            [                                 // Transport methods
                    \WP\MCP\Transport\HttpTransport::class,  // Recommended: MCP 2025-06-18 compliant
            ],
            \WP\MCP\Infrastructure\ErrorHandling\ErrorLogMcpErrorHandler::class, // Error handler
            ['my-plugin/debug-status'],         // Abilities to expose as tools
            [],                              // Resources (optional)
            [],                              // Prompts (optional)
            \WP\MCP\Infrastructure\Observability\NullMcpObservabilityHandler::class // Observability handler
        );
    });
}