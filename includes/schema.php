<?php

// Prevent direct file access.
defined( 'ABSPATH' ) || die;

add_action( 'wp_footer', 'mai_render_accordion_faq_schema' );
/**
 * Renders schema from accordion block data.
 *
 * @since TBD
 *
 * @return void
 */
function mai_render_accordion_faq_schema() {
	$qas = mai_get_accordion_faq_schema();

	if ( ! $qas ) {
		return;
	}

	$entity = [];

	foreach ( $qas as $qa ) {
		$q = isset( $qa[0] ) && ! empty( $qa[0] ) ? $qa[0] : '';
		$a = isset( $qa[1] ) && ! empty( $qa[1] ) ? $qa[1] : '';

		if ( ! ( $q & $a ) ) {
			continue;
		}

		$entity[] = [
			'@type'          => 'Question',
			'name'           => $q,
			'acceptedAnswer' => [
				'@type' => 'Answer',
				'text'  => wpautop( $a ),
			],
		];
	}

	if ( ! $entity ) {
		return;
	}

	$schema = [
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => [
			$entity,
		],
	];

	printf( '<script type="application/ld+json">%s</script>', wp_json_encode( $schema ) );
}

add_filter( 'render_block_acf/mai-accordion', 'mai_render_accordion_block_faq_schema', 10, 3 );
/**
 * Adds schema from all Mai Accordion blocks.
 *
 * @since TBD
 *
 * @param string   $block_content The block content.
 * @param array    $block         The full block, including name and attributes.
 * @param WP_Block $instance      The block instance.
 *
 * @return string
 */
function mai_render_accordion_block_faq_schema( $block_content, $parsed_block, $wp_block ) {
	if ( ! isset( $wp_block->attributes['data']['schema'] ) || 'faq' !== $wp_block->attributes['data']['schema'] ) {
		return $block_content;
	}

	if ( ! ( isset( $parsed_block['innerBlocks'] ) && $parsed_block['innerBlocks'] ) ) {
		return $block_content;
	}

	foreach ( $parsed_block['innerBlocks'] as $block ) {
		if ( ! ( isset( $block['blockName'] ) && 'acf/mai-accordion-item' === $block['blockName'] ) ) {
			continue;
		}

		$q = isset( $block['attrs']['data']['title'] ) ? strip_tags( $block['attrs']['data']['title'] ) : '';
		$a = '';

		if ( ! $q ) {
			continue;
		}

		$inner = isset( $block['innerBlocks'] ) ? $block['innerBlocks'] : [];

		if ( ! $inner ) {
			continue;
		}

		// Build answer and strip markup, partially taken from wp_strip_all_tags but allowing <a> links.
		$a = do_blocks( serialize_blocks( $inner ) );
		$a = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $a ); // Strip script and style tags.
		$a = strip_tags( $a, [ 'a' ] ); // Strip tags, leave links.
		$a = trim( $a );

		if ( ! $a ) {
			continue;
		}

		mai_get_accordion_faq_schema( [ $q, wpautop( $a ) ] );
	}

	return $block_content;
}