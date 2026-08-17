<?php
/**
 * Awakening India child-theme bootstrap.
 *
 * @package AwakeningIndiaChild
 */

defined( 'ABSPATH' ) || exit;

/** Load the parent theme, shared fonts and reusable Gate 2 design tokens. */
function awakening_india_enqueue_styles() {
	global $post;
	$is_local_preview = defined( 'AI_LOCAL_DRAFT_PREVIEW' ) && AI_LOCAL_DRAFT_PREVIEW;
	$preview_slug = $is_local_preview && $post instanceof WP_Post ? $post->post_name : '';
	wp_enqueue_style(
		'awakening-india-fonts',
		'https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Newsreader:opsz,wght@6..72,400;6..72,500;6..72,600&display=swap',
		array(),
		null
	);
	wp_enqueue_style(
		'divi-parent-style',
		get_template_directory_uri() . '/style.css',
		array( 'awakening-india-fonts' ),
		wp_get_theme( 'Divi' )->get( 'Version' )
	);

	$global_css = get_stylesheet_directory() . '/assets/css/ai-global.css';
	wp_enqueue_style(
		'awakening-india-global',
		get_stylesheet_directory_uri() . '/assets/css/ai-global.css',
		array( 'divi-parent-style' ),
		file_exists( $global_css ) ? filemtime( $global_css ) : wp_get_theme()->get( 'Version' )
	);

	if ( is_front_page() || is_page( 'homepage' ) ) {
		$home_css = get_stylesheet_directory() . '/assets/css/ai-home.css';
		wp_enqueue_style(
			'awakening-india-home',
			get_stylesheet_directory_uri() . '/assets/css/ai-home.css',
			array( 'awakening-india-global' ),
			file_exists( $home_css ) ? filemtime( $home_css ) : wp_get_theme()->get( 'Version' )
		);
	}

	if ( is_page( 'about-us' ) || 'about-us' === $preview_slug ) {
		$about_css = get_stylesheet_directory() . '/assets/css/ai-about.css';
		wp_enqueue_style(
			'awakening-india-about',
			get_stylesheet_directory_uri() . '/assets/css/ai-about.css',
			array( 'awakening-india-global' ),
			file_exists( $about_css ) ? filemtime( $about_css ) : wp_get_theme()->get( 'Version' )
		);
	}

	if ( ( is_page() && ! is_front_page() && ! is_page( array( 'homepage', 'about-us' ) ) ) || ( $is_local_preview && ! in_array( $preview_slug, array( 'homepage', 'about-us' ), true ) ) ) {
		$pages_css = get_stylesheet_directory() . '/assets/css/ai-pages.css';
		wp_enqueue_style(
			'awakening-india-pages',
			get_stylesheet_directory_uri() . '/assets/css/ai-pages.css',
			array( 'awakening-india-global' ),
			file_exists( $pages_css ) ? filemtime( $pages_css ) : wp_get_theme()->get( 'Version' )
		);
	}
}
add_action( 'wp_enqueue_scripts', 'awakening_india_enqueue_styles' );

/**
 * Restore mobile-menu behaviour Divi does not provide natively: a keyboard-operable,
 * ARIA-labelled trigger and collapsible submenus, matching the React reference.
 */
function awakening_india_enqueue_scripts() {
	$menu_js = get_stylesheet_directory() . '/assets/js/ai-mobile-menu.js';
	wp_enqueue_script(
		'awakening-india-mobile-menu',
		get_stylesheet_directory_uri() . '/assets/js/ai-mobile-menu.js',
		array(),
		file_exists( $menu_js ) ? filemtime( $menu_js ) : wp_get_theme()->get( 'Version' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'awakening_india_enqueue_scripts' );

/** Add a keyboard-accessible route to the main content region. */
function awakening_india_skip_link() {
	echo '<a class="ai-skip-link" href="#main-content">Skip to content</a>';
}
add_action( 'wp_body_open', 'awakening_india_skip_link' );

/** Use the exact verified React metadata while no SEO plugin owns these tags. */
function awakening_india_document_title_parts( $parts ) {
	if ( is_front_page() ) {
		$parts['title'] = 'Awakening India Foundation | A New Vision for care and compassion';
		unset( $parts['site'], $parts['tagline'] );
	}
	return $parts;
}
add_filter( 'document_title_parts', 'awakening_india_document_title_parts' );

function awakening_india_front_page_title( $title ) {
	return is_front_page() ? 'Awakening India Foundation | A New Vision for care and compassion' : $title;
}
add_filter( 'pre_get_document_title', 'awakening_india_front_page_title', 20 );

/** Output page metadata and the verified NGO schema from the React source. */
function awakening_india_seo_metadata() {
	if ( ! is_front_page() && ! is_page() ) {
		return;
	}
	$description = is_front_page()
		? 'An Indian NGO established in 2015, working across animal welfare, healthcare and mental well-being to support lives that are often overlooked.'
		: trim( get_the_excerpt() );
	if ( '' === $description ) {
		return;
	}
	$title = wp_get_document_title();
	echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
	echo '<meta property="og:type" content="website">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( get_permalink() ) . '">' . "\n";
	echo '<meta property="og:site_name" content="Awakening India Foundation">' . "\n";
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";

	if ( is_front_page() ) {
		$schema = array(
			'@context' => 'https://schema.org',
			'@type' => 'NGO',
			'name' => 'Awakening India Foundation',
			'slogan' => 'A New Vision',
			'foundingDate' => '2015',
			'description' => 'Awakening India Foundation directs its efforts towards physical well-being, mental well-being and animal welfare, supporting lives that are often overlooked.',
			'telephone' => '+91 97177 21011',
			'email' => 'contact.awakeningindia@gmail.com',
			'address' => array(
				'@type' => 'PostalAddress',
				'streetAddress' => 'E-199, Phase-II, New Palam Vihar',
				'addressLocality' => 'Gurugram',
				'addressRegion' => 'Haryana',
				'postalCode' => '122017',
				'addressCountry' => 'IN',
			),
		);
		echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
	}
}
add_action( 'wp_head', 'awakening_india_seo_metadata', 5 );

/** Divi omits empty alt attributes on decorative SVG Image modules. */
function awakening_india_decorative_svg_alt( $block_content, $block ) {
	if ( 'divi/image' !== ( $block['blockName'] ?? '' ) || ! preg_match( '#/awakening-india-child/assets/(?:icons|css)/[^"\']+\.svg#', $block_content ) ) {
		return $block_content;
	}
	return preg_replace( '/<img\b(?![^>]*\salt=)/i', '<img alt="" ', $block_content );
}
add_filter( 'render_block', 'awakening_india_decorative_svg_alt', 10, 2 );
