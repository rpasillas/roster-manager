<?php
/**
 * Feature for creating the Games post type.
 *
 * @package wp-roster-manager
 */

namespace Alley\WP\Roster_Manager\Features\post_types;

use Alley\WP\Types\Feature;

final class Games implements Feature {
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
			'games',
			[
				'labels'                => [
					'name'                  => __( 'Games', 'roster-manager/src/features/' ),
					'singular_name'         => __( 'Games', 'roster-manager/src/features/' ),
					'all_items'             => __( 'All Games', 'roster-manager/src/features/' ),
					'archives'              => __( 'Games Archives', 'roster-manager/src/features/' ),
					'attributes'            => __( 'Games Attributes', 'roster-manager/src/features/' ),
					'insert_into_item'      => __( 'Insert into Games', 'roster-manager/src/features/' ),
					'uploaded_to_this_item' => __( 'Uploaded to this Games', 'roster-manager/src/features/' ),
					'featured_image'        => _x( 'Featured Image', 'games', 'roster-manager/src/features/' ),
					'set_featured_image'    => _x( 'Set featured image', 'games', 'roster-manager/src/features/' ),
					'remove_featured_image' => _x( 'Remove featured image', 'games', 'roster-manager/src/features/' ),
					'use_featured_image'    => _x( 'Use as featured image', 'games', 'roster-manager/src/features/' ),
					'filter_items_list'     => __( 'Filter Games list', 'roster-manager/src/features/' ),
					'items_list_navigation' => __( 'Games list navigation', 'roster-manager/src/features/' ),
					'items_list'            => __( 'Games list', 'roster-manager/src/features/' ),
					'new_item'              => __( 'New Games', 'roster-manager/src/features/' ),
					'add_new'               => __( 'Add New', 'roster-manager/src/features/' ),
					'add_new_item'          => __( 'Add New Games', 'roster-manager/src/features/' ),
					'edit_item'             => __( 'Edit Games', 'roster-manager/src/features/' ),
					'view_item'             => __( 'View Games', 'roster-manager/src/features/' ),
					'view_items'            => __( 'View Games', 'roster-manager/src/features/' ),
					'search_items'          => __( 'Search Games', 'roster-manager/src/features/' ),
					'not_found'             => __( 'No Games found', 'roster-manager/src/features/' ),
					'not_found_in_trash'    => __( 'No Games found in trash', 'roster-manager/src/features/' ),
					'parent_item_colon'     => __( 'Parent Games:', 'roster-manager/src/features/' ),
					'menu_name'             => __( 'Games', 'roster-manager/src/features/' ),
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
				'menu_icon'             => 'dashicons-tickets',
				'show_in_rest'          => true,
				'rest_base'             => 'games',
				'rest_controller_class' => 'WP_REST_Posts_Controller',
			]
		);
	}

	public function updated_messages( $messages ): array {
		global $post;

		$permalink = get_permalink( $post );

		$messages['games'] = [
			0  => '', // Unused. Messages start at index 1.
			/* translators: %s: post permalink */
			1  => sprintf( __( 'Games updated. <a target="_blank" href="%s">View Games</a>', 'roster-manager/src/features/' ), esc_url( $permalink ) ),
			2  => __( 'Custom field updated.', 'roster-manager/src/features/' ),
			3  => __( 'Custom field deleted.', 'roster-manager/src/features/' ),
			4  => __( 'Games updated.', 'roster-manager/src/features/' ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Games restored to revision from %s', 'roster-manager/src/features/' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			/* translators: %s: post permalink */
			6  => sprintf( __( 'Games published. <a href="%s">View Games</a>', 'roster-manager/src/features/' ), esc_url( $permalink ) ),
			7  => __( 'Games saved.', 'roster-manager/src/features/' ),
			/* translators: %s: post permalink */
			8  => sprintf( __( 'Games submitted. <a target="_blank" href="%s">Preview Games</a>', 'roster-manager/src/features/' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
			/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
			9  => sprintf( __( 'Games scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Games</a>', 'roster-manager/src/features/' ), date_i18n( __( 'M j, Y @ G:i', 'roster-manager/src/features/' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
			/* translators: %s: post permalink */
			10 => sprintf( __( 'Games draft updated. <a target="_blank" href="%s">Preview Games</a>', 'roster-manager/src/features/' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		];

		return $messages;
	}

	public function players_bulk_updated_messages( $bulk_messages, $bulk_counts ): array {
		global $post;

		$bulk_messages['games'] = [
			/* translators: %s: Number of Games. */
			'updated'   => _n( '%s Games updated.', '%s Games updated.', $bulk_counts['updated'], 'roster-manager/src/features/' ),
			'locked'    => ( 1 === $bulk_counts['locked'] ) ? __( '1 Games not updated, somebody is editing it.', 'roster-manager/src/features/' ) :
				/* translators: %s: Number of Games. */
				_n( '%s Games not updated, somebody is editing it.', '%s Games not updated, somebody is editing them.', $bulk_counts['locked'], 'roster-manager/src/features/' ),
			/* translators: %s: Number of Games. */
			'deleted'   => _n( '%s Games permanently deleted.', '%s Games permanently deleted.', $bulk_counts['deleted'], 'roster-manager/src/features/' ),
			/* translators: %s: Number of Games. */
			'trashed'   => _n( '%s Games moved to the Trash.', '%s Games moved to the Trash.', $bulk_counts['trashed'], 'roster-manager/src/features/' ),
			/* translators: %s: Number of Games. */
			'untrashed' => _n( '%s Games restored from the Trash.', '%s Games restored from the Trash.', $bulk_counts['untrashed'], 'roster-manager/src/features/' ),
		];

		return $bulk_messages;
	}
}

