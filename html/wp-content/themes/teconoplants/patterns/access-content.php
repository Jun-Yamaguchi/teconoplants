<?php
/**
 * Title: アクセス情報
 * Slug: teconoplants/access-content
 * Categories: teconoplants, featured
 * Description: 住所・電話・地図
 *
 * @package TeconoPlants
 */

$info = teconoplants_shop_info();
?>
<!-- wp:group {"align":"full","className":"access-page","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull access-page">
	<!-- wp:group {"className":"access-page__inner","layout":{"type":"default"}} -->
	<div class="wp-block-group access-page__inner">
		<!-- wp:post-title {"level":1,"className":"access-page__title"} /-->



		<!-- wp:group {"className":"access-page__card","layout":{"type":"default"}} -->
		<div class="wp-block-group access-page__card">
			<!-- wp:html -->
			<dl class="access-page__list">
				<div class="access-page__item">
					<dt><?php esc_html_e( '住所', 'teconoplants' ); ?></dt>
					<dd>
						<address><?php echo esc_html( $info['address'] ); ?></address>
		<!-- wp:html -->
		<div class="access-page__map">
			<iframe
				src="<?php echo esc_url( $info['maps_embed_url'] ); ?>"
				title="<?php esc_attr_e( 'tecono plants の地図', 'teconoplants' ); ?>"
				loading="eager"
				referrerpolicy="no-referrer-when-downgrade"
				allowfullscreen
			></iframe>
		</div>
		<!-- /wp:html -->						
					</dd>
				</div>
				<div class="access-page__item">
					<dt><?php esc_html_e( '電話', 'teconoplants' ); ?></dt>
					<dd>
						<a href="<?php echo esc_url( $info['phone_tel'] ); ?>"><?php echo esc_html( $info['phone'] ); ?></a>
					</dd>
				</div>
				<div class="access-page__item">
					<dt><?php esc_html_e( '利用可能支払い方法', 'teconoplants' ); ?></dt>
					<dd><?php esc_html_e( 'クレジットカード、PayPay、現金', 'teconoplants' ); ?></dd>
				</div>
			</dl>
			<!-- /wp:html -->

		</div>
		<!-- /wp:group -->

		<!-- wp:paragraph {"className":"access-page__back"} -->
		<p class="access-page__back"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">← <?php esc_html_e( 'トップへ戻る', 'teconoplants' ); ?></a></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
