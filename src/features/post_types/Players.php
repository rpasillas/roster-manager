<?php
/**
 * Feature for creating the Players post type.
 *
 * @package wp-roster-manager
 */

namespace Alley\WP\Roster_Manager\Features\post_types;

use Alley\WP\Types\Feature;

final class Players implements Feature {
	/**
	 * Boot the feature.
	 *
	 * @return void
	 */
	public function boot(): void {
		add_action( 'init', [$this, 'register_post_type'] );
		add_filter( 'post_updated_messages', [$this, 'updated_messages'] );
		add_filter( 'bulk_post_updated_messages', [$this, 'players_bulk_updated_messages'], 10, 2 );
	}

	public function register_post_type(): void {
		register_post_type(
			'players',
			[
				'labels'                => [
					'name'                  => __( 'Players', 'roster-manager' ),
					'singular_name'         => __( 'Players', 'roster-manager' ),
					'all_items'             => __( 'All Players', 'roster-manager' ),
					'archives'              => __( 'Players Archives', 'roster-manager' ),
					'attributes'            => __( 'Players Attributes', 'roster-manager' ),
					'insert_into_item'      => __( 'Insert into Players', 'roster-manager' ),
					'uploaded_to_this_item' => __( 'Uploaded to this Players', 'roster-manager' ),
					'featured_image'        => _x( 'Featured Image', 'players', 'roster-manager' ),
					'set_featured_image'    => _x( 'Set featured image', 'players', 'roster-manager' ),
					'remove_featured_image' => _x( 'Remove featured image', 'players', 'roster-manager' ),
					'use_featured_image'    => _x( 'Use as featured image', 'players', 'roster-manager' ),
					'filter_items_list'     => __( 'Filter Players list', 'roster-manager' ),
					'items_list_navigation' => __( 'Players list navigation', 'roster-manager' ),
					'items_list'            => __( 'Players list', 'roster-manager' ),
					'new_item'              => __( 'New Players', 'roster-manager' ),
					'add_new'               => __( 'Add New', 'roster-manager' ),
					'add_new_item'          => __( 'Add New Players', 'roster-manager' ),
					'edit_item'             => __( 'Edit Players', 'roster-manager' ),
					'view_item'             => __( 'View Players', 'roster-manager' ),
					'view_items'            => __( 'View Players', 'roster-manager' ),
					'search_items'          => __( 'Search Players', 'roster-manager' ),
					'not_found'             => __( 'No Players found', 'roster-manager' ),
					'not_found_in_trash'    => __( 'No Players found in trash', 'roster-manager' ),
					'parent_item_colon'     => __( 'Parent Players:', 'roster-manager' ),
					'menu_name'             => __( 'Players', 'roster-manager' ),
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
				'menu_icon'             => 'dashicons-admin-users',
				'show_in_rest'          => true,
				'rest_base'             => 'players',
				'rest_controller_class' => 'WP_REST_Posts_Controller',
			]
		);
	}

	public function updated_messages( $messages ): array {
		global $post;

		$permalink = get_permalink( $post );

		$messages['players'] = [
			0  => '', // Unused. Messages start at index 1.
			/* translators: %s: post permalink */
			1  => sprintf( __( 'Players updated. <a target="_blank" href="%s">View Players</a>', 'roster-manager' ), esc_url( $permalink ) ),
			2  => __( 'Custom field updated.', 'roster-manager' ),
			3  => __( 'Custom field deleted.', 'roster-manager' ),
			4  => __( 'Players updated.', 'roster-manager' ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Players restored to revision from %s', 'roster-manager' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			/* translators: %s: post permalink */
			6  => sprintf( __( 'Players published. <a href="%s">View Players</a>', 'roster-manager' ), esc_url( $permalink ) ),
			7  => __( 'Players saved.', 'roster-manager' ),
			/* translators: %s: post permalink */
			8  => sprintf( __( 'Players submitted. <a target="_blank" href="%s">Preview Players</a>', 'roster-manager' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
			/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
			9  => sprintf( __( 'Players scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Players</a>', 'roster-manager' ), date_i18n( __( 'M j, Y @ G:i', 'roster-manager' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
			/* translators: %s: post permalink */
			10 => sprintf( __( 'Players draft updated. <a target="_blank" href="%s">Preview Players</a>', 'roster-manager' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		];

		return $messages;
	}

	public function players_bulk_updated_messages( $bulk_messages, $bulk_counts ): array {
		global $post;

		$bulk_messages['players'] = [
			/* translators: %s: Number of Players. */
			'updated'   => _n( '%s Players updated.', '%s Players updated.', $bulk_counts['updated'], 'roster-manager/src/features/' ),
			'locked'    => ( 1 === $bulk_counts['locked'] ) ? __( '1 Players not updated, somebody is editing it.', 'roster-manager/src/features/' ) :
				/* translators: %s: Number of Players. */
				_n( '%s Players not updated, somebody is editing it.', '%s Players not updated, somebody is editing them.', $bulk_counts['locked'], 'roster-manager/src/features/' ),
			/* translators: %s: Number of Players. */
			'deleted'   => _n( '%s Players permanently deleted.', '%s Players permanently deleted.', $bulk_counts['deleted'], 'roster-manager/src/features/' ),
			/* translators: %s: Number of Players. */
			'trashed'   => _n( '%s Players moved to the Trash.', '%s Players moved to the Trash.', $bulk_counts['trashed'], 'roster-manager/src/features/' ),
			/* translators: %s: Number of Players. */
			'untrashed' => _n( '%s Players restored from the Trash.', '%s Players restored from the Trash.', $bulk_counts['untrashed'], 'roster-manager/src/features/' ),
		];

		return $bulk_messages;
	}
}
