<?php
/**
 * Business day logic and calendar rendering.
 *
 * @package BusinessDayCalendar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Calendar renderer and business-day checker.
 */
class BDC_Calendar {

	/**
	 * Determine if a date is a business (open) day.
	 *
	 * Priority: special closed > special open > weekly closed > default open.
	 *
	 * @param string|DateTimeInterface $date Date in Y-m-d or DateTime.
	 * @return bool
	 */
	public static function is_business_day( $date ) {
		$ymd      = self::to_ymd( $date );
		$settings = BDC_Settings::get();

		if ( in_array( $ymd, $settings['special_closed_dates'], true ) ) {
			return false;
		}

		if ( in_array( $ymd, $settings['special_open_dates'], true ) ) {
			return true;
		}

		$weekday = (int) wp_date( 'w', self::to_timestamp( $ymd ) );

		if ( in_array( $weekday, $settings['weekly_closed_days'], true ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Render calendar HTML.
	 *
	 * @param array<string, mixed> $args Display arguments.
	 * @return string
	 */
	public static function render( array $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'year'  => (int) wp_date( 'Y' ),
				'month' => (int) wp_date( 'n' ),
				'show_nav' => true,
			)
		);

		$year  = max( 1970, min( 2100, (int) $args['year'] ) );
		$month = max( 1, min( 12, (int) $args['month'] ) );

		bdc_enqueue_widget_assets();

		$first_day     = mktime( 0, 0, 0, $month, 1, $year );
		$days_in_month = (int) wp_date( 't', $first_day );
		$start_weekday = (int) wp_date( 'w', $first_day );
		$today_ymd     = wp_date( 'Y-m-d' );

		$prev_month = $month - 1;
		$prev_year  = $year;
		if ( $prev_month < 1 ) {
			$prev_month = 12;
			--$prev_year;
		}

		$next_month = $month + 1;
		$next_year  = $year;
		if ( $next_month > 12 ) {
			$next_month = 1;
			++$next_year;
		}

		$weekday_labels = BDC_Settings::weekday_labels();
		$month_label    = wp_date( 'Y年n月', $first_day );

		ob_start();
		?>
		<div class="bdc-calendar" data-year="<?php echo esc_attr( (string) $year ); ?>" data-month="<?php echo esc_attr( (string) $month ); ?>">
			<?php if ( $args['show_nav'] ) : ?>
				<div class="bdc-calendar__header">
					<button type="button" class="bdc-calendar__nav bdc-calendar__nav--prev" data-year="<?php echo esc_attr( (string) $prev_year ); ?>" data-month="<?php echo esc_attr( (string) $prev_month ); ?>" aria-label="<?php esc_attr_e( '前の月', 'business-day-calendar' ); ?>">‹</button>
					<p class="bdc-calendar__title"><?php echo esc_html( $month_label ); ?></p>
					<button type="button" class="bdc-calendar__nav bdc-calendar__nav--next" data-year="<?php echo esc_attr( (string) $next_year ); ?>" data-month="<?php echo esc_attr( (string) $next_month ); ?>" aria-label="<?php esc_attr_e( '次の月', 'business-day-calendar' ); ?>">›</button>
				</div>
			<?php else : ?>
				<p class="bdc-calendar__title bdc-calendar__title--static"><?php echo esc_html( $month_label ); ?></p>
			<?php endif; ?>

			<table class="bdc-calendar__table">
				<thead>
					<tr>
						<?php foreach ( $weekday_labels as $index => $label ) : ?>
							<?php
							$classes = array( 'bdc-calendar__weekday' );
							if ( 0 === $index ) {
								$classes[] = 'bdc-calendar__weekday--sun';
							}
							if ( 6 === $index ) {
								$classes[] = 'bdc-calendar__weekday--sat';
							}
							?>
							<th scope="col" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"><?php echo esc_html( mb_substr( $label, 0, 1 ) ); ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<tr>
						<?php
						for ( $i = 0; $i < $start_weekday; $i++ ) {
							echo '<td class="bdc-calendar__day bdc-calendar__day--empty" aria-hidden="true"></td>';
						}

						for ( $day = 1; $day <= $days_in_month; $day++ ) {
							if ( ( $start_weekday + $day - 1 ) % 7 === 0 && $day > 1 ) {
								echo '</tr><tr>';
							}

							$ymd           = sprintf( '%04d-%02d-%02d', $year, $month, $day );
							$is_open       = self::is_business_day( $ymd );
							$is_today      = ( $ymd === $today_ymd );
							$weekday_index = (int) wp_date( 'w', self::to_timestamp( $ymd ) );

							$classes = array( 'bdc-calendar__day' );
							$classes[] = $is_open ? 'bdc-calendar__day--open' : 'bdc-calendar__day--closed';
							if ( $is_today ) {
								$classes[] = 'bdc-calendar__day--today';
							}
							if ( 0 === $weekday_index ) {
								$classes[] = 'bdc-calendar__day--sun';
							}
							if ( 6 === $weekday_index ) {
								$classes[] = 'bdc-calendar__day--sat';
							}

							$status_label = $is_open
								? __( '営業日', 'business-day-calendar' )
								: __( '休業日', 'business-day-calendar' );

							printf(
								'<td class="%1$s"><span class="bdc-calendar__date" aria-label="%2$s %3$s">%4$d</span></td>',
								esc_attr( implode( ' ', $classes ) ),
								esc_attr( $ymd ),
								esc_attr( $status_label ),
								(int) $day
							);
						}

						$remaining = ( 7 - ( ( $start_weekday + $days_in_month ) % 7 ) ) % 7;
						for ( $i = 0; $i < $remaining; $i++ ) {
							echo '<td class="bdc-calendar__day bdc-calendar__day--empty" aria-hidden="true"></td>';
						}
						?>
					</tr>
				</tbody>
			</table>

			<ul class="bdc-calendar__legend">
				<li class="bdc-calendar__legend-item bdc-calendar__legend-item--open"><?php esc_html_e( '営業日', 'business-day-calendar' ); ?></li>
				<li class="bdc-calendar__legend-item bdc-calendar__legend-item--closed"><?php esc_html_e( '休業日', 'business-day-calendar' ); ?></li>
			</ul>
		</div>
		<?php
		return (string) ob_get_clean();
	}

	/**
	 * Shortcode handler.
	 *
	 * @param array<string, string> $atts Shortcode attributes.
	 * @return string
	 */
	public static function shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'year'  => (string) wp_date( 'Y' ),
				'month' => (string) wp_date( 'n' ),
			),
			$atts,
			'business_day_calendar'
		);

		return self::render(
			array(
				'year'  => (int) $atts['year'],
				'month' => (int) $atts['month'],
			)
		);
	}

	/**
	 * Convert input to Y-m-d.
	 *
	 * @param string|DateTimeInterface $date Input date.
	 * @return string
	 */
	private static function to_ymd( $date ) {
		if ( $date instanceof DateTimeInterface ) {
			return $date->format( 'Y-m-d' );
		}

		return BDC_Settings::normalize_date( (string) $date );
	}

	/**
	 * Convert Y-m-d to timestamp in site timezone.
	 *
	 * @param string $ymd Date string.
	 * @return int
	 */
	private static function to_timestamp( $ymd ) {
		$tz = wp_timezone();
		$dt = DateTimeImmutable::createFromFormat( 'Y-m-d', $ymd, $tz );

		if ( ! $dt ) {
			return time();
		}

		return $dt->getTimestamp();
	}
}
