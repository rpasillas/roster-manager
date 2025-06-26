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
	$plugin = new Group(
		new Features\Player_Game_Hitting(),
		new Features\post_types\Games(),
		new Features\post_types\Players(),
		new Features\post_types\Teams(),
		new Features\taxonomies\Seasons(),
		new Features\taxonomies\Positions(),
	);

	$plugin->boot();
}
