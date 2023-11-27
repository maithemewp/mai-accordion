<?php

// Prevent direct file access.
defined( 'ABSPATH' ) || die;

add_action( 'acf/init', 'mai_register_accordion_block' );
/**
 * Register block.
 *
 * @since 1.6.0
 *
 * @return void
 */
function mai_register_accordion_block() {
	register_block_type( __DIR__ . '/block.json',
		[
			'icon' => '<svg role="img" aria-hidden="true" focusable="false" style="display:block;" width="20" height="20" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"><g transform="matrix(1,0,0,1,0,13)"><g transform="matrix(0.333333,0,0,4,19,-60)"><path d="M9,16.125C9,16.056 8.328,16 7.5,16L4.5,16C3.672,16 3,16.056 3,16.125L3,16.375C3,16.444 3.672,16.5 4.5,16.5L7.5,16.5C8.328,16.5 9,16.444 9,16.375L9,16.125Z" style="fill:rgb(35,31,32);"/></g><g transform="matrix(2.66667,0,0,4,-6,-60)"><path d="M9,16.125C9,16.056 8.916,16 8.813,16L3.188,16C3.084,16 3,16.056 3,16.125L3,16.375C3,16.444 3.084,16.5 3.188,16.5L8.813,16.5C8.916,16.5 9,16.444 9,16.375L9,16.125Z" style="fill:rgb(35,31,32);"/></g></g><g transform="matrix(1,0,0,1,0,7)"><g transform="matrix(0.333333,0,0,4,19,-60)"><path d="M9,16.125C9,16.056 8.328,16 7.5,16L4.5,16C3.672,16 3,16.056 3,16.125L3,16.375C3,16.444 3.672,16.5 4.5,16.5L7.5,16.5C8.328,16.5 9,16.444 9,16.375L9,16.125Z" style="fill:rgb(35,31,32);"/></g><g transform="matrix(2.66667,0,0,4,-6,-60)"><path d="M9,16.125C9,16.056 8.916,16 8.813,16L3.188,16C3.084,16 3,16.056 3,16.125L3,16.375C3,16.444 3.084,16.5 3.188,16.5L8.813,16.5C8.916,16.5 9,16.444 9,16.375L9,16.125Z" style="fill:rgb(35,31,32);"/></g></g><g transform="matrix(1,0,0,1,0,1)"><g transform="matrix(0.333333,0,0,4,19,-60)"><path d="M9,16.125C9,16.056 8.328,16 7.5,16L4.5,16C3.672,16 3,16.056 3,16.125L3,16.375C3,16.444 3.672,16.5 4.5,16.5L7.5,16.5C8.328,16.5 9,16.444 9,16.375L9,16.125Z" style="fill:rgb(35,31,32);"/></g><g transform="matrix(2.66667,0,0,4,-6,-60)"><path d="M9,16.125C9,16.056 8.916,16 8.813,16L3.188,16C3.084,16 3,16.056 3,16.125L3,16.375C3,16.444 3.084,16.5 3.188,16.5L8.813,16.5C8.916,16.5 9,16.444 9,16.375L9,16.125Z" style="fill:rgb(35,31,32);"/></g></g></svg>',
		]
	);
}

/**
 * Callback function to render the block.
 *
 * @since 1.6.0
 *
 * @param array    $attributes The block attributes.
 * @param string   $content The block content.
 * @param bool     $is_preview Whether or not the block is being rendered for editing preview.
 * @param int      $post_id The current post being edited or viewed.
 * @param WP_Block $wp_block The block instance (since WP 5.5).
 * @param array    $context The block context array.
 *
 * @return void
 */
function mai_do_accordion_block( $attributes, $content, $is_preview, $post_id, $wp_block, $context ) {
	$allowed  = [ 'acf/mai-accordion-item' ];
	$template = [ [ 'acf/mai-accordion-item', [], [] ] ];
	$content  = sprintf( '<InnerBlocks allowedBlocks="%s" template="%s" />', esc_attr( wp_json_encode( $allowed ) ), esc_attr( wp_json_encode( $template ) ) );
	$args     = [
		'preview' => $is_preview,
		'content' => $content,
		'schema'  => get_field( 'schema' ),
		'row_gap' => get_field( 'row_gap' ),
	];

	if ( isset( $block['anchor'] ) && ! empty( $block['anchor'] ) ) {
		$args['id'] = $block['anchor'];
	}

	if ( isset( $block['className'] ) && ! empty( $block['className'] ) ) {
		$args['class'] = $block['className'];
	}

	$accordion = new Mai_Accordion( $args, true );

	echo $accordion->get();
}

add_action( 'acf/init', 'mai_register_accordion_field_group' );
/**
 * Register field group.
 *
 * @since 1.6.0
 *
 * @return void
 */
function mai_register_accordion_field_group() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		[
			'key'    => 'mai_accordion_field_group',
			'title'  => __( 'Mai Accordion', 'mai-engine' ),
			'fields' => [
				[
					'key'           => 'mai_accordion_schema',
					'label'         => __( 'Schema / Structured Data', 'mai-engine' ),
					'name'          => 'schema',
					'type'          => 'select',
					'type'          => 'select',
					'choices'       => [ 'faq' => __( 'FAQ', 'mai-engine' ) ],
					'return_format' => 'value',
					'multiple'      => 0,
					'allow_null'    => 1,
				],
				[
					'key'           => 'mai_accordion_row_gap',
					'label'         => __( 'Bottom Spacing', 'mai-engine' ),
					'name'          => 'row_gap',
					'type'          => 'button_group',
					'default_value' => 'md',
					'choices'       => [
						''   => __( 'None', 'mai-engine' ),
						'xs' => __( 'XS', 'mai-engine' ),
						'sm' => __( 'S', 'mai-engine' ),
						'md' => __( 'M', 'mai-engine' ),
						'lg' => __( 'L', 'mai-engine' ),
						'xl' => __( 'XL', 'mai-engine' ),
					],
					'wrapper' => [
						'class' => 'mai-acf-button-group',
					],
				],
			],
			'location' => [
				[
					[
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/mai-accordion',
					],
				],
			],
		]
	);
}
