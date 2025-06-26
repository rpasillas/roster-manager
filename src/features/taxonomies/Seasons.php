<?php
/**
 * Feature for creating the Seasons taxonomy.
 *
 * @package wp-roster-manager
 */

namespace Alley\WP\Roster_Manager\Features\taxonomies;

use Alley\WP\Types\Feature;

final class Seasons implements Feature {
	/**
	 * Boot the feature.
	 *
	 * @return void
	 */
	public function boot(): void {
		add_action( 'init', [$this, 'register'] );
		add_filter( 'post_updated_messages', [$this, 'updated_messages'] );
	}

	public function register(): void {
		register_taxonomy( 'seasons', [ 'players', 'games' ], [
			'hierarchical'          => false,
			'public'                => true,
			'show_in_nav_menus'     => true,
			'show_ui'               => true,
			'show_admin_column'     => false,
			'query_var'             => true,
			'rewrite'               => true,
			'capabilities'          => [
				'manage_terms' => 'edit_posts',
				'edit_terms'   => 'edit_posts',
				'delete_terms' => 'edit_posts',
				'assign_terms' => 'edit_posts',
			],
			'labels'                => [
				'name'                       => __( 'Seasons', 'roster-manager' ),
				'singular_name'              => _x( 'Season', 'taxonomy general name', 'roster-manager' ),
				'search_items'               => __( 'Search Seasons', 'roster-manager' ),
				'popular_items'              => __( 'Popular Seasons', 'roster-manager' ),
				'all_items'                  => __( 'All Seasons', 'roster-manager' ),
				'parent_item'                => __( 'Parent Season', 'roster-manager' ),
				'parent_item_colon'          => __( 'Parent Season:', 'roster-manager' ),
				'edit_item'                  => __( 'Edit Season', 'roster-manager' ),
				'update_item'                => __( 'Update Season', 'roster-manager' ),
				'view_item'                  => __( 'View Season', 'roster-manager' ),
				'add_new_item'               => __( 'Add New Season', 'roster-manager' ),
				'new_item_name'              => __( 'New Season', 'roster-manager' ),
				'separate_items_with_commas' => __( 'Separate Seasons with commas', 'roster-manager' ),
				'add_or_remove_items'        => __( 'Add or remove Seasons', 'roster-manager' ),
				'choose_from_most_used'      => __( 'Choose from the most used Seasons', 'roster-manager' ),
				'not_found'                  => __( 'No Season found.', 'roster-manager' ),
				'no_terms'                   => __( 'No Seasons', 'roster-manager' ),
				'menu_name'                  => __( 'Seasons', 'roster-manager' ),
				'items_list_navigation'      => __( 'Seasons list navigation', 'roster-manager' ),
				'items_list'                 => __( 'Seasons list', 'roster-manager' ),
				'most_used'                  => _x( 'Most Used', 'seasons', 'roster-manager' ),
				'back_to_items'              => __( '&larr; Back to Seasons', 'roster-manager' ),
			],
			'show_in_rest'          => true,
			'rest_base'             => 'seasons',
			'rest_controller_class' => 'WP_REST_Terms_Controller',
		] );
	}

	public function updated_messages( $messages ): array {
		$messages['seasons'] = [
			0 => '', // Unused. Messages start at index 1.
			1 => __( 'Seasons added.', 'roster-manager' ),
			2 => __( 'Seasons deleted.', 'roster-manager' ),
			3 => __( 'Seasons updated.', 'roster-manager' ),
			4 => __( 'Seasons not added.', 'roster-manager' ),
			5 => __( 'Seasons not updated.', 'roster-manager' ),
			6 => __( 'Seasons deleted.', 'roster-manager' ),
		];

		return $messages;
	}
}
