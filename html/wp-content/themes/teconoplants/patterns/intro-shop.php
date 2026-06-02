<?php
/**
 * Title: 店舗紹介
 * Slug: teconoplants/intro-shop
 * Categories: teconoplants, featured
 * Description: 店舗の紹介文
 *
 * @package TeconoPlants
 */

$instagram_url = 'https://www.instagram.com/teconoplants/';
?>
<!-- wp:group {"align":"full","className":"shop-intro","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull shop-intro">
	<!-- wp:group {"className":"shop-intro__inner","layout":{"type":"default"}} -->
	<div class="wp-block-group shop-intro__inner">
		<!-- wp:heading {"level":2,"className":"shop-intro__heading"} -->
		<h2 class="wp-block-heading shop-intro__heading"><?php esc_html_e( 'ビカクシダと、暮らす。', 'teconoplants' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"className":"shop-intro__lead"} -->
		<p class="shop-intro__lead"><?php esc_html_e( 'tecono plants は、ビカクシダ（Platycerium）を中心に、板付けから育成まで一貫して手がける専門店です。店頭では厳選した株をご覧いただけます。', 'teconoplants' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:html -->
		<p class="shop-intro__instagram-link">
			<a href="<?php echo esc_url( $instagram_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Instagram @teconoplants', 'teconoplants' ); ?></a>
		</p>
		<!-- /wp:html -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
