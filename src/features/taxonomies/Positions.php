<?php
/**
 * Feature for creating the Positions taxonomy.
 *
 * @package wp-roster-manager
 */

namespace Alley\WP\Roster_Manager\Features\taxonomies;

use Alley\WP\Types\Feature;

final class Positions implements Feature {
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
		register_taxonomy( 'positions', [ 'players' ], [
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
				'name'                       => __( 'Positions', 'roster-manager/src/features/' ),
				'singular_name'              => _x( 'Positions', 'taxonomy general name', 'roster-manager/src/features/' ),
				'search_items'               => __( 'Search Positions', 'roster-manager/src/features/' ),
				'popular_items'              => __( 'Popular Positions', 'roster-manager/src/features/' ),
				'all_items'                  => __( 'All Positions', 'roster-manager/src/features/' ),
				'parent_item'                => __( 'Parent Positions', 'roster-manager/src/features/' ),
				'parent_item_colon'          => __( 'Parent Positions:', 'roster-manager/src/features/' ),
				'edit_item'                  => __( 'Edit Positions', 'roster-manager/src/features/' ),
				'update_item'                => __( 'Update Positions', 'roster-manager/src/features/' ),
				'view_item'                  => __( 'View Positions', 'roster-manager/src/features/' ),
				'add_new_item'               => __( 'Add New Positions', 'roster-manager/src/features/' ),
				'new_item_name'              => __( 'New Positions', 'roster-manager/src/features/' ),
				'separate_items_with_commas' => __( 'Separate Positions with commas', 'roster-manager/src/features/' ),
				'add_or_remove_items'        => __( 'Add or remove Positions', 'roster-manager/src/features/' ),
				'choose_from_most_used'      => __( 'Choose from the most used Positions', 'roster-manager/src/features/' ),
				'not_found'                  => __( 'No Positions found.', 'roster-manager/src/features/' ),
				'no_terms'                   => __( 'No Positions', 'roster-manager/src/features/' ),
				'menu_name'                  => __( 'Positions', 'roster-manager/src/features/' ),
				'items_list_navigation'      => __( 'Positions list navigation', 'roster-manager/src/features/' ),
				'items_list'                 => __( 'Positions list', 'roster-manager/src/features/' ),
				'most_used'                  => _x( 'Most Used', 'positions', 'roster-manager/src/features/' ),
				'back_to_items'              => __( '&larr; Back to Positions', 'roster-manager/src/features/' ),
			],
			'show_in_rest'          => true,
			'rest_base'             => 'positions',
			'rest_controller_class' => 'WP_REST_Terms_Controller',
		] );
	}

	public function updated_messages( $messages ): array {
		$messages['positions'] = [
			0 => '', // Unused. Messages start at index 1.
			1 => __( 'Positions added.', 'roster-manager/src/features/' ),
			2 => __( 'Positions deleted.', 'roster-manager/src/features/' ),
			3 => __( 'Positions updated.', 'roster-manager/src/features/' ),
			4 => __( 'Positions not added.', 'roster-manager/src/features/' ),
			5 => __( 'Positions not updated.', 'roster-manager/src/features/' ),
			6 => __( 'Positions deleted.', 'roster-manager/src/features/' ),
		];

		return $messages;
	}
}
