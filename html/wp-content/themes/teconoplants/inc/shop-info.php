<?php
/**
 * Shop contact constants.
 *
 * @package TeconoPlants
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns shared shop contact details.
 *
 * @return array<string, string>
 */
function teconoplants_shop_info() {
	$address = '福岡県糟屋郡宇美町宇美4-2-22';

	return array(
		'address'        => $address,
		'phone'          => '080-4294-0088',
		'phone_tel'      => 'tel:08042940088',
		'maps_url'       => 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $address ),
		'maps_embed_url' => 'https://www.google.com/maps?q=' . rawurlencode( $address ) . '&hl=ja&z=17&output=embed',
		'access_path'    => '/access/',
		'access_url'     => home_url( '/access/' ),
	);
}
