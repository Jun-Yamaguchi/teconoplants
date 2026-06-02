<?php
/**
 * Title: Instagram 投稿
 * Slug: teconoplants/instagram-feed
 * Categories: teconoplants, featured
 * Description: Instagram の最新投稿を表示
 *
 * @package TeconoPlants
 */

$instagram_url = 'https://www.instagram.com/teconoplants/';
?>
<!-- wp:group {"align":"full","className":"home-instagram","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull home-instagram">
	<!-- wp:group {"className":"home-instagram__inner","layout":{"type":"default"}} -->
	<div class="wp-block-group home-instagram__inner">
		<!-- wp:heading {"level":2,"className":"home-instagram__heading"} -->
		<h2 class="wp-block-heading home-instagram__heading"><?php esc_html_e( 'Instagram 投稿', 'teconoplants' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"className":"home-instagram__lead"} -->
		<p class="home-instagram__lead"><?php esc_html_e( '最新の投稿は Instagram で更新しています。', 'teconoplants' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:html -->
		<div class="home-instagram__feed" aria-live="polite">
			<?php if ( shortcode_exists( 'instagram-feed' ) ) : ?>
				<?php echo do_shortcode( '[instagram-feed]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php else : ?>
				<p class="home-instagram__fallback">
					<a href="<?php echo esc_url( $instagram_url ); ?>" target="_blank" rel="noopener noreferrer">
						<?php esc_html_e( '@teconoplants の投稿を見る', 'teconoplants' ); ?>
					</a>
				</p>
			<?php endif; ?>
		</div>
		<!-- /wp:html -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
