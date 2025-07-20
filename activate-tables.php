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
});
