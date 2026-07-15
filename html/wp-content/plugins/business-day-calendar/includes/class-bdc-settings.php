<?php
/**
 * Plugin settings storage and validation.
 *
 * @package BusinessDayCalendar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings helper.
 */
class BDC_Settings {

	const OPTION_KEY = 'bdc_settings';

	/**
	 * Default settings.
	 *
	 * @return array<string, mixed>
	 */
	public static function defaults() {
		return array(
			'weekly_closed_days'   => array( 0 ), // Sunday.
			'special_open_dates'   => array(),
			'special_closed_dates' => array(),
		);
	}

	/**
	 * Get merged settings.
	 *
	 * @return array<string, mixed>
	 */
	public static function get() {
		$settings = get_option( self::OPTION_KEY, array() );

		if ( ! is_array( $settings ) ) {
			$settings = array();
		}

		return wp_parse_args( $settings, self::defaults() );
	}

	/**
	 * Save settings after sanitization.
	 *
	 * @param array<string, mixed> $input Raw input.
	 * @return bool
	 */
	public static function save( array $input ) {
		$sanitized = self::sanitize( $input );
		return update_option( self::OPTION_KEY, $sanitized );
	}

	/**
	 * Sanitize settings input.
	 *
	 * @param array<string, mixed> $input Raw input.
	 * @return array<string, mixed>
	 */
	public static function sanitize( array $input ) {
		$defaults = self::defaults();

		$weekly = array();
		if ( ! empty( $input['weekly_closed_days'] ) && is_array( $input['weekly_closed_days'] ) ) {
			foreach ( $input['weekly_closed_days'] as $day ) {
				$day = (int) $day;
				if ( $day >= 0 && $day <= 6 ) {
					$weekly[] = $day;
				}
			}
		}
		$weekly = array_values( array_unique( $weekly ) );
		sort( $weekly );

		$special_open   = self::sanitize_date_list( $input['special_open_dates'] ?? array() );
		$special_closed = self::sanitize_date_list( $input['special_closed_dates'] ?? array() );

		return array(
			'weekly_closed_days'   => $weekly,
			'special_open_dates'   => $special_open,
			'special_closed_dates' => $special_closed,
		);
	}

	/**
	 * Sanitize a list of Y-m-d dates.
	 *
	 * @param mixed $dates Raw date list.
	 * @return string[]
	 */
	public static function sanitize_date_list( $dates ) {
		if ( ! is_array( $dates ) ) {
			return array();
		}

		$clean = array();
		foreach ( $dates as $date ) {
			$normalized = self::normalize_date( (string) $date );
			if ( $normalized ) {
				$clean[] = $normalized;
			}
		}

		$clean = array_values( array_unique( $clean ) );
		sort( $clean );

		return $clean;
	}

	/**
	 * Normalize a date string to Y-m-d.
	 *
	 * @param string $date Raw date.
	 * @return string Empty string if invalid.
	 */
	public static function normalize_date( $date ) {
		$date = trim( $date );
		if ( '' === $date ) {
			return '';
		}

		$dt = DateTime::createFromFormat( 'Y-m-d', $date );
		if ( ! $dt || $dt->format( 'Y-m-d' ) !== $date ) {
			return '';
		}

		return $date;
	}

	/**
	 * Weekday labels (0 = Sunday).
	 *
	 * @return array<int, string>
	 */
	public static function weekday_labels() {
		return array(
			0 => __( '日曜日', 'business-day-calendar' ),
			1 => __( '月曜日', 'business-day-calendar' ),
			2 => __( '火曜日', 'business-day-calendar' ),
			3 => __( '水曜日', 'business-day-calendar' ),
			4 => __( '木曜日', 'business-day-calendar' ),
			5 => __( '金曜日', 'business-day-calendar' ),
			6 => __( '土曜日', 'business-day-calendar' ),
		);
	}
}
