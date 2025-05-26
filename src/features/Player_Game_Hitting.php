<?php
/**
 * Feature for CRUDing a player's hitting for a game.
 *
 * @package wp-roster-manager
 */

namespace Alley\WP\Roster_Manager\Features;

use Alley\WP\Types\Feature;

/**
 * Player Game Hitting class.
 *
 * For record the basic hitting stats for a player for a game.
 */
final class Player_Game_Hitting implements Feature {
	/**
	 * Set up.
	 */
	public function __construct() {
	}

	/**
	 * Boot the feature.
	 *
	 * @return void
	 */
	public function boot(): void {
		//setup hooks here
	}
}
