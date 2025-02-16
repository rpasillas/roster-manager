<?php
/**
 * Roster Manager Tests: Bootstrap
 *
 * phpcs:disable Squiz.Commenting.InlineComment.InvalidEndChar
 *
 * @package wp-roster-manager
 */

/**
 * Visit {@see https://mantle.alley.com/testing/test-framework.html} to learn more.
 */
\Mantle\Testing\manager()
	// Rsync the plugin to plugins/wp-roster-manager when testing.
	->maybe_rsync_plugin()
	// Load the main file of the plugin.
	->loaded( fn () => require_once __DIR__ . '/../wp-roster-manager.php' )
	->install();
