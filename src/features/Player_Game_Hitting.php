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
		//@link https://roster-manager.alley.test/wp-json/roster-manager/v1/player-game-hitting?player_id=31&game_number=37
		register_rest_route( 'roster-manager/v1', '/player-game-hitting/', [
			'methods'  => 'GET',
			'callback' => [ $this, 'get_player_game_batting' ],
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

	public function permission_callback(): bool {
		return true;
	}

	/**
	 * Handle GET request: Retrieve a player's hitting stats.
	 *
	 * @param \WP_REST_Request $request The REST API request.
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function get_player_game_batting( \WP_REST_Request $request ): \WP_REST_Response|\WP_Error {
		$player_id = $request->get_param( 'player_id' );
		$game_number = $request->get_param( 'game_number' );

		if ( ! $player_id ) {
			return new \WP_Error(
				'stats_not_found',
				__( 'Hitting stats not found for the given player.', 'wp-roster-manager' ),
				[ 'status' => 404 ]
			);
		}

		return rest_ensure_response( $this->get_player_game_batting_data( $player_id, $game_number ) );
	}

	/**
	 *
	 *
	 * @param int $id the player id
	 * @param int|null $game_number If null, get the full season
	 *
	 * @return array
	 */
	public function get_player_game_batting_data( int $id, int $game_number = null ): array {
		//@link https://en.wikipedia.org/wiki/Batted_ball
		$data = [
			'at_bats'                => 2,
			'runs'                   => 1,
			'hits'                   => 1,
			'rbi'                    => 1,
			'hr'                     => 0,
			'at_bats_pitch_sequence' => [
				[ // first at bat
					'hand' => 'right',
					'outs' => 2,
					'rbi' => 1,
					'pitcher' => [
						'hand' => 'right',
						'number' => 37,
					],
					'runners_on_base' => [
						'first' => true,
						'second' => false,
						'third' => true,
					],
					'result' => 'single',
					'pitches' => [
						[ // first pitch
							'count' => [
								'balls'   => 0,
								'strikes' => 0,
							],
							'result' => [
								'batted_ball' => [
									'fair-foul' => 'foul',
									'characterization' => 'fly-ball', //fly-ball, pop-up, line-drive, ground-ball
									'location' => [
										'deep', //deep, shallow, standard
										'left' // left, left-center, center, center-right, right, third, third-short, short, short-second
									],
								],
							],
						],
						[ //second pitch
							'count' => [
								'balls'   => 0,
								'strikes' => 1,
							],
							'result' => [
								'reached_base' => '1', // a single
								'batted_ball' => [
									'fair-foul' => 'fair',
									'characterization' => 'line-drive',
									'location' => [
										'deep',
										'center'
									],
								],
								'rbi' => 1,
							],
						],
					],
				],
				[ // second at bat
					'hand' => 'left',
					'outs' => 0,
					'pitcher' => [
						'hand' => 'left',
						'number' => 3,
					],
					'rbi'  => 0,
					'runners_on_base' => [
						'first' => true,
						'second' => false,
						'third' => false,
					],
					'result' => 'strikeout',
					'pitches' => [
						[ // first pitch
							'count' => [
								'balls'   => 0,
								'strikes' => 1,
							],
							'result' => [
								'strike' => 'swinging',
							]
						],
						[ // second pitch
							'count' => [
								'balls'   => 0,
								'strikes' => 2,
							],
							'result' => [
								'strike' => 'looking',
							]
						],
						[ // third pitch
							'count' => [
								'balls'   => 0,
								'strikes' => 2,
							],
							'result' => [
								'batted_ball' => [
									'fair-foul' => 'foul',
									'characterization' => 'ground-ball',
									'location' => [
										'standard',
										'catcher',
									],
								],
							]
						],
						[ // fourth pitch
							'count' => [
								'balls' => 0,
								'strikes' => 2,
							],
							'result' => [
								'strikeout' => 'looking'
							]
						],
					],
				],
			]
		];

		$player = $this->get_player( $id );
		$game   = $this->get_game( $game_number );

		$data = array_merge( $player, $game, $data );

		return $data;
	}

	public function get_game( $id ): array {
		return [
			'game' => [
				'game_number' => $id, // games can be a post type
				'opponent'    => 'Red Sox', // Teams can be a post type
				'home_team'   => 'Red Sox',
				'date'        => '',
				'time'        => '',
				'field'       => [
					'location' => 'Ronald Reagan', // Locations can be a taxonomy
					'number'   => 4,
				],
				'score'       => [
					'visitors' => 6,
					'home'     => 4,
					'result'   => 'win',
				],
			],
		];
	}

	public function get_player( $id ): array {
		return [
			'player_id'     => $id, // players can be a post type
			'player_name'   => 'Ulises Pasillas',
			'player_number' => 5,
		];
	}

}
