<?php

if ( ! class_exists( 'Give_Dashboard_Widget_Extend_Feed' ) ) {

	/**
	 * GiveWP Events and News Feed.
	 *
	 * This appends additional blog feeds to the WordPress Events and News Feed Widget
	 * available in the WP-Admin Dashboard.
	 *
	 * @since 2.3.0
	 */
	class Give_Dashboard_Widget_Extend_Feed {

		/**
		 * The number of feed items to show.
		 *
		 * @since 2.3.0
		 *
		 * @var int
		 */
		const FEED_COUNT = 6;

		/**
		 * Construct.
		 *
		 * @since 2.3.0
		 */
		public function __construct() {

			// Actions.
			add_action( 'wp_feed_options', array( $this, 'dashboard_update_feed_urls' ), 10, 2 );

			// Filters.
			add_filter( 'dashboard_secondary_items', array( $this, 'dashboard_items_count' ) );
		}

		/**
		 * Set the number of feed items to show.
		 *
		 * @since 2.3.0
		 *
		 * @return int Count of feed items.
		 */
		public function dashboard_items_count() {

			/**
			 * Apply the filters give_dashboard_feed_count for letting an admin
			 * override this count.
			 */
			return (int) apply_filters( 'give_dashboard_feed_count', self::FEED_COUNT );
		}

		/**
		 * Update the planet feed to add other AM blog feeds.
		 *
		 * @since 2.3.0
		 *
		 * @param object $feed SimplePie feed object (passed by reference).
		 * @param string $url URL of feed to retrieve (original planet feed url). If an array of URLs, the feeds are merged.
		 */
		public function dashboard_update_feed_urls( $feed, $url ) {

			global $pagenow;

			// Return early if not on the right page.
			if ( 'admin-ajax.php' !== $pagenow ) {
				return;
			}

			/**
			 * Return early if not on the right feed.
			 * We want to modify the feed URLs only for the
			 * WordPress Events & News Dashboard Widget
			 */
			if ( strpos( $url, 'planet.wordpress.org' ) === false ) {
				return;
			}

			// Initialize the feeds array.
			$feed_urls = array(
				'https://givewp.com/feed/',
				$url,
			);

			// Update the feed sources.
			$feed->set_feed_url( $feed_urls );
		}

	}

	// Create an instance.
	new Give_Dashboard_Widget_Extend_Feed();
}
