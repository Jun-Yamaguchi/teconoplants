<?php
/**
 * REST API for calendar rendering.
 *
 * @package BusinessDayCalendar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * REST endpoints.
 */
class BDC_REST {

	/**
	 * Register hooks.
	 */
	public static function init() {
		add_action( 'rest_api_init', array( __CLASS__, 'register_routes' ) );
	}

	/**
	 * Register REST routes.
	 */
	public static function register_routes() {
		register_rest_route(
			'business-day-calendar/v1',
			'/calendar',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( __CLASS__, 'get_calendar' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'year'  => array(
						'required'          => true,
						'type'              => 'integer',
						'minimum'           => 1970,
						'maximum'           => 2100,
						'sanitize_callback' => 'absint',
					),
					'month' => array(
						'required'          => true,
						'type'              => 'integer',
						'minimum'           => 1,
						'maximum'           => 12,
						'sanitize_callback' => 'absint',
					),
				),
			)
		);
	}

	/**
	 * Return calendar HTML for AJAX navigation.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response
	 */
	public static function get_calendar( WP_REST_Request $request ) {
		$html = BDC_Calendar::render(
			array(
				'year'     => (int) $request->get_param( 'year' ),
				'month'    => (int) $request->get_param( 'month' ),
				'show_nav' => true,
			)
		);

		return rest_ensure_response(
			array(
				'html' => $html,
			)
		);
	}
}
