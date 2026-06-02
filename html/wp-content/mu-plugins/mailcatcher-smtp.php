<?php
/**
 * Plugin Name: MailCatcher SMTP (Docker)
 * Description: ローカル開発時、wp_mail を MailCatcher へ向ける。
 * Version: 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$smtp_host = '';
$smtp_port = 1025;

if ( defined( 'WORDPRESS_MAIL_SMTP_HOST' ) && is_string( WORDPRESS_MAIL_SMTP_HOST ) && WORDPRESS_MAIL_SMTP_HOST !== '' ) {
	$smtp_host = WORDPRESS_MAIL_SMTP_HOST;
	$smtp_port = defined( 'WORDPRESS_MAIL_SMTP_PORT' ) ? (int) WORDPRESS_MAIL_SMTP_PORT : 1025;
} else {
	$from_getenv = getenv( 'WORDPRESS_SMTP_HOST' );
	if ( is_string( $from_getenv ) && $from_getenv !== '' ) {
		$smtp_host = $from_getenv;
	} elseif ( isset( $_SERVER['WORDPRESS_SMTP_HOST'] ) && is_string( $_SERVER['WORDPRESS_SMTP_HOST'] ) && $_SERVER['WORDPRESS_SMTP_HOST'] !== '' ) {
		$smtp_host = $_SERVER['WORDPRESS_SMTP_HOST'];
	}

	$port_raw = getenv( 'WORDPRESS_SMTP_PORT' );
	if ( is_string( $port_raw ) && $port_raw !== '' ) {
		$smtp_port = (int) $port_raw;
	} elseif ( isset( $_SERVER['WORDPRESS_SMTP_PORT'] ) && is_string( $_SERVER['WORDPRESS_SMTP_PORT'] ) && $_SERVER['WORDPRESS_SMTP_PORT'] !== '' ) {
		$smtp_port = (int) $_SERVER['WORDPRESS_SMTP_PORT'];
	}
}

if ( $smtp_host === '' ) {
	return;
}

add_filter(
	'wp_mail_from',
	static function ( $from ) {
		if ( ! is_email( $from ) ) {
			return 'wordpress@example.com';
		}
		return $from;
	},
	1
);

add_action(
	'phpmailer_init',
	static function ( $phpmailer ) use ( $smtp_host, $smtp_port ) {
		$phpmailer->isSMTP();
		$phpmailer->Host       = $smtp_host;
		$phpmailer->Port       = $smtp_port;
		$phpmailer->SMTPAuth   = false;
		$phpmailer->SMTPSecure = '';
		$phpmailer->SMTPAutoTLS = false;
	},
	999,
	1
);
