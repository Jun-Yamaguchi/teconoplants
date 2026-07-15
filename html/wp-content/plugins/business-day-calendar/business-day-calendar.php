<?php
/**
 * Plugin Name: 営業日カレンダー
 * Description: 曜日・臨時休業・臨時営業を設定し、営業日カレンダーをウィジェットで表示します。
 * Version: 1.0.0
 * Author: Tecono
 * Text Domain: business-day-calendar
 *
 * @package BusinessDayCalendar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BDC_VERSION', '1.0.0' );
define( 'BDC_PLUGIN_FILE', __FILE__ );
define( 'BDC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'BDC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once BDC_PLUGIN_DIR . 'includes/class-bdc-settings.php';
require_once BDC_PLUGIN_DIR . 'includes/class-bdc-calendar.php';
require_once BDC_PLUGIN_DIR . 'includes/class-bdc-admin.php';
require_once BDC_PLUGIN_DIR . 'includes/class-bdc-widget.php';
require_once BDC_PLUGIN_DIR . 'includes/class-bdc-rest.php';

/**
 * Initialize plugin components.
 */
function bdc_init() {
	BDC_Admin::init();
	BDC_REST::init();
	add_action( 'widgets_init', array( 'BDC_Widget', 'register' ) );
	add_shortcode( 'business_day_calendar', array( 'BDC_Calendar', 'shortcode' ) );
}
add_action( 'plugins_loaded', 'bdc_init' );

/**
 * Enqueue frontend assets when calendar is rendered.
 */
function bdc_enqueue_widget_assets() {
	static $enqueued = false;

	if ( $enqueued ) {
		return;
	}

	$css_path = BDC_PLUGIN_DIR . 'assets/css/widget.css';
	$css_ver  = file_exists( $css_path ) ? (string) filemtime( $css_path ) : BDC_VERSION;

	wp_enqueue_style(
		'bdc-widget',
		BDC_PLUGIN_URL . 'assets/css/widget.css',
		array(),
		$css_ver
	);

	$js_path = BDC_PLUGIN_DIR . 'assets/js/widget.js';
	$js_ver  = file_exists( $js_path ) ? (string) filemtime( $js_path ) : BDC_VERSION;

	wp_enqueue_script(
		'bdc-widget',
		BDC_PLUGIN_URL . 'assets/js/widget.js',
		array(),
		$js_ver,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	wp_localize_script(
		'bdc-widget',
		'bdcWidget',
		array(
			'restUrl' => rest_url( 'business-day-calendar/v1/calendar' ),
		)
	);

	$enqueued = true;
}
