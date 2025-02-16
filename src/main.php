<?php
/**
 * The main plugin function
 *
 * @package wp-roster-manager
 */

namespace Alley\WP\Roster_Manager;

use Alley\WP\Features\Group;

/**
 * Instantiate the plugin.
 */
function main(): void {
	// Add features here.
	$plugin = new Group();

	$plugin->boot();
}
