<?php
/**
 * Admin settings page.
 *
 * @package BusinessDayCalendar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin UI and form handling.
 */
class BDC_Admin {

	/**
	 * Hook admin actions.
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'register_menu' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
		add_action( 'admin_post_bdc_save_settings', array( __CLASS__, 'handle_save' ) );
	}

	/**
	 * Register settings page under Settings menu.
	 */
	public static function register_menu() {
		add_options_page(
			__( '営業日カレンダー', 'business-day-calendar' ),
			__( '営業日カレンダー', 'business-day-calendar' ),
			'manage_options',
			'business-day-calendar',
			array( __CLASS__, 'render_page' )
		);
	}

	/**
	 * Enqueue admin assets on plugin page only.
	 *
	 * @param string $hook_suffix Current admin page hook.
	 */
	public static function enqueue_assets( $hook_suffix ) {
		if ( 'settings_page_business-day-calendar' !== $hook_suffix ) {
			return;
		}

		$css_path = BDC_PLUGIN_DIR . 'assets/css/admin.css';
		$js_path  = BDC_PLUGIN_DIR . 'assets/js/admin.js';
		$css_ver  = file_exists( $css_path ) ? (string) filemtime( $css_path ) : BDC_VERSION;
		$js_ver   = file_exists( $js_path ) ? (string) filemtime( $js_path ) : BDC_VERSION;

		wp_enqueue_style( 'bdc-admin', BDC_PLUGIN_URL . 'assets/css/admin.css', array(), $css_ver );
		wp_enqueue_script( 'bdc-admin', BDC_PLUGIN_URL . 'assets/js/admin.js', array(), $js_ver, true );
	}

	/**
	 * Handle settings form submission.
	 */
	public static function handle_save() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( '権限がありません。', 'business-day-calendar' ) );
		}

		check_admin_referer( 'bdc_save_settings' );

		$input = array(
			'weekly_closed_days'   => isset( $_POST['weekly_closed_days'] ) ? (array) wp_unslash( $_POST['weekly_closed_days'] ) : array(), // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			'special_open_dates'   => isset( $_POST['special_open_dates'] ) ? (array) wp_unslash( $_POST['special_open_dates'] ) : array(), // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			'special_closed_dates' => isset( $_POST['special_closed_dates'] ) ? (array) wp_unslash( $_POST['special_closed_dates'] ) : array(), // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		);

		BDC_Settings::save( $input );

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'    => 'business-day-calendar',
					'updated' => 'true',
				),
				admin_url( 'options-general.php' )
			)
		);
		exit;
	}

	/**
	 * Render settings page.
	 */
	public static function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$settings       = BDC_Settings::get();
		$weekday_labels = BDC_Settings::weekday_labels();
		$updated        = isset( $_GET['updated'] ) && 'true' === $_GET['updated']; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		?>
		<div class="wrap bdc-admin">
			<h1><?php esc_html_e( '営業日カレンダー設定', 'business-day-calendar' ); ?></h1>

			<?php if ( $updated ) : ?>
				<div class="notice notice-success is-dismissible">
					<p><?php esc_html_e( '設定を保存しました。', 'business-day-calendar' ); ?></p>
				</div>
			<?php endif; ?>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php wp_nonce_field( 'bdc_save_settings' ); ?>
				<input type="hidden" name="action" value="bdc_save_settings">

				<section class="bdc-admin__section">
					<h2><?php esc_html_e( '定期休業日（曜日指定）', 'business-day-calendar' ); ?></h2>
					<p class="description"><?php esc_html_e( '毎週休業とする曜日にチェックを入れてください。', 'business-day-calendar' ); ?></p>
					<fieldset class="bdc-admin__weekdays">
						<?php foreach ( $weekday_labels as $index => $label ) : ?>
							<label class="bdc-admin__weekday">
								<input
									type="checkbox"
									name="weekly_closed_days[]"
									value="<?php echo esc_attr( (string) $index ); ?>"
									<?php checked( in_array( $index, $settings['weekly_closed_days'], true ) ); ?>
								>
								<?php echo esc_html( $label ); ?>
							</label>
						<?php endforeach; ?>
					</fieldset>
				</section>

				<section class="bdc-admin__section">
					<h2><?php esc_html_e( '臨時営業日', 'business-day-calendar' ); ?></h2>
					<p class="description"><?php esc_html_e( '定期休業日でも、指定した日付は営業日として扱います。', 'business-day-calendar' ); ?></p>
					<?php self::render_date_list( 'special_open_dates', $settings['special_open_dates'] ); ?>
				</section>

				<section class="bdc-admin__section">
					<h2><?php esc_html_e( '臨時休業日', 'business-day-calendar' ); ?></h2>
					<p class="description"><?php esc_html_e( '通常営業日でも、指定した日付は休業日として扱います（臨時営業日より優先されます）。', 'business-day-calendar' ); ?></p>
					<?php self::render_date_list( 'special_closed_dates', $settings['special_closed_dates'] ); ?>
				</section>

				<?php submit_button( __( '設定を保存', 'business-day-calendar' ) ); ?>
			</form>

			<section class="bdc-admin__section bdc-admin__preview">
				<h2><?php esc_html_e( 'プレビュー', 'business-day-calendar' ); ?></h2>
				<?php echo BDC_Calendar::render( array( 'show_nav' => false ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</section>
		</div>
		<?php
	}

	/**
	 * Render repeatable date list field.
	 *
	 * @param string   $name  Field name.
	 * @param string[] $dates Existing dates.
	 */
	private static function render_date_list( $name, array $dates ) {
		?>
		<div class="bdc-admin__date-list" data-field="<?php echo esc_attr( $name ); ?>">
			<div class="bdc-admin__date-rows">
				<?php if ( empty( $dates ) ) : ?>
					<?php self::render_date_row( $name, '' ); ?>
				<?php else : ?>
					<?php foreach ( $dates as $date ) : ?>
						<?php self::render_date_row( $name, $date ); ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
			<button type="button" class="button bdc-admin__add-date" data-field="<?php echo esc_attr( $name ); ?>">
				<?php esc_html_e( '日付を追加', 'business-day-calendar' ); ?>
			</button>
		</div>
		<?php
	}

	/**
	 * Render a single date input row.
	 *
	 * @param string $name Field name.
	 * @param string $date Date value.
	 */
	private static function render_date_row( $name, $date ) {
		?>
		<div class="bdc-admin__date-row">
			<input type="date" name="<?php echo esc_attr( $name ); ?>[]" value="<?php echo esc_attr( $date ); ?>">
			<button type="button" class="button-link-delete bdc-admin__remove-date" aria-label="<?php esc_attr_e( '削除', 'business-day-calendar' ); ?>">
				<?php esc_html_e( '削除', 'business-day-calendar' ); ?>
			</button>
		</div>
		<?php
	}
}
