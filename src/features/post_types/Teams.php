<?php
/**
 * Feature for creating the Teams taxonomy.
 *
 * @package wp-roster-manager
 */

namespace Alley\WP\Roster_Manager\Features\post_types;

use Alley\WP\Types\Feature;

final class Teams implements Feature {
	/**
	 * Boot the feature.
	 *
	 * @return void
	 */
	public function boot(): void {
		add_action( 'init', [$this, 'register'] );
		add_filter( 'post_updated_messages', [$this, 'updated_messages'] );
		add_filter( 'bulk_post_updated_messages', [$this, 'players_bulk_updated_messages'], 10, 2 );
	}

	public function register(): void {
		register_post_type(
			'teams',
			[
				'labels'                => [
					'name'                  => __( 'Teams', 'roster-manager/src/features' ),
					'singular_name'         => __( 'Teams', 'roster-manager/src/features' ),
					'all_items'             => __( 'All Teams', 'roster-manager/src/features' ),
					'archives'              => __( 'Teams Archives', 'roster-manager/src/features' ),
					'attributes'            => __( 'Teams Attributes', 'roster-manager/src/features' ),
					'insert_into_item'      => __( 'Insert into Teams', 'roster-manager/src/features' ),
					'uploaded_to_this_item' => __( 'Uploaded to this Teams', 'roster-manager/src/features' ),
					'featured_image'        => _x( 'Featured Image', 'teams', 'roster-manager/src/features' ),
					'set_featured_image'    => _x( 'Set featured image', 'teams', 'roster-manager/src/features' ),
					'remove_featured_image' => _x( 'Remove featured image', 'teams', 'roster-manager/src/features' ),
					'use_featured_image'    => _x( 'Use as featured image', 'teams', 'roster-manager/src/features' ),
					'filter_items_list'     => __( 'Filter Teams list', 'roster-manager/src/features' ),
					'items_list_navigation' => __( 'Teams list navigation', 'roster-manager/src/features' ),
					'items_list'            => __( 'Teams list', 'roster-manager/src/features' ),
					'new_item'              => __( 'New Teams', 'roster-manager/src/features' ),
					'add_new'               => __( 'Add New', 'roster-manager/src/features' ),
					'add_new_item'          => __( 'Add New Teams', 'roster-manager/src/features' ),
					'edit_item'             => __( 'Edit Teams', 'roster-manager/src/features' ),
					'view_item'             => __( 'View Teams', 'roster-manager/src/features' ),
					'view_items'            => __( 'View Teams', 'roster-manager/src/features' ),
					'search_items'          => __( 'Search Teams', 'roster-manager/src/features' ),
					'not_found'             => __( 'No Teams found', 'roster-manager/src/features' ),
					'not_found_in_trash'    => __( 'No Teams found in trash', 'roster-manager/src/features' ),
					'parent_item_colon'     => __( 'Parent Teams:', 'roster-manager/src/features' ),
					'menu_name'             => __( 'Teams', 'roster-manager/src/features' ),
				],
				'public'                => true,
				'hierarchical'          => false,
				'show_ui'               => true,
				'show_in_nav_menus'     => true,
				'supports'              => [ 'title', 'editor' ],
				'has_archive'           => true,
				'rewrite'               => true,
				'query_var'             => true,
				'menu_position'         => null,
				'menu_icon'             => 'dashicons-groups',
				'show_in_rest'          => true,
				'rest_base'             => 'teams',
				'rest_controller_class' => 'WP_REST_Posts_Controller',
			]
		);
	}

	public function updated_messages( $messages ): array {
		global $post;

		$permalink = get_permalink( $post );

		$messages['teams'] = [
			0  => '', // Unused. Messages start at index 1.
			/* translators: %s: post permalink */
			1  => sprintf( __( 'Teams updated. <a target="_blank" href="%s">View Teams</a>', 'roster-manager/src/features' ), esc_url( $permalink ) ),
			2  => __( 'Custom field updated.', 'roster-manager/src/features' ),
			3  => __( 'Custom field deleted.', 'roster-manager/src/features' ),
			4  => __( 'Teams updated.', 'roster-manager/src/features' ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Teams restored to revision from %s', 'roster-manager/src/features' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			/* translators: %s: post permalink */
			6  => sprintf( __( 'Teams published. <a href="%s">View Teams</a>', 'roster-manager/src/features' ), esc_url( $permalink ) ),
			7  => __( 'Teams saved.', 'roster-manager/src/features' ),
			/* translators: %s: post permalink */
			8  => sprintf( __( 'Teams submitted. <a target="_blank" href="%s">Preview Teams</a>', 'roster-manager/src/features' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
			/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
			9  => sprintf( __( 'Teams scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Teams</a>', 'roster-manager/src/features' ), date_i18n( __( 'M j, Y @ G:i', 'roster-manager/src/features' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
			/* translators: %s: post permalink */
			10 => sprintf( __( 'Teams draft updated. <a target="_blank" href="%s">Preview Teams</a>', 'roster-manager/src/features' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		];

		return $messages;
	}

	public function players_bulk_updated_messages( $bulk_messages, $bulk_counts ): array {
		global $post;

		$bulk_messages['teams'] = [
			/* translators: %s: Number of Teams. */
			'updated'   => _n( '%s Teams updated.', '%s Teams updated.', $bulk_counts['updated'], 'roster-manager/src/features' ),
			'locked'    => ( 1 === $bulk_counts['locked'] ) ? __( '1 Teams not updated, somebody is editing it.', 'roster-manager/src/features' ) :
				/* translators: %s: Number of Teams. */
				_n( '%s Teams not updated, somebody is editing it.', '%s Teams not updated, somebody is editing them.', $bulk_counts['locked'], 'roster-manager/src/features' ),
			/* translators: %s: Number of Teams. */
			'deleted'   => _n( '%s Teams permanently deleted.', '%s Teams permanently deleted.', $bulk_counts['deleted'], 'roster-manager/src/features' ),
			/* translators: %s: Number of Teams. */
			'trashed'   => _n( '%s Teams moved to the Trash.', '%s Teams moved to the Trash.', $bulk_counts['trashed'], 'roster-manager/src/features' ),
			/* translators: %s: Number of Teams. */
			'untrashed' => _n( '%s Teams restored from the Trash.', '%s Teams restored from the Trash.', $bulk_counts['untrashed'], 'roster-manager/src/features' ),
		];

		return $bulk_messages;
	}
}
