<?php
/**
 * Title: Instagram プロフィール
 * Slug: teconoplants/profile-header
 * Categories: teconoplants, featured
 * Description: @teconoplants のプロフィールヘッダー
 *
 * @package TeconoPlants
 */

$profile_image = get_template_directory_uri() . '/assets/images/profile.jpg';
$instagram_url = 'https://www.instagram.com/teconoplants/';
?>
<!-- wp:group {"align":"full","className":"ig-profile","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull ig-profile">
	<!-- wp:group {"className":"ig-profile__inner","layout":{"type":"default"}} -->
	<div class="wp-block-group ig-profile__inner">
		<!-- wp:group {"className":"ig-profile__avatar","layout":{"type":"constrained"}} -->
		<div class="wp-block-group ig-profile__avatar">
			<!-- wp:image {"width":"150px","height":"150px","sizeSlug":"full","linkDestination":"none","className":"is-style-rounded"} -->
			<figure class="wp-block-image size-full is-style-rounded"><img src="<?php echo esc_url( $profile_image ); ?>" alt="<?php esc_attr_e( 'tecono plants プロフィール写真', 'teconoplants' ); ?>" style="width:150px;height:150px"/></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"ig-profile__meta","layout":{"type":"default"}} -->
		<div class="wp-block-group ig-profile__meta">
			<!-- wp:group {"className":"ig-profile__handle-row","layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
			<div class="wp-block-group ig-profile__handle-row">
				<!-- wp:heading {"level":2,"className":"ig-profile__handle"} -->
				<h2 class="wp-block-heading ig-profile__handle">teconoplants</h2>
				<!-- /wp:heading -->

				<!-- wp:buttons {"className":"ig-profile__cta"} -->
				<div class="wp-block-buttons ig-profile__cta">
					<!-- wp:button {"className":"is-style-outline"} -->
					<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $instagram_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Instagramでフォロー', 'teconoplants' ); ?></a></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->
			</div>
			<!-- /wp:group -->

			<!-- wp:html -->
			<ul class="ig-profile__stats" aria-label="<?php esc_attr_e( 'アカウント統計', 'teconoplants' ); ?>">
				<li><strong>139</strong><?php esc_html_e( '投稿', 'teconoplants' ); ?></li>
				<li><strong>2,232</strong><?php esc_html_e( 'フォロワー', 'teconoplants' ); ?></li>
				<li><strong>3,011</strong><?php esc_html_e( 'フォロー中', 'teconoplants' ); ?></li>
			</ul>
			<!-- /wp:html -->

			<!-- wp:paragraph {"className":"ig-profile__name"} -->
			<p class="ig-profile__name">tecono plants 九州鹿角会</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"className":"ig-profile__bio"} -->
			<p class="ig-profile__bio"><?php echo wp_kses_post( "植物と暮らす。\n九州から、日々の緑をお届けしています。\n" ); ?><a href="<?php echo esc_url( $instagram_url ); ?>">instagram.com/teconoplants</a></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

	<!-- wp:html -->
	<div class="ig-profile__highlights" aria-label="<?php esc_attr_e( 'ハイライト', 'teconoplants' ); ?>">
		<a class="ig-highlight" href="<?php echo esc_url( $instagram_url ); ?>" target="_blank" rel="noopener noreferrer">
			<span class="ig-highlight__ring" aria-hidden="true">🌿</span>
			<span><?php esc_html_e( '植物', 'teconoplants' ); ?></span>
		</a>
		<a class="ig-highlight" href="<?php echo esc_url( $instagram_url ); ?>" target="_blank" rel="noopener noreferrer">
			<span class="ig-highlight__ring" aria-hidden="true">🪴</span>
			<span><?php esc_html_e( '鉢', 'teconoplants' ); ?></span>
		</a>
		<a class="ig-highlight" href="<?php echo esc_url( $instagram_url ); ?>" target="_blank" rel="noopener noreferrer">
			<span class="ig-highlight__ring" aria-hidden="true">✨</span>
			<span><?php esc_html_e( '日常', 'teconoplants' ); ?></span>
		</a>
	</div>
	<!-- /wp:html -->
</div>
<!-- /wp:group -->
