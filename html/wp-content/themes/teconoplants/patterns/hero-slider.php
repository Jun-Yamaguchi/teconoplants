<?php
/**
 * Title: メインバナースライダー
 * Slug: teconoplants/hero-slider
 * Categories: teconoplants, featured, banner
 * Description: 店舗・ビカクシダの写真スライダー
 *
 * @package TeconoPlants
 */

$slides = array(
	array(
		'image' => get_template_directory_uri() . '/assets/images/banner-storefront.png',
		'alt'   => __( 'tecono plants 店舗外観', 'teconoplants' ),
		'title' => __( 'tecono plants', 'teconoplants' ),
		'caption' => __( 'ビカクシダ専門店', 'teconoplants' ),
	),
	array(
		'image' => get_template_directory_uri() . '/assets/images/banner-interior.png',
		'alt'   => __( '店内のビカクシダ展示', 'teconoplants' ),
		'title' => __( 'Platycerium', 'teconoplants' ),
		'caption' => __( '厳選した株を店内でご覧いただけます', 'teconoplants' ),
	),
	array(
		'image' => get_template_directory_uri() . '/assets/images/banner-mounting.png',
		'alt'   => __( 'ビカクシダの板付け作業', 'teconoplants' ),
		'title' => __( '板付け・育成', 'teconoplants' ),
		'caption' => __( '一株一株、丁寧にお仕立てしています', 'teconoplants' ),
	),
);
?>
<!-- wp:html -->
<section class="hero-slider" aria-label="<?php esc_attr_e( 'メインビジュアル', 'teconoplants' ); ?>" data-autoplay="6000">
	<div class="hero-slider__viewport">
		<div class="hero-slider__track">
			<?php foreach ( $slides as $index => $slide ) : ?>
				<article class="hero-slider__slide<?php echo 0 === $index ? ' is-active' : ''; ?>" aria-hidden="<?php echo 0 === $index ? 'false' : 'true'; ?>">
					<img src="<?php echo esc_url( $slide['image'] ); ?>" alt="<?php echo esc_attr( $slide['alt'] ); ?>" width="1440" height="720" loading="<?php echo 0 === $index ? 'eager' : 'lazy'; ?>" decoding="async" />
					<div class="hero-slider__overlay">
						<p class="hero-slider__eyebrow"><?php echo esc_html( $slide['caption'] ); ?></p>
						<h1 class="hero-slider__title"><?php echo esc_html( $slide['title'] ); ?></h1>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="hero-slider__controls">
		<button type="button" class="hero-slider__arrow hero-slider__arrow--prev" aria-label="<?php esc_attr_e( '前のスライド', 'teconoplants' ); ?>">‹</button>
		<div class="hero-slider__dots" role="tablist" aria-label="<?php esc_attr_e( 'スライド選択', 'teconoplants' ); ?>">
			<?php foreach ( $slides as $index => $slide ) : ?>
				<button type="button" class="hero-slider__dot<?php echo 0 === $index ? ' is-active' : ''; ?>" role="tab" aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>" aria-label="<?php echo esc_attr( sprintf( __( 'スライド %d', 'teconoplants' ), $index + 1 ) ); ?>" data-slide="<?php echo esc_attr( (string) $index ); ?>"></button>
			<?php endforeach; ?>
		</div>
		<button type="button" class="hero-slider__arrow hero-slider__arrow--next" aria-label="<?php esc_attr_e( '次のスライド', 'teconoplants' ); ?>">›</button>
	</div>
</section>
<!-- /wp:html -->
