<?php
/**
 * Feature for CRUDing a player's fielding for a game.
 *
 * @package wp-roster-manager
 */

namespace Alley\WP\Roster_Manager\Features\player\game\fielding;

use Alley\WP\Types\Feature;

/**
 * Who knows what kind of stats will go here?
 *
 * - errors
 * - catches
 * - position
 * - assists
 */
final class Player_Game_Fielding implements Feature {
	/**
	 * Set up.
	 */
	public function __construct() {}

	/**
	 * Boot the feature.
	 *
	 * @return void
	 */
	public function boot(): void {
		//setup hooks here
	}
}
