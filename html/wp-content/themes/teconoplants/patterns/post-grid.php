<?php
/**
 * Title: 投稿グリッド（Instagram風）
 * Slug: teconoplants/post-grid
 * Categories: teconoplants, query
 * Description: 3列の正方形グリッドで投稿を表示
 *
 * @package TeconoPlants
 */
?>
<!-- wp:html -->
<nav class="ig-tabs" aria-label="<?php esc_attr_e( 'コンテンツ切り替え', 'teconoplants' ); ?>">
	<span class="ig-tabs__item is-active" aria-current="page"><?php esc_html_e( '投稿', 'teconoplants' ); ?></span>
</nav>
<!-- /wp:html -->

<!-- wp:group {"align":"full","className":"ig-grid","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull ig-grid">
	<!-- wp:query {"queryId":1,"query":{"perPage":12,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true},"layout":{"type":"default"}} -->
	<div class="wp-block-query">
		<!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->
			<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"1","width":"100%","height":"100%","style":{"spacing":{"margin":{"top":"0","bottom":"0"}}}} /-->
		<!-- /wp:post-template -->

		<!-- wp:query-no-results -->
			<!-- wp:paragraph {"className":"ig-empty"} -->
			<p class="ig-empty"><?php esc_html_e( '投稿がまだありません。WordPressに投稿を追加するか、Instagramの写真をメディアとして取り込んでください。', 'teconoplants' ); ?></p>
			<!-- /wp:paragraph -->
		<!-- /wp:query-no-results -->
	</div>
	<!-- /wp:query -->
</div>
<!-- /wp:group -->
