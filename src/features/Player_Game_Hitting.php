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
		add_action( 'rest_api_init', [ $this, 'register_player_game_hitting_routes' ] );
	}

	public function register_player_game_hitting_routes() {
		register_rest_route( 'roster-manager/v1', '/player-game-hitting/', [
			'methods'  => 'GET',
			'callback' => [ $this, 'get_player_game_hitting' ],
			'args'     => [
				'player_id' => [
					'required' => true,
					'validate_callback' => fn($param) => is_numeric($param) && $param > 0,
				],
				'game_number' => [
					'required' => false,
					'validate_callback' => fn($param) => is_numeric($param) && $param > 0,
				]
			],
			'permission_callback' => [ $this, 'permission_callback'],
		]);
	}

	public function permission_callback() {
		return true;
	}

	/**
	 * Handle GET request: Retrieve a player's hitting stats.
	 *
	 * @param \WP_REST_Request $request The REST API request.
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function get_player_game_hitting( \WP_REST_Request $request ): \WP_REST_Response|\WP_Error {
		$player_id = $request->get_param( 'player_id' );
		$game_number = $request->get_param( 'game_number' );

		// Retrieve hitting stats for the player (replace with actual logic).
		//$hitting_stats = get_option( "player_{$player_id}_hitting_stats" );

		if ( ! $player_id ) {
			return new \WP_Error(
				'stats_not_found',
				__( 'Hitting stats not found for the given player.', 'wp-roster-manager' ),
				[ 'status' => 404 ]
			);
		}

		return rest_ensure_response( [
			'player_id' => $player_id,
			'game_number' => $game_number,
		] );
	}

}
