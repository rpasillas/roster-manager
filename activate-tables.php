<?php

/**
 * Class that creates the necessary tables.
 */
class Activate_Tables {
	/**
	 * Creates the `wp_player_games` table. Drops it first if it already exists.
	 *
	 * @return void
	 */
	public function player_games_table(): void {
		global $wpdb;

		// Get the WordPress database charset and collation
		$charset_collate = $wpdb->get_charset_collate();

		// Define table names with WordPress prefix
		$player_games_table = $wpdb->prefix . 'player_games';

		if ( $this->check_table_exists( $player_games_table) ) {
			$this->drop_table( $player_games_table );
			return;
		}

		// SQL for wp_player_games table
		$sql_player_games = "CREATE TABLE $player_games_table (
	        id int(11) NOT NULL AUTO_INCREMENT,
	        player_id bigint(20) unsigned NOT NULL,
	        game_id bigint(20) unsigned NOT NULL,
	        team_id bigint(20) unsigned NOT NULL,
	        batting_order tinyint(2) DEFAULT NULL,
	        position varchar(10) DEFAULT NULL,
	        at_bats int(11) DEFAULT 0,
	        runs int(11) DEFAULT 0,
	        hitzz int(11) DEFAULT 0,
	        rbi int(11) DEFAULT 0,
	        hr int(11) DEFAULT 0,
	        created_at datetime DEFAULT CURRENT_TIMESTAMP,
	        updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	        PRIMARY KEY (id),
	        KEY player_id (player_id),
	        KEY game_id (game_id),
	        KEY team_id (team_id),
	        FOREIGN KEY (player_id) REFERENCES {$wpdb->posts}(ID) ON DELETE CASCADE,
	        FOREIGN KEY (game_id) REFERENCES {$wpdb->posts}(ID) ON DELETE CASCADE,
	        FOREIGN KEY (team_id) REFERENCES {$wpdb->posts}(ID) ON DELETE CASCADE
	    ) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql_player_games );

		add_option( 'roster_manager_version', '0.0.0' );
	}

	/**
	 * Creates the `wp_at_bats` table. Drops it first if it already exists.
	 *
	 * @return void
	 */
	public function at_bats_table(): void {
		global $wpdb;

		// Get the WordPress database charset and collation
		$charset_collate = $wpdb->get_charset_collate();

		// Define table names with WordPress prefix
		$at_bats_table      = $wpdb->prefix . 'at_bats';
		$player_games_table = $wpdb->prefix . 'player_games';

		if ( $this->check_table_exists( $at_bats_table) ) {
			$this->drop_table( $at_bats_table );
			return;
		}

		// SQL for wp_player_games table
		$sql_at_bats = "CREATE TABLE $at_bats_table (
	        id int(11) NOT NULL AUTO_INCREMENT,
	        player_game_id int(11) NOT NULL,
	        at_bat_number tinyint(2) unsigned NOT NULL,
	        inning tinyint(2) unsigned NOT NULL,
	        outs tinyint(1) DEFAULT NULL,
	        batting_hand varchar(10) DEFAULT NULL,
	        pitcher_hand int(11) DEFAULT 0,
	        pitcher_number tinyint(2) DEFAULT 0,
	        runner_first tinyint(1) DEFAULT 0,
	        runner_second tinyint(1) DEFAULT 0,
	        runner_third tinyint(1) DEFAULT 0,
	        result varchar(10) DEFAULT NULL,
	        rbi_count tinyint(2) DEFAULT 0,
	        created_at datetime DEFAULT CURRENT_TIMESTAMP,
	        updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	        PRIMARY KEY (id),
	        KEY player_game_id (player_game_id),
	        FOREIGN KEY (player_game_id) REFERENCES {$player_games_table}(id) ON DELETE CASCADE
	    ) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql_at_bats );

		add_option( 'roster_manager_version', '0.0.0' );
	}

	/**
	 * Creates the `wp_pitches` table. Drops it first if it already exists.
	 *
	 * @return void
	 */
	public function pitches_table(): void {
		global $wpdb;

		// Get the WordPress database charset and collation
		$charset_collate = $wpdb->get_charset_collate();

		// Define table names with WordPress prefix
		$pitches_table = $wpdb->prefix . 'pitches';
		$at_bats_table = $wpdb->prefix . 'at_bats';

		if ( $this->check_table_exists( $pitches_table) ) {
			$this->drop_table( $pitches_table );
			return;
		}

		// SQL for wp_player_games table
		$sql_pitches = "CREATE TABLE $pitches_table (
	        id int(11) NOT NULL AUTO_INCREMENT,
	        at_bat_id int(11) NOT NULL,
	        pitch_number tinyint(2) unsigned NOT NULL,
	        balls tinyint(2) unsigned NOT NULL,
	        strikes tinyint(2) unsigned NOT NULL,
	        result_type varchar(32) DEFAULT NULL,
	        batted_ball_fair_foul varchar(32) DEFAULT NULL,
	        batted_ball_type varchar(32) DEFAULT NULL,
	        hit_location_depth varchar(32) DEFAULT NULL,
	        hit_location_area varchar(32) DEFAULT NULL,
	        reached_base tinyint(1) DEFAULT 0,
	        rbi tinyint(2) DEFAULT 0,
	        created_at datetime DEFAULT CURRENT_TIMESTAMP,
	        updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	        PRIMARY KEY (id),
	        KEY at_bat_id (at_bat_id),
	        FOREIGN KEY (at_bat_id) REFERENCES {$at_bats_table}(id) ON DELETE CASCADE
	    ) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql_pitches );

		add_option( 'roster_manager_version', '0.0.0' );
	}

	/**
	 * Checks if a given table exists.
	 *
	 * @param string $table The table name.
	 *
	 * @return string|null
	 */
	public function check_table_exists( string $table ): ?string {
		global $wpdb;
		return $wpdb->get_var( "SHOW TABLES LIKE '$table'" );
	}

	/**
	 * Drops a given table name.
	 *
	 * @param string $table The table name.
	 *
	 * @return void
	 */
	public function drop_table( string $table ): void {
		global $wpdb;
		$wpdb->query( "DROP TABLE IF EXISTS $table" );
		delete_option( 'roster_manager_version' );
	}
}
register_activation_hook( __DIR__ . '\wp-roster-manager.php', function(){
	$activator = new Activate_Tables();
	$activator->player_games_table();
	$activator->at_bats_table();
	$activator->pitches_table();
});
