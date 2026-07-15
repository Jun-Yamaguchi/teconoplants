<?php
/**
 * WordPress widget for business day calendar.
 *
 * @package BusinessDayCalendar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Calendar widget.
 */
class BDC_Widget extends WP_Widget {

	/**
	 * Register widget.
	 */
	public static function register() {
		register_widget( __CLASS__ );
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			'bdc_calendar_widget',
			__( '営業日カレンダー', 'business-day-calendar' ),
			array(
				'description' => __( '営業日・休業日を表示するカレンダー', 'business-day-calendar' ),
				'classname'   => 'bdc-widget',
			)
		);
	}

	/**
	 * Front-end output.
	 *
	 * @param array<string, string> $args     Widget arguments.
	 * @param array<string, mixed>  $instance Widget instance.
	 */
	public function widget( $args, $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( '営業日カレンダー', 'business-day-calendar' );

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( $title ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo BDC_Calendar::render(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Back-end form.
	 *
	 * @param array<string, mixed> $instance Widget instance.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'タイトル', 'business-day-calendar' ); ?></label>
			<input
				class="widefat"
				id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
				type="text"
				value="<?php echo esc_attr( $title ); ?>"
			>
		</p>
		<?php
	}

	/**
	 * Sanitize widget options.
	 *
	 * @param array<string, mixed> $new_instance New instance.
	 * @param array<string, mixed> $old_instance Old instance.
	 * @return array<string, mixed>
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] ?? '' );
		return $instance;
	}
}
