<?php
/**
 * Tecono Plants theme functions.
 *
 * @package TeconoPlants
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_template_directory() . '/inc/shop-info.php';

/**
 * Enqueue theme assets.
 */
function teconoplants_enqueue_assets() {
	$theme_version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style(
		'teconoplants-main',
		get_template_directory_uri() . '/assets/css/main.css',
		array(),
		$theme_version
	);

	wp_enqueue_script(
		'teconoplants-hero-slider',
		get_template_directory_uri() . '/assets/js/hero-slider.js',
		array(),
		$theme_version,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'teconoplants_enqueue_assets' );

/**
 * Editor styles.
 */
function teconoplants_editor_assets() {
	$theme_version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style(
		'teconoplants-editor',
		get_template_directory_uri() . '/assets/css/main.css',
		array(),
		$theme_version
	);
}
add_action( 'enqueue_block_editor_assets', 'teconoplants_editor_assets' );

/**
 * Theme supports.
 */
function teconoplants_setup() {
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 150,
			'width'       => 150,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
}
add_action( 'after_setup_theme', 'teconoplants_setup' );

/**
 * Import a bundled theme image into the media library (once).
 *
 * @param string $slug Asset key (filename without extension).
 * @return int Attachment ID, or 0 on failure.
 */
function teconoplants_get_theme_image_attachment( $slug ) {
	$files = array(
		'logo' => 'logo.png',
	);

	if ( ! isset( $files[ $slug ] ) ) {
		return 0;
	}

	$option_key    = 'teconoplants_attachment_' . $slug;
	$attachment_id = (int) get_option( $option_key, 0 );

	if ( $attachment_id && wp_attachment_is_image( $attachment_id ) ) {
		return $attachment_id;
	}

	$filename = $files[ $slug ];
	$filepath = get_template_directory() . '/assets/images/' . $filename;

	if ( ! is_readable( $filepath ) ) {
		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$upload = wp_upload_bits( $filename, null, file_get_contents( $filepath ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}

	$attachment_id = wp_insert_attachment(
		array(
			'post_mime_type' => $upload['type'],
			'post_title'     => 'Tecono Plants ' . $slug,
			'post_content'   => '',
			'post_status'    => 'inherit',
		),
		$upload['file']
	);

	if ( is_wp_error( $attachment_id ) ) {
		return 0;
	}

	wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata( $attachment_id, $upload['file'] ) );
	update_option( $option_key, $attachment_id );

	return $attachment_id;
}

/**
 * Use the bundled logo when no custom logo is set in the admin.
 */
function teconoplants_maybe_set_default_logo() {
	if ( get_theme_mod( 'custom_logo' ) ) {
		return;
	}

	$attachment_id = teconoplants_get_theme_image_attachment( 'logo' );
	if ( $attachment_id ) {
		set_theme_mod( 'custom_logo', $attachment_id );
	}
}
add_action( 'after_setup_theme', 'teconoplants_maybe_set_default_logo', 20 );

/**
 * Output favicon links from theme assets when no site icon is configured.
 */
function teconoplants_site_icons() {
	if ( has_site_icon() ) {
		return;
	}

	$base = get_template_directory_uri() . '/assets/images/';

	printf(
		'<link rel="icon" href="%1$s" sizes="32x32" type="image/png">' . "\n",
		esc_url( $base . 'favicon-32x32.png' )
	);
	printf(
		'<link rel="icon" href="%1$s" sizes="192x192" type="image/png">' . "\n",
		esc_url( $base . 'favicon-192x192.png' )
	);
	printf(
		'<link rel="apple-touch-icon" href="%1$s">' . "\n",
		esc_url( $base . 'apple-touch-icon.png' )
	);
}
add_action( 'wp_head', 'teconoplants_site_icons', 5 );

/**
 * Register block pattern category.
 */
function teconoplants_pattern_category() {
	register_block_pattern_category(
		'teconoplants',
		array(
			'label' => __( 'Tecono Plants', 'teconoplants' ),
		)
	);
}
add_action( 'init', 'teconoplants_pattern_category' );
