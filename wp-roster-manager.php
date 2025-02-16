<?php
/**
 * Plugin Name: Roster Manager
 * Plugin URI: https://github.com/alleyinteractive/wp-roster-manager
 * Description: This is my plugin Roster Manager
 * Version: 0.0.0
 * Author: Ron Pasillas
 * Author URI: https://github.com/alleyinteractive/wp-roster-manager
 * Requires at least: 5.9
 * Requires PHP: 8.2
 * Tested up to: 6.7
 *
 * Text Domain: wp-roster-manager
 * Domain Path: /languages/
 *
 * @package wp-roster-manager
 */

namespace Alley\WP\Roster_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Root directory to this plugin.
 */
define( 'WP_ROSTER_MANAGER_DIR', __DIR__ );

// Check if Composer is installed (remove if Composer is not required for your plugin).
if ( ! file_exists( __DIR__ . '/vendor/wordpress-autoload.php' ) ) {
	// Will also check for the presence of an already loaded Composer autoloader
	// to see if the Composer dependencies have been installed in a parent
	// folder. This is useful for when the plugin is loaded as a Composer
	// dependency in a larger project.
	if ( ! class_exists( \Composer\InstalledVersions::class ) ) {
		\add_action(
			'admin_notices',
			function () {
				?>
				<div class="notice notice-error">
					<p><?php esc_html_e( 'Composer is not installed and wp-roster-manager cannot load. Try using a `*-built` branch if the plugin is being loaded as a submodule.', 'wp-roster-manager' ); ?></p>
				</div>
				<?php
			}
		);

		return;
	}
} else {
	// Load Composer dependencies.
	require_once __DIR__ . '/vendor/wordpress-autoload.php';
}

// Load the plugin's main files.
require_once __DIR__ . '/src/assets.php';
require_once __DIR__ . '/src/meta.php';
require_once __DIR__ . '/src/main.php';

load_scripts();
register_post_meta_from_defs();
main();
