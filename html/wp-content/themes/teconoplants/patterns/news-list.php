<?php
/**
 * Title: 新着情報リスト
 * Slug: teconoplants/news-list
 * Categories: teconoplants, query
 * Description: 新着投稿をタイトルと本文抜粋で表示
 *
 * @package TeconoPlants
 */
?>
<!-- wp:query {"queryId":10,"query":{"perPage":5,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"className":"home-news-list","layout":{"type":"default"}} -->
<div class="wp-block-query home-news-list">
	<!-- wp:post-template {"layout":{"type":"default"}} -->
		<!-- wp:group {"className":"home-news-list__item","style":{"spacing":{"blockGap":"0.5rem","padding":{"top":"1rem","bottom":"1rem"}}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group home-news-list__item" style="padding-top:1rem;padding-bottom:1rem">
			<!-- wp:post-date {"format":"Y.m.d","className":"home-news-list__date"} /-->
			<!-- wp:post-title {"isLink":true,"level":3,"className":"home-news-list__title"} /-->
			<!-- wp:post-excerpt {"moreText":"続きを読む","showMoreOnNewLine":false,"excerptLength":24,"className":"home-news-list__excerpt"} /-->
		</div>
		<!-- /wp:group -->
	<!-- /wp:post-template -->

	<!-- wp:query-no-results -->
		<!-- wp:paragraph {"className":"home-news-list__empty"} -->
		<p class="home-news-list__empty"><?php esc_html_e( '投稿がまだありません。', 'teconoplants' ); ?></p>
		<!-- /wp:paragraph -->
	<!-- /wp:query-no-results -->
</div>
<!-- /wp:query -->
