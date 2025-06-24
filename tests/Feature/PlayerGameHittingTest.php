<?php
/**
 * Roster Manager Tests: Player Game Hitting
 *
 * @package wp-roster-manager
 */

namespace Alley\WP\Roster_Manager\Tests\Feature;

use Alley\WP\Roster_Manager\Tests\TestCase;
use Alley\WP\Roster_Manager\Features;

/**
 * A test suite for an example feature.
 *
 * @link https://mantle.alley.com/testing/test-framework.html
 */
class PlayerGameHittingTest extends TestCase {
	public function test_example(): void {
		$player_game_hitting = new Features\Player_Game_Hitting();

		$this->assertIsArray( $player_game_hitting->get_player( 3 ) );
	}
}
